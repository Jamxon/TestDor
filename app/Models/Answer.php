<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'answer',
        'is_true',
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function studentanswer()
    {
        return $this->hasMany(StudentAnswer::class);
    }
}
