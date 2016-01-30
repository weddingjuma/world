<?php
namespace App\Addons\Autotranslator\Controller;
use App\Addons\Autotranslator\Classes\AutoTranslatorRepository;

/**
*
*@author: Tiamiyu waliu kola
*@website : www.crea8social.com
*/
class AutotranslatorController extends \BaseController
{
    public function __construct(AutoTranslatorRepository $autoTranslatorRepository)
    {
        parent::__construct();
        $this->repository = $autoTranslatorRepository;

    }

    public function translate()
    {
        $text = \Input::get('text');
        $id = md5($text);

        $result = [
            'code' => 0,
            'result' => trans('autotranslator::global.error')
        ];
        if ($translate = $this->repository->findById($id)) {
            $result['code'] = 1;
            $result['result'] = (String) $this->theme->section('autotranslator::result', ['text' => $translate->result]);
        } else {

            $url = "https://translate.yandex.net/api/v1.5/tr.json/translate?key=".\Config::get('yandex-key', 'dfsdsf')."&lang=en&text=".urlencode($text);
            $content = file_get_contents($url);

            try{
                if ($content) {
                    $json = json_decode($content, true);

                    if ($json['code'] == 200) {
                        $result['code'] = 1;
                        $translatedText = $json['text'][0];
                        $this->repository->add($id, $translatedText);
                        $result['result'] = (String) $this->theme->section('autotranslator::result', ['text' => $translatedText]);
                    }


                }

            } catch(\Exception $e) {}

        }

        return json_encode($result);
    }
}