<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AlumnoSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ( $i = 1; $i <= 3; $i++ )
        {
            $this->seed_alu_alumno($i);
        }
    }

    function seed_alu_alumno($i)
    {
        $path = base_path().'/database/seeds/seed_alu_alumno/seed_alu_alumno_' . ($i < 10 ? '0' . $i : $i) . '.sql';
        $sql = file_get_contents($path);
        DB::unprepared($sql);
    }
}
