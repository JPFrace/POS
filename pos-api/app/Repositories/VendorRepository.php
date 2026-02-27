<?php

namespace App\Repositories;

use App\Enums\ContactType;
use App\Models\Contact;
use App\Models\ContactDetail;
use App\Supports\Utils\Upload;
use Illuminate\Database\Eloquent\Model;

class VendorRepository extends Repository
{
    use Conditions\ContactConditions;
    use Upload;

    public function __construct(protected Contact $model)
    {
    }

    public function create(array $data): Contact
    {
        return \DB::transaction(function () use ($data) {
            $data['type'] = ContactType::VENDOR;
            $vendor = parent::create($data);

            if (isset($data['contacts']) && is_array($data['contacts'])) {
                $vendor->contacts()->createMany($data['contacts']);
            }

            return $vendor;
        });
    }

    public function update(array $data, $id, $key = 'uuid'): Contact
    {
        return \DB::transaction(function () use ($data, $id, $key) {
            $contacts = $data['contacts'] ?? [];
            $file = $data['file'] ?? null;
            $fileExists = array_key_exists('file', $data);

            unset($data['contacts'], $data['file']);

            $vendor = parent::update($data, $id, $key);

            if ($file === "") {
                $file = null;
            }

            if ($fileExists) {
                if ($file === null) {
                    $vendor->file()->dissociate();
                } else {
                    $uploaded = $this->upload($file, 'contacts');
                    if ($uploaded) {
                        $vendor->file()->associate($uploaded);
                    }
                }
            }

            $vendor->save();
            $vendor->refresh();

            if (!empty($contacts) && is_array($contacts)) {
                foreach ($contacts as $contact) {
                    if (!empty($contact['uuid'])) {
                        $existingContact = ContactDetail::where('uuid', $contact['uuid'])->first();
                        $existingContact?->update($contact);
                    } else {
                        $vendor->contacts()->create($contact);
                    }
                }
            }
            return $vendor;
        });
    }

    public function delete(string|int|array $id, $key = 'uuid'): bool|null
    {
        return \DB::transaction(function () use ($id, $key) {
            $ids = is_array($id) ? $id : [$id];
            if ($this->model()->where('type', ContactType::VENDOR)->whereIn($key, $ids)->exists()) {
                return parent::delete($id, $key);
            }
        });
    }

    public function findByUuid(mixed $id, $key = 'uuid'): ?Model
    {
        return \DB::transaction(function () use ($id, $key) {
            $vendor = $this->model()->where($key, $id)
                ->where('type', ContactType::VENDOR)
                ->with(['class', 'file', 'tax', 'country'])
                ->first();
            if ($vendor) {
                $vendor->load('contacts');
            }
            return $vendor;
        });
    }
}
