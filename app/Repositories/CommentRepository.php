<?php

namespace App\Repositories;

use App\Interfaces\PhotoRepositoryInterface;
use App\Models\Comment;
use Illuminate\Events\Dispatcher;
use Illuminate\Mail\Mailer;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class CommentRepository
{
    public function __construct(
        Comment $comment,
        Dispatcher $event,
        PhotoRepositoryInterface $photoRepository,
        BlockedUserRepository $blockedUserRepository,
        NotificationRepository $notificationRepository,
        PostRepository $postRepository,
        NotificationReceiverRepository $notificationReceiverRepository,
        UserRepository $userRepository,
        Mailer $mailer,
        MustAvoidUserRepository $mustAvoidUserRepository
    )
    {
        $this->model = $comment;
        $this->event = $event;
        $this->photoRepository = $photoRepository;
        $this->blockedUserRepository = $blockedUserRepository;
        $this->notification = $notificationRepository;
        $this->notificationReceiver = $notificationReceiverRepository;
        $this->userRepository = $userRepository;
        $this->postRepository = $postRepository;
        $this->mailer = $mailer;
        $this->mustAvoidUserRepository = $mustAvoidUserRepository;
    }



    /**
     * Method to add comment
     *
     * @param string $text
     * @param string $type
     * @param string $typeId
     * @param string $image
     * @param int $userid
     * @return \\App\\Models\\Comment
     */
    public function add($text, $type, $typeId, $image = null, $userid = null)
    {
        $userid = (empty($userid)) ? \Auth::user()->id : $userid;
        $ignoreIds = []; //user ids to ignore notifications for


        if ($type == 'post') {
            $post = app('App\\Repositories\\PostRepository')->findById($typeId);
            //trying to replace this poster with the page incase the post is from page and the user is an admin
            if ($post and $post->page_id != 0) {
                //check if this comment post is a admin or editor or moderator
                $page = $post->page;
                if ($page and ($page->present()->isAdmin(true) or $page->present()->isEditor() or $page->present()->isModerator())) {
                    $userid = $page->user_id;
                    $isPageAdmin = true;
                    $ignoreIds = array_merge($ignoreIds, app('App\\Repositories\\PageAdminRepository')->getUserListId($page->id));
                }
            }
        }

        $text = sanitizeText($text);
        $type = sanitizeText($type);
        $typeId = sanitizeText($typeId);
        $userid = sanitizeText($userid);
        $comment = $this->model->newInstance();
        $comment->text = \Hook::fire('filter-text', $text);
        $comment->type = sanitizeText($type);
        $comment->type_id = sanitizeText($typeId);
        $comment->user_id = sanitizeText($userid);

        if ($image) {
            $image = $this->photoRepository->upload($image, [
                'path' => 'users/'.$userid.'/comments',
                'slug' => 'comments',
                'userid' => $userid
            ]);
            $comment->img_path = $image;
        }



        // if image and text is empty don't insert anything
        if (empty($text) and empty($image)) return false;

        $comment->save();

        //Also auto add this user to the reciever of notifications for this comment type
        $this->notificationReceiver->add($userid, $type, $typeId);

        /**
         * Let send notification to all receiver of this comment type
         */
        $this->notification->sendToReceivers($type, $typeId, [
            'path' => 'notification.comment.add-comment',
            'type' => $type,
            'typeId' => $typeId,
            'text' => $text
        ], $userid, $ignoreIds);

        if ($type == 'post') {
            $post = $this->postRepository->findById($typeId);
            $post->touch();//method to update the post
            \Cache::forget('post-'.$post->id);

            $commentor = app('App\\Repositories\\UserRepository')->findById($userid);
            $user = $post->user;

            if ($post->user_id != $userid) {

                if ($user->present()->privacy('email-notify-comment-post', true)) {

                    try{
                        $this->mailer->queue('emails.post.comment', [
                            'fullname' => $user->fullname,
                            'username' => $user->username,
                            'commentorName' => $commentor->fullname,
                            'commentorUsername' => $commentor->username,
                            'commentorProfileUrl' => $commentor->present()->url(),
                            'commentorAtName' => $commentor->present()->atName(),
                            'postLink' => \URL::route('post-page', ['id', $post->id]),
                            'postId' => $post->id
                        ], function($mail) use($user, $commentor) {
                            $mail->to($user->email_address, $user->fullname)
                                ->subject(trans('mail.new-comment', ['fullname' => $commentor->fullname]));
                        });
                    } catch(\Exception $e) {

                    }

                }

            }
        }

        $this->event->fire('comment.add', [$text, $userid, $type, $typeId, $comment, $image]);

        return $comment;
    }

    /**
     * Method to get comments
     *
     * @param string $type
     * @param string $typeId
     * @param int $limit
     * @return array
     */
    public function get($type, $typeId, $limit = 10)
    {
        $comments = $this->model->where('type', '=', $type)
            ->where('type_id', '=', $typeId)
            ->whereNotIn('user_id', $this->mustAvoidUserRepository->get());

        if (\Auth::check()) {
            $blockedUsers = $this->blockedUserRepository->listIds(\Auth::user()->id);
            $comments = $comments->whereNotIn('user_id', $blockedUsers);
        }
        return $comments = $comments->paginate($limit);
    }

    public function getInfo($id)
    {
        return $this->model->where('id', '=', $id)->first();
    }

    public function lists($type, $typeId, $offset, $limit)
    {
        $comments = $this->model->where('type', '=', $type)
            ->where('type_id', '=', $typeId)
            ->whereNotIn('user_id', $this->mustAvoidUserRepository->get());
        if (\Auth::check()) {
            $blockedUsers = $this->blockedUserRepository->listIds(\Auth::user()->id);
            $comments = $comments->whereNotIn('user_id', $blockedUsers);
        }

        $comments = $comments->orderBy('id', 'desc')->take($limit)->skip($offset)->get();

        return $comments->reverse();
    }

    public function findById($id)
    {
        return $this->model->where('id', '=', $id)->first();
    }

    public function save($id, $text)
    {
        $comment = $this->findById($id);
        if (!$comment or !\Auth::check()) return false;
        if ($comment->user_id != \Auth::user()->id) {
            if (!\Auth::user()->isAdmin()) return false;
        }

        $comment->text = sanitizeText($text);
        $comment->save();

        return $comment;
    }

    /**
     * Method to delete comments
     *
     * @param int $id
     * @return boolean
     */
    public function delete($id)
    {
        $comment = $this->findById($id);
        if (!$comment or !\Auth::check()) return false;
        if ($comment->user_id != \Auth::user()->id) {
            if (!\Auth::user()->isAdmin()) return false;
        }

        return $this->model->where('id', '=', $id)->delete();
    }

    /**
     * Method to count comments
     *
     * @param string $type
     * @param int $id
     * @return int
     */
    public function count($type, $id)
    {
        return $this->model
            ->where('type', '=', $type)
            ->where('type_id', '=', $id)
            ->count();
    }

    public function deleteAllByUser($userid)
    {
        return $this->model->where('user_id', '=', $userid)->delete();
    }

    public function deleteByType($type, $typeId)
    {
        return $this->model->where('type', '=', $type)->where('type_id', '=', $typeId)->delete();
    }

    public function total()
    {
        return $this->model->count();
    }
}