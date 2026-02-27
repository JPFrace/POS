<?php
namespace App\Repositories;

use App\Models\JournalEntryStatus;

class JournalEntryStatusRepository extends Repository
{
    use Conditions\JournalEntryStatusConditions;

    public function __construct(protected JournalEntryStatus $model)
    {

    }
}