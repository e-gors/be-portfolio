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
            'jobPosition' => 'required|string',
            'companyName' => 'required|string',
            'description' => 'required|string|min:350|max:700',
            'companyLogo' => 'nullable|file|mimes:jpg,jpeg,png, svg|max:2048',
            'link' => ['nullable', 'string', 'regex:/^(http:\/\/|https:\/\/)/'],
            'startDate' => ['required', 'regex:/^(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) \d{4}$/'],
            'endDate' => ['nullable', 'regex:/^(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) \d{4}$/', 'after_or_equal:startDate']
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'job_position' => $this->jobPosition,
            'company_name' => $this->companyName,
            'company_logo' => $this->companyLogo,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
        ]);
    }
}
