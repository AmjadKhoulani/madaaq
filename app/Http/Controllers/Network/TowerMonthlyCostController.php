<?php

namespace App\Http\Controllers\Network;

use App\Http\Controllers\Controller;
use App\Models\Tower;
use App\Models\TowerMonthlyCost;
use Illuminate\Http\Request;

class TowerMonthlyCostController extends Controller
{
    public function store(Request $request, Tower $tower)
    {
        $validated = $request->validate([
            'month' => 'required|date_format:Y-m',
            'ampere_bill' => 'nullable|numeric|min:0',
            'ampere_cost' => 'nullable|numeric|min:0', // Added
            'ampere_kwh_consumed' => 'nullable|integer|min:0',
            'diesel_cost' => 'nullable|numeric|min:0',
            'maintenance_cost' => 'nullable|numeric|min:0',
            'other_costs' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        // Check if cost already exists for this month
        $exists = $tower->monthlyCosts()->where('month', $validated['month'])->exists();
        if ($exists) {
            return back()->withErrors(['month' => 'يوجد فاتورة مضافة لهذا الشهر مسبقاً']);
        }

        $tower->monthlyCosts()->create($validated);

        return redirect()->route('network.towers.show', $tower)
            ->with('success', 'تم إضافة التكاليف الشهرية بنجاح');
    }

    public function destroy(Tower $tower, TowerMonthlyCost $cost)
    {
        $cost->delete();
        return redirect()->route('network.towers.show', $tower)
            ->with('success', 'تم حذف التكاليف بنجاح');
    }
}
