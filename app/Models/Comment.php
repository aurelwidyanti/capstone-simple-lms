<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'user_id',
        'course_id',
        'comment',
    ];

    public function content()
    {
        return $this->belongsTo(CourseContent::class, 'content_id');
    }

    public function member()
    {
        return $this->belongsTo(CourseMember::class, 'member_id');
    }
}
