<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory;
     protected $table = 'courses';
    protected $fillable = ['name', 'description', 'slug'];

    public function set(): HasMany{
        return $this->hasMany(Set::class);
    }
    public function enrolment()
    {
        return $this->hasMany(Enrollments::class, 'course_id');
    }
}
