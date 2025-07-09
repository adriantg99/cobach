<?php

namespace App\Http\Requests\Catalogos;

use Illuminate\Foundation\Http\FormRequest;

class CicloEscRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //return false;
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'abreviatura'       =>  'required|max:20',
            'nombre'            =>  'required|max:150',
            'per_inicio'        =>  'date',
            'per_final'         =>  'date',
            'activo'            =>  'required',
        ];
    }
}
