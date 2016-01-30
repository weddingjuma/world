<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class Connection extends Model
{
    use PresentableTrait;

    protected $table = "connections";

    protected $presenter = "App\\Presenters\\ConnectionPresenter";

    public function fromUser()
    {
        return $this->belongsTo('App\\Models\\User', 'user_id');
    }

    public function toUser()
    {
        return $this->belongsTo('App\\Models\\User', 'to_user_id');
    }
}
 