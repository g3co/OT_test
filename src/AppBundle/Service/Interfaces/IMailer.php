<?php


namespace AppBundle\Service\Interfaces;


interface IMailer
{
    public function sendMessage($to, $subject, $message): int;
}