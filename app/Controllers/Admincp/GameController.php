<?php

namespace App\Controllers\Admincp;
use App\Repositories\GameCategoryRepository;
use App\Repositories\GameRepository;

/**
*
*@author: Tiamiyu waliu kola
*@website : www.crea8social.com
*/
class GameController extends AdmincpController
{
    public function __construct(
        GameCategoryRepository $categoryRepository,
        GameRepository $gameRepository
    )
    {
        parent::__construct();
        $this->activePage('games');
        $this->categoryRepository = $categoryRepository;
        $this->gameRepository = $gameRepository;
    }

    public function index()
    {
        $this->setTitle('Manage Games');
        return $this->theme->view('games.index', ['games' => $this->gameRepository->adminList()])->render();
    }

    public function approveGame($id)
    {
        $this->gameRepository->approve($id);
        return \Redirect::to(\URL::previous());
    }

    public function add()
    {
        $message = null;

        if ($val = \Input::get('val')) {
            $game = $this->gameRepository->add($val, true);

            if ($game) {
                //send to game list
                return \Redirect::route('admincp-games');
            } else {
                $message = 'Failed to add game, maybe the game already exist or invalid game details';
            }
        }

        return $this->theme->view('games.add', [
            'categories' => $this->categoryRepository->listAll(),
            'message' => $message
        ])->render();
    }

    public function editGame($id)
    {
        $this->setTitle('Edit Game Category');
        $game = $this->gameRepository->get($id);

        if (!$game) return \Redirect::to(\URL::previous());

        $message = null;

        if ($val = \Input::get('val')) {
            $game = $this->gameRepository->save($val, $game, true);

            if ($game) {
                $message = "Game successfully saved";
            } else {
                $message = "Failed : please check your details, if you are change title, it may already exists";
            }
        }

        return $this->theme->view('games.edit', [
            'categories' => $this->categoryRepository->listAll(),
            'game' => $game,
            'message' => $message
        ])->render();
    }

    public function categories()
    {
        $this->setTitle('Game Categories');

        return $this->theme->view('games.categories.index', ['categories' => $this->categoryRepository->lists()])->render();
    }

    public function createCategory()
    {
        $this->setTitle('Add Game Categories');

        $message = null;
        if ($val = \Input::get('val')) {

            $validator = \Validator::make($val, [
                'title' => 'required|alpha_dash'
            ]);

            if (!$validator->fails()) {
                $category = $this->categoryRepository->add($val);
                if($category) {
                    $message = "Category added successfully";
                } else {
                    $message = "Failed to add category due to existence or invalid details";
                }
            } else {
                $message = $validator->messages()->first();
            }
        }

        return $this->theme->view('games.categories.add', ['message' => $message])->render();
    }

    public function editCategory($id)
    {
        $this->setTitle('Edit Game Category');
        $category = $this->categoryRepository->get($id);

        if (!$category) return \Redirect::to(\URL::previous());

        $message = null;

        if ($val = \Input::get('val')) {
            $validator = \Validator::make($val, [
                'title' => 'required|alpha_dash'
            ]);

            if ($validator->fails()) {
                $message = $validator->messages()->first();
            } else {
                $s = $this->categoryRepository->save($val, $category);
                if($s) {
                    $message = "Category save successfully";
                } else {
                    $message = "Failed to add category due to existence or invalid details";
                }
            }
        }
        return $this->theme->view('games.categories.edit', ['message' => $message, 'category' => $category])->render();

    }

    public function deleteCategory($id)
    {
        $this->categoryRepository->delete($id);
        return \Redirect::to(\URL::previous());
    }
}