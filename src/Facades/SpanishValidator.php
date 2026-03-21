<?php

namespace Danielmrdev\SpanishValidator\Facades;

use Illuminate\Support\Facades\Facade;

class SpanishValidator extends Facade
{
    /**
     * Get the binding in the IoC container.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'Validator'; // the IoC binding.
    }
}
