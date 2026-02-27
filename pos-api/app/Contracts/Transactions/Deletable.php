<?php

namespace App\Contracts\Transactions;

interface Deletable
{
    public function delete(mixed $id, string $key = 'uuid');
}