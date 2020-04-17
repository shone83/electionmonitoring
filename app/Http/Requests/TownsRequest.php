<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TownsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'     => 'required|string|max:50|unique:towns',
        ];
    }

    public function messages()
    {
        return [
          'name.required'     => 'Morate uneti naziv grada/opštine',
          'name.max'          => 'Grad/opština ne može imati više od 50 karaktera',
          'name.unique'      =>  'Grad/opština već postoji u bazi',
        ];
    }
}
