<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\ContactUs;
use Illuminate\Http\Request;

class ContactUsController extends Controller
{

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'message' => 'nullable|string',
]);
    

        ContactUs::create($validatedData);

        return redirect()->back()->with('success', 'Your message has been sent successfully!');
    }
}
