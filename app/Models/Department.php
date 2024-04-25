<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name_uz',
        'name_ru',
    ];

    public function userDepartments()
    {
        return $this->hasMany(UserDepartment::class, 'department_id');
    }
}
