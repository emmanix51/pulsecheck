<?php

namespace App\Http\Requests;

use App\Http\Resources\QuestionResource;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;


class StoreSurveyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

     protected function prepareForValidation()
    {
        $this->merge([
            'user_id' => $this->user()->id
        ]);
        Log::info('Validated Data:');


        // Decode questions data if it's a JSON string
        if($this->has('question_sections')){
            $questionSections = $this->input('question_sections');
            foreach($questionSections as &$section){
                if(isset($section['question_groups'])){
                    foreach($section['question_groups'] as &$group){
                        if (isset($group['questions'])) {
                            foreach ($group['questions'] as &$question) {
                                if (isset($question['data']) && is_string($question['data'])) {
                                    $question['data'] = json_decode($question['data'], true);
                        }
                    }
                }
                    }
                }
            }
        }
    }

    // protected function prepareForValidation()
    // {
    //     $this->merge([
    //         'user_id' => $this->user()->id
    //     ]);
    //     if ($this->has('questions')) {
    //         $questions = $this->input('questions');
    //         foreach ($questions as &$question) {
    //             if (isset($question['data']) && is_string($question['data'])) {
    //                 $question['data'] = json_decode($question['data'], true);
    //             }
    //         }
    //         $this->merge(['questions' => $questions]);
    //     }
    // }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
            'title' => 'required|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'user_id' => 'exists:users,id',
            'status' => 'required|boolean',
            'is_public' => 'required|boolean',
            'description' => 'nullable|string',
            'instruction' => 'nullable|string',
            'expire_date' => 'nullable|date|after:tomorrow',
            'respondent_groups' => 'required|array',
            'respondent_groups.*.type' => 'required|string|in:student,faculty,staff,stakeholder',
            'respondent_groups.*.category' => 'nullable|string',
            // 'questions.*.data.*' => 'integer|in:1,2,3,4,5',
            'information_fields' => 'required|array',
            'information_fields.*.label' => 'required|string|max:255',
            'information_fields.*.type' => 'required|string|in:text,number,textarea,select',
            'information_fields.*.options' => 'nullable|string',

            // Question sections validation
        'question_sections' => 'required|array',
        'question_sections.*.section_number' => 'required|integer',
        'question_sections.*.section_label' => 'required|string|max:255',
        'question_sections.*.section_instruction' => 'nullable|string|max:99999',

        // Question groups within sections validation
        'question_sections.*.question_groups' => 'required|array',
        // 'question_sections.*.question_groups.*.section_number' => 'required|integer',
        'question_sections.*.question_groups.*.format' => 'required|string',
        'question_sections.*.question_groups.*.number' => 'required|integer',
        'question_sections.*.question_groups.*.label' => 'required|string|max:255',
        'question_sections.*.question_groups.*.question_instruction' => 'nullable|string|max:99999',
        'question_sections.*.question_groups.*.category_label' => 'nullable|string|max:255',
        
        // Question categories validation within question groups
        'question_sections.*.question_groups.*.question_categories' => 'nullable|array',
        'question_sections.*.question_groups.*.question_categories.*' => 'nullable|string|max:255',

        // Questions within question groups validation
        'question_sections.*.question_groups.*.questions' => 'required|array',
        'question_sections.*.question_groups.*.questions.*.group' => 'required|integer',
        'question_sections.*.question_groups.*.questions.*.category' => 'nullable|string|max:255',
        'question_sections.*.question_groups.*.questions.*.question_type' => 'required_with:question_sections.*.question_groups.*.questions|string|in:text,radio',
        'question_sections.*.question_groups.*.questions.*.question' => 'required_with:question_sections.*.question_groups.*.questions|string|max:2000',
        'question_sections.*.question_groups.*.questions.*.description' => 'nullable|string',
        'question_sections.*.question_groups.*.questions.*.data' => 'nullable|array',
        'question_sections.*.question_groups.*.questions.*.data.*' => 'nullable|integer|in:1,2,3,4,5', // Validate Likert scale options
        ];
    }
}
