<?php

namespace AppBundle\Manager;


class MailManager
{
    protected $swift_Mailer;

    public function __construct(\Swift_Mailer $swift_Mailer)
    {
        $this->swift_Mailer = $swift_Mailer;
    }

    public function sendMail($student, $body)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('Register Confirm Mail!')
            ->setFrom('zhaoxiangtest@outlook.com')
            ->setTo($student->getEmail())
            ->setBody($body);
        $this->swift_Mailer->send($message);
    }
}