<?php

namespace App\Controllers\Admincp;
use App\Repositories\CommunityRepository;

/**
*
*@author: Tiamiyu waliu kola
*@website : www.crea8social.com
*/
class CommunityController extends AdmincpController
{
    public function __construct(CommunityRepository $communityRepository)
    {
        $this->communityRepository = $communityRepository;
        parent::__construct();
        $this->activePage('communities');
    }

    public function index()
    {

        return $this->theme->view('communities.index', [
            'communities' => $this->communityRepository->getAll(\Input::get('term'))
        ])->render();
    }

    public function edit($id)
    {
        $community = $this->communityRepository->get($id);
        $message = null;

        if ($val = \Input::get('val')) {
            $this->communityRepository->adminSave($val, $community);
            $message = "Successfully saved";
        }

        return $this->theme->view('communities.edit', [
            'community' => $community,
            'message' => $message
        ])->render();
    }
}