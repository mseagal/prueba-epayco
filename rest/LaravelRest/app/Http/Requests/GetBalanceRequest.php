<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetBalanceRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'doc' => 'required|min:1|string|numeric',
            'cel' => 'required|min:10|string|numeric'
        ];
    }
}
