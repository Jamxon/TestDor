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
    public function generatePDF($id, $studentid)
    {
        $quiz = Quiz::find($id);
        $student = User::where(['loginId' => $studentid])->first();
        $data = [];
        $foo = $student->getUserDepartments()->with('department')->get();
        $foo1 = $foo->pluck('department');
            $data['studentAnswers'] = $student->getStudentAnswers()->get()->reverse();
            $data['studentDepartment'] = $foo1[0]->name_uz; ;
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
    public function generateQuizPDF($quizId)
    {
        $quiz = Quiz::find($quizId);
        if (!$quiz) {
            return response()->json(['error' => 'Quiz not found'], 404);
        }

        // Fetch unique users who participated in the quiz
        $studentAnswers = StudentAnswer::where('quiz_id', $quizId)
            ->with(['user.department'])
            ->get()
            ->groupBy('student_id');

        $pdfDataList = [];
        foreach ($studentAnswers as $userId => $answers) {
            $user = $answers->first()->user;
            $department = $user->department;
            $data = [
                'studentAnswers' => $answers->reverse(),
                'studentDepartment' => $department ? $department->name_uz : 'No Department',
                'studentSubject' => $quiz->subject->name,
                'studentId' => $user->loginId,
                'studentGuruh' => $user->course,
                'studentName' => $user->name,
                'quizType' => $quiz->type == 1 ? "yakuniy" : "oraliq",
            ];

            $pdf = PDF::loadView('pdf.document', ['data' => $data]);
            $pdfContent = $pdf->output();
            $pdfBase64 = base64_encode($pdfContent);

            $pdfDataList[] = [
                'studentName' => $user->name,
                'pdfBase64' => $pdfBase64,
            ];
        }

        return response()->json($pdfDataList);
    }

}
