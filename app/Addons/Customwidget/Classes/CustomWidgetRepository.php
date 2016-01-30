<?php

namespace App\Addons\Customwidget\Classes;

/**
*
*@author: Tiamiyu waliu kola
*@website : www.crea8social.com
*/
class CustomWidgetRepository
{
    public function __construct(CustomWidget $customWidget)
    {
        $this->model = $customWidget;
    }

    public function getAll()
    {
        return $this->model->get();
    }

    public function getList()
    {
        if (\Cache::has('widgets-list')) {
            return \Cache::get('widgets-list');
        } else {
            $lists = $this->model->where('status', 1)->get();

            \Cache::forever($lists, 'widgets-list');

            return $lists;
        }
    }

    public function findById($id)
    {
        return $this->model->where('id', $id)->first();
    }

    public function pages()
    {
        return [
            'all' => 'All Pages',
            'user-home' => 'User Home Page',
            'user-search' => 'Search Page',
            'user-discover' => 'Discover Page',
            'notifications' => 'Notifications Page',
            'user-community' => 'Communities Page',
            'user-pages' => 'Pages Page'
        ];
    }

    public function save($val, $widget)
    {
        $expected = [
            'title' => '',
            'content' => '',
            'status' => 1,
            'page' => 'all'
        ];

        /**
         * @var $title
         * @var $content
         * @var $status
         * @var $page
         */
        extract(array_merge($expected, $val));

        if (empty($title) and empty($content)) return false;


        $widget->title = $title;
        $widget->content = $content;
        $widget->status = $status;
        $widget->page = $page;
        $widget->save();

        \Cache::forget('widgets-list');

        return $widget;
    }

    public function delete($id)
    {
        \Cache::forget('widgets-list');
        return $this->model->where('id', $id)->delete();
    }

    public function add($val)
    {
        $expected = [
            'title' => '',
            'content' => '',
            'status' => 1,
            'page' => 'all'
        ];

        /**
         * @var $title
         * @var $content
         * @var $status
         * @var $page
         */
        extract(array_merge($expected, $val));

        if (empty($title) and empty($content)) return false;

        $widget = $this->model->newInstance();
        $widget->title = $title;
        $widget->content = $content;
        $widget->status = $status;
        $widget->page = $page;
        $widget->save();

        \Cache::forget('widgets-list');
        return $widget;
    }
}