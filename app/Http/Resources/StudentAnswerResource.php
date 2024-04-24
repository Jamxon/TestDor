<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentAnswerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'quiz' => $this->quiz,
            'question' => $this->question,
            'question-answer' => $this->question->answers,
            'student' => $this->student,
            'answer' => $this->answer,
            'answer_id' => $this->answer_id,
            'score' => $this->score,
        ];
    }
}
