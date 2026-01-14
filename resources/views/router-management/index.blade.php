@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">إدارة الراوترات المتصلة</h2>
            <p class="text-gray-500 mt-1">ربط وإدارة أجهزة MikroTik تلقائياً</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        @forelse($routers as $router)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition">
            <!-- Status Badge -->
            <div class="p-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900">{{ $router->name }}</h3>
                            <p class="text-xs text-gray-500">{{ $router->host }}</p>
                        </div>
                    </div>
                    @if($router->is_connected)
                        <span class="flex items-center gap-1 px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">
                            <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                            متصل
                        </span>
                    @else
                        <span class="px-2 py-1 bg-gray-100 text-gray-800 text-xs font-medium rounded-full">غير متصل</span>
                    @endif
                </div>
            </div>

            <!-- Info -->
            <div class="p-4 space-y-3">
                @if($router->vpn_ip)
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">VPN IP:</span>
                    <span class="font-mono font-semibold text-gray-900">{{ $router->vpn_ip }}</span>
                </div>
                @endif

                @if($router->tower)
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">البرج:</span>
                    <span class="font-semibold text-gray-900">{{ $router->tower->name }}</span>
                </div>
                @endif

                @if($router->last_sync)
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">آخر مزامنة:</span>
                    <span class="text-gray-700">{{ $router->last_sync->diffForHumans() }}</span>
                </div>
                @endif
            </div>

            <!-- Actions -->
            <div class="p-4 bg-gray-50 border-t border-gray-200">
                <a href="{{ route('router-management.script', $router) }}" class="block w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-center font-semibold rounded-lg transition">
                    {{ $router->is_connected ? '🔄 تحديث السكريبت' : '🔗 توليد سكريبت الربط' }}
                </a>
            </div>
        </div>
        @empty
        <div class="col-span-full bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/></svg>
            </div>
            <p class="text-gray-500 font-medium mb-3">لا توجد أجهزة</p>
            <a href="{{ route('routers.create') }}" class="text-blue-600 hover:text-blue-700 font-semibold text-sm">أضف جهاز أولاً</a>
        </div>
        @endforelse
    </div>

    <!-- Instructions -->
    <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-xl p-6">
        <h3 class="text-lg font-bold text-blue-900 mb-3">📖 كيفية الربط</h3>
        <div class="space-y-2 text-sm text-blue-800">
            <p class="flex items-start gap-2">
                <span class="font-bold">1.</span>
                <span>اضغط على "توليد سكريبت الربط" للجهاز المطلوب</span>
            </p>
            <p class="flex items-start gap-2">
                <span class="font-bold">2.</span>
                <span>انسخ السكريبت الكامل</span>
            </p>
            <p class="flex items-start gap-2">
                <span class="font-bold">3.</span>
                <span>افتح Terminal على جهاز MikroTik والصق السكريبت</span>
            </p>
            <p class="flex items-start gap-2">
                <span class="font-bold">4.</span>
                <span>سيتم الربط تلقائياً والمزامنة كل 15 ثانية!</span>
            </p>
        </div>
    </div>
</div>
@endsection
