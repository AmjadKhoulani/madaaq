<?php

namespace App\Http\Controllers\Network;

use App\Http\Controllers\Controller;
use App\Models\DeviceModel;
use Illuminate\Http\Request;

class DeviceModelController extends Controller
{
    public function quickStore(Request $request)
    {
        $validated = $request->validate([
            'manufacturer' => 'required|string|max:255',
            'model_name' => 'required|string|max:255',
            'type' => 'required|string|in:antenna,router,switch,other', // Simplified types
        ]);

        $deviceModel = DeviceModel::create([
            'manufacturer' => $validated['manufacturer'],
            'model_name' => $validated['model_name'],
            'device_type' => $validated['type'], // Mapping to existing column
            'frequency' => '5GHz', // Default
        ]);

        return response()->json([
            'success' => true,
            'device' => [
                'id' => $deviceModel->id,
                'name' => $deviceModel->manufacturer . ' ' . $deviceModel->model_name
            ]
        ]);
    }
}
