<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class BangladeshGeocodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();

        // 1. Divisions
        $divisionsData = $this->loadJsonData(database_path('data/divisions.json'), 'divisions');
        if($divisionsData) {
            DB::table('divisions')->truncate();
            foreach ($divisionsData as $item) {
                DB::table('divisions')->insert([
                    'id' => $item['id'],
                    'name' => $item['name'],
                    'bn_name' => $item['bn_name'],
                    'url' => $item['url'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // 2. Districts
        $districtsData = $this->loadJsonData(database_path('data/districts.json'), 'districts');
        if($districtsData) {
            DB::table('districts')->truncate();
            foreach ($districtsData as $item) {
                DB::table('districts')->insert([
                    'id' => $item['id'],
                    'division_id' => $item['division_id'],
                    'name' => $item['name'],
                    'bn_name' => $item['bn_name'],
                    'lat' => $item['lat'] ?? null,
                    'lon' => $item['lon'] ?? null,
                    'url' => $item['url'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // 3. Thanas (Upazilas)
        $thanasData = $this->loadJsonData(database_path('data/thanas.json'), 'upazilas');
        if($thanasData) {
            DB::table('thanas')->truncate();
            foreach ($thanasData as $item) {
                DB::table('thanas')->insert([
                    'id' => $item['id'],
                    'district_id' => $item['district_id'],
                    'name' => $item['name'],
                    'bn_name' => $item['bn_name'],
                    'url' => $item['url'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
    }

    private function loadJsonData($path, $tableName)
    {
        if (!File::exists($path)) {
            return null;
        }

        $json = File::get($path);
        $data = json_decode($json, true);

        // Check if it's the simplified list (divisions.json might be) or the full dump structure
        // Simple array check
        if (isset($data[0]) && isset($data[0]['id']) && !isset($data[0]['type'])) {
             return $data;
        }

        // Check for phpMyAdmin export structure
        foreach ($data as $block) {
            if (isset($block['type']) && $block['type'] === 'table' && $block['name'] === $tableName) {
                return $block['data'];
            }
        }
        
        return null;
    }
}
