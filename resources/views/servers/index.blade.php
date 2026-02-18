@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">🖥️ إدارة السيرفرات</h2>
            <p class="text-gray-500 mt-1">سيرفر MikroTik الرئيسي لإدارة المستخدمين</p>
        </div>
        @if($servers->count() > 0)
        <a href="{{ route('servers.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-sm transition">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            إضافة سيرفر
        </a>
        @endif
    </div>

    @if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
        <div class="flex items-center gap-3">
            <svg class="w-6 h-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p class="text-green-800 font-semibold">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if(session('info'))
    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
        <div class="flex items-center gap-3">
            <svg class="w-6 h-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p class="text-blue-800 font-semibold">{{ session('info') }}</p>
        </div>
    </div>
    @endif

    @if($servers->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($servers as $server)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col transition hover:shadow-md">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-gray-50 rounded-lg p-2 border border-gray-200 flex items-center justify-center">
                            <img src="/images/devices/mikrotik_ccr1009.png" alt="Server" class="max-w-full max-h-full object-contain"
                                 onerror="this.src='https://cdn-icons-png.flaticon.com/512/9637/9637409.png'">
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 line-clamp-1">{{ $server->name }}</h3>
                            <p class="text-xs text-gray-500 mt-1 font-mono">{{ $server->ip }}:{{ $server->api_port }}</p>
                        </div>
                    </div>
                    {!! $server->status_badge !!}
                </div>

                <div class="space-y-3 mb-6 flex-grow">
                    <div class="flex items-center text-sm text-gray-600">
                        <span class="w-24 text-gray-400">اسم المستخدم:</span>
                        <span class="font-medium">{{ $server->username }}</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <span class="w-24 text-gray-400">الموقع:</span>
                        <span class="font-medium">{{ $server->location ?? '-' }}</span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3 mt-auto pt-4 border-t border-gray-100">
                    <a href="{{ route('servers.show', $server) }}" class="px-4 py-2 bg-blue-50 text-blue-700 hover:bg-blue-100 text-center font-semibold rounded-lg transition text-sm">
                        ⚙️ إدارة
                    </a>
                    <a href="{{ route('servers.edit', $server) }}" class="px-4 py-2 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 text-center font-semibold rounded-lg transition text-sm">
                        ✏️ تعديل
                    </a>
                    <form action="{{ route('servers.destroy', $server) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا السيرفر؟');" class="w-full">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full px-3 py-2 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg text-sm font-medium transition">
                            🗑️ حذف
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
            
            <!-- Add New Card -->
            <a href="{{ route('servers.create') }}" class="border-2 border-dashed border-gray-300 rounded-xl p-6 flex flex-col items-center justify-center text-gray-400 hover:border-blue-500 hover:text-blue-500 hover:bg-blue-50 transition gap-3 min-h-[250px]">
                <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span class="font-bold">إضافة سيرفر جديد</span>
            </a>
        </div>
    @else
        <!-- No Servers State -->
        <!-- No Server -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <div class="w-24 h-24 bg-gray-100 rounded-full mx-auto flex items-center justify-center mb-6">
                <svg class="w-12 h-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/></svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">لم يتم ربط سيرفر بعد</h3>
            <p class="text-gray-600 mb-6">قم بإضافة سيرفر MikroTik الرئيسي الخاص بك لبدء إدارة المستخدمين</p>
            <a href="{{ route('servers.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow-sm transition">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                إضافة سيرفر الآن
            </a>
        </div>
    @endif

</div>

<script>
function testConnection() {
    // Will be implemented
    alert('سيتم تنفيذ هذه الميزة قريباً');
}
</script>
@endsection
