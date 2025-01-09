<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable =[
        'name',
        'description',
        'price',
        'image',
        'teacher_id',
        'category_id'
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function members()
    {
        return $this->hasMany(CourseMember::class, 'course_id');
    }

    public function contents()
    {
        return $this->hasMany(CourseContent::class, 'course_id');
    }

    public function isMember($user)
    {
        return $this->members()->where('user_id', $user->id)->exists();
    }

    public function announcements()
    {
        return $this->hasMany(CourseAnnouncement::class, 'course_id');
    }

    public function category()
    {
        return $this->belongsTo(CourseCategory::class, 'category_id');
    }
}
