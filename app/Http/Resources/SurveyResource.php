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
            'status' => $this->status,
            'description' => $this->description,
            'expire_date' => $this->expire_date,
            'user_id' => $this->user_id,
            'respondent_groups' => RespondentGroupResource::collection($this->respondentGroups),
            'questions' => $this->questions->map(function ($question) {
                return [
                    'id' => $question->id,
                    'question_type' => $question->question_type,
                    'question' => $question->question,
                    'description' => $question->description,
                    'data' => json_decode($question->data),  // Decode JSON to array
                ];
            }),
            'information_fields' => InformationFieldResource::collection($this->whenLoaded('informationFields')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
