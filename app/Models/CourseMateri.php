<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class CourseMateri extends Model
{
    protected $fillable = [
        'course_id',
        'title',
        'slug',
        'file',
    ];

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function course(){
        return $this->belongsTo(Course::class, 'course_id');
    }
}
