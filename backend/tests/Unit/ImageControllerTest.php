<?php

namespace Tests\Unit;

use App\Http\Controllers\Api\ImageController;
use Tests\TestCase;

class ImageControllerTest extends TestCase
{
    /**
     * Test for the right hangman image according to lives
     *
     * @return void
     */
    public function testCorrectImage(): void
    {
        $imageController = new ImageController();
        $fullHangman = file_get_contents("./storage/full_hangman.txt");
        $onlyHeadHangman = file_get_contents("./storage/head_hangman.txt");
        $this->assertEquals($fullHangman, $imageController->getHangman(0));
        $this->assertEquals($onlyHeadHangman, $imageController->getHangman(6));
    }

}
