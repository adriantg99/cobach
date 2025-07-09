<?php

// ANA MOLINA 23/08/2023
namespace App\Http\Resources\Catalogos;

use Illuminate\Http\Resources\Json\JsonResource;

class UbicacionGeoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
