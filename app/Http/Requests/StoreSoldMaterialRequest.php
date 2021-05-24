<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSoldMaterialRequest extends FormRequest
{
    protected $errorBag = 'storeSoldMaterial';

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
            'sold_on' => 'required|date',
            'partner_id' => 'required|integer',
            'material_id' => 'required|integer',
            'quantity' => 'required|numeric',
            'price' => 'required|numeric',
            'paid' => 'required|boolean',
        ];
    }
}
