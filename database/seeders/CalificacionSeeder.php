<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CalificacionSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ( $i = 1; $i <= 70; $i++ )
        {
            $this->seed_esc_calificacion($i);
        }
    }

    function seed_esc_calificacion($i)
    {
        $path = base_path() . '/database/seeds/seed_esc_calificacion/seed_esc_calificacion_' . ($i < 10 ? '0' . $i : $i) . '.sql';
        $sql = file_get_contents($path);
        DB::unprepared($sql);
    }
}
