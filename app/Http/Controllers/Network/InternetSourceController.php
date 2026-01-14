<?php

namespace App\Http\Controllers\Network;

use App\Http\Controllers\Controller;
use App\Models\InternetSource;
use Illuminate\Http\Request;

class InternetSourceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sources = InternetSource::latest()->get();
        return view('network.internet_sources.index', compact('sources'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:fiber,microwave,starlink,4g,other',
            'capacity' => 'nullable|string|max:100',
            'status' => 'required|string|in:online,offline,maintenance',
            'ip_gateway' => 'nullable|ipv4',
        ]);

        InternetSource::create($validated);

        return redirect()->back()->with('success', 'تم إضافة مصدر الإنترنت بنجاح');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InternetSource $internetSource)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:fiber,microwave,starlink,4g,other',
            'capacity' => 'nullable|string|max:100',
            'status' => 'required|string|in:online,offline,maintenance',
            'ip_gateway' => 'nullable|ipv4',
            'connection_type' => 'nullable|in:cable,fiber,wireless',
        ]);

        $internetSource->update($validated);

        return redirect()->back()->with('success', 'تم تحديث البيانات بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InternetSource $internetSource)
    {
        $internetSource->delete();
        return redirect()->back()->with('success', 'تم حذف المصدر بنجاح');
    }
}
