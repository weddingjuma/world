<?php

namespace App\Controllers\Admincp;

use App\Repositories\CustomFieldRepository;
use App\Repositories\UserRepository;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class UserController extends AdmincpController
{

    public function __construct(CustomFieldRepository $customFieldRepository, UserRepository $userRepository)
    {
        parent::__construct();
        $this->activePage('users');
        $this->customField = $customFieldRepository;
        $this->userRepository = $userRepository;
    }

    public function lists()
    {
        $this->setTitle('Users');
        return $this->theme->view('user.lists', ['users' => $this->userRepository->listAll(\Input::get('term'))])->render();
    }

    public function unvalidated()
    {
        $this->setTitle('Unvalidated Users');
        return $this->theme->view('user.lists', ['users' => $this->userRepository->listUnvalidatedUsers()])->render();

    }

    public function banUsers()
    {
        $this->setTitle('Banned Users');
        return $this->theme->view('user.lists', ['users' => $this->userRepository->listBanned(\Input::get('term'))])->render();
    }

    public function ban()
    {
        $this->setTitle('Ban User');

        $user = $this->userRepository->findById(\Input::get('userid', false));

        if (!$user) return \Redirect::route('admincp-user-list');

        if ($val = \Input::get('val')) {
            $this->userRepository->ban($val, $user);
            return \Redirect::route('admincp-user-list');
        }

        return $this->theme->view('user.ban-user', ['action' => \Input::get('action', 'ban')])->render();
    }

    public function edit($id)
    {
        $this->setTitle('Edit User');

        $user = $this->userRepository->findById($id);

        if (empty($user)) return \Redirect::route('admincp-user-list');

        if ($val = \Input::get('val')) {
            $this->userRepository->adminUpdate($val, $user);
            return \Redirect::route('admincp-user-list');
        }

        return $this->theme->view('user.edit', ['user' => $user])->render();
    }

    public function customFields()
    {
        $type = \Input::get('type', 'profile');

        return $this->theme->view('user.custom-field.list', [
            'type' => $type,
            'fields' => $this->customField->lists($type)
        ])->render();
    }

    public function addCustomFields()
    {
        $message = "";

        if ($val = \Input::get('val')) {
            $this->customField->add($val);
            $message = "Custom field added successfully";
        }

        return $this->theme->view('user.custom-field.add', ['message' => $message])->render();
    }

    public function editCustomFields($id)
    {
        $field = $this->customField->get($id);

        if (!empty($field)) {

            if ($val = \Input::get('val')) {
                $this->customField->save($val, $id);

                return \Redirect::route('admincp-user-custom-field').'?='.$field->type;
            }
            return $this->theme->view('user.custom-field.edit', ['field' => $field])->render();
        }
    }

    public function deleteCustomFields($id)
    {
        $this->customField->delete($id);

        return \Redirect::route('admincp-user-custom-field');
    }
}