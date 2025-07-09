<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        /**
         * Execute every seeder class. Fernando Carrasco 2021-09-23.
         */
        DB::disableQueryLog();

        $this->call([
            PermissionsSeeder::class,
            RolesSeeder::class,
            UsersSeeder::class,
            AreaFormacionSeeder::class,
            NucleoSeeder::class,
            PlantelSeeder::class,
            LocalidadesSeeder::class,
            Aula_tipoSeeder::class, //lS 2023-10-05
            Aula_condicionSeeder::class, //LS 2023-10-05
            AulaSeeder::class, //LS 2023-10-06
            Ciclos_escSeeder::class, //LS 2023-10-13
            AsignaturaSeeder::class, //LS 2023-10-17
            Reglamentos_politicasSeeder::class,  //LS 2023-10-22
            PlanestudioSeeder::class, //LS2023-10-22
            GruposSeeder::class,
            CursoSeeder::class,
            PeriodosSeeder::class,
            SecundariaSeeder::class,//LS2023-10-25
            GrupoAlumnoSeeder::class,
            AlumnoSeeder::class,
            CalificacionSeeder::class,
        ]);

        // \App\Models\User::factory(10)->create();

    }
}
