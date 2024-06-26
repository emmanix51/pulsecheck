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
            'user_id' => $this->user_id,
            'status' => $this->status,
            'description' => $this->description,
            'expire_date' => $this->expire_date,
            'respondent_groups' => $this->whenLoaded('respondentGroups', function () {
                return $this->respondentGroups->map(function ($group) {
                    return [
                        'respondent_type' => $group->respondent_type,
                        'categories' => $group->categories,
                    ];
                });
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'questions' => []
        ];
    }
}
