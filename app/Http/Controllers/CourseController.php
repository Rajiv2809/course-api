<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProgersUserresource;
use App\Models\Course;
use App\Models\Enrollments;
use Illuminate\Http\Request;
use App\Http\Resources\SetResource;
use App\Http\Requests\CourseRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\CourseResource;
use App\Http\Resources\CourseCollection;
use App\Http\Requests\UpdateCourseRequest;
use App\Models\CompletedLessons;

use function Laravel\Prompts\progress;

class CourseController extends Controller
{
    public function create(CourseRequest $request){
        $course = Course::create([
            'name' => $request->name,
            'description' => $request->description,
            'slug' => $request->slug
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'Course successfully added',
            'data' => $course
        ],201);
    }
    public function update(UpdateCourseRequest $request, $course_slug){
        $course = Course::where('slug' , $course_slug)->first();
        if(!$course){
            return response()->json([
                'status' => 'not_found',
                'message' => 'Resource not found'
        
            ],404);
        }
        if($request->is_published){
            $course->is_published = true;
        }
        $course->update($request->all());
        
        return response()->json([
            'status' => 'success',
            'message' => 'Course successfully updated',
            'data' => $course
        ],200);


    }
    public function delete($course_slug){
        $course = Course::where('slug' , $course_slug)->first();
        if(!$course){
            return response()->json([
                'status' => 'not_found',
                'message' => 'Resource not found'
        
            ],404);
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Course successfully deleted',
        ]);
    }
    public function allCourse(){
        $courses = Course::where('is_published', 1)->get();
        $courseResources = CourseCollection::collection($courses);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Courses retrieved successfully',
            'data' => (object) [
                'courses' => $courseResources
            ]
        ]);
    }
    public function getCourse($course_slug){
        $course = Course::where('slug', $course_slug)->first();
        
        if (!$course) {
            return response()->json([
                'status' => 'not_found',
                'message' => 'Resource not found',
            ], 404);
        }

        $courseDetail = new CourseResource($course);
        
        
        return response()->json([
            'status' => 'success',
            'message' => 'Course details retrieved successfully',
            'data' => $courseDetail
        ]);
    }
    public function progres(){
        $course = Enrollments::where('user_id', Auth::user()->id)->first();
        $completeCourse = CompletedLessons::where('user_id', Auth::user()->id)->get();
        $progres = [];
        $complete = [
            'completed_lessons' => ProgersUserresource::collection($completeCourse)
        ];
        $progres['progress'] = [ 
            $course->course,
            $complete
        ];

        return response()->json([
            "status"=> "success",
            "message"=> "User progress retrieved successfully",
            'data' =>  $progres
        ], 200);
    }
}
