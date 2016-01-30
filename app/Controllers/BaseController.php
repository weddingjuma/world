<?php

class BaseController extends Controller {

    public function __construct()
    {

        $this->theme = Theme::type('frontend')
            ->current(\ThemeManager::getActive('frontend'))
            ->reBoot()
            ->layout('layouts.default');
        $this->setTitle();

        $this->theme->share('loggedInUser', \Auth::user());

        $this->theme->share('languages', app('App\Repositories\LanguageRepository')->all());

        //attach before filter
        if (Config::get('maintenance-mode', 0)) {
            if (Auth::check() and Auth::user()->id == 1) {
                //don't enable it for maintain admin
            } else {
                $this->beforeFilter('maintenance');
            }
        }


        /**
         * Set global site variables
         */
        $updateSpeed = \Config::get('realtime-check-interval', 30000);
        $updateSpeed = (!$updateSpeed) ? 30000 : $updateSpeed;

        $this->theme->share('site_name', \Config::get('site_title'));
        $this->theme->share('site_description', \Config::get('site_description'));
        $this->theme->share('site_keywords', \Config::get('site_keywords'));
        $this->theme->share('ogType', 'website');
        $this->theme->share('ogSiteName', \Config::get('site_title'));
        $this->theme->share('ogUrl', \URL::to('/'));
        $this->theme->share('ogTitle', \Config::get('site_title'));
        $this->theme->share('ogImage', $this->theme->asset()->img('theme/images/logo.png'));


        $ajaxify = (\Config::get('ajaxify_frontend')) ? 'true' : 'false';

        $emoticons = \Theme::option()->get('emoticons');
        $emoticonLists = '';



        if ($emoticons){
            foreach($emoticons as $code => $details) {
                $emoticonLists .= "'<img width=\'16\' height=\'16\' src=\'".$details['image']."\' title=\'".$details['title']."\'/>',\n";
            }
        }

        $this->theme->asset()->beforeScriptContent("
            var baseUrl = '".\URL::to('/')."/';
            var siteName = '".\Config::get('site_title')."';
            var doAjaxify = ".$ajaxify.";
            var maxPostImage = ".\Config::get('max-images-per-post', '').";
            var updateSpeed = ".$updateSpeed.";
            var isLogin = '".((Auth::check()) ? 'true' : 'false')."';
            var imgIndicator = '".\Theme::asset()->img('theme/images/loading.gif')."';

            //time_ago translation
            var trans_ago = '".trans('global.ago')."';
            var trans_from_now = '".trans('global.from-now')."';
            var trans_any_moment = '".trans('global.any-moment')."';
            var trans_less_than_minute = '".trans('global.less-than-minute')."';
            var trans_about_minute = '".trans('global.about-minute')."';
            var trans_minutes = '".trans('global.minutes')."';
            var trans_about_hour = '".trans('global.about-hour')."';
            var trans_hours = '".trans('global.hours')."';
            var trans_about = '".trans('global.about')."';
            var trans_a_day = '".trans('global.a-day')."';
            var trans_days = '".trans('global.days')."';
            var trans_about_month = '".trans('global.about-month')."';
            var trans_months = '".trans('global.months')."';
            var trans_about_year = '".trans('global.about-year')."';
            var trans_years = '".trans('global.years')."';
            var emoticons = [".$emoticonLists."];
        ");

        /**
         * Usefull repository in views
         */
        $this->theme->share('connectionRepository', app('App\\Repositories\\ConnectionRepository'));
        $this->theme->share('searchRepository', app('App\\Repositories\\SearchRepository'));
        $this->theme->share('notificationRepository', app('App\\Repositories\\NotificationRepository'));
        $this->theme->share('likeRepository', app('App\\Repositories\\LikeRepository'));

    }
	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

    public function setTitle($title = null)
    {
        $title = (empty($title)) ? null : ' - '.$title;
        $title = \Config::get('site_title').$title;
        $this->theme->setTitle($title);

        return $title;
    }

    public function render($path, $param = array(), $setting = [])
    {
        $expectedSettings = [
            'container' => '#content-container',
            'title' => \Config::get('site_title'),
            'content' => ''
        ];
        $settings = array_merge($expectedSettings, $setting);

        if (\Request::ajax()) {
            $settings['content'] = (String) $this->theme->section($path, $param);

            if (isset($settings['design'])) $settings['design'] = json_encode($settings['design']);

            return json_encode($settings);
        } else {
            return $this->theme->view($path, $param)->render();
        }
    }


}
