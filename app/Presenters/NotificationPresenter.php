<?php

namespace App\Presenters;

use Laracasts\Presenter\Presenter;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class NotificationPresenter extends Presenter
{
    public function render()
    {
        $data = ['path' => 'notification.display', 'notification' => $this->entity];

        if ($this->entity->user) {
            try {
                $data = array_merge($data, (empty($this->entity->data)) ? [] : perfectUnserialize($this->entity->data));
                /**
                 * @var $path
                 */
                extract($data);

                /**user have seen it**/
                $this->entity->markSeen();

                return \Theme::section($path, $data);
            } catch(\Exception $e) {
                $this->entity->delete();
            }
        } else {
            $this->entity->delete();
        }

    }

    public function time()
    {
        return str_replace(' ', 'T', $this->entity->created_at).'Z';
    }
}