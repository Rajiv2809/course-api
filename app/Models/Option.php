<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;
    public function lesson(){
        return $this->belongsTo(LessonContent::class);
    }
    protected $fillable = [
        'lesson_content_id', 'option_text'
    ];
}
