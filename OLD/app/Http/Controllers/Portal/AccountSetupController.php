<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class AccountSetupController extends Controller
{
    // Step 1: Request Setup (Enter Phone)
    public function showRequestForm()
    {
        return view('portal.setup.request');
    }

    // Step 2: Check Phone
    public function checkPhone(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|exists:clients,username',
        ], [
            'phone.exists' => 'رقم الهاتف هذا غير مسجل لدينا.'
        ]);

        $client = Client::where('username', $request->phone)->firstOrFail();

        // Generate a secure signed URL for the next step to prevent enumeration
        // If client has email, we can skip to sending link directly or ask to confirm?
        // User flow: "Enter Email if none". 
        
        if ($client->email) {
            // Already has email, send reset link directly
            // We can reuse the sendResetLink logic or just redirect with status
            // For now, let's treat it same flow: confirm email or just send
            // Let's redirect to email form but pre-filled or just say "We sent link to your email"
            // To simplify: Always go to "Email Step". If email exists, show it masked and Button "Send Link".
            // If no email, show Input "Enter Email".
        }

        // Generate signed route for Email Step
        $url = URL::temporarySignedRoute(
            'portal.setup.email', 
            now()->addMinutes(30), 
            ['tenant_domain' => $request->getHost(), 'token' => encrypt($client->id)]
        );

        return redirect($url);
    }

    // Step 3: Show Email Form
    public function showEmailForm(Request $request, $tenant, $token)
    {
        if (! $request->hasValidSignature()) {
            abort(403, 'الرابط غير صالح أو منتهي الصلاحية');
        }

        try {
            $clientId = decrypt($token);
            $client = Client::findOrFail($clientId);
        } catch (\Exception $e) {
            abort(403);
        }

        return view('portal.setup.email', compact('client', 'token'));
    }

    // Step 4: Send Reset Link (and save email if needed)
    public function sendResetLink(Request $request)
    {
        // manually validate signature from referer or passed param? 
        // Better: Pass the signed token again in the form and validate it.
        $clientId = decrypt($request->token);
        $client = Client::findOrFail($clientId);

        $request->validate([
            'email' => 'required|email|unique:clients,email,'.$client->id,
        ]);

        // Update email if different (or empty)
        if ($client->email !== $request->email) {
            $client->email = $request->email;
            $client->save();
        }

        // Generate Reset Token (Custom or Laravel's)
        // Let's use a simple custom Signed URL for reset form since we are handling auth manually
        $resetUrl = URL::temporarySignedRoute(
            'portal.setup.reset',
            now()->addHours(24),
            ['tenant_domain' => $request->getHost(), 'token' => encrypt($client->id)] // Using encrypt(id) as token for simplicity
        );

        // Send Email (Mocked for now, or use Mail notification)
        // In real app: Mail::to($client->email)->send(new ResetPasswordMail($resetUrl));
        
        // For Verification/Demo purposes, we will Log it and maybe flash it if dev mode?
        // Or just redirect to a page saying "Check your email".
        // Use NotificationService to send email if implemented, otherwise just log.
        \Log::info("Password Reset Link for {$client->username}: {$resetUrl}");

        return view('portal.setup.sent', compact('client'));
    }

    // Step 5: Show Reset Form
    public function showResetForm(Request $request, $tenant, $token)
    {
        if (! $request->hasValidSignature()) {
            abort(403, 'الرابط غير صالح أو منتهي الصلاحية');
        }

        return view('portal.setup.reset', compact('token'));
    }

    // Step 6: Perform Reset
    public function reset(Request $request)
    {
        // decrypt token to get client
        try {
            $clientId = decrypt($request->token);
            $client = Client::findOrFail($clientId);
        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ غير متوقع');
        }

        $request->validate([
            'password' => 'required|confirmed|min:6',
        ]);

        $client->password = Hash::make($request->password);
        $client->save();

        // Auto Login
        auth('client')->login($client);

        return redirect()->route('portal.dashboard');
    }
}
