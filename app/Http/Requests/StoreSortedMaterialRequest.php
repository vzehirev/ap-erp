<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSortedMaterialRequest extends FormRequest
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
            'sorted_on' => 'required|date',
            'partner_id' => 'required|integer',
            'worker_id' => 'required|integer',
            'quantity' => 'required|numeric',
        ];
    }
}
