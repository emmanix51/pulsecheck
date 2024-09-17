<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionGroupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'section_number'=>$this->section_number,
            'number' => $this->number,
            'label' => $this->label,
            'question_categories' => $this->question_categories,
            'question_instruction' => $this->question_instruction,
            // Add other fields if needed
        ];
    }
}
