<?php

namespace App\Http\Controllers;

use App\Models\MobileApp;
use Illuminate\Http\Request;

class MobileAppController extends Controller
{
    public function index()
    {
        $app = MobileApp::where('tenant_id', auth()->user()->tenant_id ?? 1)->first();
        return view('mobile-app.index', compact('app'));
    }

    public function create()
    {
        // Check if already has an app
        $existingApp = MobileApp::where('tenant_id', auth()->user()->tenant_id ?? 1)->first();
        
        if ($existingApp) {
            return redirect()->route('mobile-app.index')
                ->with('info', 'لديك طلب موجود مسبقاً');
        }

        return view('mobile-app.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'app_name' => 'required|string|max:255',
            'app_name_en' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'required|image|mimes:png,jpg|max:2048',
            'primary_color' => 'required|string',
            'secondary_color' => 'required|string',
            'contact_email' => 'required|email',
            'contact_phone' => 'required|string',
            'website' => 'nullable|url',
        ]);

        $validated['tenant_id'] = auth()->user()->tenant_id ?? 1;
        $validated['submitted_at'] = now();
        $validated['status'] = 'pending';

        // Handle icon upload
        if ($request->hasFile('icon')) {
            $iconPath = $request->file('icon')->store('app-icons', 'public');
            $validated['icon_path'] = $iconPath;
        }

        MobileApp::create($validated);

        return redirect()->route('mobile-app.index')
            ->with('success', 'تم إرسال طلبك بنجاح! سنتواصل معك قريباً.');
    }

    public function download()
    {
        return view('mobile-app.download');
    }
}
