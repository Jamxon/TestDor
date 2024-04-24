<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\StudentAnswerResource;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\Student;
use App\Models\StudentAnswer;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PDFController extends Controller
{
    public function generatePDF($id)
    {
        $quiz = Quiz::find($id);
        $students = Student::where('course',$quiz->course_id)->get();
        $data = [];
        foreach ($students as $student) {
            $data['studentAnswers'] = $student->getStudentAnswers()->get();
            $data['studentDepartment'] = $student->faculty;
            $data['studentSubject'] = $quiz->subject->name;
            $data['studentId'] = $student->loginId;
            $data['studentGuruh'] = $student->course;
            $data['studentName'] = $student->name;
            if ($quiz->type == 1){
                $data['quizType'] = "yakuniy";
            }else{
                $data['quizType'] = "oraliq";
            }
            $pdf = PDF::loadView('pdf.document',['data'=>$data]);
            return  $pdf->download($data['studentName']);
        }
        return $data;

    }
}
