<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocalidadesModel extends Model
{
    use HasFactory;

    protected $table = 'cat_localidades';

    protected $fillable = [
        'mapa',
        'estatus',
        'cve_ent',
        'nom_ent',
        'nom_abr',
        'cve_mun',
        'nom_mun',
        'cve_loc',
        'nom_loc',
        'ambito',
        'latitud',
        'longitud',
        'lat_decimal',
        'lon_decimal',
        'altitud',
        'cve_carta',
        'pob_total',
        'pob_masculina',
        'pob_femenina',
        'total_de_viviendas_habitadas',

    ];

    public $timestamps = false;
}
