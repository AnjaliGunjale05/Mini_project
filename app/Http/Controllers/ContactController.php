<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMail;


class ContactController extends Controller
{
    public function store(Request $request)
{
    $request->validate([
        'name' => 'required',
        'email' => 'required|email',
        'message' => 'required'
    ]);

    $data = $request->only('name', 'email', 'message');

    // Save to DB
    \App\Models\Contact::create($data);

    // Send Email
    Mail::to('connecttoexplorewithus@gmail.com')->send(new ContactMail($data));

    return back()->with('success', 'Message sent successfully!');
}
}
