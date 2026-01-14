<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // 1. Validate Input
        $request->validate([
            'phone' => 'required',
            'password' => 'required',
        ]);

        // 2. Find Client by Phone (Username)
        // Note: Phone is unique per Tenant? Or globally?
        // In this system, username is phone.
        // If phone is not unique globally, we have a problem unless we know the tenant.
        // Assuming Phone is unique for now. If duplicates exist across tenants, login might pick the first one or fail.
        // Ideally, phone should be unique.
        
        $client = Client::where('username', $request->phone)
            ->orWhere('phone', $request->phone)
            ->first();

        if (!$client || !Hash::check($request->password, $client->password)) {
            return response()->json([
                'message' => 'بيانات الدخول غير صحيحة'
            ], 401);
        }

        // 3. Create Token
        $token = $client->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'message' => 'تم تسجيل الدخول بنجاح',
            'token' => $token,
            'user' => [
                'id' => $client->id,
                'name' => $client->name,
                'username' => $client->username,
                'phone' => $client->phone,
                'tenant_id' => $client->tenant_id,
            ]
        ]);
    }

    public function user(Request $request)
    {
        return $request->user();
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'تم تسجيل الخروج']);
    }
}
