<?php

namespace App\Install;

/**
*
*@author: Tiamiyu waliu kola
*@website : www.crea8social.com
*/
class InstallController extends \BaseController
{
    public function __construct(InstallRepository $installRepository)
    {
        $this->installRepository = $installRepository;
    }

    public function dbInfo()
    {
        if (\Config::get('system.installed')) return \Redirect::to('/');
        //if (!\Session::has('valid-usage')) return \Redirect::to('/install');
        $message = null;
        if($val = \Input::get('val')) {
            $db = $this->installRepository->installDbinfo($val);

            if ($db) {
                //redirect
                return \Redirect::route('install-db');
            } else {

                $message = "Failed: Please confirm your details, unable to connect to database";
            }
        }
        return $this->instalRender(\View::make('install.db', ['message' => $message]), '');
    }

    public function db()
    {
        if (\Config::get('system.installed')) return \Redirect::to('/');
        //if (!\Session::has('valid-usage')) return \Redirect::to('/install');
        $this->installRepository->installDB();
        return \Redirect::route('install-site-info');
    }

    public function site()
    {
        if (\Config::get('system.installed')) return \Redirect::to('/');
        //if (!\Session::has('valid-usage')) return \Redirect::to('/install');
        $message = null;

        if ($val = \Input::get('val')) {
            $user = $this->installRepository->createAccount($val);

            if ($user) {
                return \Redirect::to('/');
            } else {
                $message = "Failed : Please check your details";
            }
        }
        return $this->instalRender(\View::make('install.site', ['message' => $message]), '');
    }

    public function index()
    {
        if (\Config::get('system.installed')) return \Redirect::to('/');

        $error = false;

        return $this->instalRender(\View::make('install.index', ['error' => $error]), '');
    }

    public function instalRender($content, $title = null)
    {
        return \View::make('install.layout', ['content' => $content, 'title' => $title]);
    }



    public function check()
    {
        $domain = \Input::get('domain');
        $buyerCode = \Input::get('code');
        $apiKey = "2ndevom38wcgtiag8r52hrn09rhvb18o";
        ini_set('user_agent', 'Mozilla/5.0');

        try{
            $requestUrl = 'http://marketplace.envato.com/api/edge/procrea8/'.$apiKey.'/verify-purchase:'.$buyerCode.'.json';

            $jsonContent = file_get_contents($requestUrl);


            if (empty($jsonContent)) return '0';

            $jsonData = json_decode($jsonContent, true);


            if (isset($jsonData['error'])) return '0';

            if (empty($jsonData['verify-purchase'])) return '0';




            return '1';
        } catch(\Exception $e) {
            return '0';
        }

    }


}