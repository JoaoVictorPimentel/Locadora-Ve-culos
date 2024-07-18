<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreModeloRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'marca_id' => 'exists:marcas,id',
            'nome' => 'required|unique:modelos,nome,'.$this->id.'|min:3',
            'imagem' => 'required|file|mimes:png,jpeg,jpg',
            'numero_portas' => 'required|integer|digits_between:1,5',
            'lugares' => 'required|integer|digits_between:1,20',
            'air_bag' => 'required|boolean',
            'abs' => 'required|boolean',
        ];
    }

    public function messages(){
        return [
            'required' => 'O campo :attribute é obrigatório!',
            'marca_id.exists' => 'A marca não está cadastrada no sistema!',
            'imagem.mime' => 'A imagem precisa ser do tipo png, jpeg ou jpg!',
            'numero_portas.integer' => 'O campo precisa ser preenchido com um número inteiro',
            'numero_portas.digits_between' => 'O campo deve ter no mínimo 1 caracter e no máximo 5 carateres!',
            'lugares.integer' => 'O campo precisa ser preenchido com um número inteiro',
            'lugares.digits_between' => 'O campo deve ter no mínimo 1 caracter e no máximo 20 carateres!',
        ];
    }
}
