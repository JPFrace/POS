<?php

namespace App\Http\Controllers\setup;

use App\Http\Controllers\Controller;
use App\Http\Requests\setup\Config\StoreConfigRequest;
use App\Http\Resources\Setup\ConfigResources;
use App\Models\Config;
use App\Repositories\ConfigRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConfigController extends Controller
{
    public function __construct(protected ConfigRepository $repository)
    {

    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->query($this->repository, ConfigResources::class, $request);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreConfigRequest $request)
    {
        $this->repository->create($request->all());
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
    public function update(Request $request, string $id)
    {
        $configs = $request->all();

        DB::transaction(function () use ($configs) {
            foreach ($configs as $config) {
                $cfg = Config::where('uuid', $config['uuid'])->first();

                if (!$cfg) {
                    continue;
                }

                $data = [
                    'value' => $config['value'] ?? null,
                    'use_prefix' => $config['use_prefix'] ?? 0,
                    'use_suffix' => $config['use_suffix'] ?? 0,
                ];

                if (!empty($config['use_prefix'])) {
                    $data['prefix'] = $config['prefix'] ?? null;
                } else {
                    $data['prefix'] = null;
                }

                if (!empty($config['use_suffix'])) {
                    $data['suffix'] = $config['suffix'] ?? null;
                } else {
                    $data['suffix'] = null;
                }

                $cfg->update($data);
            }
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
