<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStudentAnswerRequest;
use App\Http\Requests\UpdateStudentAnswerRequest;
use App\Http\Resources\StudentAnswerResource;
use App\Models\Answer;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\Student;
use App\Models\StudentAnswer;
use Illuminate\Http\Request;

class StudentAnswerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $studentAnswer = new StudentAnswer();
        $studentAnswer->student_id = $request->student_id;
        $studentAnswer->quiz_id = $request->quiz_id;
        $studentAnswer->question_id = $request->question_id;
        $type = Question::findOrFail($request->question_id);
        if ($type->is_close == 1){
            $studentAnswer->answer = $request->answer;
            $studentAnswer->answer_id = null;
            $studentAnswer->score = null;
        }else{
            $studentAnswer->answer = null;
            $studentAnswer->answer_id = $request->answer_id;
            $answer = Answer::find($request->answer_id);
            if ($answer->is_true == 1){
                $studentAnswer->score = $type->score;
            }else{
                $studentAnswer->score = 0;
            }
        }
        if ($studentAnswer->save()){
            return response()->json([
                'status' => 'success',
                'message' => 'Answer submitted successfully'
            ]);
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to submit answer'
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStudentAnswerRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $studentAnswers = StudentAnswer::where('quiz_id',$request->quiz_id)
        ->where('student_id',$request->student_id)->get();
        $studentAnswersResource = StudentAnswerResource::collection($studentAnswers);
        return response()->json($studentAnswersResource);
    }

    public function getStudents($id)
    {
        $quiz = Quiz::find($id);
        $students = Student::where('course',$quiz->course_id)->get();
        return $students;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StudentAnswer $studentAnswer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $studentId = $request->student_id;
        $questionId = $request->question_id;
        $score = $request->score;

        $studentAnswer = StudentAnswer::where('student_id', $studentId)
            ->where('question_id', $questionId)
            ->first();

        if ($studentAnswer) {
            $studentAnswer->score = $score;
            $studentAnswer->save();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StudentAnswer $studentAnswer)
    {
        //
    }
}
