<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class JopController extends Controller
{
    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email',
        'phone' => 'required|numeric|unique:jobs,phone',
        'title' => 'required|string|max:255',
        'description' => 'required|string',
    ]);

    $job = auth()->user()->jobs()->create($request->all());

    return response()->json([
        'message' => 'Job created successfully',
        'job' => $job,
    ], 201);
}

}
