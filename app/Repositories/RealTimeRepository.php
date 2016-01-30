<?php

namespace App\Repositories;
use Illuminate\Filesystem\Filesystem;

/**
*
*@author: Tiamiyu waliu kola
*@website : www.crea8social.com
*/
class RealTimeRepository
{
    protected $types = [
            'chat',
            'update'
    ];

    //current type working on
    protected $type;

    public function __construct(Filesystem $filesystem)
    {
        $this->file = $filesystem;
        $this->path = storage_path('realtime').'/';

        //ensure existence
        $this->file->makeDirectory($this->path, 0777, true, true);
    }

    public function addType($type)
    {
        if (in_array($this->types, $type)) return $this;
        $this->types[] = $type;
        return $this;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Method to add new event for a user
     *
     * @param int $userid
     * @param string $type
     * @return bool
     */
    public function add($userid, $type)
    {
        $getInfo = $this->get($userid);

        $getInfo[$type] = $type;

        $this->update($userid, $getInfo);
    }

    public function get($userid)
    {
        if (empty($this->type)) return [];

        $userFilePath = $this->path.'/'.$this->type.'/'.$userid.'.php';

        if (!$this->file->exists($userFilePath)) return [];

        $fileContent = $this->file->get($userFilePath);

        return perfectUnserialize($fileContent);
    }

    public function update($userid, $info)
    {
        foreach($this->types as $type) {
            $this->file->makeDirectory($this->path.'/'.$type.'/', 0777, true, true);
            $userFilePath = $this->path.'/'.$type.'/'.$userid.'.php';

            if (!$this->file->exists($userFilePath)) {
                $handle = fopen($userFilePath, 'a+');
                fwrite($handle, perfectSerialize($info));
                fclose($handle);
            }

            $this->file->put($userFilePath, perfectSerialize($info));
        }
    }

    public function has($userid, $type, $lastAccessTime = null)
    {


        //$info = $this->get($userid);

        if (isset($info[$type])) return true;

        if ($lastAccessTime) {
            $userFilePath = $this->path.'/'.$this->type.'/'.$userid.'.php';

            if ($this->file->exists($userFilePath)) {
                $time = filemtime($userFilePath);
                if ($time > $lastAccessTime) return true;
            }

        }
        return false;
    }

    public function getLastAccess($userid)
    {
        $userFilePath = $this->path.'/'.$this->type.'/'.$userid.'.php';
        if (!$this->file->exists($userFilePath)) return false;
        return $time = filemtime($userFilePath);
    }

    public function remove($userid, $type)
    {
        $info = $this->get($userid);
        if (isset($info[$type])) unset($info[$type]);
        $this->update($userid, $info);
        return true;
    }
}