<?php

namespace Database\Seeders;

use App\Models\Config;
use App\Services\Configuration;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class ConfigurationSeeder extends Seeder
{
    public function run()
    {
        $configs = include database_path('seeders/data/configs.php');
        $this->insertConfigs($configs);
        $this->GenerateCache();
    }

    private function insertConfigs(array $configs, int $parentId = 0)
    {
        $now = Carbon::now();

        foreach ($configs as $config) {
            $children = $config['children'] ?? [];
            unset($config['children']);

            $config['uuid'] = Str::uuid();
            $config['parent_id'] = $parentId;
            $config['options'] = $config['options'] ?? null;
            $config['value'] = $config['value'] ?? null;
            $config['use_prefix'] = $config['use_prefix'] ?? 0;
            $config['use_suffix'] = $config['use_suffix'] ?? 0;
            $config['is_inactive'] = $config['is_inactive'] ?? 1;
            $config['created_at'] = $now;
            $config['updated_at'] = $now;

            $existing = DB::table('configs')->where('slug', $config['slug'])->first();

            if ($existing) {
                // unset($config['value'], $config['is_inactive']);
                // DB::table('configs')->where('id', $existing->id)->update($config);
                $currentId = $existing->id;
            } else {
                $currentId = DB::table('configs')->insertGetId($config);
            }

            if (!empty($children)) {
                $this->insertConfigs($children, $currentId);
            }
        }
    }

    private function GenerateCache()
    {
        try {
            Configuration::GenerateCache();
            $this->command->info("Configurations Cache generated");
        } catch (Exception $e) {
            $this->command->error($e);
        }
    }
}
