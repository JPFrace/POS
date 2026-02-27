<?php

namespace App\Contracts\Transactions;

interface Creatable
{
    public function create(array $attributes = []);
}