<?php

namespace LaravelLiberu\Logs\Http\Controllers;

use Illuminate\Routing\Controller;
use LaravelLiberu\Logs\Services\Presenter;

class Show extends Controller
{
    public function __invoke(string $filename)
    {
        return (new Presenter($filename))->get();
    }
}
