<?php

namespace Ttnppedr\ChucknorrisJokes\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Ttnppedr\ChuckNorrisJokes\JokeFactory;

class JokeFactoryTest extends TestCase
{
    /** @test */
    public function it_returns_a_random_joke()
    {
        $mock = new MockHandler([
            new Response(200, [], '{ "type": "success", "value": { "id": 537, "joke": "Each hair in Chuck Norris\'s beard contributes to make the world\'s largest DDOS.", "categories": ["nerdy"] } }'),
        ]);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $jokes = new JokeFactory($client);

        $joke = $jokes->getRandomJoke();

        $this->assertSame('Each hair in Chuck Norris\'s beard contributes to make the world\'s largest DDOS.', $joke);
    }
}
