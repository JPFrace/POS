<?php

namespace App\Supports\Dto\Journals;

use App\Enums\ContactType;
use App\Enums\TransType;
use App\Models\Department;
use App\Models\User;
use Carbon\Carbon;

class JournalEntryDetail
{
    public function __construct(
        protected TransType $type,
        protected string $refNo,
        protected string $transCode,
        protected User $creator,
        protected Carbon $postedAt,
        protected ?string $contactName = null,
        protected ?ContactType $contactType = null,
        protected ?string $contactIdNo = null,
        protected ?string $description = null,
        protected ?string $subContactIdNo = null,
        protected ?Department $department = null,
    ) {

    }

    /**
     * Dynamic calls of attribute function
     * @param mixed $name
     * @param mixed $arguments
     * @throws \Exception
     */
    public function __call($name, $arguments)
    {
        if (!property_exists($this, $name)) {
            throw new \Exception("This method [{$name}] is undefined.");
        }

        return $this->$name;
    }

    /**
     * Parse to array format
     * @return array{contact_idno: string, contact_name: string, contact_type: ContactType, creator_id: mixed, description: string, ref_no: string, sub_contact_idno: string|null}
     */
    public function toArray()
    {
        return [
            'ref_no' => $this->refNo,
            'trans_code' => $this->transCode,
            'creator_id' => $this->creator?->id,
            'posted_by' => $this->creator?->id,
            'contact_name' => $this->contactName,
            'contact_type' => $this->contactType,
            'contact_idno' => $this->contactIdNo,
            'sub_contact_idno' => $this->subContactIdNo,
            'description' => $this->description,
            'posted_at' => $this->postedAt,
            'dept_id' => $this->department?->id,
        ];
    }
}