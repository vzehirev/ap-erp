<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePrepaidRequest extends FormRequest
{
    protected $errorBag = 'storePrepaid';

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
            'paid_on' => ['required', 'date'],
            'worker_id' => ['required', 'integer'],
            'price' => ['required', 'numeric'],
        ];
    }
}
