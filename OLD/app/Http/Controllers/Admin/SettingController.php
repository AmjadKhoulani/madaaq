<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminSetting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = AdminSetting::all()->pluck('value', 'key');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->except('_token');

        // Handle File Uploads
        if ($request->hasFile('sham_cash_qr')) {
            $path = $request->file('sham_cash_qr')->store('settings', 'public');
            AdminSetting::set('sham_cash_qr', $path);
            unset($data['sham_cash_qr']);
        }

        foreach ($data as $key => $value) {
            AdminSetting::set($key, $value);
        }

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}
