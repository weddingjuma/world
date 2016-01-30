<?php

namespace App\Controllers\Admincp;

use App\Repositories\ConfigurationRepository;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class ConfigurationController extends AdmincpController
{
    public function __construct(ConfigurationRepository $configurationRepository)
    {
        parent::__construct();
        $this->activePage('configurations');
        $this->configurationRepository = $configurationRepository;
    }

    public function index($type)
    {
        $this->setTitle(trans('admincp.configurations'));
        return $this->theme->view('configuration.index', [
            'configurations' => $this->configurationRepository->lists('app/config/site/'.$type),
            'type' => $type,
            'configurationRepository' => $this->configurationRepository
        ])->render();
    }

    public function save()
    {
        if ($val = \Input::get('val')) {
            $this->configurationRepository->save($val);

            return \Redirect::to(\URl::previous())->with('message', trans('admincp.success-saved'));
        }

        return \Redirect::to(\URl::previous())->with('message', trans('admincp.save-error'));
    }

    public function update()
    {
        $this->configurationRepository->update();
        return \Redirect::to(\URl::previous())->with('message', trans('admincp.success-saved'));
    }
}
 