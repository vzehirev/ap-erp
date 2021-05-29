<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWastedMaterialRequest extends FormRequest
{
    protected $errorBag = 'storeWastedMaterial';

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
            'wasted_on' => ['required', 'date'],
            'worker_id' => ['nullable'],
            'from_material_id' => ['required', 'integer'],
            'quantity' => ['required', 'numeric'],
        ];
    }
}
