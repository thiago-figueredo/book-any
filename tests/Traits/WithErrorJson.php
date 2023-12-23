<?php

namespace Tests\Traits;

trait WithErrorJson
{
    protected function errorJsonStructure(): array
    {
        return ['message'];
    }

    protected function errorJson(string $message): array
    {
        return compact('message');
    }
}