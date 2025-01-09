<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseFeedback extends Model
{
    protected $fillable = [
        'course_id',
        'member_id',
        'feedback',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function member()
    {
        return $this->belongsTo(CourseMember::class, 'member_id');
    }
}
