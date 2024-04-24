<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserDepartment;
use App\Models\UserSubject;
use App\Models\Subject;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function getSubjects(){
        $subjects = Subject::all();
        $departments = Department::all();
        return response()->json(['subjects'=> $subjects,'departments' => $departments]);
    }


    public function create(Request $request)
    {
       $model = new User();
       $model->loginId = $request->loginId;
       $model->name = $request->name;
       $model->phone = $request->phone;
       $model->password = Hash::make($request->password);
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
                foreach ($request->subject as $subject){
                    $userSubject = new UserSubject();
                    $userSubject->user_id = $model->id;
                    $userSubject->subject_id = $subject;
                    $userSubject->save();
                }
                foreach ($request->department as $department){
                    $userDepartment = new UserDepartment();
                    $userDepartment->user_id = $model->id;
                    $userDepartment->department_id = $department;
                    $userDepartment->save();
                }
                return response()->json(['status'=>'success','message'=>'User created successfully', 'teacher' => $model]);
         }
            return response()->json(['status'=>'error','message'=>'User not created']);
    }

    public function update(Request $request)
    {
        $model = User::find($request->id);
        $model->loginId = $request->loginId;
        $model->name = $request->name;
        $model->phone = $request->phone;
        $model->password = Hash::make($request->password);
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
            UserSubject::where('user_id',$model->id)->delete();
            foreach ($request->subject as $subject){
                $userSubject = new UserSubject();
                $userSubject->user_id = $model->id;
                $userSubject->subject_id = $subject;
                $userSubject->save();
            }
            return response()->json(['status'=>'success','message'=>'User updated successfully']);
        }
        return response()->json(['status'=>'error','message'=>'User not updated']);
    }
    public function show()
    {
        return "defrefgfhb";
    }
}
