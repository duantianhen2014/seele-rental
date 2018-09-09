<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductStoreRequest extends FormRequest
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
            'product_title' => 'required|max:255|min:10',
            'product_description' => 'required',
            'address' => 'required',
        ];
    }

    public function filldata()
    {
        return [
            'title' => $this->post('product_title'),
            'description' => $this->post('product_description'),
            'charge' => $this->post('charge', 0),
            'deposit' => $this->post('deposit', 0),
            'address' => $this->post('address'),
        ];
    }

}
