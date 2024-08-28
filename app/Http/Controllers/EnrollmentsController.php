<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnrollmentsController extends Controller
{
    public function create($course_slug){
        $course = Course::where('slug', $course_slug)->first();
        if(!$course){
            return response()->json([
                "status" => "not_found",
                "message" => "Resource not found"
            ],404);
        }
        $enrolment =  Enrollments::where('user_id',  Auth::user()->id,)->where('course_id',  $course->id )->first();
        if($enrolment){
            return response()->json([
                "status" => "error",
                "message" => "The user is already registered for this course"
            ],400);
        }
        Enrollments::create([
            'user_id' => Auth::user()->id,
            'course_id' => $course->id
        ]);
        return response()->json([
            "status" => "success",
            "message" => "User registered successful"
        ],400);
    }

    
}
