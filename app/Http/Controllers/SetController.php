<?php

namespace App\Http\Controllers;

use App\Models\Set;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Http\Requests\SetRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SetController extends Controller
{
    public function create(SetRequest $request, $course_slug){
        $course = Course::where('slug', $course_slug)->first();
    
        if(!$course){
            return response()->json([
                "status"  =>  "not_found",
                "message" => "Resource not found"
            ],404);
        }
        $order = Set::where('course_id', $course->id)->max('order') + 1;
        
        $set = Set::create([
            'name' => $request->name,
            'course_id' => $course->id,
            'order' => $order
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'Set successfully added',
            'data' => [
                'name' => $set->name,
                'order' =>$set->order,
                'id' => $set->id,
            ]
        ], 201);
    }
    public function delete( $course_slug, $set_id){
        $course = Course::where('slug', $course_slug)->first();
        
        $set = set::where('id', $set_id)->first();
    
        if(!$set || !$course || !$course->id === $set->course_id){
            return response()->json([
                "status"  =>  "not_found",
                "message" => "Resource not found"
            ],404);
        }
        $set->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Set successfully deleted',
            
        ], 201);



    }

}
