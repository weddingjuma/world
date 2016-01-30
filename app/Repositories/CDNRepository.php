<?php
namespace App\Repositories;
use Illuminate\Config\Repository;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;

/**
*
*@author: Tiamiyu waliu kola
*@website : www.crea8social.com
*/
class CDNRepository
{
    private $driver = "amazon"; //amazon or self cdn

    private $amazonBucket = "";

    private $amazonKeyId = "";

    private $amazonAccess = "";

    private $enabled = false;

    private $keepFile = false;

    private $cdnBaserUrl = '';

    private $cdnKey = '';

    private $cdnProcessor = '';

    private $amazonEndpoint = "s3.amazonaws.com";

    public function __construct(Filesystem $filesystem, Repository $config)
    {
        $this->config = $config;
        $this->file = $filesystem;
        $this->driver = $this->config->get('cdn-driver');
        $this->enabled = $this->config->get('enable-cdn');
        $this->amazonBucket = $this->config->get('amazon-bucket');
        $this->amazonKeyId = $this->config->get('amazon-id');
        $this->amazonAccess = $this->config->get('amazon-access-key');
        $this->keepFile = $this->config->get('keep-local-files');
        $this->cdnBaserUrl = $this->config->get('cdn-self-base-url');
        $this->cdnKey  = $this->config->get('cdn-self-key');
        $this->cdnProcessor = $this->config->get('cdn-self-processor');

        $endpoint = $this->config->get('amazon-endpoint-url');
        if (!empty($endpoint)) $this->amazonEndpoint = $endpoint;
    }

    /**
     * Method to upload file to preferred CDN Driver
     *
     * @param mixed $file
     * @param string $fileName
     * @return string
     */
    public function upload($file, $fileName)
    {
        if ($this->enabled) {
            if ($this->driver == 'amazon') {
                return $this->amazonUpload($file, $fileName);
            } else {
                return $this->selfUpload($file, $fileName);
            }
        }

        return $fileName;
    }

    /**
     * Amazon way of upload to their CDN server
     *
     * @param mixed $file
     * @param string $fileName
     * @return string
     */
    protected function amazonUpload($file, $fileName)
    {
        $s3 = $this->loadAmazon();
        $amazonFileName = 'amazon/'.$fileName;

        try {
            //lets upload to amazon now
            if ($s3->putObjectFile($file, $this->amazonBucket, $amazonFileName, \S3::ACL_PUBLIC_READ)) {
                $this->deleteThisFile($file);
                return $amazonFileName;
            } else {
                return $fileName;
            }
        } catch(\Exception $e) {}
    }

    protected function selfUpload($file, $fileName)
    {
        $selfFileName = 'cdnuploads/'.$fileName;

        $target_url = $this->cdnBaserUrl.'/'.$this->cdnProcessor.'.php?action=save&name='.$selfFileName.'&key='.$this->cdnKey;
        //This needs to be the full path to the file you want to send.
        $file_name_with_full_path = $file;
        /*  the at sign '@' is required before the
         * file name.
         */
        $post = array('extra_info' => '123456','file_contents'=>'@'.$file_name_with_full_path);

        //exit($target_url);

        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,$target_url);
            curl_setopt($ch, CURLOPT_POST,1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
            $result=curl_exec ($ch);
            curl_close ($ch);

            return $selfFileName;
        } catch(\Exception $e) {}

        return $fileName;

    }

    protected function loadAmazon()
    {
        //load our precious library to do the job for us
        require_once(app_path('library/amazon-3/S3.php'));

        $s3 = new \S3($this->amazonKeyId, $this->amazonAccess);
        return $s3;
    }

    public function getLink($imagePath, $size = 50)
    {
        if ($this->isAmazon($imagePath)) {
            return "http://".$this->amazonEndpoint."/".str_replace('%d', $size, $imagePath);
        } elseif($this->isSelfCDN($imagePath)) {
            return $this->cdnBaserUrl.str_replace('%d', $size, $imagePath);
        }
    }



    public function convertToPath($photo)
    {
        $photo = str_replace("http://".$this->amazonEndpoint."/", '', $photo);
        $photo = str_replace($this->cdnBaserUrl, '', $photo);

        return $photo;
    }
    public function deleteThisFile($file)
    {
        if (!$this->keepFile) {
            $this->file->delete($file);
        }
    }

    public function delete($path, $sizes = [])
    {
        if ($this->isAmazon($path)) {
            $s3 = $this->loadAmazon();

            if (preg_match('#%d#', $path)) {
                foreach($sizes as $size)
                {
                    try{
                        \S3::deleteObject($this->amazonBucket, str_replace('%d', $size, $path));
                    } catch (\Exception $e) {}
                }
            } else {
                try{
                    \S3::deleteObject($this->amazonBucket, $path);
                } catch (\Exception $e) {}
            }
        } elseif ($this->isSelfCDN($path)) {

            $targetUrl = $this->cdnBaserUrl.'/'.$this->cdnProcessor.'.php?action=delete&name='.$path.'&key='.$this->cdnKey;
            if (preg_match('#%d#', $path)) {

                foreach($sizes as $size)
                {
                    try{
                        $newPath = str_replace('%d', $size, $path);
                        $targetUrl = $this->cdnBaserUrl.'/'.$this->cdnProcessor.'.php?action=delete&name='.$newPath.'&key='.$this->cdnKey;
                        file_get_contents($targetUrl);
                        //echo $targetUrl.'<br/>';
                    } catch(\Exception $e) {}
                }

            } else {
                @file_get_contents($targetUrl);
            }
        }
    }

    public function exists($image)
    {
        $link = $this->getLink($image, 50);
        return remoteFileExists($link);
    }

    public function isAmazon($image)
    {
        if (substr($image, 0, 6) == 'amazon') return true;
        return false;
    }

    public function isSelfCDN($image)
    {
        if (substr($image, 0, 10) == 'cdnuploads') return true;
        return false;
    }

    public function has($path)
    {
        if ($this->isAmazon($path)) return true;
        if ($this->isSelfCDN($path)) return true;
        return false;
    }


}