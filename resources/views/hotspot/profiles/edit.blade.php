@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('hotspot.profiles.index') }}" class="p-2 rounded-lg bg-white border border-gray-200 text-gray-500 hover:text-gray-700 transition">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-gray-900">تعديل باقة هوت سبوت</h2>
            <p class="text-gray-500 mt-1">تعديل بيانات الباقة: {{ $profile->name }}</p>
        </div>
    </div>

    <form action="{{ route('hotspot.profiles.update', $profile) }}" method="POST" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-5">
        @csrf
        @method('PUT')
        
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">اسم الباقة</label>
            <input type="text" name="name" value="{{ old('name', $profile->name) }}" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition" placeholder="مثال: باقة 50 ميجا">
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">سرعة التحميل (Mbps)</label>
                <input type="number" name="speed_down" value="{{ old('speed_down', $profile->speed_down) }}" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition" placeholder="50">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">سرعة الرفع (Mbps)</label>
                <input type="number" name="speed_up" value="{{ old('speed_up', $profile->speed_up) }}" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition" placeholder="25">
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">تحديد السيرفرات/الرواتر المستهدفة</label>
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Routers -->
                <div>
                    <h4 class="text-xs font-bold text-gray-500 uppercase mb-2">الرواتر (Routers)</h4>
                    <div class="space-y-2">
                        @forelse($routers as $router)
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="targets[]" value="router_{{ $router->id }}" 
                                    {{ $profile->routers->contains($router->id) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                                <span class="text-sm text-gray-700">{{ $router->name }}</span>
                            </label>
                        @empty
                            <p class="text-xs text-gray-400">لا يوجد رواتر</p>
                        @endforelse
                    </div>
                </div>

                <!-- Servers -->
                <div>
                    <h4 class="text-xs font-bold text-gray-500 uppercase mb-2">السيرفرات (Servers)</h4>
                    <div class="space-y-2">
                        @forelse($servers as $server)
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="targets[]" value="server_{{ $server->id }}" 
                                    {{ $profile->mikrotikServers->contains($server->id) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                                <span class="text-sm text-gray-700">{{ $server->name }}</span>
                            </label>
                        @empty
                            <p class="text-xs text-gray-400">لا يوجد سيرفرات</p>
                        @endforelse
                    </div>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-1">تعديل الأهداف سيؤدي لإعادة المزامنة.</p>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">السعر</label>
            <div class="relative">
                <input type="number" name="price" step="0.01" value="{{ old('price', $profile->price) }}" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition" placeholder="75.00">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">{{ $currency }}</span>
            </div>
        </div>

        <div class="flex items-center gap-3 pt-4">
            <button type="submit" class="px-6 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg shadow-sm transition">
                تحديث الباقة
            </button>
            <a href="{{ route('hotspot.profiles.index') }}" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg transition">
                إلغاء
            </a>
        </div>
    </form>
</div>
@endsection
