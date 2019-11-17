<?php


namespace AppBundle\Service\Interfaces;


interface IJokeApi
{
    public function getCategories(): array;

    public function getJoke(string $category): string;
}