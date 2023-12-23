<?php

namespace Tests\Traits;

use Tests\TestCase;

trait WithDatabaseMany
{
    protected function assertDatabaseHasMany(string $table, array $data): void
    {
        if (is_subclass_of($this, TestCase::class)) {
            collect($data)->each(fn ($item) => $this->assertDatabaseHas($table, $item));
        }
    }

    protected function assertDatabaseMissingMany(string $table, array $data): void
    {
        if (is_subclass_of($this, TestCase::class)) {
            collect($data)->each(fn ($item) => $this->assertDatabaseMissing($table, $item));
        }
    }

    protected function assertDatabaseHasOne(string $table, array $data): void
    {
        if (is_subclass_of($this, TestCase::class)) {
            $this->assertDatabaseCount($table, 1);
            $this->assertDatabaseHas($table, $data);
        }
    }
}