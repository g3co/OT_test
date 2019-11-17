<?php


namespace AppBundle\Service\Interfaces;


interface IJokeSaver
{
    public function save(string $joke): bool;
}