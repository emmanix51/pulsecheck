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

    public function prepareForValidation()
    {
        $questions = $this->input('questions', []);
        foreach ($questions as &$question) {
            if (is_string($question['data'])) {
                $question['data'] = json_decode($question['data'], true);
            }
        }
        $this->merge(['questions' => $questions]);
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
            'respondent_groups.*.type' => 'required_with:respondent_groups|string|in:student,faculty,staff,stakeholder',
            'respondent_groups.*.category' => 'nullable|string',
            'questions' => 'array',
            'questions.*.question_type' => 'sometimes|required|string|in:text,radio',
            'questions.*.question' => 'sometimes|required|string|max:2000',
            'questions.*.description' => 'nullable|string',
            'questions.*.data' => 'nullable|array',
            // 'questions.*.data.*' => 'integer|in:1,2,3,4,5',
            'information_fields' => 'sometimes|required|array',
            'information_fields.*.label' => 'required_with:information_fields|string|max:255',
            'information_fields.*.type' => 'required_with:information_fields|string|in:text,textarea,select',
            'information_fields.*.options' => 'nullable|string',
        ];
    }
}
