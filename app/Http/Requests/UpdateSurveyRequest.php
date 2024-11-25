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
        $survey = $this->route('survey');
        if ($this->user()->id !== $survey->user_id) {
            return false;
        }
        return true;
    }

    public function prepareForValidation()
    {
        $questionGroups = $this->input('question_groups', []);
    foreach ($questionGroups as &$group) {
        if (isset($group['questions'])) {
            foreach ($group['questions'] as &$question) {
                if (is_string($question['data'])) {
                    $question['data'] = json_decode($question['data'], true);
                }
            }
        }
    }
    $this->merge(['question_groups' => $questionGroups]);
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
            
            'status' => 'sometimes|required|boolean',
            'is_public' => 'required|boolean',
            'instruction' => 'nullable|string',
            'description' => 'nullable|string',
            'expire_date' => 'nullable|date|after:tomorrow',
            'respondent_groups' => 'sometimes|required|array',
            'respondent_groups.*.type' => 'required_with:respondent_groups|string|in:student,faculty,staff,stakeholder',
            'respondent_groups.*.category' => 'nullable|string',
      
            // 'questions.*.data.*' => 'integer|in:1,2,3,4,5',
            'information_fields' => 'sometimes|required|array',
            'information_fields.*.label' => 'required_with:information_fields|string|max:255',
            'information_fields.*.type' => 'required_with:information_fields|string|in:text,textarea,select,number',
            'information_fields.*.options' => 'nullable|string',
            // Question sections validation
        'question_sections' => 'sometimes|array',
        'question_sections.*.section_number' => 'sometimes|required|integer',
        'question_sections.*.section_label' => 'sometimes|required|string|max:255',
        'question_sections.*.section_instruction' => 'sometimes|nullable|string|max:99999',

        // Question groups within sections validation
        'question_sections.*.question_groups' => 'sometimes|array',
        'question_sections.*.question_groups.*.section_number' => 'sometimes|required|integer',
        'question_sections.*.question_groups.*.format' => 'required|string',
        'question_sections.*.question_groups.*.number' => 'sometimes|required|integer',
        'question_sections.*.question_groups.*.label' => 'sometimes|required|string|max:255',
        'question_sections.*.question_groups.*.group_question' => 'sometimes|required|string|max:255',
        'question_sections.*.question_groups.*.question_instruction' => 'sometimes|nullable|string|max:99999',
        'question_sections.*.question_groups.*.category_label' => 'sometimes|nullable|string|max:255',
        
        // Question categories validation within question groups
        'question_sections.*.question_groups.*.question_categories' => 'sometimes|nullable|array',
        'question_sections.*.question_groups.*.question_categories.*' => 'sometimes|nullable|string|max:255',

        // Questions within question groups validation
        'question_sections.*.question_groups.*.questions' => 'sometimes|array',
        'question_sections.*.question_groups.*.questions.*.group' => 'sometimes|required|integer',
        'question_sections.*.question_groups.*.questions.*.category' => 'sometimes|nullable|string|max:255',
        'question_sections.*.question_groups.*.questions.*.question_type' => 'sometimes|required_with:question_sections.*.question_groups.*.questions|string|in:text,radio',
        'question_sections.*.question_groups.*.questions.*.question' => 'sometimes|required_with:question_sections.*.question_groups.*.questions|string|max:2000',
        'question_sections.*.question_groups.*.questions.*.description' => 'sometimes|nullable|string',
        'question_sections.*.question_groups.*.questions.*.data' => 'sometimes|nullable|array',
        'question_sections.*.question_groups.*.questions.*.data.*' => 'sometimes|nullable|integer|in:1,2,3,4,5', // Validate Likert scale options
        //            'question_groups' => 'sometimes|array', // Changed to sometimes for partial updates
        // 'question_groups.*.number' => 'sometimes|required|integer',
        // 'question_groups.*.label' => 'sometimes|required|string|max:255',
        // 'question_groups.*.question_instruction' => 'sometimes|nullable|string|max:99999',
        // 'question_groups.*.category_label' => 'sometimes|nullable|string|max:255',
        // 'question_groups.*.question_categories' => 'sometimes|nullable|array',
        // 'question_groups.*.question_categories.*' => 'sometimes|nullable|string',
        // 'question_groups.*.questions' => 'sometimes|array',
        // 'question_groups.*.questions.*.question_type' => 'sometimes|required|string|in:text,radio',
        // 'question_groups.*.questions.*.question' => 'sometimes|required|string|max:2000',
        // 'question_groups.*.questions.*.description' => 'nullable|string',
        // 'question_groups.*.questions.*.data' => 'nullable|array',
        // 'question_groups.*.questions.*.data.*' => 'nullable|integer|in:1,2,3,4,5',
        // 'question_groups.*.questions.*.category' => 'nullable|string',
        // 'question_groups.*.questions.*.group' => 'sometimes|required|integer',
        ];
    }
}
