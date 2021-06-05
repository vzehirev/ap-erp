<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWashedMaterialRequest extends FormRequest
{
    protected $errorBag = 'storeWashedMaterial';

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
            'washed_on' => ['required', 'date'],
            'worker_id' => ['required', 'integer'],
            'from_material_id' => ['required', 'integer'],
            'quantity_before' => ['required', 'numeric'],
            'quantity' => ['required', 'numeric'],
            'to_material_id' => ['required', 'integer'],
        ];
    }
}
