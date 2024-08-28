<?php

namespace App\Http\Controllers;

use App\Http\Requests\OptionRequest;
use App\Models\LessonContent;
use App\Models\Option;
use Illuminate\Http\Request;

class OptionController extends Controller
{
     public function check(OptionRequest $request, $lesson_id)
    {
       
        $lessonContent = LessonContent::where('id', $lesson_id)->first();

      
        if (!$lessonContent) {
            return response()->json([
                'status' => 'not_found',
                'message' => 'Lesson content not found'
            ], 404);
        }

        $option = Option::where('id', $request->option_id)->first();

        
        if (!$option) {
            return response()->json([
                'status' => 'not_found',
                'message' => 'Resource not found'
            ], 404);
        }

       
        return response()->json([
            'status' => 'success',
            'message' => 'Check answer success',
            'data' => [
                'question' => $lessonContent->content,
                'user_answer' => $option->option_text,
                'is_correct' => $option->is_correct ? 'correct' : 'false'
            ]
        ], 200);
    }
}
