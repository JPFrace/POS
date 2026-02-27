<?php
namespace App\Repositories;

use App\Models\Bookmark;

class BookmarkRepository extends Repository
{
    use Conditions\BookmarkConditions;
    public function __construct(protected Bookmark $model)
    {

    }

    public function update(array $data, $id, $key = 'uuid'): Bookmark
    {
        return parent::update($data, $id, $key);   
    }
}
