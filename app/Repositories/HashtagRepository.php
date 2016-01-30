<?php

namespace App\Repositories;

use App\Models\Hashtag;
use Illuminate\Html\HtmlBuilder;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class HashtagRepository
{
    public function __construct(
        Hashtag $hashtag,
        HtmlBuilder $htmlBuilder
    )
    {
        $this->model = $hashtag;
        $this->html = $htmlBuilder;
    }

    /**
     * Method to add hashtag into database
     *
     * @param string $hash
     * @param boolean $increament
     * @return bool
     */
    public function add($hash, $increament = true)
    {
        $hash = trim($hash);
        if ($this->exists($hash)) {
            /**
             * Update the use count for this hashtag
             */
            $this->model->where('hash', '=', $hash)->increment('count');
        } else {
            if ($increament) {
                $hashtag = $this->model->newInstance();
                $hashtag->hash = sanitizeText($hash);
                $hashtag->count = 1;
                $hashtag->save();
            }
        }

        return true;
    }

    /**
     * Method to get trending #hashtags
     *
     * @param int $limit
     * @return array
     */
    public function trending($limit = 10)
    {
        $query = $this->model->orderBy('count', 'desc');

        if (\Config::get('enable-query-cache')) {
            return $query = $query->remember(\Config::get('query-cache-time-out', 5), 'trending-hashtags')->take($limit)->get();
        } else {
            return $query = $query->take($limit)->get();
        }
    }

    /**
     * @param $hash
     * @return mixed
     */

    public function exists($hash)
    {
        return $this->model->where('hash', '=', $hash)->first();
    }

    /**
     * Method to process text to extract hashtags
     *
     * @param string $text
     * @param bool $increament
     * @return boolean
     */
    public function process($text, $increament = true)
    {
        $hashtags = $this->parse($text);

        if(!empty($hashtags)) {
            foreach($hashtags as $hashtag)
            {
                $this->add('#'.$hashtag, $increament);
            }
        }

        return true;
    }

    /**
     * Method to transform hashtags into links
     *
     * @param string $text
     * @return string
     */
    public function transform($text)
    {
        $hashtags = $this->parse($text);

        if(!empty($hashtags)) {
            foreach($hashtags as $hashtag) {
                $link = 'search/hashtag?term='.ltrim(str_replace('#', '', $hashtag));
                $link = $this->html->link($link, '#'.$hashtag, ['data-ajaxify' => 'true']);
                $text = str_replace('#'.$hashtag, $link, $text);
            }
        }

        return $text;
    }

    /**
     * Parse string and extract #hashtags
     *
     * @param string $text
     * @return array
     */
    public function parse($text)
    {
        if (empty($text)) return false;
        $hashtags = extractIt($text);

        return $hashtags['hashtags'];
    }

    /**
     * Method to search hashtags
     *
     * @param string $text
     * @param int $limit
     * @return array
     */
    public function search($text, $limit = 5)
    {
        return $this->model->where('hash', 'LIKE', '%'.$text.'%')->orderBy('count', 'desc')->take($limit)->get();
    }
}