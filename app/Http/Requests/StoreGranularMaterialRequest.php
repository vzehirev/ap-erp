<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGranularMaterialRequest extends FormRequest
{
    protected $errorBag = 'storeGranularMaterial';

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
            'granular_on' => ['required', 'date'],
            'worker_id' => ['required', 'integer'],
            'from_materials' => ['required', 'exists:materials,id'],
            'quantity_before' => ['required'],
            'to_material_id' => ['required', 'integer'],
            'quantity' => ['required', 'numeric'],
        ];
    }
}
