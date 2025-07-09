<?php

namespace App\Models;

use App\Models\Administracion\BitacoraModel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;
    // ANA MOLINA 23/06/2023
    // asegurar conexion en bd (al mover la bitácora a otra base de datos apuntaba a la bd de la bitácora)
    protected $connection ="mysql";
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'google_picture',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function adminlte_image()
    {   
        if(strlen($this->google_picture)>0)
        {
            return $this->google_picture;
        }
        else
        {
            return url('/').'/images/AdminLTELogo.png';
        }
    }

    public function ultimo_ingreso()
    {
        $bitacora_id = BitacoraModel::where('user_id',$this->id)->latest()->value('id');
        $bitacora = BitacoraModel::find($bitacora_id);
        if($bitacora)
        {
            return $bitacora->created_at;
        }
        else
        {
            return null;
        }
    }
}
