<?php
// ANA MOLINA 01/09/2023
namespace App\Http\Resources\Escolares;

use Illuminate\Http\Resources\Json\JsonResource;

class GeneralesResource extends JsonResource
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
