@extends('layouts.app')

@section('content')
<div class="space-y-6" 
     x-data="{ 
        selectedDevice: null
     }"
     @device-selected.window="selectedDevice = $event.detail">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">خريطة الشبكة التفاعلية</h2>
            <p class="text-gray-500 mt-1">عرض تفاعلي لبنية الشبكة والأجهزة المتصلة</p>
        </div>
    </div>

    <!-- Legend -->
    <div class="glass rounded-xl p-4 shadow-lg border border-white/30 flex gap-6 items-center">
        <span class="text-sm font-semibold text-gray-700">الحالة:</span>
        <div class="flex gap-4">
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                <span class="text-sm text-gray-600">متصل</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                <span class="text-sm text-gray-600">معطل</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 bg-orange-500 rounded-full"></div>
                <span class="text-sm text-gray-600">بطيء</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 bg-gray-400 rounded-full"></div>
                <span class="text-sm text-gray-600">غير محدد</span>
            </div>
        </div>
        
        <span class="text-sm font-semibold text-gray-700 mr-6">الأجهزة:</span>
        <div class="flex gap-4">
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 bg-blue-100 rounded flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path d="M2 5a2 2 0 012-2h12a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V5zm3.293 1.293a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 01-1.414-1.414L7.586 10 5.293 7.707a1 1 0 010-1.414zM11 12a1 1 0 100 2h3a1 1 0 100-2h-3z"/></svg>
                </div>
                <span class="text-sm text-gray-600">سيرفر</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 bg-purple-100 rounded flex items-center justify-center">
                    <svg class="w-4 h-4 text-purple-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7 2a2 2 0 00-2 2v12a2 2 0 002 2h6a2 2 0 002-2V4a2 2 0 00-2-2H7zm3 14a1 1 0 100-2 1 1 0 000 2z"/></svg>
                </div>
                <span class="text-sm text-gray-600">راوتر</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 bg-green-100 rounded flex items-center justify-center">
                    <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a.75.75 0 01.75.75v1.5a.75.75 0 01-1.5 0v-1.5A.75.75 0 0110 2zM10 15a.75.75 0 01.75.75v1.5a.75.75 0 01-1.5 0v-1.5A.75.75 0 0110 15zM10 7a3 3 0 100 6 3 3 0 000-6zM15.657 5.404a.75.75 0 10-1.06-1.06l-1.061 1.06a.75.75 0 001.06 1.06l1.06-1.06zM6.464 14.596a.75.75 0 10-1.06-1.06l-1.06 1.06a.75.75 0 001.06 1.06l1.06-1.06zM18 10a.75.75 0 01-.75.75h-1.5a.75.75 0 010-1.5h1.5A.75.75 0 0118 10zM5 10a.75.75 0 01-.75.75h-1.5a.75.75 0 010-1.5h1.5A.75.75 0 015 10zM14.596 15.657a.75.75 0 001.06-1.06l-1.06-1.061a.75.75 0 10-1.06 1.06l1.06 1.06zM5.404 6.464a.75.75 0 001.06-1.06l-1.06-1.06a.75.75 0 10-1.06 1.06l1.06 1.06z"/></svg>
                </div>
                <span class="text-sm text-gray-600">برج</span>
            </div>
        </div>
    </div>

    <!-- Topology Map Container -->
    <div class="rounded-2xl shadow-xl border border-gray-200 overflow-hidden relative" style="height: 800px; background: #f8fafc;">
        <!-- Light Gradient Overlay -->
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,_transparent_0%,_#f1f5f9_100%)] pointer-events-none"></div>

        <!-- NOC Header Overlay -->
        <div class="absolute top-0 left-0 right-0 p-4 z-10 flex justify-between items-start pointer-events-none">
            <div>
                <h3 class="text-gray-500 font-mono text-xs tracking-widest uppercase opacity-70">Network Operations Center</h3>
                <h1 class="text-gray-800 font-bold text-lg tracking-tight">Live Topology Map</h1>
            </div>
            <div class="flex flex-col items-end">
                <div class="flex items-center gap-2 mb-1">
                    <span class="relative flex h-2 w-2">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                    </span>
                    <span class="text-green-600 font-mono text-xs">SYSTEM ONLINE</span>
                </div>
                <span class="text-gray-400 font-mono text-[10px]">{{ now()->format('H:i:s UTC') }}</span>
            </div>
        </div>

        <!-- Toolbar (White Glass) -->
        <div class="absolute bottom-6 right-6 z-10 flex flex-col gap-2 bg-white/90 backdrop-blur-md rounded-lg shadow-xl border border-gray-200 p-2">
            <button onclick="network.fit()" class="p-2 hover:bg-gray-100 rounded text-gray-500 hover:text-blue-600 transition-colors" title="Fit All">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
            </button>
            <button onclick="network.moveTo({scale: 1.5})" class="p-2 hover:bg-gray-100 rounded text-gray-500 hover:text-blue-600 transition-colors" title="Zoom In">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            </button>
            <button onclick="network.moveTo({scale: 0.5})" class="p-2 hover:bg-gray-100 rounded text-gray-500 hover:text-blue-600 transition-colors" title="Zoom Out">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
            </button>
        </div>

        <div id="mynetwork" class="w-full h-full z-0"></div>
        
        <!-- Loading Indicator -->
        <div id="loading" class="absolute inset-0 flex items-center justify-center bg-slate-50 z-50">
            <div class="flex flex-col items-center">
                <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500 mb-4 shadow-lg"></div>
                <span class="text-blue-600 font-mono text-sm tracking-widest animate-pulse">INITIALIZING VISUALIZATION...</span>
            </div>
        </div>
    </div>

    <!-- Details Modal (Alpine) -->
    <div x-show="selectedDevice" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/20 backdrop-blur-sm" @click.self="selectedDevice = null">
        <div class="bg-white rounded-2xl p-6 max-w-sm w-full mx-4 shadow-2xl border border-gray-100 transform transition-all"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">
            
            <div class="text-center">
                <div class="w-24 h-24 mx-auto rounded-lg bg-gray-50 border border-gray-200 flex items-center justify-center mb-4 p-2">
                    <img :src="selectedDevice?.image" class="max-w-full max-h-full object-contain" alt="Device">
                </div>
                <h3 class="text-xl font-bold text-gray-900" x-text="selectedDevice?.label"></h3>
                <p class="text-sm text-gray-500 mb-6" x-text="selectedDevice?.group === 'server' ? 'سيرفر رئيسي' : (selectedDevice?.group === 'router' ? 'راوتر توزيع' : 'برج تغطية')"></p>
                
                <div class="bg-gray-50 rounded-xl p-4 mb-6 text-right">
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-500 text-sm">الحالة</span>
                        <span class="font-bold text-sm" 
                              :class="selectedDevice?.status === 'online' ? 'text-green-600' : 'text-red-600'" 
                              x-text="selectedDevice?.status === 'online' ? 'متصل ✅' : 'منقطع ❌'"></span>
                    </div>
                </div>

                <button @click="selectedDevice = null" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-semibold shadow-md transition">
                    إغلاق
                </button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/vis-network/9.1.2/dist/dist/vis-network.min.css" integrity="sha512-WgxfT5LWjfszlPHXRmBWHkV2PHnB7QYxuTqpD2n33d3FE6+Q5yQ/+mrqbPY+UdDIDyvUb8UTU1Esn31rIwx+EQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
    #mynetwork { outline: none; }
