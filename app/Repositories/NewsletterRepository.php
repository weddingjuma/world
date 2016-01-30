<?php

namespace App\Repositories;
use App\Models\Newsletter;
use Illuminate\Mail\Mailer;

/**
*
*@author: Tiamiyu waliu kola
*@website : www.crea8social.com
*/
class NewsletterRepository
{
    public function __construct(
        Newsletter $newsletter,
        UserRepository $userRepository,
        Mailer $mailer
    )
    {
        $this->model = $newsletter;
        $this->userRepository  = $userRepository;
        $this->mailer = $mailer;
    }

    /**
     * Method to send newsletter
     *
     * @param array $val
     * @return bool
     */
    public function send($val)
    {
        $expected = [
            'subject',
            'content',
            'to',
            'selected'
        ];

        /**
         * @var $subject
         * @var $content
         * @var $to
         * @var $selected
         */
        extract(array_merge($expected, $val));


        if (empty($subject) or empty($content)) return false;


        //first add the newsletter to the table
        $newsletter = $this->model->newInstance();
        $newsletter->subject = sanitizeText($subject);
        $newsletter->content = $content;
        $newsletter->to = ($to == 'all') ? 'all' : $selected;
        $newsletter->save();

        return $this->sendIt($newsletter);


    }

    protected function sendIt($newsletter)
    {
        $nUsers = [];

        if ($newsletter->to == 'all') {
            $users = $this->userRepository->getAll();
            foreach($users as $user) {
                if ($user->id != \Auth::user()->id) {
                    $nUsers[] = $user;
                }
            }
        } else {
            $selected = explode(',', $newsletter->to);
            foreach($selected as $user) {
                $user = $this->userRepository->findByIdUsername($user);
                if($user) {
                    $nUsers[] = $user;
                }
            }
        }

        foreach($nUsers as $user) {
            try {
                $this->mailer->queue('emails.newsletter', [
                    'content' => nl2br($newsletter->content),
                    'subject' => $newsletter->subject,
                    'fullname' => $user->fullname,
                    'username' => $user->username,
                    'profileUrl' => $user->present()->url()
                ], function($mail) use($user, $newsletter) {
                    $mail->to($user->email_address, $user->fullname)
                        ->subject($newsletter->subject);
                });
            } catch(\Exception $e) {

            }
        }

        return true;

    }

    public function getAll($limit = 10)
    {
        return $this->model->paginate($limit);
    }

    public function delete($id)
    {
        return $this->model->where('id', '=', $id)->delete();
    }

    public function get($id)
    {
        return $this->model->where('id', '=', $id)->first();
    }

    public function resend($id)
    {
        $newsletter = $this->get($id);
        if ($newsletter) return $this->sendIt($newsletter);
    }
}