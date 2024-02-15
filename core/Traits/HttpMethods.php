<?php

namespace Core\Traits;

use Enums\HTTP;

trait HttpMethods
{
    static public function get(string $uri): static
    {
        return static::setUri($uri)->setMethod(HTTP::GET);
    }

    static public function post(string $uri): static
    {
        return static::setUri($uri)->setMethod(HTTP::POST);
    }

    static public function put(string $uri): static
    {
        return static::setUri($uri)->setMethod(HTTP::PUT);
    }

    static public function delete(string $uri): static
    {
        return static::setUri($uri)->setMethod(HTTP::DELETE);
    }
}
