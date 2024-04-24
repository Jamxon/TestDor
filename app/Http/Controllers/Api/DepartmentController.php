<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DepartmentIndexResource;
use App\Models\Department;
use Illuminate\Support\Facades\Auth;

class DepartmentController extends Controller
{
    public function index()
    {
        $dep = Auth::user()->getUserDepartments()->with('department')->get();

        $depResource = DepartmentIndexResource::collection($dep->pluck('department'));

        return response()->json($depResource, 200);
    }
}
