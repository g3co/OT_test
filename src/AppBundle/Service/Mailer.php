<?php

namespace AppBundle\Service;

use AppBundle\Service\Interfaces\IJokeApi;
use AppBundle\Service\Interfaces\IMailer;
use GuzzleHttp\Client;
use Exception;

class Mailer implements IMailer
{
    protected $mailer;

    public function __construct()
    {
        $transport = (new \Swift_SmtpTransport('smtp.yandex.ru', 465))
            ->setUsername('admin@myobr.ru')
            ->setPassword('3piKnVG7q')
            ->setEncryption('SSL')
        ;

        $this->mailer = new \Swift_Mailer($transport);
    }

    public function sendMessage($to, $subject, $message): int
    {
        $message = (new \Swift_Message($subject))
            ->setFrom('admin@myobr.ru')
            ->setTo($to)
            ->setBody($message)
        ;

        return $this->mailer->send($message);
    }
}
