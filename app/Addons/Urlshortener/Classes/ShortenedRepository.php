<?php
namespace App\Addons\Urlshortener\Classes;

/**
*
*@author: Tiamiyu waliu kola
*@website : www.crea8social.com
*/
class ShortenedRepository
{
    public function __construct(ShortenedModel $model)
    {
        $this->model = $model;
    }

    /**
     * Method to process content and process all url or links found
     *
     * @param string $text
     * @return string
     */
    public function process($text)
    {
        $text = preg_replace("#www\.#","http://www.",$text);
        $text = preg_replace("#http://http://www\.#","http://www.",$text);
        $text = preg_replace("#https://http://www\.#","https://www.",$text);
        $reg_url = "!(((f|ht)tp(s)?://)[-a-zA-Zа-яА-Я()0-9@:%_+.~#?&;//=]+)!i";

        if(preg_match_all($reg_url,$text,$aMatch))
        {
            $match = $aMatch[0];
            foreach($match as $url)
            {
                $shortenedUrl = $this->add($url);
                $text = str_replace($url, $shortenedUrl->getLink(), $text);
            }
        }

        return $text;
    }

    /**
     * Method to add url
     *
     * @param string $url
     * @return \\App\\Addons\\Urlshortener\\Classes\\ShortenedModel
     */
    public function add($url)
    {
        $hash = $this->getHash($url);

        if ($shortenedUrl = $this->get($hash)) {
            return $shortenedUrl;
        } else {
            $shortenedUrl = $this->model->newInstance();
            $shortenedUrl->hash = $hash;
            $shortenedUrl->link = $url;
            $shortenedUrl->save();

            return $shortenedUrl;
        }
    }

    /**
     * Method to get a shortened url
     *
     * @param string $hash
     * @return \\App\\Addons\\Urlshortener\\Classes\\ShortenedModel
     */
    public function get($hash)
    {
        return $this->model->where('hash', '=', $hash)->first();
    }

    /**
     * Method  to get 8digit hash from a url content
     *
     * @param string $url
     * @return string
     */
    public function getHash($url)
    {
        return hash('crc32', $url);
    }

}
 