<?php

namespace App\Repositories;

use App\Models\Contact;
use App\Supports\Utils\Upload;

class ContactRepository extends Repository
{
    use Conditions\ContactConditions;
    use Upload;

    public function __construct(protected Contact $model)
    {

    }

    public function create(array $data): Contact
    {
        return \DB::transaction(function () use ($data) {
            $contact = parent::create($data);

            if (isset($data['contacts']) && is_array($data['contacts'])) {
                $contact->contacts()->createMany($data['contacts']);
            }

            return $contact;
        });
    }

    public function update(array $data, $id, $key = 'uuid'): Contact
    {
        return \DB::transaction(function () use ($data, $id, $key) {
            $contacts = $data['contacts'] ?? [];
            $file = $data['file'] ?? null;

            unset($data['contacts'], $data['file']);

            $contact_person = parent::update($data, $id, $key);

            if ($file === null) {
                $contact_person->file()->dissociate();
            } else {
                $uploaded = $this->upload($file, 'contacts');
                if ($uploaded) {
                    $contact_person->file()->associate($uploaded);
                }
            }

            $contact_person->save();
            $contact_person->refresh();

            if ($contacts != []) {
                foreach ($contacts as $contact) {
                    if (isset($contact['uuid'])) {
                        $contact_person->contacts()->where('uuid', $contact['uuid'])->update($contact);
                    } else {
                        $contact_person->contacts()->create($contact);
                    }
                }
            }

            return $contact_person;
        });
    }
}