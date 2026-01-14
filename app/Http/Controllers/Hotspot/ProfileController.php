<?php

namespace App\Http\Controllers\Hotspot;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Package;

class ProfileController extends Controller
{
    public function index()
    {
        $profiles = Package::where('type', 'hotspot')->get();
        return view('hotspot.profiles.index', compact('profiles'));
    }

    public function create()
    {
        return view('hotspot.profiles.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'speed_down' => 'required|integer',
            'speed_up' => 'required|integer',
            'price' => 'required|numeric',
        ]);

        $validated['type'] = 'hotspot';
        Package::create($validated);

        return redirect()->route('hotspot.profiles.index')->with('success', 'Hotspot Profile created successfully.');
    }

    public function destroy(Package $profile)
    {
        $profile->delete();
        return redirect()->route('hotspot.profiles.index')->with('success', 'Profile deleted successfully.');
    }
}
