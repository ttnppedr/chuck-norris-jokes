<?php

namespace Ttnppedr\ChuckNorrisJokes\Tests;

use Illuminate\Support\Facades\Artisan;
use Orchestra\Testbench\TestCase;
use Ttnppedr\ChuckNorrisJokes\ChuckNorrisJokesServiceProvider;
use Ttnppedr\ChuckNorrisJokes\Console\ChuckNorrisJoke;
use Ttnppedr\ChuckNorrisJokes\Facades\ChuckNorris;
use Ttnppedr\ChuckNorrisJokes\Models\Joke;

class LaravelTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            ChuckNorrisJokesServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'ChuckNorris' => ChuckNorrisJoke::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        include_once __DIR__.'/../databases/migrations/create_jokes_table.php.stub';

        (new \CreateJokesTable)->up();
    }

    /** @test */
    public function the_console_command_return_a_joke()
    {
        $this->withoutMockingConsoleOutput();

        ChuckNorris::shouldReceive('getRandomJoke')
            ->once()
            ->andReturn('some joke');

        $this->artisan('chuck-norris');

        $output = Artisan::output();

        $this->assertSame('some joke'.PHP_EOL, $output);
    }

    /** @test */
    public function the_route_can_be_accessed()
    {
        ChuckNorris::shouldReceive('getRandomJoke')
            ->once()
            ->andReturn('some joke');

        $this->get('/chuck-norris')
            ->assertViewIs('chuck-norris::joke')
            ->assertViewHas('joke', 'some joke')
            ->assertStatus(200);
    }

    /** @test */
    public function it_can_access_the_database()
    {
        $joke = new Joke();
        $joke->joke = 'this is funny';
        $joke->save();

        $newJoke = Joke::find($joke->id);

        $this->assertSame($newJoke->joke, 'this is funny');
    }
}
