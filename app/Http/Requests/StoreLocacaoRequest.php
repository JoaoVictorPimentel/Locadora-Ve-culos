<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLocacaoRequest extends FormRequest
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
            'cliente_id' => 'required|exists:clientes,id',
            'carro_id' => 'required|exists:carros,id',
            'data_inicio_periodo' => 'required', 
            'data_final_previsto_periodo' => 'required',
            'data_final_realizado_periodo' => 'required',
            'valor_diaria' => 'required',
            'km_inicial' => 'required',
            'km_final' => 'required'
        ];
    }

    public function messages() {
        return [
            'required' => 'O campo :attribute é obrigatório!',
            'cliente_id.exists' => 'O cliente não está cadastrado no sistema!',
            'carro_id.exists' => 'O carro não está cadastrado no sistema!',
        ];
    }
}
