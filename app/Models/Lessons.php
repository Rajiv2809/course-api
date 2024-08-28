<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lessons extends Model
{
    use HasFactory;
    public function set(){
        return $this->belongsTo(set::class);
    }
    protected $fillable = [
        'name', 'set_id'
    ];
    public function contents(){
        return $this->hasMany(LessonContent::class, 'lesson_id');
    }
    public function lessonComplete()
    {
        return $this->hasMany(CompletedLessons::class, 'lesson_id');
    }

}
