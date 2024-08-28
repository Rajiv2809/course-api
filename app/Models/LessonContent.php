<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonContent extends Model
{
    use HasFactory;
    public function lesson(){
        return $this->belongsTo(Lessons::class, 'lesson_id');
    }
    protected $fillable = [
        'lesson_id', 'content'
    ];
    public function options(){
        return $this->hasMany(Option::class, 'lesson_content_id');
    }
    
}
