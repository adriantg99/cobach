<?php
namespace App\Traits;

use App\Models\Administracion\BitacoraModel;

trait BitacoraTrait {
    public function createBitacoraEntry( $function, $description ) {
        return BitacoraModel::create([
            'user_id'       =>  request()->user()->id,
            'ip'            =>  request()->ip(),
            'path'          =>  request()->path(),
            'method'        =>  request()->method(),
            'controller'    =>  class_basename(__CLASS__),
            'component'     =>  __CLASS__,
            'function'      =>  $function,
            'description'   =>  $description,
        ]);
    }
}