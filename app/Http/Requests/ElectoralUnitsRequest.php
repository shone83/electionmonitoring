<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ElectoralUnitsRequest extends FormRequest
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
            'name'     => 'required|string|max:50',
        ];
    }

    public function messages()
    {
        return [
          'name.required'     => 'Morate uneti naziv izborne jedinice',
          'name.max'          => 'Izborna jedinica ne može imati više od 50 karaktera'
        ];
    }
}
