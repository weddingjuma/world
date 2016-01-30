<?php

namespace App\Controllers;
use App\Controllers\Base\UserBaseController;
use App\Interfaces\PhotoRepositoryInterface;
use App\Repositories\GameCategoryRepository;
use App\Repositories\GameRepository;

/**
*
*@author: Tiamiyu waliu kola
*@website : www.crea8social.com
*/
class GameController extends UserBaseController
{
    public function __construct(
        GameRepository $gameRepository,
        GameCategoryRepository $categoryRepository,
        PhotoRepositoryInterface $photoRepositoryInterface
    )
    {
        parent::__construct();
        $this->gameRepository = $gameRepository;
        $this->categoryRepository = $categoryRepository;
        $this->photoRepository = $photoRepositoryInterface;

    }

    public function out()
    {
        return $this->test->out();
    }

    public function index()
    {
        return $this->preRender($this->theme->section('game.index', [
            'games' => $this->gameRepository->listAll(10, \Input::get('category'))
        ]), $this->setTitle(trans('game.games')));
    }

    public function myGames()
    {
        return $this->preRender($this->theme->section('game.index', [
            'games' => $this->gameRepository->myList(10, \Input::get('category'))
        ]), $this->setTitle(trans('game.games')));
    }

    public function add()
    {
        if (!\Config::get('game-create-allowed')) return \Redirect::to(\URL::previous());

        $message = null;
        if ($val = \Input::get('val')) {

            $validator = \Validator::make($val, [
                'title' => 'required|predefined|validalpha|min:3',
            ]);

            if (!$validator->fails()) {
                $game = $this->gameRepository->add($val);

                if ($game) {
                    //send to game list
                    if (\Config::get('game-create-allowed') and !\Auth::user()->isAdmin()) {
                        $message = "<strong>Thanks for adding game:</strong> One of our administrator will now inspect your game and confirm very soon";
                    } else {
                        return \Redirect::to($game->present()->url());
                    }
                } else {
                    $message = trans('game.error');
                }
            } else {
                $message = $validator->messages()->first();
            }
        }

        return $this->preRender($this->theme->section('game.add', [
            'message' => $message,
            'categories' => $this->categoryRepository->listAll()
        ]), $this->setTitle(trans('game.add-games')));
    }

    public function preRender($content, $title)
    {
        return $this->render('game.layout', ['content' => $content], ['title' => $title]);
    }

    public function delete($id)
    {
        $this->gameRepository->delete($id);

        return \Redirect::to(\URL::previous());
    }

    public function changePhoto()
    {
        $id = \Input::get('id');
        $game = $this->gameRepository->get($id);

        $response = [
            'code' => 0,
            'message' => trans('photo.error', ['size' => formatBytes()]),
            'url' => ''
        ];

        if (\Request::hasFile('image')) {

            if (!$this->photoRepository->imagesMetSizes(\Input::file('image'))) return json_encode($response);

            $image = $this->gameRepository->changePhoto(\Input::file('image'), $game);
            if ($image) {
                $response['code'] = 1;
                $response['url'] = \Image::url($image, 100);
            }
        }

        return json_encode($response);
    }

}