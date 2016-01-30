<?php

namespace App\Install;
use App\Repositories\ConfigurationRepository;
use App\Repositories\UserRepository;
use App\SetupDatabase\Install;
use Illuminate\Filesystem\Filesystem;


/**
*
*@author: Tiamiyu waliu kola
*@website : www.crea8social.com
*/
class InstallRepository
{

    public function __construct(ConfigurationRepository $configurationRepository,
                                Filesystem $filesystem,
                                UserRepository $userRepository,
                                Install $install

    )
    {
        $this->file = $filesystem;
        $this->userRepository = $userRepository;
        $this->configRepository = $configurationRepository;
        $this->dbInstall = $install;

    }

    public function installDB()
    {
        $this->dbInstall->database();
        $this->configRepository->update();
    }

    public function installDbInfo($val)
    {
        $expected = [
            'host',
            'username',
            'name',
            'prefix' => 'crea8social_',
            'password',
        ];

        /**
         * @var $host
         * @var $username
         * @var $name
         * @var $prefix
         * @var $password
         */
        extract(array_merge($expected, $val));



        if (empty($host) or empty($username) or empty($name) or empty($password)) return false;


        $connect = \mysqli_connect($host, $username,$password);

        $db  = \mysqli_select_db($connect, $name);


        try{

            $connect = \mysqli_connect($host, $username,$password);

            $db  = \mysqli_select_db($connect, $name);

            if (!$connect or !$db) {
                return false;
            }
            $dbcontent = file_get_contents(app_path().'/config/database.old.php');

            $dbcontent = str_replace(['[localhost]','[name]','[username]', '[password]', '[prefix]'],[
                $host,
                $name,
                $username,
                $password,
                $prefix
            ], $dbcontent);

            $this->file->put(app_path().'/config/database.php', $dbcontent);

            return true;

        } catch(\Exception $e) {

            return false;
        }
    }

    public function createAccount($val)
    {
        $expected = [
            'title' => '',
            'fullname',
            'username',
            'email',
            'password'
        ];
        /**
         * @var $title
         * @var $fullname
         * @var $username
         * @var $email
         * @var $password
         */
        extract(array_merge($expected, $val));

        if (empty($username) or empty($fullname) or empty($title) or empty($email) or empty($password)) return false;

        $user = $this->userRepository->model->newInstance();
        $user->fullname = $fullname;
        $user->email_address = $email;
        $user->username = $username;
        $user->password = \Hash::make($password);
        $user->admin = 1;
        $user->activated = 1;
        $user->active = 1;

        $user->save();

        $this->configRepository->save([
            'site_title' => $title,
            'site_email' => $email
        ]);

        $systemcontent = file_get_contents(app_path().'/config/system.old.php');
        $systemcontent = str_replace('[false]', 'true', $systemcontent);

        $this->file->put(app_path().'/config/system.php', $systemcontent);
        return true;

    }
}