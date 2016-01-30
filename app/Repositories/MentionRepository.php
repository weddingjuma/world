<?php

namespace App\Repositories;

use Illuminate\Html\HtmlBuilder;
use Illuminate\Mail\Mailer;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class MentionRepository
{
    public function __construct(
        UserRepository $userRepository,
        NotificationRepository $notificationRepository,
        Mailer $mailer,
        HtmlBuilder $htmlBuilder
    )
    {
        $this->userRepository = $userRepository;
        $this->notificationRepository = $notificationRepository;
        $this->mailer = $mailer;
        $this->html = $htmlBuilder;
    }

    /**
     * Method to process a text to find @mentions
     *
     * @param string $text
     * @param int $postId
     * @return boolean
     */
    public function process($text, $postId)
    {
        $mentions = $this->parse($text);
        if (!empty($mentions)) {
            foreach($mentions as $mention) {
                $username = str_replace('@', '', $mention);
                $user = $this->userRepository->findByUsername($username);
                if ($user and \Auth::user()->id != $user->id) {
                    //send notification to this user for mention him/her
                    $this->notificationRepository->send($user->id, [
                        'path' => 'notification.mention',
                        'postId' => $postId
                    ], null, true, 'notify-mention-post', $user);

                    //send email notification if user allow it
                    if ($user->present()->privacy('email-notify-mention-post', 1)) {
                        $loggedUser = \Auth::user();
                        try{
                            $this->mailer->queue('emails.mention', [
                                'fullname' => $user->fullname,
                                'username' => $user->username,
                                'mentionName' => $loggedUser->fullname,
                                'mentionUsername' => $loggedUser->username,
                                'mentionProfileUrl' => $loggedUser->present()->url(),
                                'mentionAtName' => $loggedUser->present()->atName(),
                                'postLink' => \URL::route('post-page', ['id' => $postId]),
                            ], function($mail) use($user) {
                                $mail->to($user->email_address, $user->fullname)
                                    ->subject(trans('mail.new-mention', ['fullname' => $user->fullname]));
                            });
                        } catch(\Exception $e) {

                        }
                    }
                }


            }
        }
    }

    /**
     * Method to transform @mentions in a text to corresponding text
     *
     * @param string $text
     * @return string
     */
    public function transform($text)
    {
        $mentions = $this->parse($text);

        if(!empty($mentions)) {
            foreach($mentions as $mention) {
                $username = ltrim(str_replace('@', '', $mention));
                $link = $this->html->link($username, $mention, ['data-ajaxify' => 'true']);
                $text = str_replace($mention, $link, $text);
            }
        }

        return $text;
    }

    /**
     * Method to parse string and extract @mentions
     *
     * @param string $text
     * @return array
     */
    public function parse($text)
    {
        if (empty($text)) return false;
        preg_match_all('/(^|\s)(@\w+)/', $text, $matches);

        return $matches[0];
    }
}