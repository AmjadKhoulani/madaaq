@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight" style="font-family: 'Cairo', sans-serif;">تعديل مصدر الإنترنت</h1>
            <p class="text-sm text-gray-500 mt-1" style="font-family: 'Cairo', sans-serif;">أنت الآن تعدل بيانات: <span class="font-bold text-indigo-600">{{ $internetSource->name }}</span></p>
        </div>
        <a href="{{ route('network.internet-sources.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors shadow-sm">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            العودة للقائمة
        </a>
    </div>

    <!-- Main Card -->
    <div class="bg-white rounded-lg border border-gray-300 shadow-sm">
        <form action="{{ route('network.internet-sources.update', $internetSource->id) }}" method="POST" class="p-6 md:p-8 space-y-8">
            @csrf
            @method('PUT')

            <!-- Section: Primary Identity -->
            <div class="space-y-4">
                <h2 class="text-base font-bold text-gray-900 border-b border-gray-100 pb-2 flex items-center gap-2" style="font-family: 'Cairo', sans-serif;">
                    <span class="w-1 h-5 bg-indigo-500 rounded-full"></span>
                    الهوية والتعريف
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1">
                        <label class="block text-sm font-bold text-gray-700">اسم المصدر</label>
                        <input type="text" name="name" value="{{ $internetSource->name }}" required 
                            class="block w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors text-sm font-bold text-gray-900 placeholder-gray-400 shadow-sm"
                            placeholder="مثال: الخط الرئيسي 1">
                    </div>
                </div>
            </div>

            <!-- Section: Technical Config -->
            <div class="space-y-4">
                <h2 class="text-base font-bold text-gray-900 border-b border-gray-100 pb-2 flex items-center gap-2" style="font-family: 'Cairo', sans-serif;">
                    <span class="w-1 h-5 bg-indigo-500 rounded-full"></span>
                    الإعدادات التقنية
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Type -->
                    <div class="space-y-1">
                        <label class="block text-sm font-bold text-gray-700">النوع</label>
                        <div class="relative">
                            <select name="type" required class="block w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors text-sm font-medium text-gray-900 shadow-sm appearance-none">
                                <option value="fiber" {{ $internetSource->type == 'fiber' ? 'selected' : '' }}>Fiber Optic (فايبر)</option>
                                <option value="starlink" {{ $internetSource->type == 'starlink' ? 'selected' : '' }}>Starlink (ستارلينك)</option>
                                <option value="microwave" {{ $internetSource->type == 'microwave' ? 'selected' : '' }}>Microwave Link (مايكروويف)</option>
                                <option value="4g" {{ $internetSource->type == '4g' ? 'selected' : '' }}>4G / LTE (شبكة خلوية)</option>
                                <option value="other" {{ $internetSource->type == 'other' ? 'selected' : '' }}>Other (آخر)</option>
                            </select>
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Connection Type -->
                    <div class="space-y-1">
                        <label class="block text-sm font-bold text-gray-700">نوع الوصل (Connection)</label>
                        <div class="relative">
                            <select name="connection_type" class="block w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors text-sm font-medium text-gray-900 shadow-sm appearance-none">
                                <option value="">غير محدد</option>
                                <option value="fiber" {{ $internetSource->connection_type == 'fiber' ? 'selected' : '' }}>فايبر (Fiber)</option>
                                <option value="cable" {{ $internetSource->connection_type == 'cable' ? 'selected' : '' }}>كابل (Cable)</option>
                                <option value="wireless" {{ $internetSource->connection_type == 'wireless' ? 'selected' : '' }}>لاسلكي (Wireless)</option>
                            </select>
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Capacity -->
                    <div class="space-y-1">
                        <label class="block text-sm font-bold text-gray-700">السعة (Capacity)</label>
                        <input type="text" name="capacity" value="{{ $internetSource->capacity }}" 
                            class="block w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors text-sm font-medium text-gray-900 dir-ltr text-right shadow-sm"
                            placeholder="100 Mbps">
                    </div>

                    <!-- Gateway IP -->
                    <div class="space-y-1">
                        <label class="block text-sm font-bold text-gray-700">Gateway IP</label>
                        <input type="text" name="ip_gateway" value="{{ $internetSource->ip_gateway }}" 
                            class="block w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors text-sm font-mono font-medium text-gray-900 dir-ltr shadow-sm"
                            placeholder="192.168.1.1">
                    </div>
                </div>
            </div>

            <!-- Section: Lifecycle -->
            <div class="space-y-4">
                <h2 class="text-base font-bold text-gray-900 border-b border-gray-100 pb-2 flex items-center gap-2" style="font-family: 'Cairo', sans-serif;">
                    <span class="w-1 h-5 bg-indigo-500 rounded-full"></span>
                    الحالة والتشغيل
                </h2>

                <div class="space-y-2">
                    <label class="block text-sm font-bold text-gray-700">الحالة التشغيلية</label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Online Option -->
                        <label class="relative flex items-center p-3 cursor-pointer rounded-lg border transition-all {{ $internetSource->status == 'online' ? 'bg-emerald-50 border-emerald-500 ring-1 ring-emerald-500' : 'bg-white border-gray-300 hover:border-gray-400' }}">
                            <input type="radio" name="status" value="online" class="hidden" {{ $internetSource->status == 'online' ? 'checked' : '' }}>
                            <div class="flex items-center gap-3">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                                <span class="font-bold text-sm text-gray-900" style="font-family: 'Cairo', sans-serif;">متصل (Online)</span>
                            </div>
                        </label>

                        <!-- Offline Option -->
                        <label class="relative flex items-center p-3 cursor-pointer rounded-lg border transition-all {{ $internetSource->status == 'offline' ? 'bg-rose-50 border-rose-500 ring-1 ring-rose-500' : 'bg-white border-gray-300 hover:border-gray-400' }}">
                            <input type="radio" name="status" value="offline" class="hidden" {{ $internetSource->status == 'offline' ? 'checked' : '' }}>
                            <div class="flex items-center gap-3">
                                <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                                <span class="font-bold text-sm text-gray-900" style="font-family: 'Cairo', sans-serif;">منقطع (Offline)</span>
                            </div>
                        </label>

                        <!-- Maintenance Option -->
                        <label class="relative flex items-center p-3 cursor-pointer rounded-lg border transition-all {{ $internetSource->status == 'maintenance' ? 'bg-orange-50 border-orange-500 ring-1 ring-orange-500' : 'bg-white border-gray-300 hover:border-gray-400' }}">
                            <input type="radio" name="status" value="maintenance" class="hidden" {{ $internetSource->status == 'maintenance' ? 'checked' : '' }}>
                            <div class="flex items-center gap-3">
                                <span class="w-2.5 h-2.5 rounded-full bg-orange-500"></span>
                                <span class="font-bold text-sm text-gray-900" style="font-family: 'Cairo', sans-serif;">صيانة (Maintenance)</span>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                <button type="button" @click="window.history.back()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                    إلغاء
                </button>
                <button type="submit" class="px-6 py-2 text-sm font-bold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-sm transition-colors">
                    حفظ التعديلات
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Simple logic to handle radio visual feedback
    document.querySelectorAll('input[name="status"]').forEach(radio => {
        radio.addEventListener('change', function() {
            document.querySelectorAll('input[name="status"]').forEach(r => {
                const label = r.closest('label');
                // Remove active classes
                label.classList.remove('bg-emerald-50', 'border-emerald-500', 'ring-1', 'ring-emerald-500');
                label.classList.remove('bg-rose-50', 'border-rose-500', 'ring-1', 'ring-rose-500');
                label.classList.remove('bg-orange-50', 'border-orange-500', 'ring-1', 'ring-orange-500');
                
                // Add default classes
                label.classList.add('bg-white', 'border-gray-200');
            });
            
            const label = this.closest('label');
            label.classList.remove('bg-white', 'border-gray-200');
            
            if (this.value === 'online') label.classList.add('bg-emerald-50', 'border-emerald-500', 'ring-1', 'ring-emerald-500');
            if (this.value === 'offline') label.classList.add('bg-rose-50', 'border-rose-500', 'ring-1', 'ring-rose-500');
            if (this.value === 'maintenance') label.classList.add('bg-orange-50', 'border-orange-500', 'ring-1', 'ring-orange-500');
        });
    });
</script>
@endsection
