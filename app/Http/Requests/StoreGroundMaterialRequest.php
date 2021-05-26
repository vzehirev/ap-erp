<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGroundMaterialRequest extends FormRequest
{
    protected $errorBag = 'storeGroundMaterial';

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
            'ground_on' => ['required', 'date'],
            'worker_id' => ['required', 'integer'],
            'quantity' => ['required', 'numeric'],
            'material_id' => ['required', 'integer'],
        ];
    }
}
