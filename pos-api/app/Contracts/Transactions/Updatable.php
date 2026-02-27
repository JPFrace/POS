<?php

namespace App\Contracts\Transactions;

interface Updatable
{
    public function update(string $uuid, array $attributes = []);
}