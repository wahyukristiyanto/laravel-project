<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Env;
use Tests\TestCase;

class EnvironmentTest extends TestCase
{
    public function testGetEnv()
    {
        $youtube = env("Youtube");

        self::assertEquals("Laravel Project", $youtube);
    }

    public function testDefaultEnv()
    {
        $author = Env::get("AUTHOR", "Navy");

        self::assertEquals("Navy", $author);
    }
}
