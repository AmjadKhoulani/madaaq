<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SupportController extends Controller
{
    /**
     * Display the support and contact interface.
     */
    public function index()
    {
        return view('support.contact');
    }

    /**
     * Handle support inquiries (optional placeholder for future extension).
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // Logic for sending email or storing inquiry can be implemented here.
        
        return back()->with('success', 'Your inquiry has been transmitted to MadaaQ Central.');
    }
}
