<?php

namespace App\Http\Controllers\Network;

use App\Http\Controllers\Controller;
use App\Models\WebsiteAnalytic;
use App\Models\BlockedWebsite;
use Illuminate\Http\Request;

class WebsiteController extends Controller
{
    public function analytics(Request $request)
    {
        $period = $request->get('period', '7d');
        
        // Fetch analytics from database (collected via background command)
        $from = match($period) {
            '30d' => now()->subDays(30),
            '90d' => now()->subDays(90),
            default => now()->subDays(7),
        };
        
        $topWebsites = WebsiteAnalytic::where('recorded_date', '>=', $from)
            ->selectRaw('domain, SUM(hits) as total_hits, SUM(bytes) as total_bytes')
            ->groupBy('domain')
            ->orderByDesc('total_hits')
            ->limit(100)
            ->get();

        return \Inertia\Inertia::render('Network/Website/Analytics', [
            'topWebsites' => $topWebsites,
            'period' => $period
        ]);
    }

    public function blocked()
    {
        $blocked = BlockedWebsite::latest()->paginate(50);
        return view('network.website.blocked', compact('blocked'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'domain' => 'required|string|max:255',
            'type' => 'required|in:domain,keyword',
            'reason' => 'nullable|string|max:500',
        ]);

        $blocked = BlockedWebsite::create([
            'tenant_id' => auth()->user()->tenant_id ?? 1,
            'domain' => $validated['domain'],
            'type' => $validated['type'],
            'reason' => $validated['reason'],
            'is_active' => true,
        ]);

        // Sync to Routers
        $routers = \App\Models\Router::where('tenant_id', auth()->user()->tenant_id ?? 1)->get();
        foreach ($routers as $router) {
            try {
                $service = new \App\Services\MikroTikService($router);
                $service->blockWebsite($validated['domain']);
            } catch (\Exception $e) {
                // Log error but continue
                \Illuminate\Support\Facades\Log::error("Failed to sync block to router {$router->name}: " . $e->getMessage());
            }
        }

        return back()->with('success', 'تم حظر الموقع بنجاح ورسالة الأمر للموجهات');
    }

    public function destroy($id)
    {
        $blocked = BlockedWebsite::findOrFail($id);
        
        // Sync to Routers
        $routers = \App\Models\Router::where('tenant_id', auth()->user()->tenant_id ?? 1)->get();
        foreach ($routers as $router) {
            try {
                $service = new \App\Services\MikroTikService($router);
                $service->unblockWebsite($blocked->domain);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Failed to sync unblock to router {$router->name}: " . $e->getMessage());
            }
        }

        $blocked->delete();

        return back()->with('success', 'تم إلغاء الحظر وإزالته من الموجهات');
    }

    public function toggle($id)
    {
        $blocked = BlockedWebsite::findOrFail($id);
        $newState = !$blocked->is_active;
        $blocked->update(['is_active' => $newState]);

        $routers = \App\Models\Router::where('tenant_id', auth()->user()->tenant_id ?? 1)->get();
        foreach ($routers as $router) {
            try {
                $service = new \App\Services\MikroTikService($router);
                if ($newState) {
                    $service->blockWebsite($blocked->domain);
                } else {
                    $service->unblockWebsite($blocked->domain);
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Failed to toggle block on router {$router->name}: " . $e->getMessage());
            }
        }

        return back()->with('success', 'تم تحديث الحالة ومزامنتها');
    }
}
