<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuizShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'department' => $this->department->name_uz ?? '',
            'course' => $this->course->name ?? '',
            'type' => $this->type,
            'user' => $this->user->name ?? '',
            'subject' => $this->subject->name ?? '',
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'questions' => $this->questions,
            'answers' => $this->answers,
        ];
    }
}
