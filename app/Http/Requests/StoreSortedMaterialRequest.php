<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSortedMaterialRequest extends FormRequest
{
    protected $errorBag = 'storeSortedMaterial';

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
            'sorted_on' => ['required', 'date'],
            'workers' => ['required', 'exists:workers,id'],
            'from_material_id' => ['nullable'],
            'to_material_id' => ['nullable'],
            'wasted_quantity' => ['required', 'numeric'],
            'quantity' => ['required', 'numeric'],
        ];
    }
}
