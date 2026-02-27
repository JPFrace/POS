<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reports\Bookmarks\BookmarkStoreRequest;
use App\Http\Requests\Reports\Bookmarks\BookmarkUpdateRequest;
use App\Http\Resources\Reports\BookmarkResource;
use App\Models\Bookmark;
use App\Repositories\BookmarkRepository;
use Illuminate\Http\Request;

class BookmarksController extends Controller
{
    public function __construct(protected BookmarkRepository $repository)
    {
        // You can inject any dependencies here if needed
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = gettype($request->get('query', [])) == 'string' ? json_decode($request->get('query', []), true) : $request->get('query', []);
        $request->merge([
            'query' => [
                ...$query,
                'user_bookmark' => true,
            ]
        ]);
        return $this->query(
            $this->repository,
            BookmarkResource::class,
            $request
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BookmarkStoreRequest $request)
    {
        $this->catch(fn() => $this->repository->create($request->only([
            'date_from',
            'date_to',
            'report_id',
            'user_id',
            'name',
            'group'
        ])), false);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BookmarkUpdateRequest $request, Bookmark $bookmark)
    {
        $this->catch(fn(): mixed => $this->repository->update($request->only([
            'date_from',
            'date_to',
            'report_id',
            'name',
            'group'
        ]), $bookmark->uuid, 'uuid'), false);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bookmark $bookmark)
    {
        $this->catch(fn() => $this->repository->delete($bookmark->uuid, 'uuid'), false);
    }
}
