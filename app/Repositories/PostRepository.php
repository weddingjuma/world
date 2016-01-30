<?php

namespace App\Repositories;

use App\Interfaces\PhotoRepositoryInterface;
use App\Models\Post;
use Illuminate\Cache\Repository;
use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Whoops\Example\Exception;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class PostRepository
{
    public function __construct(
        Post $post,
        Dispatcher $event,
        PhotoRepositoryInterface $photoRepositoryInterface,
        ConnectionRepository $connectionRepository,
        BlockedUserRepository $blockedUserRepository,
        NotificationReceiverRepository $notificationReceiverRepository,
        NotificationRepository $notificationRepository,
        Repository $cache,
        HashtagRepository $hashtagRepository,
        PhotoAlbumRepository $photoAlbumRepository,
        Filesystem $filesystem,
        MustAvoidUserRepository $mustAvoidUserRepository
    )
    {
        $this->model = $post;
        $this->event = $event;
        $this->photoRepository = $photoRepositoryInterface;
        $this->connectionRepository = $connectionRepository;
        $this->blockedUserRepository = $blockedUserRepository;
        $this->notificationReceiver = $notificationReceiverRepository;
        $this->notification = $notificationRepository;
        $this->hashtagRepository = $hashtagRepository;
        $this->cache = $cache;
        $this->photoAlbum = $photoAlbumRepository;
        $this->mustAvoidUserRepository = $mustAvoidUserRepository;
        $this->file = $filesystem;
        $this->hidePostRepository = app('App\Repositories\HidePostRepository');
    }

    /**
     * Method to find a post by its ID
     *
     * @param int $id
     * @return \App\Models\Post
     */
    public function findById($id)
    {
        return $this->model->with('user')->where('id', '=', $id)->first();
    }

    /**
     * Method to links from a text
     *
     * @param string $text
     * @return array
     */
    public function getLinks($text)
    {
        $links = array();

        $text = preg_replace("#www\.#","http://www.",$text);
        $text = preg_replace("#http://http://www\.#","http://www.",$text);
        $text = preg_replace("#https://http://www\.#","https://www.",$text);
        $reg_url = "!(((f|ht)tp(s)?://)[-a-zA-Zа-яА-Я()0-9@:%_+.~#?&;//=]+)!i";

        if(preg_match_all($reg_url,$text,$aMatch))
        {
            $match = $aMatch[0];
            foreach($match as $url)
            {
                $links[] = $url;
            }
        }

        return $links;
    }

    public function turnLinks($text)
    {
        return AutoLinkUrls($text);
       //return preg_replace('@(http)?(s)?(://)?(([-\w]+\.)+([^\s]+)+[^,\s])@', '<a onclick=\'return window.open("http$2://$4")\' href="javascript:void(0)">$1$2$3$4</a>', $text);

    }


    /**
     * Method to get url details
     *
     * @param string $link
     * @param bool $doImage
     * @return array
     */
    public function getLinkDetails($link, $doImage = false)
    {
        $result = [
            'title' => '',
            'description' => '',
            'image' => '',
            'link' => $link
        ];

        try{
            $urlContent = @file_get_contents($link);
            ///$urlContent = iconv("Windows-1252","UTF-8",$urlContent);
            $dom = new \DOMDocument("4.01", 'UTF-8');
            @$dom->loadHTML('<meta http-equiv="content-type" content="text/html; charset=utf-8">' . $urlContent);
            $title = $dom->getElementsByTagName('title');


            //once the page does not have title the url is invalid from here
            if($title->length < 1) return $result;

            $result['title'] = sanitizeText($title->item(0)->nodeValue);
            $description = null;
            $metas = $dom->getElementsByTagName('meta');
            for($i = 0; $i < $metas->length; $i++)
            {
                $meta = $metas->item($i);
                if($meta->getAttribute('name') == 'description')
                {
                    $result['description'] = sanitizeText($meta->getAttribute('content'));
                }
            }

            if (empty($result['description'])) {
                //lets try the facebook
                preg_match('#<meta property="og:description" content="(.*?)" \/>|<meta content="(.*?)" property="og:description" \/>#',
                    $urlContent, $matches);
                if ($matches) {
                    if (isset($matches[1]) and $matches[1]) {
                        $result['description'] = sanitizeText($matches[1]);
                    } else {
                        $result['description'] = sanitizeText($matches[2]);
                    }
                }

            }

            //images
            $image = null;
            $result['image'] = null;

            if ($doImage) {
                $images = [];

                if (preg_match('#google\.com#', $link)) {
                    //$images[] = 'http://www.google.com.ng/images/google_favicon_128.png';
                }

                //lets first get site that favour facebook because the images are gooder
                preg_match('#<meta property="og:image" content="(.*?)" \/>|<meta content="(.*?)" property="og:image" \/>#',
                    $urlContent, $matches);

                if ($matches) {
                    if (isset($matches[1]) and $matches[1] and preg_match('#http://|https://#', $matches[1])) {
                        if ($this->checkRemoteFile($matches[1])) $images[] = $matches[1];
                    } else {
                       if (preg_match('#http://|https://#', $matches[2]) and $this->checkRemoteFile($matches[2])) $images[] = $matches[2];
                    }
                }

                //now lets search for more images through there <img element
                $imgElements = $dom->getElementsByTagName('img');
                for($i=0; $i < $imgElements->length; $i++) {
                    $cImg = $imgElements->item($i);
                    if (count($images) <= 5 and preg_match("#http:\/\/|https:\/\/#", $cImg->getAttribute('src'))) {
                        if ($this->checkRemoteFile($cImg->getAttribute('src'))) $images[] = $cImg->getAttribute('src');
                    }
                }

                $result['image'] = array_reverse($images);
            }


        } catch(Exception $e) {
            ///silent ignore it
        }
        return $result;

    }

    function checkRemoteFile($url)
    {
        $im = @imagecreatefromjpeg($url);
        if($im) {
            imagedestroy($im); // dont save, just ack...
            return true;
        }
        else{
            return false;
        }
    }
    /**
     * Method to update post text
     *
     * @param $id
     * @param $text
     * @return Post|bool
     */
    public function updateText($id, $text)
    {
        $post = $this->findById($id);
        if (!$post  or $post->shared) return false;
        if ($post->user_id != \Auth::user()->id) {
            if (!\Auth::user()->isAdmin()) return false;
        }

        if ($post) {
            if ($post->text != $text) {
                $post->edited = 1;
            }
            $post->text = sanitizeText($text);

            $post->save();

            $this->event->fire('post.edit', [$post]);

            return $post;
        }

        return false;
    }

    /**
     * Method to add post
     *
     * @param array $val
     * @param \App\Models\User $user
     * @return \App\Models\Post
     */
    public function add($val, $user = null)
    {
        $expected = [
            'text' => '',
            'content_type' => '',
            'type_content' => [],
            'to_userid' => '',
            'type' => '',
            'type_id' => '',
            'community_id' => '',
            'page_id' => '',
            'privacy' => 2,
            'tags' => [],
            'link_title',
            'link_description',
            'link_image',
            'the_link',
            'video_path' => '',
            'auto_like_id' => '',
            'file_path' => '',
            'file_path_name' => ''
        ];
        $user = (empty($user)) ? \Auth::user() : $user;
        $CDNRepository = app('App\\Repositories\\CDNRepository');

        /**
         * @var $text
         * @var $content_type
         * @var $to_userid
         * @var $type
         * @var $type_id
         * @var $community_id
         * @var $page_id
         * @var $type_content
         * @var $image
         * @var $privacy
         * @var $tags
         * @var $link_title
         * @var $link_description
         * @var $link_image,
         * @var $the_link
         * @var $video_path
         * @var $auto_like_id
         * @var $file_path
         * @var $file_path_name
         */
        extract($val = array_merge($expected, $val));



        //we need to make sure that if its page, user is page owner
        if ($page_id) {
            $page = app('App\\Repositories\\PageRepository')->get($page_id);
            if ($page) {
                $user = $page->user;
            }
        }

        if ($to_userid) {
            $toUser= app('App\\Repositories\\UserRepository')->findById($to_userid);
            if ($toUser and !$toUser->present()->canPost()) return false;
        }

        $imageAttachPostId = false;

        if ($content_type == 'image') {
            if (\Input::hasFile('image') and empty($type_content)) {
                $images = \Input::file('image');

                //send back once one of the images does not met allowed size by admin
                if (!$this->photoRepository->imagesMetSizes($images)) return false;
                $image = [];
                if (!$page_id and !$community_id) $album = $this->photoAlbum->add('posts', $user->id, true);
                $param = [
                    'path' => 'users/'.$user->id.'/posts',
                    'slug' => (!$page_id and !$community_id) ? 'album-'.$album->id : 'post',
                    'userid' => $user->id
                ];

                if ($community_id) {
                    $community = app('App\\Repositories\\CommunityRepository')->get($community_id);
                    if ($community  and $community->privacy == 0) {
                        $param['privacy'] = 5;
                    }
                }

                if ($page_id) {
                    $param['privacy'] = 5; //prevent it from showing on this user profile
                    $param['page_id'] = $page_id;
                }


                foreach($images as $im) {
                    $theImage = $this->photoRepository->upload($im, $param);
                    if ($theImage) {
                        if (!$page_id and !$community_id) {
                            if ($album->default_photo == '') {
                                $album->default_photo = $theImage;
                                $album->save();
                            }
                        }

                        $image[] = $theImage;

                    }
                }

                $type_content = $image;


            }

            if (count($type_content) == 1 and isset($type_content[0])) {
                $imageAttachPostId = $this->photoRepository->get($type_content[0]);
            }
        }

        if ($content_type == 'link' and empty($link_title)) {
            //append the link to the post text
            $text .= ' '.$type_content;
            if (empty($type_content)) return false;
        } elseif ($content_type == 'location') {
            $type_content = sanitizeText($type_content);
        }

        if (\Input::hasFile('video')) {
            $videoMaxSize = \Config::get('max-size-upload-video');
            $videoFile = \Input::file('video');
            $videoExt = $videoFile->getClientOriginalExtension();

            if ($videoFile->getSize() > $videoMaxSize or strtolower($videoExt) != 'mp4') return false;

            $userid = \Auth::user()->id;
            $filePath = "uploads/videos/" . $userid. '/';

            //ensure the folder exists

            $this->file->makeDirectory(public_path().'/'.$filePath, 0777, true, true);
            $fileName = md5($videoFile->getClientOriginalName().time()).'.mp4';
            $video_path = $filePath.$fileName;

            $videoFile->move(public_path().'/'.$filePath, $fileName);


            $newFileName = $CDNRepository->upload(public_path().'/'.$video_path, $video_path);

            if ($newFileName != $video_path) {
                //that means file has been succcessfully uploaded to a CDN Server so
                $video_path = $newFileName;
            }
        }

        if (\Input::hasFile('share_file')) {
            $fileMaxSize = \Config::get('max-upload-files');
            $mainFile = \Input::file('share_file');
            $fileExt = $mainFile->getClientOriginalExtension();
            $allowedExts = explode(',', \Config::get('allow-files-types'));

            if ($mainFile->getSize() > $fileMaxSize or !in_array(strtolower($fileExt), $allowedExts)) return false;

            $userid = \Auth::user()->id;
            $filePath = "uploads/files/" . $userid. '/';

            //ensure the folder exists

            $this->file->makeDirectory(public_path().'/'.$filePath, 0777, true, true);
            $fileName = md5($mainFile->getClientOriginalName().time()).'.'.$fileExt;
            $file_path = $filePath.$fileName;
            $file_path_name = $mainFile->getClientOriginalName();

            $mainFile->move(public_path().'/'.$filePath, $fileName);

            $newFileName = $CDNRepository->upload(public_path().'/'.$file_path, $file_path);

            if ($newFileName != $file_path) {
                //that means file has been succcessfully uploaded to a CDN Server so
                $file_path = $newFileName;
            }
        }

        if ($content_type == 'auto-post') {
            $autoPostType = $type_content['type'];

            if ($autoPostType == 'change-avatar') {
                $imageAttachPostId = $this->photoRepository->get($type_content['avatar']);
            } elseif ($autoPostType == 'add-photos') {
                $autoPostPhotosCount = $type_content['photo-count'];
                if ($autoPostPhotosCount == 1) {
                    $imageAttachPostId = $this->photoRepository->get($type_content['photos'][0]);
                }
            }
        }



        //now validate post
        if (empty($text) and empty($type_content) and empty($video_path) and empty($file_path)) return false;

        if ($content_type == "video" and !$video_path) {
            //parse for valid youtube or video link
            $y = parseYouTube($type_content);
            $v = parseVimeo($type_content);
            if (!$y and !$v) return false;
        }

        $oembed = \Config::get('enable-oembed', true);
        if ($oembed) {
            $embraPath = app_path("library/Embera/Autoload.php");
            require_once $embraPath;
            $embera = new \Embera\Embera([
                'params' => array('width' => '598', 'height' => 300),
                'oembed' => null
            ]);
            $oembedInfo = $embera->getUrlInfo($text);
            if ($oembedInfo) {
                foreach($oembedInfo as $r) {
                    $type_content = $r['html'];
                    $content_type = 'oembed';
                    break;
                }
            }
        }

        $type_content = (Array) $type_content;

        if ($auto_like_id) {
            //lets delete the auto like before
            $this->model->where('auto_like_id', '=', $auto_like_id)->where('user_id', '=', $user->id)->delete();
        }


        $to_userid = sanitizeText($to_userid);
        $content_type = sanitizeText($content_type);
        $type = sanitizeText($type);
        $type_id = sanitizeText($type_id);
        $community_id = sanitizeText($community_id);
        $page_id = sanitizeText($page_id);

        $post = $this->model->newInstance();
        $post->text = \Hook::fire('filter-text', sanitizeText($text));
        $post->user_id = $user->id;
        $post->to_user_id = ($to_userid == $user->id) ? 0 : $to_userid;
        $post->content_type = $content_type;
        $post->type_content = perfectSerialize($type_content);
        $post->type = $type;
        $post->type_id = $type_id;
        $post->community_id = $community_id;
        $post->page_id = $page_id;
        $post->tags = perfectSerialize($tags);
        $post->privacy = sanitizeText($privacy);
        $post->video_path = $video_path;
        $post->auto_like_id = $auto_like_id;
        $post->file_path = $file_path;
        $post->file_path_name = $file_path_name;

        if (!empty($link_title)) {
            /**Search for links in post because lin**/

            $details = [
                'link' => $the_link,
                'title' => $link_title,
                'description' => $link_description,
                'image' => $link_image
            ];
            $post->link = perfectSerialize($details);
        }

        try{
            $post->save();
        } catch(Exception $e){
            echo $e->getMessage();
        }


        if ($imageAttachPostId) {
            $imageAttachPostId->post_id = $post->id;
            $imageAttachPostId->save();
        }

        /**
         * Automatically add this user to notification receiver of this post
         */
        $this->notificationReceiver->add($user->id, 'post', $post->id);

        //lets add all the user that are admins if the post is a page post
        if ($post->page_id != 0) {
            $adminsIds = app('App\\Repositories\\PageAdminRepository')->getUserListId($post->page_id);

            foreach($adminsIds as $adminId) {
                $this->notificationReceiver->add($adminId, 'post', $post->id);
            }
        }

        /**For community posting we need to send a notification to the community notifications receivers***/
        if ($type == "community") {
            $this->notification->sendToReceivers('community', $community_id, [
                'path' => 'notification.post.community',
                'communityId' => $community_id,
                'postId' => $post->id
            ]);
        }

        //we are to send notification to the profile owner incase user is posting to him
        if ($post->to_user_id) {
            $this->notification->send($post->to_user_id, [
                'path' => 'notification.post.profile',
                'postId' => $post->id
            ]);
        }

        $this->event->fire('post.add', [$post, $val]);

        return $post;

    }

    /**
     * Upload post image
     *
     * @param string $image
     * @param \App\Models\User $user
     * @return string
     */
    public function uploadImage($image, $user = null)
    {
        $user = (empty($user)) ? \Auth::user() : $user;
        $album = $this->photoAlbum->add('posts', $user->id, true);
        $image = $this->photoRepository->upload($image, [
            'path' => 'users/'.$user->id.'/posts',
            'slug' => 'album-'.$album->id,
            'userid' => $user->id
        ]);

        if ($image) {
            if (isset($album->default_photo) and empty($album->default_photo)) {
                $album->default_photo = $image;
                $album->save();
            }
        }

        return $image;
    }

    /**
     * Get user timeline posts
     *
     * @return array
     */
    public function timeline($userid = null, $take = 20)
    {

        return $this->model
            ->where('user_id', '=', $userid)
            ->where('privacy', '=', 1)
            ->orderBy('id', 'desc')->get();
    }

    public function pageTimeline($id)
    {
        return $this->model
            ->where('page_id', '=', $id)
            ->where('privacy', '=', 1)
            ->orderBy('id', 'desc')->get();
    }

    /**
     * Method to lists posts
     *
     * @param string $type
     * @param int $offset
     * @param int $userid
     * @param int $lastTime
     * @return array
     */
    public function lists($type, $offset = 0, $userid = null, $lastTime = null)
    {
        /**
         * Consider ajax pagination for search type
         */
        if (preg_match('#search-#', $type)) {
            list($name, $term) = explode('-', $type);

            return $this->search($term, $offset, $lastTime);
        } elseif($type == 'discover') {
            return $this->discover($offset, $lastTime);
        }

        $post = $this->model;
        if (\Auth::check()) $userid = (empty($userid)) ? \Auth::user()->id : $userid;
        $post = $post->whereNotIn('user_id', $this->mustAvoidUserRepository->get());
        $post = $post->where(function($post) use($type, $userid, $offset) {

            if ($type == "user-timeline") {
                $followings = $this->connectionRepository->getFollowingId();
                $friends = $this->connectionRepository->getFriendsId();

                $post = $post->where(function($post) use($friends) {
                    $post->whereIn('user_id', $friends)
                        ->where('to_user_id', '=', '')
                        ->whereIn('privacy', [1,2,4])
                        ->where('page_id', '=', '');
                })->orWhere(function($post) use($followings) {
                    $post->whereIn('user_id', $followings)
                        ->where('to_user_id', '=', '')
                        ->whereIn('privacy', [1,3,4])
                        ->where('page_id', '=', '');
                })->orWhere(function($post) use($userid) {
                        $post->where('user_id', '=', $userid)
                            ->where('page_id', '=', '');
                });

                if (\Config::get('enable-public-post', false)) {
                    $post = $post->orWhere('privacy', '=', 1);
                }

                $likeRepo = app('App\\Repositories\\LikeRepository');

                $likes = $likeRepo->getLikesId('page', $userid);



                if (!empty($likes) and count($likes) > 1) {
                    unset($likes[0]);

                    $post = $post->orWhereIn('page_id', $likes);
                }

            } elseif ($type == 'profile') {
                $privacy = [1];

                if (\Auth::check()) {
                    if ($userid == \Auth::user()->id) {
                        $privacy = [1,2,3,4,5];
                    } else {
                        if ($this->connectionRepository->isFollowing(\Auth::user()->id, $userid)) {
                            $privacy[] = 3;
                            $privacy[] = 4;
                        }


                        if ($this->connectionRepository->isConfirmedFriends(\Auth::user()->id, $userid)) {
                            $privacy[] = 2;
                        }
                    }
                }
                $post = $post->where(function($post) use($userid) {
                    $post->where(function($post) use($userid) {
                        $post->where('user_id', '=', $userid)
                            ->where('to_user_id', '=', '')
                            ->where('page_id', '=', '');
                    })->orWhere(function($post) use($userid) {
                        $post
                            ->where('to_user_id', '=', $userid)
                            ->where('page_id', '=', '');
                    });

                })
                    ->whereIn('privacy', $privacy);
            } elseif(preg_match('#communitycategory-#', $type)) {
                list($c, $categoryId) = explode('-', $type);
                $post = $post->where('type', '=', 'community')->where('type_id', '=', $categoryId);
            } elseif (preg_match('#community-#', $type)) {
                list($c, $id) = explode('-', $type);
                $post = $post->where('type', '=', 'community')->where('community_id', '=', $id);
            } elseif(preg_match('#page-#', $type)) {
                list($c, $id) = explode('-', $type);
                $post = $post->where('type', '=', 'page')->where('page_id', '=', $id);
            }

        });


        if (\Auth::check()) {
            $blockedUsers = $this->blockedUserRepository->listIds(\Auth::user()->id);
            $post = $post->whereNotIn('user_id', $blockedUsers);

            //prevent hide posts too
            $hidePosts = $this->hidePostRepository->lists();
            $post = $post->whereNotIn('id', $hidePosts);
        }


        if ($lastTime) {
            $post = $post->where('created_at', '>', $lastTime);

            if (\Auth::check()) {
                $post = $post->where('user_id', '!=', \Auth::user()->id);
            }
        }

        if ($type == 'user-timeline' and \Config::get('enable-hot-posts', false)) {
            $post = $post->orderBy('updated_at', 'desc');
        } else {
            $post = $post->orderBy('id', 'desc');
        }
        $post = $post->skip($offset)->take(\Config::get('post-per-page'))->get();

        return $post;
    }

    /**
     * Method to discover posts base on popular hashtags
     *
     * @param int $offset
     * @return array
     */
    public function discover($offset = 0, $lastTime = null)
    {
        $post = $this->model->with('user', 'comments')
        ->whereNotIn('user_id', $this->mustAvoidUserRepository->get());
        $hashtags = $this->hashtagRepository->trending(10);

        $post = $post->where(function($post) use($hashtags) {
            $started = false;
            foreach($hashtags as $hashtag) {
                if (!$started) {
                    $started = true;
                    $post = $post->where('text', 'LIKE', '%'.$hashtag->hash.'%');
                } else {
                    $post = $post->orWhere('text', 'LIKE', '%'.$hashtag->hash.'%');
                }
            }
        });

        $post = $post->where(function($post) {
            $followings = $this->connectionRepository->getFollowingId();
            $friends = $this->connectionRepository->getFriendsId();

            $post->where(function($post) use($friends) {

                $post->whereIn('user_id', $friends)
                    ->where('to_user_id', '=', '')
                    ->whereIn('privacy', [1,2,4]);
            })->orWhere(function($post) use($followings) {
                $post->whereIn('user_id', $followings)
                    ->where('to_user_id', '=', '')
                    ->whereIn('privacy', [1,3,4]);
            })
                ->orWhere('privacy', '=', 1)
                ->where('to_user_id', '=', '');
        });

        //post must not come from current logged in user
        $post = $post->where('user_id', '!=', \Auth::user()->id);
        if (\Auth::check()) {
            $blockedUsers = $this->blockedUserRepository->listIds(\Auth::user()->id);
            $post = $post->whereNotIn('user_id', $blockedUsers);

            //prevent hide posts too
            $hidePosts = $this->hidePostRepository->lists();
            $post = $post->whereNotIn('id', $hidePosts);
        }

        if ($lastTime) {
            $post = $post->where('created_at', '>', $lastTime);

            if (\Auth::check()) {
                $post = $post->where('user_id', '!=', \Auth::user()->id);
            }
        }


        $post = $post->orderBy('id', 'desc')->skip($offset)->take(\Config::get('post-per-page'))->get();

        return $post;
    }

    /**
     * Method to search posts using normal
     *
     * @param string $term
     * @param int $offset
     * @return array
     */
    public function search($term, $offset = 0, $lastTime = null)
    {
        $post = $this->model->with('user', 'comments')->where('text', 'LIKE', '%'.$term.'%')
        ->whereNotIn('user_id', $this->mustAvoidUserRepository->get());

        if (!\Auth::check()) {
            $post = $post->where('privacy', '=', 1);
        } else {
            $post = $post->where(function($post) {
                $followings = $this->connectionRepository->getFollowingId();
                $friends = $this->connectionRepository->getFriendsId();

               $post->where(function($post) use($friends) {

                   $post->whereIn('user_id', $friends)
                       ->where('to_user_id', '=', '')
                       ->whereIn('privacy', [1,2,4]);
               })->orWhere(function($post) use($followings) {
                   $post->whereIn('user_id', $followings)
                       ->where('to_user_id', '=', '')
                       ->whereIn('privacy', [1,3,4]);
               })
                   ->orWhere('privacy', '=', 1)
                   ->orWhere('user_id', '=', \Auth::user()->id)->where('to_user_id', '=', '');
            });
        }

        if (\Auth::check()) {
            $blockedUsers = $this->blockedUserRepository->listIds(\Auth::user()->id);
            $post = $post->whereNotIn('user_id', $blockedUsers);

            //prevent hide posts too
            $hidePosts = $this->hidePostRepository->lists();

            $post = $post->whereNotIn('id', $hidePosts);
        }

        if ($lastTime) {
            $post = $post->where('created_at', '>', $lastTime);

            if (\Auth::check()) {
                $post = $post->where('user_id', '!=', \Auth::user()->id);
            }
        }


        $post = $post->orderBy('id', 'desc')->skip($offset)->take(\Config::get('post-per-page'))->get();

        return $post;
    }

    /**
     * Share a post
     *
     * @param int $id
     * @return boolean
     */
    public function share($id)
    {
        $post = $this->findById($id);

        if($post) {
            $newPost = $this->model->newInstance();
            /**lets take time to transfer details from **/
            $newPost->text = $post->text;
            $newPost->user_id = \Auth::user()->id;
            $newPost->content_type = $post->content_type;
            $newPost->type_content = $post->type_content;
            $newPost->type = 'user-timeline';
            $newPost->tags = $post->tags;
            $newPost->page_id ='';
            $newPost->video_path = $post->video_path;
            $newPost->link = $post->link;
            $newPost->community_id = "";
            $newPost->to_user_id ="";

            if (!$post->page_id) {
                $newPost->shared = 1;
                $newPost->shared_id = $post->id;
                $newPost->shared_from = (empty($post->shared_from)) ? $post->user_id : $post->shared_from;
            } else {
                $newPost->shared = $post->id;
            }
            $newPost->save();

            /**update the post share count***/
            $post->shared_count +=1;
            $post->save();

            /**
             * Send notification to poster
             */
            if ($post->user_id != \Auth::user()->id) {
                $this->notification->send($post->user_id, [
                    'path' => 'notification.post.share',
                    'post' => $post
                ], null, true, 'notify-share-post', $post->user);
            }

            $this->event->fire('post.share', [$post, $newPost]);
            return $post->shared_count;
        }

        return false;
    }

    /**
     * Method to get shares
     *
     * @param int $id
     * @param int $limit
     * return array
     */
    public function getShares($id, $limit = 10)
    {
        return $this->model->where('shared_id', '=', $id)->paginate($limit);

    }

    /**
     * delete post
     *
     * @param int $id
     * @return boolean
     */
    public function delete($id)
    {
        $post = $this->findById($id);
        if (!$post or !\Auth::check()) return false;

        if ($post->user_id != \Auth::user()->id) {
            if (!\Auth::user()->isAdmin()) return false;
        }

        $this->model->where('id', '=', $id)->delete();
        if ($post->content_type == 'image') {
            $images = $post->present()->images();
            foreach($images as $image) {
                app('App\\Repositories\\PhotoRepository')->deletePhoto($image);
            }
        }

        $CDNRepository = app("App\\Repositories\\CDNRepository");

        if ($post->video_path != '') {
            //lets delete this video too
            $filePath = base_path('/'.$post->video_path);
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
            if ($CDNRepository->has($post->video_path)) $CDNRepository->delete($post->video_path);
        }

        if ($post->file_path != '') {
            $filePath = base_path('/'.$post->file_path);
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
            if ($CDNRepository->has($post->file_path)) $CDNRepository->delete($post->file_path);
        }
        app('App\\Repositories\\CommentRepository')->deleteByType('post', $id);
        app('App\\Repositories\\LikeRepository')->deleteByType('post', $id);
    }

    public function deleteAllByUser($userid)
    {
        $posts = $this->model
            ->where('user_id', '=', $userid)
            ->orWhere('to_user_id', '=', $userid)
            ->orWhere('shared_from', '=', $userid)
            ->get();
        foreach($posts as $post) {
            $this->delete($post->id);
        }
    }

    public function deleteAllByCommunity($id)
    {
        $posts =  $this->model->where('community_id', '=', $id)->get();

        foreach($posts as $post) {
            $this->delete($post->id);
        }
    }

    public function deleteAllByPage($id)
    {
        $allPosts = $this->model->where('page_id', '=', $id)->get();

        foreach($allPosts as $post) {
            $this->delete($post->id);
        }
    }

    public function total()
    {
        return $this->model->count();
    }
}