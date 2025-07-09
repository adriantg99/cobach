<?php

namespace App\Models\Administracion;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BitacoraModel extends Model
{
    use HasFactory;
    // ANA MOLINA 23/06/2023
    // la bitácora se movió a otra base de datos
    protected $connection="mysql_bitacora";
    // ANA MOLINA 23/06/2023
    //se agregó prefijo a la bitácora
    protected $table = 'adm_bitacora';

    protected $fillable = [
        'user_id',
        'ip',
        'path',
        'method',
        'controller',
        'component',
        'function',
        'description',
        'created_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
