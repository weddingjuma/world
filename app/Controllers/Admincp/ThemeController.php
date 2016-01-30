<?php

namespace App\Controllers\Admincp;


use App\Repositories\ConfigurationRepository;
use App\Repositories\ThemeRepository;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class ThemeController extends AdmincpController
{
    public function __construct(ThemeRepository $themeRepository, ConfigurationRepository $configurationRepository)
    {
        parent::__construct();
        $this->themeRepository = $themeRepository;
        $this->configrationRepository = $configurationRepository;
        $this->activePage('theme');
    }

    public function index()
    {
        $type = \Request::query('type', 'frontend');

        $this->setTitle(trans('theme.theme-management'));

        return $this->theme->view('theme.index', [
            'types' => $this->themeRepository->getTypes(),
            'currentType' => $type,
            'themes' => $this->themeRepository->getThemes($type),
            'themeRepository' => $this->themeRepository
        ])->render();
    }

    public function setActive()
    {
        $type = \Request::query('type', 'frontend');
        $theme = \Request::query('theme', 'default');

        $this->themeRepository->setActive($type, $theme);

        return \Redirect::to(\URL::route('admincp-theme').'?type='.$type);
    }

    public function configurations()
    {
        $type = \Request::query('type', 'frontend');
        $theme = \Request::query('theme', 'default');
        //exit('themes/'.$type.'/'.$theme.'/config');
        return $this->theme->view('theme.configurations', [
            'configurations' => $this->configrationRepository->lists('themes/'.$type.'/'.$theme.'/config'),
            'configurationRepository' => $this->configrationRepository
        ])->render();
    }
}
 