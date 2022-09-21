<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class testConfig extends TestCase
{
    public function testConfig(){
        $firstName = config('contoh.author.first');
        $lastName = config('contoh.author.last');
        $email = config('contoh.email');
        $web = config('contoh.web');

        self::assertEquals('Rian', $firstName);
        self::assertEquals('nvs', $lastName);
        self::assertEquals('rian@mail.com', $email);
        self::assertEquals('https://rian.com', $web);
    }
}
