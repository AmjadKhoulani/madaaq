@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-end mb-6">
        <div>
            <h2 class="text-3xl font-bold gradient-text">خريطة الشبكة 🗺️</h2>
            <p class="text-gray-500 mt-1">عرض الأبراج والأجهزة والعملاء على الخريطة باحترافية</p>
        </div>
        <div class="hidden md:block">
            <span class="px-3 py-1 bg-indigo-50 text-indigo-600 rounded-full text-sm font-semibold border border-indigo-100">
                التحديث التلقائي نشط
            </span>
        </div>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-sm p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm">الأبراج</p>
                    <p class="text-2xl font-bold mt-1">{{ $towers->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/></svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-sm p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm">الأجهزة</p>
                    <p class="text-2xl font-bold mt-1">{{ $routers->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/></svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-sm p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm">العملاء</p>
                    <p class="text-2xl font-bold mt-1">{{ $clients->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg shadow-sm p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm">التغطية</p>
                    <p class="text-2xl font-bold mt-1">{{ $towers->count() * 5 }} كم²</p>
                </div>
                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Controls -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
        <div class="flex items-center gap-4 flex-wrap" x-data="{ showTowers: true, showRouters: true, showClients: true }">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                <span class="font-semibold text-gray-700">الفلاتر:</span>
            </div>

            <label class="flex items-center gap-2 px-4 py-2 bg-purple-50 border-2 border-purple-200 rounded-lg cursor-pointer transition hover:bg-purple-100" :class="showTowers ? 'border-purple-500' : 'border-purple-200'">
                <input type="checkbox" x-model="showTowers" @change="toggleLayer('towersLayer', showTowers)" class="w-4 h-4 text-purple-600 rounded">
                <svg class="w-5 h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/></svg>
                <span class="text-sm font-medium text-purple-900">الأبراج</span>
                <span class="px-2 py-0.5 bg-purple-200 text-purple-800 text-xs rounded-full font-bold">{{ $towers->count() }}</span>
            </label>

            <label class="flex items-center gap-2 px-4 py-2 bg-blue-50 border-2 border-blue-200 rounded-lg cursor-pointer transition hover:bg-blue-100" :class="showRouters ? 'border-blue-500' : 'border-blue-200'">
                <input type="checkbox" x-model="showRouters" @change="toggleLayer('routersLayer', showRouters)" class="w-4 h-4 text-blue-600 rounded">
                <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/></svg>
                <span class="text-sm font-medium text-blue-900">الأجهزة</span>
                <span class="px-2 py-0.5 bg-blue-200 text-blue-800 text-xs rounded-full font-bold">{{ $routers->count() }}</span>
            </label>

            <label class="flex items-center gap-2 px-4 py-2 bg-green-50 border-2 border-green-200 rounded-lg cursor-pointer transition hover:bg-green-100" :class="showClients ? 'border-green-500' : 'border-green-200'">
                <input type="checkbox" x-model="showClients" @change="toggleLayer('clientsLayer', showClients)" class="w-4 h-4 text-green-600 rounded">
                <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                <span class="text-sm font-medium text-green-900">العملاء</span>
                <span class="px-2 py-0.5 bg-green-200 text-green-800 text-xs rounded-full font-bold">{{ $clients->count() }}</span>
            </label>
        </div>
    </div>

    <!-- Map Container -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div id="map" style="height: 600px; width: 100%;"></div>
    </div>
</div>

<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>
<script src="https://domoritz.github.io/leaflet-locatecontrol/dist/L.Control.Locate.min.js" charset="utf-8"></script>
<script src="https://cdn.jsdelivr.net/npm/leaflet-semicircle@2.0.4/Semicircle.min.js"></script>

<style>
    .gradient-text {
        background: linear-gradient(135deg, #6366f1 0%, #3b82f6 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .custom-cluster-icon {
        background: rgba(99, 102, 241, 0.9);
        border: 2px solid white;
        border-radius: 50%;
        color: white;
        font-weight: bold;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
    }
    .leaflet-control-layers {
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;
        border: none !important;
        padding: 8px;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Street Map
    const streets = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap'
    });
    
    // Satellite Map (Google)
    const satellite = L.tileLayer('http://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', {
        maxZoom: 20,
        subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
    });

    // Initialize Map
    const map = L.map('map', {
        center: [33.5138, 36.2765],
        zoom: 12,
        layers: [streets] // Default
    });

    // Layer Control
    const baseMaps = {
        "خريطة الشوارع": streets,
        "قمر صناعي": satellite
    };
    L.control.layers(baseMaps).addTo(map);

    const routersData = @json($routers);
    const clientsData = @json($clients);
    const towersData = @json($towers);

    // Marker Clusters
    const clusterGroup = L.markerClusterGroup({
        showCoverageOnHover: false,
        spiderfyOnMaxZoom: true,
        disableClusteringAtZoom: 16
    }).addTo(map);

    // Icons
    const siteIcons = {
        tower: L.divIcon({
            className: 'custom-div-icon',
            html: `<div style="background: linear-gradient(135deg, #6366f1 0%, #4338ca 100%); width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 6px rgba(0,0,0,0.3); border: 2px solid white;">
                <svg style="width: 20px; height: 20px; color: white;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0" />
                </svg>
            </div>`,
            iconSize: [40, 40],
            iconAnchor: [20, 20]
        }),
        cabinet: L.divIcon({
            className: 'custom-div-icon',
            html: `<div style="background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%); width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 6px rgba(0,0,0,0.3); border: 2px solid white;">
                <svg style="width: 20px; height: 20px; color: white;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
            </div>`,
            iconSize: [40, 40],
            iconAnchor: [20, 20]
        }),
        office: L.divIcon({
            className: 'custom-div-icon',
            html: `<div style="background: linear-gradient(135deg, #10b981 0%, #065f46 100%); width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 6px rgba(0,0,0,0.3); border: 2px solid white;">
                <svg style="width: 20px; height: 20px; color: white;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>`,
            iconSize: [40, 40],
            iconAnchor: [20, 20]
        })
    };

    function getDeviceColor(type) {
        if (type === 'server') return { main: '#6b7280', dark: '#374151', light: '#9ca3af' };
        if (type === 'access_point') return { main: '#8b5cf6', dark: '#5b21b6', light: '#a78bfa' };
        if (type === 'base_station') return { main: '#ec4899', dark: '#be185d', light: '#f472b6' };
        return { main: '#3b82f6', dark: '#1d4ed8', light: '#60a5fa' }; // Blue
    }

    function createDeviceIcon(type) {
        const c = getDeviceColor(type);
        return L.divIcon({
            className: 'custom-div-icon',
            html: `<div style="background: linear-gradient(135deg, ${c.main} 0%, ${c.dark} 100%); width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 3px 5px rgba(0,0,0,0.2); border: 2px solid white;">
                <svg style="width: 16px; height: 16px; color: white;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/></svg>
            </div>`,
            iconSize: [30, 30],
            iconAnchor: [15, 15]
        });
    }

    const clientIcon = L.divIcon({
        className: 'custom-div-icon',
        html: `<div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 4px rgba(0,0,0,0.2); border: 2px solid white;">
            <svg style="width: 12px; height: 12px; color: white;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
        </div>`,
        iconSize: [24, 24],
        iconAnchor: [12, 12]
    });

    const towerMarkers = [];
    const routerMarkers = [];
    const clientMarkers = [];
    const coverageLayers = L.layerGroup().addTo(map);

    // Add Towers
    towersData.forEach(tower => {
        const lat = parseFloat(tower.lat);
        const lng = parseFloat(tower.lng);
        const type = tower.type || 'tower';
        const icon = siteIcons[type] || siteIcons['tower'];
        let typeLabel = '📡 برج بث (Tower)';
        let headerColor = '#4338ca';

        if (type === 'cabinet') {
            typeLabel = '🗄️ كبينة / نقطة توزيع (POP)';
            headerColor = '#1e40af';
        } else if (type === 'office') {
            typeLabel = '🏢 مكتب / فرع (Office)';
            headerColor = '#065f46';
        }

        const popupContent = `
            <div style="min-width: 220px;">
                <div style="font-size: 10px; font-bold; text-transform: uppercase; color: ${headerColor}; opacity: 0.8; margin-bottom: 2px;">${typeLabel}</div>
                <h3 style="margin: 0 0 8px 0; font-size: 16px; font-weight: bold; color: ${headerColor}; border-bottom: 1px solid #eee; padding-bottom: 4px;">${tower.name}</h3>
                <p style="margin: 4px 0; font-size: 13px; color: #666;">📍 ${tower.location || 'موقع غير محدد'}</p>
                <div style="margin-top: 10px; display: flex; gap: 4px;">
                    <a href="/network/towers/${tower.id}" style="flex: 1; text-align: center; padding: 6px 12px; background: ${headerColor}; color: white; text-decoration: none; border-radius: 6px; font-size: 12px;">إدارة الموقع →</a>
                </div>
            </div>
        `;
        // Add small jitter
        const jitter = () => (Math.random() - 0.5) * 0.0002;
        const marker = L.marker([lat + jitter(), lng + jitter()], { 
            icon: icon,
            zIndexOffset: 1000 
        }).bindPopup(popupContent);
        towerMarkers.push(marker);
        clusterGroup.addLayer(marker);
    });

    // Add Routers with Coverage
    routersData.forEach(router => {
        const lat = parseFloat(router.lat);
        const lng = parseFloat(router.lng);
        if (isNaN(lat) || isNaN(lng)) return;

        const type = router.device_type || 'router';
        const colors = getDeviceColor(type);
        const icon = createDeviceIcon(type);
        
        // Link logic
        let linkUrl = `/routers/${router.id}`; // Default redirect to show/edit
        // If it's a server model, link to server page
        if (router.is_server_model) {
            linkUrl = `/servers/${router.id}`;
        } else {
             // For router, usually edit page is used in dashboard
             linkUrl = `/routers/${router.id}/edit`;
        }

        // Add small jitter to prevent exact overlap
        const jitter = () => (Math.random() - 0.5) * 0.0002;
        const marker = L.marker([lat + jitter(), lng + jitter()], { 
            icon: icon,
            zIndexOffset: 500 
        })
            .bindPopup(`
                <div>
                    <h3 style="margin: 0 0 8px 0; font-weight: bold; color: ${colors.dark};">${router.name}</h3>
                    <p style="margin: 4px 0; font-size: 13px; color: #666;">IP: ${router.ip || router.host || 'N/A'}</p>
                    <a href="${linkUrl}" style="display: inline-block; margin-top: 8px; padding: 4px 10px; background: ${colors.main}; color: white; text-decoration: none; border-radius: 4px; font-size: 12px;">إدارة الجهاز</a>
                </div>
            `);
        routerMarkers.push(marker);
        clusterGroup.addLayer(marker);

        // Draw Coverage
        if (router.coverage_radius > 0) {
            const radiusInMeters = parseFloat(router.coverage_radius) * 1000;
            const azimuth = router.azimuth !== null ? parseFloat(router.azimuth) : null;
            const beamWidth = router.beam_width !== null ? parseFloat(router.beam_width) : 0;

            if (azimuth !== null && beamWidth > 0) {
                // Sector
                const startAngle = azimuth - (beamWidth / 2);
                const stopAngle = azimuth + (beamWidth / 2);
                L.semiCircle([lat, lng], {
                    radius: radiusInMeters,
                    startAngle: startAngle,
                    stopAngle: stopAngle,
                    color: colors.main,
                    weight: 1,
                    fillColor: colors.light,
                    fillOpacity: 0.15
                }).addTo(coverageLayers);
            } else {
                // Full circle
                L.circle([lat, lng], {
                    radius: radiusInMeters,
                    color: colors.main,
                    weight: 1,
                    fillColor: colors.light,
                    fillOpacity: 0.1
                }).addTo(coverageLayers);
            }
        }
    });

    // Add Clients
    clientsData.forEach(client => {
        const lat = parseFloat(client.lat);
        const lng = parseFloat(client.lng);
        if (isNaN(lat) || isNaN(lng)) return;

        const marker = L.marker([lat, lng], { icon: clientIcon })
            .bindPopup(`<div style="text-align:center;"><h4 style="font-weight:bold; color:#059669;">${client.username}</h4><p class="text-xs text-slate-500">${client.type || ''}</p></div>`);
        clientMarkers.push(marker);
        clusterGroup.addLayer(marker);
    });

    // Toggle logic
    window.toggleLayer = function(layerName, show) {
        const markers = {
            'towersLayer': towerMarkers,
            'routersLayer': routerMarkers,
            'clientsLayer': clientMarkers
        }[layerName];

        if (markers) {
            markers.forEach(m => show ? clusterGroup.addLayer(m) : clusterGroup.removeLayer(m));
        }
        
        if (layerName === 'routersLayer') {
            show ? map.addLayer(coverageLayers) : map.removeLayer(coverageLayers);
        }
    };

    // Auto-fit
    const allCoords = [];
    [...towersData, ...routersData, ...clientsData].forEach(item => {
        const lat = parseFloat(item.lat);
        const lng = parseFloat(item.lng);
        if (!isNaN(lat) && !isNaN(lng)) {
            allCoords.push([lat, lng]);
        }
    });

    if (allCoords.length > 0) {
        map.fitBounds(allCoords, { padding: [50, 50] });
    }
});
</script>
@endsection
