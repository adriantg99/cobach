<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesUpdateSeeder extends Seeder {
    use WithoutModelEvents;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $path = base_path() . '/database/seeds/seeds_temporales/seed_roles_update_csv.sql';
        $sql = file_get_contents($path);
        DB::unprepared($sql);
    }
}
