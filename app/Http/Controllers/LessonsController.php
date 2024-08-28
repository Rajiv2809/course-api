<?php

namespace App\Http\Controllers;

use App\Models\Set;
use App\Models\Option;
use App\Models\lessons;
use Illuminate\Http\Request;
use App\Models\LessonContent;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LessonsRequest;
use App\Models\CompletedLessons;

class LessonsController extends Controller
{
   
    public function create(LessonsRequest $request)
    {
        // Find the set by ID
        $set = Set::where('id', $request->set_id)->firstOrFail();
    
        // Find the current max order in the set, or default to 0 if no lessons exist
        $order = $set->lessons()->max('order');
        $order = is_null($order) ? 1 : $order + 1;
    
        // Create the lesson with the calculated order
        $lesson = lessons::create([
            'name' => $request->name,
            'set_id' => $request->set_id,
            'order' => $order
        ]);
    
        // Create the related lesson content
        foreach ($request->contents as $contentData) {
            $lessonContent = LessonContent::create([
                'lesson_id' => $lesson->id,
                'type' => $contentData['type'],
                'content' => $contentData['content']
            ]);
    
            // Create options if they exist
            if (isset($contentData['options'])) {
                foreach ($contentData['options'] as $optionData) {
                    Option::create([
                        'lesson_content_id' => $lessonContent->id,
                        'option_text' => $optionData['option_text'],
                        'is_correct' => $optionData['is_correct']
                    ]);
                }
            }
        }
    
        if ($lesson) {
            return response()->json([
                'status' => 'success',
                'message' => 'Lesson successfully added',
                'data' => [
                    'name' => $lesson->name,
                    'id' => $lesson->id,
                    'order' => 1
                ]
            ], 201);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Lesson creation failed'
            ], 500);
        }
    }
    public function delete($lesson_id){
        $lesson = lessons::where('id', $lesson_id)->first();
        if(!$lesson){
            return response()->json([
                'status' => 'not_found',
                'message' => 'Resource Not found'
            ], 404);
        }
        $lesson->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Lesson successfully deleted'
        ], 200);
    }
    public function completed($lesson_id){
        $user = Auth::user();
        $lesson = lessons::where('id', $lesson_id)->first();
        if(!$lesson){
            return response()->json([
                'status' => 'not_found',
                'message' => 'Resource not found'
            ], 404);
        }
        CompletedLessons::create([
            'user_id' => $user->id,
            'lesson_id' => $lesson->id
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'Lesson successfully completed'
        ], 200);
        

    }

    
}
