<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQuizRequest;
use App\Http\Requests\UpdateQuizRequest;
use App\Http\Resources\QuizIndexResource;
use App\Http\Resources\QuizShowResource;
use App\Models\Answer;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class QuizController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $quizzes = Auth::user()->getQuizes()->with('department', 'course', 'user', 'subject', 'questions')->get();

        $quizzeResource = QuizIndexResource::collection($quizzes);

        return response()->json($quizzeResource);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $model = new Quiz();
        $genId = rand(1000000000, 9999999999);
        $quizzes = Quiz::where('id', "$genId")->first();
        while ($quizzes){
            $genId = rand(1000000000, 9999999999);
            $quizzes = Quiz::where('id', "$genId")->first();
        }
        $model->id = $genId;
        $model->dep_id = $request->dep_id;
        $model->course_id = $request->course_id;
        $model->type = $request->type;
        $model->user_id = $request->user_id;
        $model->subject_id = $request->subject_id;
        $model->start_time = $request->start_time;
        $model->end_time = $request->end_time;
        if ($model->save()){
            return response()->json(['message' => "Success"]);
        }
        return response()->json(['message' => "Failed"]);
    }

    /**
     * Store a newly created resource in storage.
     */

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $quiz = Quiz::where('id', $id)->with('questions.answers')->first();
        $quizResource = new QuizShowResource($quiz);
        return response()->json($quizResource);
    }

    /**
     * Show the form for editing the specified resource.
     */


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $model = Quiz::find($id);
        $model->dep_id = $request->dep_id;
        $model->course_id = $request->course_id;
        $model->type = $request->type;
        $model->user_id = $request->user_id;
        $model->subject_id = $request->subject_id;
        $model->start_time = $request->start_time;
        $model->end_time = $request->end_time;
        if ($model->save()){
            return response()->json(['message' => "Success"]);
        }
        return response()->json(['message' => "Failed"]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quiz $quiz)
    {
        $model = Quiz::find($quiz->id);
        if ($model->delete()){
            return response()->json(['message' => "Success"]);
        }
        return response()->json(['message' => "Failed"]);
    }
}