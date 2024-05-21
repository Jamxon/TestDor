<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'loginId',
        'name',
        'phone',
        'password',
        'course'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
    ];

    public function getUserSubjects(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(UserSubject::class, 'user_id');
    }

    public function getQuizes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Quiz::class);
    }


    public function getUserDepartments(): HasMany
    {
        return $this->hasMany(UserDepartment::class, 'user_id');
    }
    public function getQuestions()
    {
        return $this->belongsTo(Question::class);
    }
    public function getStudentAnswers()
    {
        return $this->hasMany(StudentAnswer::class,'student_id','loginId');
    }
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
