<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQuestionRequest;
use App\Http\Requests\UpdateQuestionRequest;
use App\Http\Resources\QuestionIndexResource;
use App\Models\Answer;
use App\Models\CloseAnswer;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $questions = auth()->user()->getQuizes()->with('questions.answers')->get();
        $questionResource = QuestionIndexResource::collection($questions);
        return response()->json($questionResource, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $model = new Question();
        $model->quiz_id = $request->quiz_id;
        $model->question = $request->question;
        $model->score = $request->score;
        $model->is_close = $request->is_close;
        if ($model->save()){
            if ($model->is_close == 0){
                foreach ($request->answers as $answer){
                    $answerModel = new Answer();
                    $answerModel->question_id = $model->id;
                    $answerModel->answer = $answer['answer'];
                    $answerModel->is_true = $answer['is_true'];
                    $answerModel->save();
                }
            }
            return response()->json(['message' => 'Success']);
        }
        return response()->json(['message' => $model->getErrors()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreQuestionRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Question $question)
    {
        return $question;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Question $question)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateQuestionRequest $request, Question $question)
    {
        $question->question = $request->question;
        $question->score = $request->score;
        $question->is_close = $request->is_close;
        if ($question->save()){
            if ($question->is_close == 0){
                foreach ($request->answers as $answer){
                    $answerModel = new Answer();
                    $answerModel->question_id = $question->id;
                    $answerModel->answer = $answer['answer'];
                    $answerModel->is_true = $answer['is_true'];
                    $answerModel->save();
                }
            }
            return response()->json(['message' => 'Success']);
        }
        return response()->json(['message' => $question->getErrors()]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $question = Question::findOrFail($id);
        if ($question->delete()){
            return response()->json(['message' => 'Success']);
        }
        return response()->json(['message' => $question->getErrors()]);
    }
}
