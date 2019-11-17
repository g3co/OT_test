<?php

namespace Tests\AppBundle\Service;

use AppBundle\Service\JokeSaver;
use PHPUnit\Framework\TestCase;

class JokeSaverTest extends TestCase
{
    public function testSavingJoke()
    {
        $saver = new JokeSaver('/tmp/');
        $joke = sha1(mt_rand(0, PHP_INT_MAX));

        $result = $saver->save($joke);
        $this->assertEquals(true, $result);

        $expectingFile = '/tmp/' . sha1($joke) . '.txt';
        $this->assertFileExists($expectingFile);

        $content = file_get_contents($expectingFile);
        $this->assertEquals($joke, $content);
    }
}
