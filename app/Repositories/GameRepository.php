<?php

namespace App\Repositories;
use App\Interfaces\PhotoRepositoryInterface;
use App\Models\Game;
use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Mail\Mailer;

/**
*
*@author: Tiamiyu waliu kola
*@website : www.crea8social.com
*/
class GameRepository
{
    public function __construct(
        Game $game,
        PhotoRepositoryInterface $photoRepositoryInterface,
        Mailer $mailer,
        NotificationRepository $notificationRepository,
        Filesystem $filesystem,
        MustAvoidUserRepository $mustAvoidUserRepository,
        Dispatcher $event,
        GameCategoryRepository $gameCategoryRepository
    )
    {
        $this->model = $game;
        $this->photoRepository = $photoRepositoryInterface;
        $this->mailer = $mailer;
        $this->notification = $notificationRepository;
        $this->file = $filesystem;
        $this->mustAvoidUserRepository = $mustAvoidUserRepository;
        $this->event = $event;
        $this->category = $gameCategoryRepository;
    }

    /**
     * Method to add games
     *
     * @param array $val
     * @param boolean $isAdmin
     * @return bool
     */
    public function add($val, $isAdmin = false)
    {
        $expected = [
            'title',
            'description',
            'category',
            'content' => '',
            'approved' => (\Config::get('game-need-confirm')) ? 0 : 1,
            'verified' => 0,
            'width' => '100%',
            'height' => '450'
        ];

        /**
         * @var $title
         * @var $description
         * @var $category
         * @var $approved
         * @var $verified
         * @var $content
         * @var $width
         * @var $height
         */
        extract(array_merge($expected, $val));

        if ($isAdmin) {
            $approved = 1;
        }

        $gameFile = '';

        if (\Input::hasFile('file')) {
            $maxSize = \Config::get('game-max-upload', 10000000);
            $file = \Input::file('file');
            $ext = $file->getClientOriginalExtension();

            if ($file->getSize() > $maxSize or strtolower($ext) != 'swf') return false;
            $userid = \Auth::user()->id;
            $filePath = "uploads/games/" . $userid. '/';

            //ensure the folder exists

            $this->file->makeDirectory(public_path().'/'.$filePath, 0777, true, true);
            $fileName = md5($file->getClientOriginalName().time()).'.swf';
            $gameFile = $filePath.$fileName;

            $file->move(public_path().'/'.$filePath, $fileName);

        }

        $gameIcon = '';

        if (\Input::hasFile('icon')) {
            if (!$this->photoRepository->imagesMetSizes(\Input::file('icon'))) return false;
            $user = \Auth::user();
            $gameIcon = $this->photoRepository->upload(\Input::file('icon'), [
                'path' => 'users/'.$user->id,
                'slug' => 'games-',
                'userid' => $user->id
            ]);

        }
        //one of game file and content must not be empty
        if (empty($gameFile) and empty($content)) return false;

        $category = sanitizeText($category);

        if (!$this->category->get($category)) return false;

        $slug = toAscii($title);

        if (!empty($title)) {
            $game = $this->model->newInstance();
            $game->title = sanitizeText($title, 130);
            $game->description = sanitizeText($description);
            $game->category = $category;
            $game->user_id = \Auth::user()->id;
            $game->verified = $verified;
            $game->slug = hash('crc32', $title.time());
            $game->approved = $approved;
            if (isset($content)) $game->iframe_content = $content;
            $game->game_path = $gameFile;
            $game->logo = $gameIcon;
            if ($width) $game->width = sanitizeText($width);
            if ($height) $game->height = sanitizeText($height);
            $game->save();

            $this->event->fire('game.add', [$game]);

            return $game;
        }

        return false;
    }

    public function save($val, $game, $isAdmin = false)
    {
        $expected = [
            'title',
            'description',
            'category',
            'content' => '',
            'approved' => (\Config::get('game-need-confirm')) ? 0 : 1,
            'verified' => 0,
            'info' => [],
            'width' => '100%',
            'height' => '450'
        ];

        /**
         * @var $title
         * @var $description
         * @var $category
         * @var $approved
         * @var $verified
         * @var $content
         * @var $info
         * @var $width
         * @var $height
         */
        extract(array_merge($expected, $val));

        if ($isAdmin or \Auth::user()->isAdmin()) {
            $approved = 1;
        }

        $gameFile = '';

        if (\Input::hasFile('file')) {
            $maxSize = \Config::get('game-max-upload', 10000000);
            $file = \Input::file('file');
            $ext = $file->getClientOriginalExtension();

            if ($file->getSize() > $maxSize or strtolower($ext) != 'swf') return false;
            $userid = \Auth::user()->id;
            $filePath = "uploads/games/" . $userid. '/';

            //ensure the folder exists

            $this->file->makeDirectory(public_path().'/'.$filePath, 0777, true, true);
            $fileName = md5($file->getClientOriginalName().time()).'.swf';
            $gameFile = $filePath.$fileName;

            if (!empty($game->game_path)) {
                //lets delete this old game
                $this->file->delete(public_path('').'/'.$game->game_path);
            }

            $file->move(public_path().'/'.$filePath, $fileName);

        }

        //one of game file and content must not be empty
        //if (empty($gameFile) and empty($content)) return false;

        $category = sanitizeText($category);

        if (!$this->category->get($category)) return false;



        if (!empty($title)) {

            $game->title = sanitizeText($title, 130);

            $game->description = sanitizeText($description);
            $game->category = $category;
            $game->user_id = \Auth::user()->id;
            $game->verified = $verified;
            $game->approved = $approved;
            $game->iframe_content = $content;
            if ($gameFile) $game->game_path = $gameFile;

            if ($width) $game->width = sanitizeText($width);
            if ($height) $game->height = sanitizeText($height);

            $game->info = serialize(sanitizeUserInfo($info));
            $game->save();

            return $game;
        }

        return false;
    }

