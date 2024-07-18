<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMarcaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return True;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nome' => 'required|unique:marcas,nome,'.$this->id.'|min:3',
            'imagem' => 'required|file|mimes:png'
        ];
    }

    public function messages() {
        return [
            'required' => 'O campo :attribute é obritatório!',
            'nome.unique' => 'A marca já existe!',
            'nome.min' => 'O nome deve ter no mínimo 3 caracteres!',
            'imagem.mimes' => 'O arquivo deve ser uma imagem do tipo png!'
        ];
    }
}
