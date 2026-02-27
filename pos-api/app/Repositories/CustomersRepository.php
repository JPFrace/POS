<?php

namespace App\Repositories;

use App\Models\Contact;
use App\Enums\ContactType;
use App\Models\ContactDetail;
use App\Supports\Utils\Upload;
use Illuminate\Database\Eloquent\Model;

class CustomersRepository extends Repository
{
    use Conditions\ContactConditions;
    use Upload;

    public function __construct(protected Contact $model)
    {
    }

    public function create(array $data): Contact
    {
        return \DB::transaction(function () use ($data) {
            $data['type'] = ContactType::CUSTOMER;
            $customer = parent::create($data);

            if (isset($data['contacts']) && is_array($data['contacts'])) {
                $customer->contacts()->createMany($data['contacts']);
            }

            return $customer;
        });
    }

    public function update(array $data, $id, $key = 'uuid'): Contact
    {
        return \DB::transaction(function () use ($data, $id, $key) {
            $contacts = $data['contacts'] ?? [];
            $file = $data['file'] ?? null;
            $fileExists = array_key_exists('file', $data);

            unset($data['contacts'], $data['file']);

            $customer = parent::update($data, $id, $key);

            if ($file === "") {
                $file = null;
            }

            if ($fileExists) {
                if ($file === null) {
                    $customer->file()->dissociate();
                } else {
                    $uploaded = $this->upload($file, 'contacts');
                    if ($uploaded) {
                        $customer->file()->associate($uploaded);
                    }
                }
            }

            $customer->save();
            $customer->refresh();

            if (!empty($contacts) && is_array($contacts)) {
                foreach ($contacts as $contact) {
                    if (!empty($contact['uuid'])) {
                        $existingContact = ContactDetail::where('uuid', $contact['uuid'])->first();
                        $existingContact?->update($contact);
                    } else {
                        $customer->contacts()->create($contact);
                    }
                }
            }
            return $customer;
        });
    }

    public function delete(string|int|array $id, $key = 'uuid'): bool|null
    {
        return \DB::transaction(function () use ($id, $key) {
            $ids = is_array($id) ? $id : [$id];
            if ($this->model()->where('type', ContactType::CUSTOMER)->whereIn($key, $ids)->exists()) {
                return parent::delete($id, $key);
            }
        });
    }

    public function findByUuid(mixed $id, $key = 'uuid'): ?Model
    {
        return \DB::transaction(function () use ($id, $key) {
            $customer = $this->model()->where($key, $id)
                ->where('type', ContactType::CUSTOMER)
                ->with(['class', 'file', 'tax', 'country'])
                ->first();
            if ($customer) {
                $customer->load('contacts');
            }
            return $customer;
        });
    }
}
