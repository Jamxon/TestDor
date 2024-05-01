<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'question_id',
        'student_id',
        'answer',
        'answer_id',
        'score'
    ];

    public function Student()
    {
        return $this->belongsTo(User::class,'student_id','loginId');
    }
    public function question()
    {
        return $this->belongsTo(Question::class,'question_id');
    }
    public function quiz()
    {
        return $this->belongsTo(Quiz::class,'quiz_id');
    }
    public function studentanswer()
    {
        return $this->belongsTo(Answer::class,'answer_id');
    }
}
