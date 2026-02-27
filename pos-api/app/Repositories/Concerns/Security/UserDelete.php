<?php

namespace App\Repositories\Concerns\Security;

use App\Exceptions\Throws;
use Auth;
use Exception;

trait UserDelete
{

    /**
     * Delete user exclude the current authenticated
     * @param string|int|array $id
     * @param mixed $key
     * @throws \Exception
     * @return bool|null
     */
    public function delete(string|int|array $id, $key = 'uuid'): bool|null
    {
        $ids = is_array($id) ? $id : [$id];

        foreach ($ids as $id) {
            if (Auth::user()->sameAs($id, $key)) {
                throw new Throws(Auth::user()->name . " is not allowed to delete since it is being used.");
            }
        }

        return parent::delete($ids);
    }
}