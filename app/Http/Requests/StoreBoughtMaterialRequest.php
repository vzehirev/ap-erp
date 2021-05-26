<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBoughtMaterialRequest extends FormRequest
{
    protected $errorBag = 'storeBoughtMaterial';

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
            'bought_on' => ['required', 'date'],
            'partner_id' => ['required', 'integer'],
            'material_id' => ['required', 'integer'],
            'price' => ['required', 'numeric'],
            'quantity' => ['required', 'numeric'],
            'invoice_num' => ['nullable', 'string'],
        ];
    }
}
