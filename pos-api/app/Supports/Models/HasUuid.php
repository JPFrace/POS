<?php

namespace App\Supports\Models;

use Ramsey\Uuid\Uuid;

trait HasUuid
{
    /**
     * Generate a new UUID for the model.
     */
    public function newUniqueId(): string
    {
        return Uuid::uuid4()->toString();
    }

    /**
     * Get the columns that should receive a unique identifier.
     *
     * @return array<int, string>
     */
    public function uniqueIds(): array
    {
        return ['uuid'];
    }
}