<?php

namespace LaravelLiberu\Logs\Exceptions;

use LaravelLiberu\Helpers\Exceptions\LiberuException;
use LaravelLiberu\Logs\Services\Presenter;

class Log extends LiberuException
{
    public static function sizeExceded()
    {
        return new self(__(
            'Log file exceeds the limit of :limit MB',
            ['limit' => Presenter::LogSizeLimit]
        ));
    }
}
