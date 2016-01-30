<?php

namespace App\Controllers;
use App\Controllers\Base\GameBaseController;
use App\Repositories\GameCategoryRepository;

/**
*
*@author: Tiamiyu waliu kola
*@website : www.crea8social.com
*/
class  GameProfileController extends GameBaseController
{
    public function __construct(GameCategoryRepository $categoryRepository)
    {
        parent::__construct();
        $this->categoryRepository = $categoryRepository;
    }

    public function index()
    {
        if (!$this->exists()) {
            return \Redirect::route('games');
        }
        return $this->render('game.profile.index', [], []);
    }

    public function edit()
    {
        $message = null;

        if ($val = \Input::get('val')) {

            $validator = \Validator::make($val, [
                'title' => 'required',
            ]);

            if (!$validator->fails()) {
                $game = $this->gameRepository->save($val, $this->game);

                if ($game) {
                    return \Redirect::to($this->game->present()->url());
                } else {

                    $message = trans('game.edit-error');
                }
            } else {
                $message = $validator->messages()->first();
            }
        }

        return $this->render('game.profile.edit', ['message' => $message, 'categories' => $this->categoryRepository->listAll()], []);
    }
}