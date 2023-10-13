<?php

namespace LaravelLiberu\Logs\Http\Controllers;

use Illuminate\Routing\Controller;
use LaravelLiberu\Logs\Services\Collection;

class Index extends Controller
{
    public function __invoke()
    {
        return (new Collection())->get();
    }
}
