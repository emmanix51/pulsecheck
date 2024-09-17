<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SurveyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'image' => $this->image,
            'slug' => $this->slug,
            'status' => $this->status,
            'is_public' => $this->is_public,
            'instruction' => $this->instruction,
            'description' => $this->description,
            'expire_date' => $this->expire_date,
            'user_id' => $this->user_id,
            'respondent_groups' => RespondentGroupResource::collection($this->respondentGroups),
            'question_sections'=>$this->questionSections->map(function($section){
                return  [
                'section_number' => $section->section_number,
                'section_label' => $section->section_label,
                'section_instruction' => $section->section_instruction,
                'question_groups' => $section->question_groups ? $section->question_groups->map(function ($group) {
                    return [
                        'number' => $group->number,
                        'label' => $group->label,
                        'category_label' => $group->category_label,
                        'question_categories' => $group->question_categories ? json_decode($group->question_categories, true) : null,
                        'question_instruction' => $group->question_instruction,
                        'questions' => QuestionResource::collection($group->questions),
                ];
            }):[],];
        }),
            // 'questions' => $this->questions->map(function ($question) {
            //     return [
            //         'id' => $question->id,
            //         'question_type' => $question->question_type,
            //         'group' => $question->group,
            //         'category' => $question->category,
            //         'question' => $question->question,
            //         'description' => $question->description,
            //         'data' => json_decode($question->data),  // Decode JSON to array
            //     ];
            // }),
            'information_fields' => InformationFieldResource::collection($this->whenLoaded('informationFields')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
