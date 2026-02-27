<?php

namespace App\Supports\Models;

use Illuminate\Database\Eloquent\Model;

trait Common
{
    /**
     * Match model value
     */
    public function sameAs(Model|int|string $model, $key = 'id'): bool
    {
        if ($model instanceof Model) {
            return $model->$key === $this->$key;
        }

        return $model === $this->$key;
    }
}