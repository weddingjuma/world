<?php

namespace App\Controllers;

use App\Controllers\Base\UserBaseController;
use App\Repositories\CommunityRepository;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class CommunityController extends UserBaseController
{
    public function __construct(CommunityRepository $communityRepository)
    {
        $this->communityRepostory = $communityRepository;
        parent::__construct();
    }

    public function index()
    {
        return $this->preRender($this->theme->section('community.index', [
            'communities' => $this->communityRepostory->getMyCommunities()
        ]), $this->setTitle(trans('community.communities')));
    }

    public function joined()
    {
        return $this->preRender($this->theme->section('community.joined', [
            'communities' => $this->communityRepostory->getJoinedCommunities()
        ]), $this->setTitle(trans('community.communities')));
    }

    public function create()
    {
        $message = null;
        if ($val = \Input::get('val')) {

            /**
             * @var $privacy
             */

            extract($val);
            if($privacy == 1) {
                $validator = \Validator::make($val, [
                    'public_name' => 'required|predefined|validalpha|min:3',
                    'public_url' => 'required|predefined|validalpha|min:3|alpha_dash|slug|unique:communities,slug'
                ]);
            } else {
                $validator = \Validator::make($val, [
                    'private_name' => 'required|predefined|validalpha|min:3',
                    'private_url' => 'required|predefined|validalpha|min:3|alpha_dash|slug|unique:communities,slug'
                ]);
            }

            if (!$validator->fails()) {
                $community = $this->communityRepostory->create($val);

                if ($community) {
                    //redirect to community page
                    return \Redirect::to($community->present()->url());
                } else {
                    $message = trans('community.create-error');
                }
            } else {
                $message = $validator->messages()->first();
            }
        }

        return $this->preRender($this->theme->section('community.create', ['message' => $message]),
            $this->setTitle(trans('community.create')));
    }

    public function leave($id)
    {
        $userid = \Input::get('userid');
        $this->communityRepostory->leave($id, $userid);

        return \Redirect::to(\URL::previous());
    }

    public function assignModerator($id, $userid)
    {
        $this->communityRepostory->assignModerator($id, $userid);

        return \Redirect::to(\URL::previous());
    }

    public function removeModerator($id, $userid)
    {
        $this->communityRepostory->removeModerator($id, $userid);

        return \Redirect::to(\URL::previous());
    }

    public function delete($id)
    {
        $this->communityRepostory->delete($id);

        $ref = \Input::get('ref', false);

        if ($ref) return \Redirect::to(\URL::previous());

        return \Redirect::route('communities');
    }

    public function preRender($content, $title)
    {
        return $this->render('community.layout', ['content' => $content], ['title' => $title]);
    }
}