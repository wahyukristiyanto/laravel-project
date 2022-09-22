<?php

namespace Tests\Feature;

use App\Data\Bar;
use App\Data\Foo;
use App\Data\Person;
use App\Services\HelloService;
use App\Services\HelloServiceIndonesia;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertNotSame;

class ServiceContainerTest extends TestCase
{
    public function testDependency()
    {
        $foo1 = $this->app->make(Foo::class);
        $foo2 = $this->app->make(Foo::class); //new Foo

        self::assertEquals('Foo', $foo1->foo());
        self::assertEquals('Foo', $foo2->foo());
        self::assertNotSame($foo1, $foo2);
    }

    public function testBind()
    {
        // $person = $this->app->make(Person::class);
        // self::assertNotNull($person);
        $this->app->bind(Person::class, function ($app){
            return new Person('Rian', 'Nvs');
        });

        $person1 = $this->app->make(Person::class); //closure(), new Person from bind
        $person2 = $this->app->make(Person::class); //closure(), new Person from bind

        self::assertEquals('Rian', $person1->firstName);
        self::assertEquals('Rian', $person2->firstName);
        self::assertNotSame($person1, $person2);
    }

    public function testSingleton()
    {
        $this->app->singleton(Person::class, function ($app){
            return new Person('Rian', 'Nvs');
        });

        $person1 = $this->app->make(Person::class); // new Person, if not exists
        $person2 = $this->app->make(Person::class); // return existing

        self::assertEquals('Rian', $person1->firstName);
        self::assertEquals('Rian', $person2->firstName);
        self::assertSame($person1, $person2);
    }

    public function testInstance()
    {
        $person = new Person('Rian', 'Nvs');
        $this->app->instance(Person::class, $person);

        $person1 = $this->app->make(Person::class); // $person
        $person2 = $this->app->make(Person::class); // $person
        $person3 = $this->app->make(Person::class); // $person
        $person4 = $this->app->make(Person::class); // $person

        self::assertEquals('Rian', $person1->firstName);
        self::assertEquals('Rian', $person2->firstName);
        self::assertSame($person, $person1);
        self::assertSame($person, $person2);
    }

    public function testDependencyInjection()
    {
        $this->app->singleton(Foo::class, function ($app) {
            return new Foo();
        });

        $this->app->singleton(Bar::class, function ($app) {
            return new Bar($app->make(Foo::class));
        });

        $foo = $this->app->make(Foo::class);
        $bar1 = $this->app->make(Bar::class);
        $bar2 = $this->app->make(Bar::class);
        
        self::assertEquals('Foo and Bar', $bar1->bar());
        self::assertSame($foo, $bar1->foo);
        self::assertSame($bar1, $bar2);
    }

    public function testInterfaceToClass()
    {
        $this->app->singleton(HelloService::class, HelloServiceIndonesia::class);

        // $this->app->singleton(HelloService::class, function ($app){
        //     return new HelloServiceIndonesia();
        // });

        $helloService = $this->app->make(HelloService::class);

        self::assertEquals('Halo Rian', $helloService->hello('Rian'));
    }
}
