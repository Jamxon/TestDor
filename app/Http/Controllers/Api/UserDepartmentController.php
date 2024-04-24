<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserDepartmentRequest;
use App\Http\Requests\UpdateUserDepartmentRequest;
use App\Models\UserDepartment;

class UserDepartmentController extends Controller
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
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserDepartmentRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(UserDepartment $userDepartment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserDepartment $userDepartment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserDepartmentRequest $request, UserDepartment $userDepartment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserDepartment $userDepartment)
    {
        //
    }
}
