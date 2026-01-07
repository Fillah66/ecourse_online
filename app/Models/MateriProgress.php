<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MateriProgress extends Model
{
    protected $fillable = [
        'user_id',
        'course_id',
        'course_video_id',
        'is_completed'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
    public function materi()
    {
        return $this->belongsTo(CourseVideo::class, 'course_video_id');
    }
}
