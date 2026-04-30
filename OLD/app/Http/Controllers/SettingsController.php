<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function toggleLayout(Request $request)
    {
        $tenant = auth()->user()->tenant;
        if (!$tenant) {
            return back()->with('error', 'No tenant associated with this user.');
        }
        $settings = $tenant->settings ?? [];
        
        // Toggle Logic
        $current = $settings['layout_mode'] ?? 'wizard';
        $settings['layout_mode'] = ($current === 'wizard') ? 'enterprise' : 'wizard';
        
        $tenant->settings = $settings;
        $tenant->save();

        return back()->with('success', 'تم تغيير نمط العرض بنجاح');
    }
}
