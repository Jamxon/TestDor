<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\User;
use App\Models\UserDepartment;
use App\Models\UserSubject;
use DOMDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UserAuthController extends Controller
{
    public function register(Request $request)
    {
        $user = new User();
        $user->loginId = $request->loginId;
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->password = Hash::make($request->password);
        $user->save();

        $token = $user->createToken('authToken')->accessToken;

        if (isset($request->subject)){
            foreach ($request->subjects as $subject){
                $user_subject = new UserSubject();
                $user_subject->user_id = $user->id;
                $user_subject->subject = $subject;
                $user_subject->save();
            }
        }

        if ($user){
            return response()->json(['message' => "Success"]);
        }else{
            return response()->json([$user->getErrors()]);
        }
    }
    public function login(Request $request)
    {

        if (strlen($request->loginId) == 10){
            $html = file_get_contents("http://www.kiuf.info/HaksaSystem/Cert/JeaHakCertORViewUz.aspx?StudNo=$request->loginId");

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
            $studentData['studentID'] = $request->loginId;
            $studentData['course'] = $this->getNextNumberAfterSlash($student[22]);

            if (!User::where('loginId', $request->loginId)->first()){
                $model = new User();
                $model->loginId = $studentData['studentID'];
                $model->name = $studentData['name'];
                $model->phone = $request->phone ?? null;
                $model->password = Hash::make($studentData['passportNumber']);
                $model->course = $studentData['course'];
                //image file upload
                if($request->hasFile('image')){
                    $file = $request->file('image');
                    $extension = $file->getClientOriginalExtension();
                    $filename = time().'.'.$extension;
                    $file->move('uploads/user/',$filename);
                    $model->image = $filename;
                }else{
                    $model->image = 'default.jpg';
                }
                if ($model->save()){
                    $userDepartment = Department::where('name_uz', $studentData['faculty'])->first();
                    $department = new UserDepartment();
                    $department->department_id = $userDepartment->id;
                    $department->user_id = $model->id;
                    $department->save();
                }
            }

        }

    $data = [
        'loginId' => $request->loginId,
        'password' => $request->password
    ];

    $user = User::where('loginId', $data['loginId'])->first();

    if (!$user) {
        return response()->json(['error' => 'Invalid loginId or password'], 401);
    }

    if (Auth::attempt(['loginId' => $data['loginId'], 'password' => $data['password']])) {
        $token = $user->createToken('authToken')->accessToken;
        return response()->json(['token' => $token, 'user' => auth()->user()]);
    } else {
        // Parol noto'g'ri
        return response()->json(['error' => 'Invalid loginId or password'], 401);
    }
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
