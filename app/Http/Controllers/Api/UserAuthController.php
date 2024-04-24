<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserSubject;
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
    $data = [
        'loginId' => $request->loginId,
        'password' => $request->password
    ];

    // Foydalanuvchini loginId bo'yicha topish
    $user = User::where('loginId', $data['loginId'])->first();

    if (!$user) {
        // Foydalanuvchi topilmadi
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
}
