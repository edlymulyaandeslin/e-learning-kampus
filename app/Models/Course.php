<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];

    public function lecturer()
    {
        return $this->belongsTo(User::class, 'lecturer_id', 'id');
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'course_students', 'course_id', 'student_id')->withTimestamps();
    }
}
