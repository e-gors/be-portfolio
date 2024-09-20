<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectRequest extends FormRequest
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
            'clientType' => 'required|string',
            'type' => 'required|string',
            'name' => 'required|string',
            'link' => 'nullable|string|regex:/^(https?:\/\/)?(www\.)?[a-zA-Z0-9-]+\.[a-zA-Z]{2,}(\/[^\s]*)?$/',
            'picture' => 'required|file|mimes:jpg,jpeg,png|max:2048',
            'description' => 'required|string|min:50|max:150'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'client_type' => $this->clientType,
        ]);
    }
}
