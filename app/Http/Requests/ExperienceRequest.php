<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExperienceRequest extends FormRequest
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
            'title' => 'required|string',
            'description' => 'required|string|min:350|max:700',
            'companyLogo' => 'required|file|mimes:jpg,jpeg,png|max:2048',
            'link' => 'required|string|regex:/^(http:\/\/|https:\/\/)/',
            'startDate' => 'required|date',
            'endDate' => 'nullable|date|after_or_equal:startDate'
        ];
    }

      /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'company_logo' => $this->companyLogo,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
        ]);
    }
}
