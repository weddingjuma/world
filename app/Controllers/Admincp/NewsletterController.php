<?php

namespace App\Controllers\Admincp;
use App\Controllers\Admincp\AdmincpController;
use App\Repositories\NewsletterRepository;

/**
*
*@author: Tiamiyu waliu kola
*@website : www.crea8social.com
*/
class NewsletterController extends AdmincpController
{
    public function __construct(NewsletterRepository $newsletterRepository)
    {
        parent::__construct();
        $this->activePage('newsletter');
        $this->newsletter = $newsletterRepository;
    }

    public function index()
    {
        $this->setTitle('Newsletter');
        return $this->theme->view('newsletter.index', ['newsletters' => $this->newsletter->getAll()])->render();
    }

    public function add()
    {
        $this->setTitle('Add Newsletter');
        $message = null;

        if ($val = \Input::get('val')) {
            $send = $this->newsletter->send($val);

            if ($send) {
                $message = "Newsletter sent successfully";
            } else {
                $message = "Failed to send, please check your details";
            }
        }
        return $this->theme->view('newsletter.add', ['message' => $message])->render();
    }

    public function resend($id)
    {
        $this->newsletter->resend($id);

        return \Redirect::to(\URL::previous());
    }

    public function delete($id)
    {
        $this->newsletter->delete($id);
        return \Redirect::to(\URL::previous());
    }
}