<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DistrictsRequest extends FormRequest
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
            'name'     => 'required|string|max:50|unique:districts',
        ];
    }

    public function messages()
    {
        return [
          'name.required'     => 'Morate uneti naziv okruga',
          'name.max'          => 'Okrug ne može imati više od 50 karaktera',
          'name.unique'      =>  'Okrug već postoji u bazi',
        ];
    }
}
