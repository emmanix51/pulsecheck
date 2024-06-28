<?php

namespace App\Http\Requests;

use App\Http\Resources\QuestionResource;
use Illuminate\Foundation\Http\FormRequest;

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
        if ($this->has('questions')) {
            $questions = $this->input('questions');
            foreach ($questions as &$question) {
                if (isset($question['data']) && is_string($question['data'])) {
                    $question['data'] = json_decode($question['data'], true);
                }
            }
            $this->merge(['questions' => $questions]);
        }
    }
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
            'image' => 'nullable|string',
            'user_id' => 'exists:users,id',
            'status' => 'required|boolean',
            'description' => 'nullable|string',
            'expire_date' => 'nullable|date|after:tomorrow',
            'respondent_groups' => 'required|array',
            'respondent_groups.*.type' => 'required|string|in:student,faculty,staff,stakeholder',
            'respondent_groups.*.category' => 'nullable|string',
            'questions' => 'array',
            'questions.*.question_type' => 'sometimes|required|string|in:text,radio',
            'questions.*.question' => 'sometimes|required|string|max:2000',
            'questions.*.description' => 'nullable|string',
            'questions.*.data' => 'nullable|array',
            // 'questions.*.data.*' => 'integer|in:1,2,3,4,5',
            'information_fields' => 'required|array',
            'information_fields.*.label' => 'required|string|max:255',
            'information_fields.*.type' => 'required|string|in:text,textarea,select',
            'information_fields.*.options' => 'nullable|string',
        ];
    }
}