    public function exists($title)
    {
        return $this->model
            ->where('title', '=', $title)
            ->whereNotIn('user_id', $this->mustAvoidUserRepository->get())
            ->orWhere('id', '=', $title)
            ->first();
    }

    public function seperateExists($title, $id)
    {
        return $this->model->where('slug', '=', $title)->Where('id', '!=', $id)->first();
    }

    public function get($id)
    {
        return $this->exists($id);
    }

    public function getProfile($slug)
    {
        return $this->model->where('slug', '=', $slug)
            ->whereNotIn('user_id', $this->mustAvoidUserRepository->get())
            ->first();
    }

    public function approve($id)
    {
        $game = $this->get($id);

        if($game) {
            $game->approved = 1;
            $game->save();

            //send notification to owner
            $user = $game->user;
            $this->notification->send($user->id, [
               'path' => 'notification.game.approved',
                'game' => $game
            ]);

            try{
                $this->mailer->queue('emails.game.approved', [
                    'fullname' => $user->fullname,
                    'username' => $user->username,
                    'gameLink' => $game->present()->url()
                ], function($mail) use($user) {
                    $mail->to($user->email_address, $user->fullname)
                        ->subject(trans('mail.game-approved', ['fullname' => $user->fullname]));
                });
            } catch(\Exception $e) {

            }
        }
    }

    public function listAll($limit = 10, $cat = null)
    {
         $games = $this->model->with('cat', 'user', 'likes')
             ->whereNotIn('user_id', $this->mustAvoidUserRepository->get());

         if ($cat) {
             $games = $games->where('category', '=', $cat);
         }

         return $games = $games->where('approved', '=', 1)->orderBy('id', 'desc')->paginate($limit);
    }

    public function suggest($limit)
    {
        $games = $this->model->with('cat', 'user', 'likes')
            ->whereNotIn('user_id', $this->mustAvoidUserRepository->get());


        $query = $games->where('approved', '=', 1)->orderBy(\DB::raw('rand()'));

        if (\Config::get('enable-query-cache')) {
            return $query = $query->remember(\Config::get('query-cache-time-out', 5), 'game-suggestions-'.\Auth::user()->id)->take($limit)->get();
        } else {
            return $query = $query->paginate($limit);
        }
    }

    public function adminList($limit = 10, $cat = null)
    {
        $games = $this->model->with('cat', 'user', 'likes');

        if ($cat) {
            $games = $games->where('category', '=', $cat);
        }

        return $games = $games->orderBy('id', 'desc')->paginate($limit);
    }

    public function myList($limit = 10)
    {
        return $this->model->with('cat', 'user', 'likes')->where('user_id', '=', \Auth::user()->id)->orderBy('id', 'desc')->paginate($limit);
    }

    public function search($term, $limit = 10)
    {
        $games = $this->model->with('cat', 'user', 'likes')
            ->whereNotIn('user_id', $this->mustAvoidUserRepository->get());

        $games = $games->where(function($games) use($term) {
             $games->where('title', 'LIKE', '%'.$term.'%')
                 ->orWhere('description', 'LIKE', '%'.$term.'%');
        });

        return $games = $games->where('approved', '=', 1)->orderBy('id', 'desc')->paginate($limit);

    }

    public function delete($id)
    {
        $game = $this->get($id);

        if ($game) {
            if ($game->user_id != \Auth::user()->id) {
                if (!\Auth::user()->isAdmin()) return false;
            }
            $game->delete();
            foreach([
                        'App\\Repositories\\LikeRepository',
                    ] as $object) {
                app($object)->deleteAllByGame($id);
            }
        }
    }

    public function changePhoto($image, $game)
    {
        $user = (empty($user)) ? \Auth::user() : $user;
        $image = $this->photoRepository->upload($image, [
            'path' => 'users/'.$user->id,
            'slug' => 'games-'.$game->id,
            'userid' => $user->id
        ]);

        /**
         * Now save user avatar
         */
        $game->logo = $image;
        $game->save();

        return $image;
    }

    public function deleteAllByUser($userid)
    {
        return $this->model->where('user_id', '=', $userid)->delete();
    }

    public function total()
    {
        return $this->model->count();
    }
}