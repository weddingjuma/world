<?php

namespace App\Controllers;

use App\Controllers\Base\UserBaseController;
use App\Repositories\CommentRepository;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class CommentController extends UserBaseController
{
    public function __construct(CommentRepository $commentRepository)
    {
        parent::__construct();
        $this->commentRepository = $commentRepository;
    }

    public function add()
    {
        if ($val = \Input::get('val')) {
            /**
             * @var $text
             * @var $type
             * @var $type_id
             */
            extract($val);
            $comment = $this->commentRepository->add($text, $type, $type_id, \Input::file('image'));

            if ($comment) {
                if ($comment->type != 'post') {
                    return $this->theme->section('comment.display', ['comment' => $comment]);
                } else {
                    $post = $comment->post;
                    if ($post and $post->type == 'page' and $comment->user->id == $post->page->user->id) {
                        return $this->theme->section('comment.display-page', ['comment' => $comment, 'page' => $post->page]);
                    } else {
                        return $this->theme->section('comment.display', ['comment' => $comment]);
                    }
                }
            }
        }
    }

    public function edit($id)
    {
        $text = \Input::get('text');
        $comment = $this->commentRepository->save($id, $text);

        if ($comment) {
            return $comment->present()->text();
        }
    }

    public function delete($id)
    {
        return $this->commentRepository->delete($id);
    }

    public function count($type, $id)
    {
        return $this->commentRepository->count($type, $id);
    }

    public function loadMore()
    {
        $limit = \Input::get('limit');
        $offset = \Input::get('offset');
        $type =  \Input::get('type');
        $typeId =  \Input::get('typeId');


        $newOffset =(int) $offset  + (int) $limit;

        $comments = $this->commentRepository->lists($type, $typeId, $offset, $limit);

        return json_encode([
            'offset' => $newOffset,
            'content' => (String) $this->theme->section('comment.paginate', ['comments' => $comments])
        ]);
    }
}