<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WalletSharesRequest extends FormRequest
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
            'comment' => 'max:255',
            'price' => 'numeric|gt:0',
            'comission' => 'numeric|gt:0',
            'amount' => 'numeric|gt:0',
        ];
    }
}
