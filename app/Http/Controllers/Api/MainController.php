<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use DOMDocument;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class MainController extends Controller
{
    public function index()
    {
        return User::all();
    }
    public function login(Request $request)
    {
        $html = file_get_contents("http://www.kiuf.info/HaksaSystem/Cert/JeaHakCertORViewUz.aspx?StudNo=$request->studentID");

        libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        @ $dom->loadHTML($html);

        $h2s = $dom->getElementsByTagName('span');
        $student = [];
        $cleanedData = [];
        $i = 1;
        foreach ($h2s as $h2) {
                $i++;
                $title_text = $h2->textContent;
                $student[] = $title_text;
        }

        $upperPassportNumber = strtoupper($student[13]);
        $studentData['name'] = trim($student[4]);
        $studentData['faculty'] = $this->getTextAfterSlash($student[7]);
        $studentData['passportNumber'] = trim($upperPassportNumber);
        $studentData['studentID'] = $request->studentID;
        $studentData['course'] = $this->getNextNumberAfterSlash($student[22]);

        if (!Student::where('loginId', $studentData['studentID'])->first()){
            $model = new Student();
            $model->loginId = $studentData['studentID'];
            $model->name = $studentData['name'];
            $model->faculty = $studentData['faculty'];
            $model->passportNumber = $studentData['passportNumber'];
            $model->course = $studentData['course'];
            $model->save();
        }

        $user = Student::where('loginId' , $request->stundentID)->first();

        if (Auth::guard('student')->check(['loginId' => $request->studentID, 'passportNumber' => $request->PassportNumber])) {
            $user = Auth::guard('student')->user(); // Autentifikatsiya muvaffaqiyatli bo'lsa foydalanuvchi obyektini olish
            $token = $user->createToken('authToken')->accessToken;
            return response()->json(['token' => $token, 'user' => $user]);
        } else {
            return response()->json(['error' => 'Invalid loginId or password'], 401);
        }


//        if ($request->PassportNumber == $studentData['passportNumber']) {
//            return response()->json($studentData, 200);
//        } else {
//            return response()->json(['message' => 'Invalid Passport Number'], 401);
//        }
//        return view('view', ['student' => $studentData['course']]);

    }

    private function getNextNumberAfterSlash($string)
    {
        $parts = explode('/', $string);

        $lastPart = array_pop($parts);

        return (int) $lastPart;
    }

    private function getTextAfterSlash($string)
    {
        $part = trim($string);
        $parts = explode('/', $part);

        array_shift($parts);

        return implode('/', $parts);
    }
}
