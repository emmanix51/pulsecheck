<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSurveyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // $survey = $this->route('survey');
        // if ($this->user()->id !== $survey->user_id) {
        //     return false;
        // }
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'sometimes|required|string|max:1000',
            'image' => 'nullable|string',
            'status' => 'sometimes|required|boolean',
            'description' => 'nullable|string',
            'expire_date' => 'nullable|date|after:tomorrow',
            'respondent_groups' => 'sometimes|required|array',
            'respondent_groups.*.respondent_type' => 'required_with:respondent_groups|string|in:student,faculty,staff,stakeholder',
            'respondent_groups.*.categories' => 'nullable|string',
            'questions' => 'array'
        ];
    }
}
