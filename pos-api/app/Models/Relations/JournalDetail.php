<?php

namespace App\Models\Relations;

use App\Models\Auditable;
use App\Models\ChartAccount;
use App\Models\Contact;
use App\Models\Department;
use App\Models\JournalEntry;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

abstract class JournalDetail extends Auditable
{
    public function parent(): BelongsTo
    {
        return $this->belongsTo(JournalEntry::class, 'entry_id');
    }

    public function chartAccount()
    {
        return $this->belongsTo(ChartAccount::class);
    }

    public function journalEntry()
    {
        return $this->belongsTo(JournalEntry::class);
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class, 'contact_idno', 'id_no');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'dept_id');
    }

    public function postedBy()
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    public function unpostedBy()
    {
        return $this->belongsTo(User::class, 'unposted_by');
    }

    public function debit()
    {
        return $this->parent->journals()->where('debit', '>', 0);
    }

    public function credit()
    {
        return $this->parent->journals()->where('credit', '>', 0);
    }
}