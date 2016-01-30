<?php
namespace App\Addons\Urlshortener\Classes;
use Illuminate\Database\Eloquent\Model;


/**
*
*@author: Tiamiyu waliu kola
*@website : www.crea8social.com
*/
class ShortenedModel extends Model
{
    protected $table = "shortened_urls";

    public function getLink()
    {
        $domain = \Config::get('shortener-domain', \Request::root());
        $domain = (!$domain) ? \Request::root() : $domain;
        $prefix = \Config::get('shortener-prefix', '+');
        $prefix = (empty($prefix)) ? '+' : $prefix;
        return str_replace(['https://', 'http://'], '', $domain.'/'.$prefix.$this->hash);
    }
}