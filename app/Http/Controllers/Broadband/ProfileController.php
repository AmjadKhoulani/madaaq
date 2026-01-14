<?php

namespace App\Http\Controllers\Broadband;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Package;

class ProfileController extends Controller
{
    public function index()
    {
        $profiles = Package::where('type', 'pppoe')->get();
        return view('broadband.profiles.index', compact('profiles'));
    }

    public function create()
    {
        return view('broadband.profiles.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'speed_down' => 'required|integer',
            'speed_up' => 'required|integer',
            'price' => 'required|numeric',
        ]);

        $validated['type'] = 'pppoe';
        Package::create($validated);

        return redirect()->route('broadband.profiles.index')->with('success', 'Profile created successfully.');
    }

    public function edit(Package $profile)
    {
        return view('broadband.profiles.edit', compact('profile'));
    }

    public function update(Request $request, Package $profile)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'speed_down' => 'required|integer',
            'speed_up' => 'required|integer',
            'price' => 'required|numeric',
            'duration_days' => 'nullable|integer',
            'data_limit_mb' => 'nullable|integer',
        ]);

        $profile->update($validated);

        return redirect()->route('broadband.profiles.index')->with('success', 'Profile updated successfully.');
    }

    public function destroy(Package $profile)
    {
        $profile->delete();
        return redirect()->route('broadband.profiles.index')->with('success', 'Profile deleted successfully.');
    }
}
