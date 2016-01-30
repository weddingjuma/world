<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class NotificationReceiver extends Model
{
    protected $table = "notification_receivers";

    public function user()
    {
        return $this->belongsTo('App\\Models\\User', 'user_id');
    }
}