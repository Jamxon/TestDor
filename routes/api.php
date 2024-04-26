<?php

use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\MainController;
use App\Http\Controllers\Api\QuestionController;
use App\Http\Controllers\Api\QuizController;
use App\Http\Controllers\Api\SubjectController;
use App\Http\Controllers\Api\UserAuthController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:student')->group(function (){
    Route::get('/student',[\App\Http\Controllers\StudentController::class,'index']);
});

Route::middleware('auth:api')->group(function () {
    Route::get('mains', [MainController::class, 'index']);
    Route::post('quizze/create', [QuizController::class,'create']);
    Route::get('teacher/quizzes', [QuizController::class,'index']);
    Route::get('courses', [CourseController::class, 'index']);
    Route::get('departments', [DepartmentController::class, 'index']);
    Route::get('subjects', [SubjectController::class, 'index']);
    Route::post('teacher/update',[\App\Http\Controllers\Api\UserController::class, 'update']);
    Route::post('teacher/delete',[\App\Http\Controllers\Api\UserController::class, 'delete']);
    Route::get('teachers',[\App\Http\Controllers\Api\UserController::class, 'index']);
    Route::get('quizze/{id}',[QuizController::class, 'show']);
    Route::post('quizze/update/{id}', [QuizController::class, 'update']);
    Route::get('student/quizze',[QuizController::class,'studentQuiz']);
    Route::post('question/create', [QuestionController::class,'create']);
    Route::post('question/delete/{id}', [QuestionController::class, 'destroy']);
    Route::get('questions', [QuestionController::class, 'index']);
    Route::post('student/answer/create',[\App\Http\Controllers\Api\StudentAnswerController::class,'create']);
    Route::post('student/answer/update',[\App\Http\Controllers\Api\StudentAnswerController::class,'update']);
    Route::post('student/answer/show',[\App\Http\Controllers\Api\StudentAnswerController::class,'show']);
    Route::get('/quiz/students/{id}',[\App\Http\Controllers\Api\StudentAnswerController::class,'getStudents']);
});

Route::get('getdata',[UserController::class,'getsubjects']);
Route::get('/generate-pdf/{id}', [\App\Http\Controllers\Api\PDFController::class,'generatePDF']);
Route::post('register',[\App\Http\Controllers\Api\UserController::class, 'create']);
Route::post('login',[UserAuthController::class, 'login']);
