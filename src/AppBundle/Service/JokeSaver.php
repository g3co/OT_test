<?php


namespace AppBundle\Service;


use AppBundle\Service\Interfaces\IJokeSaver;

class JokeSaver implements IJokeSaver
{
    protected $filePath;

    public function __construct(string $filePath)
    {
        $this->filePath = realpath($filePath) . '/';
    }

    /**
     * Save joke on the disk
     * @param string $joke
     * @return bool
     */
    public function save(string $joke):bool
    {
        $fileName = sha1($joke). ".txt";
        $id = file_put_contents($this->filePath . $fileName, $joke);

        return $id !== false;
    }
}