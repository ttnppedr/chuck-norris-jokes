<?php

namespace Ttnppedr\ChuckNorrisJokes\Http\Controllers;

use Ttnppedr\ChuckNorrisJokes\Facades\ChuckNorris;

class ChuckNorrisController
{
    public function __invoke()
    {
        return view('chuck-norris::joke', [
            'joke' => ChuckNorris::getRandomJoke(),
        ]);
    }
}
