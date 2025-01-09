<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseAnnouncement extends Model
{
    protected $fillable = [
        'course_id',
        'title',
        'content'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
