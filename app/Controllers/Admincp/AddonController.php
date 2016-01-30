<?php

namespace App\Controllers\Admincp;

use App\Repositories\AddonRepository;
use App\Repositories\ConfigurationRepository;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class AddonController extends AdmincpController
{
    public function __construct(AddonRepository $addonRepository, ConfigurationRepository $configurationRepository)
    {
        parent::__construct();
        $this->addonRepository = $addonRepository;
        $this->configurationRepository = $configurationRepository;
        $this->activePage('addon');
    }

    public function index()
    {
        return $this->theme->view('addons.index', [
            'addons' => $this->addonRepository->listAddons(),
            'addonRepository' => $this->addonRepository
        ])->render();
    }

    public function enable($addon)
    {
        $this->addonRepository->enable($addon);
        return \Redirect::to(\URl::previous())->with('message', trans('admincp.success-saved'));
    }

    public function disable($addon)
    {
        $this->addonRepository->disable($addon);
        return \Redirect::to(\URl::previous())->with('message', trans('admincp.success-saved'));
    }

    public function update($addon)
    {
        $this->addonRepository->update($addon);
        return \Redirect::to(\URl::previous())->with('message', trans('admincp.success-saved'));
    }

    public function configurations($addon)
    {
        return $this->theme->view('addons.configurations', [
            'configurations' => $this->configurationRepository->lists('app/Addons/'.ucwords($addon).'/config'),
            'configurationRepository' => $this->configurationRepository
        ])->render();
    }
}