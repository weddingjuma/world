<?php

namespace App\Repositories;
use App\Repositories\Models\PageAdmin;
use Illuminate\Cache\Repository;

/**
*
*@author: Tiamiyu waliu kola
*@website : www.crea8social.com
*/
class PageAdminRepository
{
    public function __construct(PageAdmin $pageAdmin, Repository $cache, NotificationRepository $notificationRepository)
    {
        $this->model = $pageAdmin;
        $this->cache = $cache;
        $this->notification = $notificationRepository;
    }

    public function add($pageId, $userId, $type)
    {
        //if (!is_numeric($pageId) or !is_numeric($userId)) return false;

        if (!$this->exists($pageId, $userId)) {
            $admin = $this->model->newInstance();
            $admin->page_id = $pageId;
            $admin->user_id = $userId;
            $admin->type = $type;

            $admin->save();

            //lets send notification to this user about this great news
            $this->notification->send($userId, [
                'path' => 'notification.pages.make-admin',
                'admin' => $admin,
            ]);

            //lets clear the cache base on the page admin role type
            if ($type == 1) {
                $this->cache->forget('page-admins-'.$pageId);
            } elseif ($type == 2) {
                $this->cache->forget('page-editors-'.$pageId);
            } else {
                $this->cache->forget('page-moderators-'.$pageId);
            }
            return $admin;
        }

        return false;
    }

    public function exists($pageId, $userId)
    {
        return $this->model->where('page_id', '=', $pageId)->where('user_id', '=', $userId)->first();
    }

    public function getList($pageId)
    {
        return $this->model->where('page_id', '=', $pageId)->get();
    }

    public function getUserListId($pageId)
    {
        return $this->model->where('page_id', '=', $pageId)->lists('user_id');
    }

    public function findById($id)
    {
        return $this->model->where('id', '=', $id)->first();
    }

    public function removeAdmin($adminId)
    {
        $admin = $this->findById($adminId);
        if ($admin) {
            $type = $admin->type;

            if ($type == 1) {
                $this->cache->forget('page-admins-'.$admin->page_id);
            } elseif ($type == 2) {
                $this->cache->forget('page-editors-'.$admin->page_id);
            } else {
                $this->cache->forget('page-moderators-'.$admin->page_id);
            }

            return $this->model->where('id', '=', $adminId)->delete();
        }

        return false;
    }
    public function updateAdmin($adminId, $type)
    {
        //lets clear the cache base on the page admin role type
        $admin = $this->findById($adminId);
        if ($type == 1) {
            $this->cache->forget('page-admins-'.$admin->page_id);
        } elseif ($type == 2) {
            $this->cache->forget('page-editors-'.$admin->page_id);
        } else {
            $this->cache->forget('page-moderators-'.$admin->page_id);
        }
        return $this->model->where('id', '=', $adminId)->update(['type' => $type]);
    }

    public function listAdmins($pageId)
    {
        $cacheName = 'page-admins-'.$pageId;
        if ($this->cache->has($cacheName)) {
            return $this->cache->get($cacheName);
        } else {
            $get = $this->model->where('page_id', '=', $pageId)->where('type', '=', 1)->lists('user_id');

            if (empty($get)) return [];
            $this->cache->forever($cacheName, $get);
            return $get;
        }
    }

    public function listEditors($pageId)
    {
        $cacheName = 'page-editors-'.$pageId;
        if ($this->cache->has($cacheName)) {
            return $this->cache->get($cacheName);
        } else {
            $get = $this->model->where('page_id', '=', $pageId)->where('type', '=', 2)->lists('user_id');

            if (empty($get)) return [];
            $this->cache->forever($cacheName, $get);
            return $get;
        }
    }

    public function listModerators($pageId)
    {
        $cacheName = 'page-moderators-'.$pageId;
        if ($this->cache->has($cacheName)) {
            return $this->cache->get($cacheName);
        } else {
            $get = $this->model->where('page_id', '=', $pageId)->where('type', '=', 3)->lists('user_id');

            if (empty($get)) return [];
            $this->cache->forever($cacheName, $get);
            return $get;
        }
    }

    public function isAdmin($pageId, $userid)
    {
        return (in_array($userid, $this->listAdmins($pageId)));
    }

    public function isModerator($pageId, $userid)
    {
        return (in_array($userid, $this->listModerators($pageId)));
    }

    public function isEditor($pageId, $userid)
    {
        return (in_array($userid, $this->listEditors($pageId)));
    }
}