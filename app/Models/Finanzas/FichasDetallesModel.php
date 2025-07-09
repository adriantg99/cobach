<?php

namespace App\Models\Finanzas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FichasDetallesModel extends Model
{
    use HasFactory;

    protected $table = "fin_fichas_detalle";

    protected $fillable = [
        'folio',
        'matricula',
        'descripcion',
        'importe',
    ];
}
