<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseMember extends Model
{
    protected $fillable = [
        'course_id',
        'user_id',
        'roles'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function feedbacks()
    {
        return $this->hasMany(CourseFeedback::class, 'member_id');
    }
}
