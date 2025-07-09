<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission; //Spatie es un paquete que permite el manejo de permisos y roles

class PermissionsSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permisos = [

            //Operacions sobre tabla users
            'user-ver',
            'user-crear',
            'user-editar',
            'user-borrar',

            //Operaciones sobre tabla roles
            'rol-ver',
            'rol-crear',
            'rol-editar',
            'rol-borrar',

            //Operaciones sobre tabla bitacoras
            'bitacoras-ver',

            //Operaciones sobre tabla planteles
            'plantel-ver',
            'plantel-crear',
            'plantel-editar',
            'plantel-borrar',

            //Operaciones sobre tabla ciclos esc
            'ciclos_esc-ver',
            'ciclos_esc-crear',
            'ciclos_esc-editar',
            'ciclos_esc-borrar',

            //Operaciones sobre tabla núcleos
            'nucleo-ver',
            'nucleo-crear',
            'nucleo-editar',
            'nucleo-borrar',

            //Operaciones sobre tabla áreas de formación
            'areaformacion-ver',
            'areaformacion-crear',
            'areaformacion-editar',
            'areaformacion-borrar',

            //Operaciones sobre tabla asignaturas
            'asignatura-ver',
            'asignatura-crear',
            'asignatura-editar',
            'asignatura-borrar',

            //Operaciones sobre tabla reglamentos
            'reglamento-ver',
            'reglamento-crear',
            'reglamento-editar',
            'reglamento-borrar',

            //Operaciones sobre tabla planes de estudio
            'plandeestudio-ver',
            'plandeestudio-crear',
            'plandeestudio-editar',
            'plandeestudio-borrar',

            //Operaciones sobre tabla planes de estudio
            'politica-ver',
            'politica-crear',
            'politica-editar',
            'politica-borrar',

             //Operaciones sobre tabla docente
            'docente-ver',
            'docente-crear',
            'docente-editar',
            'docente-borrar',

            //Operaciones sobre tabla aula LS2023-10-05
            'aula-ver',
            'aula-crear',
            'aula-editar',
            'aula-borrar',

            //Operaciones sobre table Grupos
            'grupos-ver',
            'grupos-crear',
            'grupos-editar',
            'grupos-borrar',

            //Operaciones sobre cursos
            'cursos-ver',
            'cursos-crear',
            'cursos-editar',
            'cursos-borrar',

            //Promocion alumnos
            'promocion-ver',
            'promocion-crear',
            'promocion-borrar',

            //admin_alumno
            'kardex-imprimir',
            'traslado-alumno',
            'alumno-imagen',
            'grupo-eliminar-alumno',
            'cursos-omitidos-alumno',
            'acta_extemporanea_alumno',
            'credenciales',
            'reconocimiento_capacitacion',

            //Operaciones sobre tabla equivalencia
            'equivalencia-ver',
            'equivalencia-crear',
            'equivalencia-editar',
            'equivalencia-borrar',

        ];

        foreach($permisos as $permiso) {
            $permission = Permission::where('name',$permiso)->first();

            if($permission == null)
            {
                Permission::create(['name'=>$permiso]);
            }
        }
    }
}
