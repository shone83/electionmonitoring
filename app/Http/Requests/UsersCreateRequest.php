<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UsersCreateRequest extends FormRequest
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
            'username' => 'required|string|max:50|unique:users',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6'
        ];
    }

    public function messages()
    {
        return [
          'name.required'     => 'Morate uneti ime i prezime',
          'name.max'          => 'Ime i prezime ne može imati više od 50 karaktera',
          'username.required' => 'Morate uneti korisničko ime',
          'username.max'      => 'Korisničko ime ne moze imati više od 50 karaktera',
          'username.unique'   => 'Korisničko ime već postoji u bazi',
          'email.required'    => 'Morate uneti email',
          'email.max'         => 'Email ne moze imati više od 255 karaktera',
          'email.unique'      => 'Email već postoji u bazi',
          'password.required' => 'Morate uneti lozinku',
          'password.min'      => 'Lozinka ne može imati manje od 6 karaktera'
        ];
    }
}
