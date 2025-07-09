<?php

namespace App\Models\Escolares;

use App\Models\Adminalumnos\AlumnoModel;
use App\Models\Administracion\PerfilModel;
use App\Models\Catalogos\AsignaturaModel;
use App\Models\Catalogos\CicloEscModel;
use App\Models\Catalogos\PlantelesModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActaExtemporaneaModel extends Model
{
    use HasFactory;

    protected $table = "esc_acta_extemporanea";

    protected $fillable = [
        'alumno_id',
        'ciclo_esc_id',
        'grupo',
        'turno',
        'plantel',
        'plantel_id',
        'asignatura_id',
        'email_docente',
        'calificacion',
        'calif',
        'tipo_acta',
        'motivo',
        'user_id_creacion',
        'fecha_creacion',
        'impresiones',
        'fecha_impresion',
    ];

    public function asignatura()
    {
        return $this->belongsTo(AsignaturaModel::class, 'asignatura_id', 'id');
    }

    public function alumno()
    {
        return $this->belongsTo(AlumnoModel::class, 'alumno_id','id');
    }

    public function plantel_mod()
    {
        return $this->belongsTo(PlantelesModel::class, 'plantel_id','id');
    }

    public function ciclo_esc()
    {
        return $this->belongsTo(CicloEscModel::class, 'ciclo_esc_id','id');
    }

    public function nombre_doc()
    {
        $user_doc = User::where('email',$this->email_docente)->first();
        $perfil = PerfilModel::where('user_id',$user_doc->id)->first();
        if(is_null($perfil) == false)
        {
            $nombre = $perfil->nombre." ".$perfil->apellido1." ".$perfil->apellido2;
        }
        else
        {
            $nombre = $user_doc->name;
        }
        return $nombre;
    }

    public function nombre_creador_acta()
    {
        $perfil = PerfilModel::where('user_id',$this->user_id_creacion)->first();
        if(is_null($perfil) == false)
        {
            $nombre = $perfil->nombre." ".$perfil->apellido1." ".$perfil->apellido2;
        }
        else
        {
            $user_creador = User::find($this->user_id_creacion);
            $nombre = $user_creador->name;
        }
        return $nombre;
    }

    public function semestre_en_letra()
    {
        switch ($this->asignatura->periodo) {
            case 1:
                $letra = "PRIMER";
                break;

            case 2:
                $letra = "SEGUNDO";
                break;

            case 3:
                $letra = "TERCER";
                break;

            case 4:
                $letra = "CUARTO";
                break;

            case 5:
                $letra = "QUINTO";
                break;

            case 6:
                $letra = "SEXTO";
                break;
            
            default:
                // code...
                break;
        }
    }
}