</style>
@endpush

@push('scripts')
<!-- Switched to unpkg standalone build for better compatibility -->
<script type="text/javascript" src="https://unpkg.com/vis-network/standalone/umd/vis-network.min.js"></script>
<script>
    var network;

    // Failsafe: Force hide loading after 5 seconds
    setTimeout(function() {
        var loader = document.getElementById('loading');
        if(loader) loader.style.display = 'none';
    }, 5000);

    // Fallback Icon Generator (if no image)
    const getFallbackIcon = (type, color) => {
         const svgs = {
            cloud: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="${color}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.5 19c0-1.7-1.3-3-3-3h-1.1c-.8-3.4-3.9-6-7.6-6-4.1 0-7.4 3.4-7.4 7.5s3.3 7.5 7.5 7.5c1.4 0 2.7-.4 3.9-1.1"/><path d="M12 12v4"/><path d="M12 12l2.3 2.3"/><path d="M12 12l-2.3 2.3"/></svg>`,
            tower: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="${color}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L2 22h20L12 2z"/><path d="M12 6l-3.5 7h7L12 6z"/><path d="M12 16a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/></svg>`
        };
        const svg = svgs[type] || svgs.cloud;
        return 'data:image/svg+xml;charset=utf-8,' + encodeURIComponent(svg);
    };

    document.addEventListener('DOMContentLoaded', function() {
        try {
            var nodes = [];
            var edges = [];
            
            // Defines
            // Placeholder images if actual images are missing
            const IMG_ROUTER_DEFAULT = 'https://cdn-icons-png.flaticon.com/512/9637/9637409.png';
            const IMG_TOWER = 'https://cdn-icons-png.flaticon.com/512/2942/2942544.png'; // Tower Icon
            const IMG_SERVER = '/images/devices/mikrotik_ccr1009.png';
            const IMG_CLOUD = 'https://cdn-icons-png.flaticon.com/512/4138/4138124.png'; // Internet

            // 1. Internet Sources
            var internetSources = @json($internetSources) || [];
            if (internetSources.length === 0) {
                 nodes.push({ 
                    id: 'internet', 
                    label: 'Internet', 
                    group: 'cloud', 
                    image: IMG_CLOUD, 
                    shape: 'image', 
                    size: 40
                });
            } else {
                internetSources.forEach(function(source) {
                    nodes.push({ 
                        id: 'is_' + source.id, 
                        label: source.name, 
                        group: 'cloud', 
                        image: IMG_CLOUD,
                        shape: 'image', 
                        size: 40
                    });
                });
            }

            // 2. Servers
            var servers = @json($servers) || [];
            servers.forEach(function(server) {
                nodes.push({ 
                    id: 's_' + server.id, 
                    label: server.name, 
                    group: 'server', 
                    image: IMG_SERVER,
                    shape: 'image',
                    size: 45,
                    font: { color: '#334155', face: 'Rubik', size: 12, strokeWidth: 3, strokeColor: '#ffffff' }
                });
                
                // Edges
                if (internetSources.length > 0) {
                     internetSources.forEach(function(source) {
                        edges.push({ from: 'is_' + source.id, to: 's_' + server.id, dashes: [5, 5], color: { color: '#94a3b8' } });
                     });
                } else {
                     edges.push({ from: 'internet', to: 's_' + server.id, dashes: [5, 5], color: { color: '#ef4444' } });
                }
            });

            // 3. Routers
            var routers = @json($routers) || [];
            routers.forEach(function(router, index) {
                // Use backend image_url or fallback
                let img = router.image_url ? router.image_url : IMG_ROUTER_DEFAULT;
                
                nodes.push({ 
                    id: 'r_' + router.id, 
                    label: router.name, 
                    group: 'router', 
                    image: img,
                    shape: 'image',
                    size: 35,
                    font: { color: '#475569', face: 'Rubik', size: 11, strokeWidth: 3, strokeColor: '#ffffff' }
                });
                
                if (servers.length > 0) {
                    var serverId = servers[index % servers.length].id;
                    edges.push({ from: 's_' + serverId, to: 'r_' + router.id, color: { color: '#cbd5e1' } });
                }
            });

            // 4. Towers
            var towers = @json($towers) || [];
            towers.forEach(function(tower) {
                nodes.push({ 
                    id: 't_' + tower.id, 
                    label: tower.name, 
                    group: 'tower', 
                    image: IMG_TOWER,
                    shape: 'image',
                    size: 30,
                    font: { color: '#64748b', face: 'Rubik', size: 10, strokeWidth: 3, strokeColor: '#ffffff' }
                });

                if (tower.router_id) {
                    edges.push({ from: 'r_' + tower.router_id, to: 't_' + tower.id, color: { color: '#cbd5e1' } });
                } else if (routers.length > 0) {
                    edges.push({ from: 'r_' + routers[0].id, to: 't_' + tower.id, dashes: [2, 2], color: { color: '#94a3b8' } });
                }
            });

            // Config for Light Mode
            var container = document.getElementById('mynetwork');
            var data = { nodes: nodes, edges: edges };
            var options = {
                nodes: {
                    borderWidth: 0,
                    shadow: {
                        enabled: true,
                        color: 'rgba(0,0,0,0.1)',
                        size: 10,
                        x: 2,
                        y: 2
                    }
                },
                edges: {
                    width: 1,
                    shadow: false,
                    smooth: {
                        type: 'cubicBezier',
                        forceDirection: 'vertical',
                        roundness: 0.5
                    },
                    color: {
                        color: '#cbd5e1',
                        highlight: '#3b82f6',
                        opacity: 1.0
                    }
                },
                layout: {
                    hierarchical: {
                        enabled: true,
                        direction: 'UD',
                        sortMethod: 'directed',
                        nodeSpacing: 200,
                        levelSeparation: 200,
                        blockShifting: true,
                        edgeMinimization: true
                    }
                },
                physics: {
                     hierarchicalRepulsion: {
                        centralGravity: 0.0,
                        springLength: 250,
                        springConstant: 0.01,
                        nodeDistance: 250,
                        damping: 0.09
                    },
                    solver: 'hierarchicalRepulsion'
                },
                interaction: {
                    hover: true,
                    tooltipDelay: 100,
                    zoomView: true,
                    dragView: true
                }
            };

            network = new vis.Network(container, data, options);

            // Hide loading
            network.on("stabilizationIterationsDone", function() {
                 document.getElementById('loading').style.display = 'none';
            });
            network.on("afterDrawing", function() {
                 document.getElementById('loading').style.display = 'none';
                 network.off("afterDrawing");
            });

            // Click Events
            network.on("click", function(params) {
                if (params.nodes.length > 0) {
                    var nodeId = params.nodes[0];
                    var node = nodes.find(n => n.id === nodeId);
                    if (node && node.id !== 'internet') {
                        // Safe way to update Alpine state
                        var el = document.querySelector('[x-data]');
                        if (el && el.__x_data_stack__) {
                            // High-level access if possible, otherwise we use standard dispatch
                            window.dispatchEvent(new CustomEvent('device-selected', { detail: node }));
                        } else {
                            // Fallback for older Alpine or custom implementations
                            var root = document.querySelector('[x-data]');
                            if (root && root.__x) {
                                root.__x.$data.selectedDevice = node;
                            }
                        }
                    }
                }
            });

        } catch (error) {
             document.getElementById('loading').innerHTML = '<div class="text-red-500 p-4 font-bold text-center">خطأ في التحميل<br><span class="text-sm font-normal text-gray-500">' + error.message + '</span></div>';
        }
    });
</script>
@endpush
@endsection
