<?php

namespace App\Repositories;

use App\Models\CustomField;
use Illuminate\Cache\Repository;

/**
*
*@author : Tiamiyu waliu
*@website : http://www.iDocrea8.com
*/
class CustomFieldRepository
{

    protected $cacheName = "custom_fields";

    public function __construct(CustomField $customField, Repository $cache)
    {
        $this->model = $customField;
        $this->cache = $cache;
    }

    /**
     * Method to add custom fields
     *
     * @param array $val
     * @return boolean
     */
    public function add($val)
    {
        $expected = [
            'name' => '',
            'type' => 'profile',
            'description' => '',
            'field_type' => '',
            'options' => []
        ];

        /**
         * @var $name
         * @var $type
         * @var $description
         * @var $field_type
         * @var $options
         */
        extract(array_merge($expected, $val));

        if (!$this->exists($name, $type)) {
            $field = $this->model->newInstance();
            $field->name = sanitizeText($name, 100);
            $field->description = sanitizeText($description);
            $field->type = $type;
            $field->field_type = $field_type;
            $field->data = serialize($options);
            $field->save();

            $this->cache->forget($this->cacheName.$type);
            return true;
        }

        return false;
    }

    /**
     * Method to save custom field
     *
     * @param array $val
     * @param int $id
     * @return boolean
     */
    public function save($val, $id)
    {
        $expected = [
            'name' => '',
            'type' => 'profile',
            'description' => '',
            'field_type' => '',
            'options' => []
        ];

        /**
         * @var $name
         * @var $type
         * @var $description
         * @var $field_type
         * @var $options
         */
        extract(array_merge($expected, $val));

        $field = $this->get($id);

        if (!empty($field)) {
            $field->name = sanitizeText($name, 100);
            $field->description = sanitizeText($description);
            $field->type = $type;
            $field->field_type = $field_type;
            $field->data = serialize($options);
            $field->save();

            $this->cache->forget($this->cacheName.$type);
            return true;
        }

        return true;
    }

    /**
     * Method to check if a custom fields exists
     *
     * @param string $name
     * @param string $type
     * @return boolean
     */
    public function exists($name, $type)
    {
        return $this->model
            ->where('name', '=', $name)
            ->where('type', '=', $type)
            ->first();
    }

    /**
     * Method to list with pagination
     *
     * @param string $type
     * @param int $limit
     * @return array
     */
    public function lists($type, $limit = 20)
    {

            return $this->model->where('type', '=', $type)->paginate($limit);
    }

    /**
     * Method to list fields for a type
     *
     * @param string $type
     * @return array
     */
    public function listAll($type)
    {
        if ($this->cache->has($this->cacheName.$type)) {
            return $this->cache->get($this->cacheName.$type);
        } else {
            $fields =  $this->model->where('type', '=', $type)->get();

            $this->cache->forever($this->cacheName.$type, $fields);

            return $fields;
        }
    }

    /**
     * Get field by its id
     *
     * @param int $id
     * @return \App\Models\CustomField
     */
    public function get($id)
    {
        return $this->model->find($id);
    }


    /**
     * Method to delete
     *
     * @param int $id
     * @return boolean
     */
    public function delete($id)
    {
        $field = $this->get($id);
        if ($field) {
            $this->cache->forget($this->cacheName.$field->type);
            return $this->model->where('id', '=', $id)->delete();
        }
    }
}