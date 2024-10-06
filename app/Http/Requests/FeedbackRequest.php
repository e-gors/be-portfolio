<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FeedbackRequest extends FormRequest
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
            'profileImage' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'guestName' => 'nullable|string',
            'project' => 'required|string',
            'message' => 'required|string|min:200|max:600',
            'rating' => 'required|numeric|min:1|max:5'
        ];
    }

     /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'profile_image' => $this->profilePicture,
            'guest_name' => $this->guestName,
        ]);
    }
}
