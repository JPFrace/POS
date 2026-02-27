<?php

namespace App\Contracts\Supports;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface Repository
{
    /**
     * Create
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function create(array $data): ?Model;

    /**
     * Update
     * @param array $data
     * @param string $uuid
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function update(array $data, string $uuid);

    /**
     * Find by uuid
     * @param mixed $id
     * @param mixed $key
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function findByUuid(mixed $id, $key = 'uuid'): ?Model;

    /**
     * Find by uuid
     * @param mixed $id
     * @param mixed $key
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findBy(mixed $id, $key = 'id'): Collection;

    /**
     * Delete
     * @param string|int|array $id
     * @param mixed $key
     * @return void
     */
    public function delete(string|int|array $id, $key = 'uuid'): bool|null;
}