<?php

namespace App\Controllers;

use App\Controllers\Base\UserBaseController;
use App\Repositories\HidePostRepository;
use App\Repositories\PostRepository;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class PostController extends UserBaseController
{
    public function __construct(PostRepository $postRepository, HidePostRepository $hidePostRepository)
    {
        parent::__construct();
        $this->postRepository = $postRepository;
        $this->hidePost = $hidePostRepository;
    }

    public function index($id)
    {
        $post = $this->postRepository->findById($id);

        if (!$post or !$post->present()->canView()) {

            if (\Request::ajax()) {
                return '';
            }
            return $this->theme->section('error-page');
        }

        $this->theme->share('site_description', ($post->text) ? \Str::limit($post->text, 100) : \Config::get('site_description'));
        $this->theme->share('ogUrl', \URL::route('post-page', ['id' => $post->id]));

        if ($post->text)  $this->theme->share('ogTitle', \Str::limit($post->text, 100));

        if ($post->present()->hasValidImage()) {
            foreach($post->present()->images() as $image) {
                $this->theme->share('ogImage', \Image::url($image, 600));
            }
        }


        $ad = (empty($post->text)) ? trans('post.post')  : str_limit($post->text,60);
        $design = [];
        if ($post->page_id) {
            $design = $post->page->present()->readDesign();
        } elseif($post->community_id) {
            $design = $post->community->present()->readDesign();
        } else {
            $design = $post->user->present()->readDesign();
        }
        return $this->render('post.page', ['post' => $post],
            [
                'title' => $this->setTitle($ad),
                'design' => $design

            ]
        );
    }

    public function downloadFile($id)
    {
       $post = $this->postRepository->findById($id);

        if (!$post and !$post->file_path or !$post->present()->canView()) return 'Wrong Requests';


        $file = base_path().'/'.$post->file_path;

        $CDNRepository = app('App\\Repositories\\CDNRepository');
        $cdn = false;

        if ($CDNRepository->has($post->file_path)) {
            $file = $CDNRepository->getLink($post->file_path);
            $cdn = true;
        }

        $this->Download($file, null, $cdn);
    }

    public function Download($path, $speed = null, $cdn = false)
    {

        if (is_file($path) === true or $cdn)
        {

            set_time_limit(0);

            while (ob_get_level() > 0)
            {
                ob_end_clean();
            }

            $ext = pathinfo($path, PATHINFO_EXTENSION);
            $basename = md5(basename($path).time().time()).'.'.$ext;

            $size = ($cdn) ? curl_get_file_size($path) : sprintf('%u', filesize($path));
            $speed = (is_null($speed) === true) ? $size : intval($speed) * 1024;

            header('Expires: 0');
            header('Pragma: public');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Content-Type: application/octet-stream');
            header('Content-Length: ' . $size);
            header('Content-Disposition: attachment; filename="' . $basename . '"');
            header('Content-Transfer-Encoding: binary');

            for ($i = 0; $i <= $size; $i = $i + $speed)
            {
                echo file_get_contents($path, false, null, $i, $speed);

                while (ob_get_level() > 0)
                {
                    ob_end_clean();
                }

                flush();
                sleep(1);
            }

            exit();
        }

        return false;
    }

    public function playVideo()
    {
        $videoUrl = \Input::get('path');

        return $this->theme->section('post.video', ['path' => $videoUrl]);
    }

    public function loadLinkPreview()
    {
        $link = \Input::get('link');
        $link = str_replace(['http://', 'https://'], ['', ''], $link);
        $link = 'http://'.$link;
        $result = $this->postRepository->getLinkDetails($link, true);
        $result['preview'] = (String) $this->theme->section('post.editor.link-preview', ['result' => $result]);

        return json_encode($result);
    }

    public function searchMedia()
    {
        $v = \Input::get('v');
        $type = \Input::get('type');

        if ($type == 'audio') {
            $soundcloud = 'http://api.soundcloud.com/tracks.json?client_id=e8d2797b62ce47938f3baa699a725864&limit=5&q=' . urlencode($v);

            $soundcloud = @file_get_contents($soundcloud);


            $results = json_decode($soundcloud, true);


            $a = [];
            if (is_array($results) and count($results) > 1) {
                foreach($results as $s) {

                    if ($s['kind'] == 'track') {
                        $a[] = [
                            'title' => $s['title'],
                            'description' => $s['description'],
                            'link' => $s['uri'],
                            'image' => $s['artwork_url']
                        ];
                    }
                }

                return $this->theme->section('post.format-media-search', ['medias' => $a]);
            } else {
                //return (string) trans('post.no-result-found');
            }
        } elseif ($type == 'video') {
            if (preg_match('#vimeo#', $v)) return '';
            $youtube = 'http://gdata.youtube.com/feeds/api/videos?q=' . urlencode($v) . '&max-results=5&orderby=relevance&alt=json&format=5&v=2';
            $youtube = @file_get_contents($youtube);
            $results = json_decode($youtube, true);

            $libPath = app_path('library/Google/src/Google/');

            require_once $libPath.'Client.php';
            require_once $libPath.'Service/YouTube.php';

            $client = new \Google_Client();
            $youtubeKey = \Config::get('youtube-key');
            $youtubeKey = (empty($youtubeKey)) ? '' : $youtubeKey;
            $client->setDeveloperKey($youtubeKey);

            $youtube = new \Google_Service_YouTube($client);

            $maxLimit = \Config::get('youtube-result-limit');
            $maxLimit = (!$maxLimit) ? 5 : $maxLimit;

            try {
                $searchResult = $youtube->search->listSearch('id,snippet', [
                    'q' => $v,
                    'maxResults' => $maxLimit
                ]);

                $a = [];
                foreach ($searchResult['items'] as $result) {
                    switch($result['id']['kind']) {
                        case 'youtube#video':
                            $l = "http://www.youtube.com/embed/".$result['id']['videoId'];
                            $a[] = [
                                'title' => $result['snippet']['title'],
                                'link' => (String) $l,
                                'image' => $result['snippet']['thumbnails']['medium']['url']
                            ];

                            break;
                    }
                }

                return $this->theme->section('post.format-media-search', ['medias' => $a]);
            } catch(\Exception $e){}


        }


    }


    public function add()
    {
        if ($post = $this->postRepository->add(\Input::get('val'))) {

            return (String) $this->theme->section('post.media', ['post' => $post]);
        }

        return 0;
    }

    public function edit($id)
    {
        $text = \Input::get('text');

        $post = $this->postRepository->updateText($id, $text);

        if ($post) {
            return $post->present()->text();
        }
    }

    public function uploadImage()
    {
        $result = [
            'code' => 0,
            'message' => trans('post.post-image-error')
        ];
        if ($image = \Input::file('image')) {
            $uploadImage = $this->postRepository->uploadImage($image);

            if ($uploadImage) {
                $result['image'] = $uploadImage;
                $result['imageurl'] = \Image::url($uploadImage, 100);
                $result['code'] = 1;
            }
        }

        return json_encode($result);
    }

    public function share($id)
    {
        if ($count = $this->postRepository->share($id)) {
            return json_encode([
                'code' => 1,
                'count' => $count
            ]);
        }

        return json_encode([
            'code' => 0,
            'message' => 'Failed to share post, please try again'
        ]);
    }

    public function delete($id)
    {
        $this->postRepository->delete($id);
    }

    public function hide($id)
    {
        $this->hidePost->add($id);

        return $this->theme->section('post.hide');
    }

    public function unHide($id)
    {
        $this->hidePost->remove($id);
    }

    public function loadShares($id)
    {
        if (\Request::ajax()) {
            return $this->theme->section('post.shares.inline', ['posts' => $this->postRepository->getShares($id)]);
        }
    }

    public function paginate()
    {
        $type = \Input::get('type');
        $offset = \Input::get('offset');
        $userid = \Input::get('userid');
        $offset = (empty($offset)) ? \Config::get('post-per-page') : $offset;
        $newOffset = $offset  + (int) \Config::get('post-per-page');


        return json_encode([
            'offset' =>$newOffset,
            'posts' => (String)  $this->theme->section('post.paginate', ['posts' => $this->postRepository->lists($type, $offset, $userid)])
        ]);
    }

}