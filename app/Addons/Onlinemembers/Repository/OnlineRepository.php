<?php
namespace App\Addons\Onlinemembers\Repository;
use App\Models\User;

/**
*
*@author: Tiamiyu waliu kola
*@website : www.crea8social.com
*/
class OnlineRepository
{
    public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function getLists($limit = null, $gender = '', $isAdmin = false)
    {
        $offset = time() - 1000;
        $query = $this->model
            ->where('last_active_time', '>', $offset);

        if (!$isAdmin) $query = $query->where('online_status', '!=', 0);

        if ($gender and $gender != 'all') {
            $query = $query->where('genre', '=', $gender);
        }


        return $query = $query->paginate($limit);
    }
}