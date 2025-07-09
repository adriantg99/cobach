<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GrupoAlumnoSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ( $i = 1; $i <= 8; $i++ )
        {
            $this->seed_esc_grupo_alumno($i);
        }
    }

    function seed_esc_grupo_alumno($i)
    {
        $path = base_path().'/database/seeds/seed_esc_grupo_alumno/seed_esc_grupo_alumno_' . ($i < 10 ? '0' . $i : $i) . '.sql';
        $sql = file_get_contents($path);
        DB::unprepared($sql);
    }
}
