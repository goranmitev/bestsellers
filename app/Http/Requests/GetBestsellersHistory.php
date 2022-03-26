<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetBestsellersHistory extends FormRequest
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
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'offset.multipleof20' => 'The offset should be multiple of 20',
            'isbn.isbn' => 'The ISBN should be 10 or 13 digits and can be multiple ISBNs separated with semicolons'
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'author' => 'string',
            'isbn' => 'nullable|isbn',
            'title' => 'string',
            'offset' => 'integer|multipleof20',
        ];
    }
}
