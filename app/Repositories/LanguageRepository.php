<?php

namespace App\Repositories;

use App\Models\Language;
use Illuminate\Events\Dispatcher;
use Illuminate\Cache\Repository;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class LanguageRepository
{
    protected $model;

    public function __construct(
        Language $language,
        Dispatcher $event,
        Repository $cache
    )
    {
        $this->model = $language;
        $this->event = $event;
        $this->cache = $cache;
    }

    public static function allInArray()
    {
        return [
            'en' => 'English Language'
        ];
    }

    /**
     * Retrieve all languages
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all()
    {
        if ($this->cache->has('languages')) {
            return $this->cache->get('languages');
        } else {
            $result = $this->model->all();
            $this->cache->forever('languages', $result);

            return $result;
        }
    }

    /**
     * Method to add languages
     *
     * @param array $val
     * @return boolean
     */
    public function add($val)
    {
        $expected = [
            'var' => '',
            'name' => ''
        ];

        /**
         * @var $var
         * @var $name
         */
        extract(array_merge($expected, $val));

        /**Check for existence**/
        if ($this->exists($var)) return false;

        $model = $this->model->newInstance();
        $model->var = sanitizeText($var, 10);
        $model->name = sanitizeText($name, 100);
        $model->save();

        $this->cache->forget('languages');
        $this->event->fire("language.added", $val);
        return true;
    }

    /**
     * Check existence of a language
     *
     * @param string $var
     * @return boolean
     */
    public function exists($var)
    {
        return $this->model->where("var", '=', $var)->first();
    }

    /**
     * Method to find a language by its by var
     *
     * @param string $var
     * @return \App\Models\Language
     */
    public function findByVar($var)
    {
        return $this->model->where("var", '=', $var)->orWhere('id', $var)->first();
    }

    /**
     * Activate a language
     *
     * @param string $var
     * @return boolean
     */
    public function activate($var)
    {
        if ($model = $this->findByVar($var)) {
            $this->deActivateAll();
            $model->active = 1;
            $model->save();

            /**update the active language**/
            $this->cache->forever('active-language', $var);
            $this->event->fire('activate.language', [$model, $var]);
            $this->cache->forget('languages');
            return true;
        }

        return false;
    }

    public function deActivateAll()
    {
        return $this->model->where('active', '=', 1)->update([
            'active' => 0
        ]);
    }

    public function delete($var)
    {
        $this->cache->forget('languages');
        if ($var != 'en' and $model = $this->findByVar($var)) {

            return $model->delete();
        }
        return false;
    }
}