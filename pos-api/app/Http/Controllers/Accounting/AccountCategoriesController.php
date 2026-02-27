<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\AccountCategory;
use App\Repositories\GlobalRepository;
use Illuminate\Http\Request;

class AccountCategoriesController extends Controller
{
    private GlobalRepository $repository;

    public function __construct()
    {
        $this->repository = new GlobalRepository(new AccountCategory());
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $request->user()->throwCannot("");

        return $this->repository->list(paginate: true);
    }
}
