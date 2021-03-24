<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreAttitude;
use App\Models\AttitudeAssessment;

class AttitudeAssessmentController extends Controller
{
    public function index()
    {
        $attitude = AttitudeAssessment::get();
        return $attitude;
    }

    // public function show()
    // {
    //     $user = $request->user();

    //     $attitude = new AttitudeAssessment();
    //     return $attitude;
    // }

    public function store(StoreAttitude $request)
    {
        $attitude = new Attitude($request->validated());
    }
}
