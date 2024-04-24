<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
class Student extends Authenticatable
{
    use HasApiTokens,HasFactory;

    protected $fillable = [
        'loginId',
        'name',
        'faculty',
        'passportNumber',
        'course',
    ];

    public function getStudentAnswers()
    {
        return $this->hasMany(StudentAnswer::class,'student_id','loginId');
    }
}
