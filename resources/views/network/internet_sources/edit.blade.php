@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-gradient-to-br from-indigo-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg shadow-indigo-200">
                <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </div>
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight" style="font-family: 'Cairo', sans-serif;">تعديل مصدر الإنترنت</h1>
                <p class="text-slate-500 font-medium" style="font-family: 'Cairo', sans-serif;">أنت الآن تعدل بيانات: <span class="text-indigo-600 font-bold">{{ $internetSource->name }}</span></p>
            </div>
        </div>
        <a href="{{ route('network.internet-sources.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-slate-200 text-slate-600 hover:text-slate-900 hover:bg-slate-50 rounded-xl font-bold transition-all shadow-sm active:scale-95" style="font-family: 'Cairo', sans-serif;">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            العودة للقائمة
        </a>
    </div>

    <!-- Main Card -->
    <div class="glass relative overflow-hidden rounded-[2.5rem] shadow-2xl shadow-indigo-100/50 border border-white/60">
        <!-- Decoration side gradient -->
        <div class="absolute top-0 right-0 w-2 h-full bg-gradient-to-b from-indigo-500 via-blue-500 to-purple-600"></div>
        
        <form action="{{ route('network.internet-sources.update', $internetSource->id) }}" method="POST" class="p-8 md:p-12 space-y-10">
            @csrf
            @method('PUT')

            <!-- Section: Primary Identity -->
            <div class="space-y-6">
                <div class="flex items-center gap-2 pb-2 border-b border-slate-100">
                    <span class="w-2 h-6 bg-indigo-500 rounded-full"></span>
                    <h2 class="text-lg font-black text-slate-800" style="font-family: 'Cairo', sans-serif;">الهوية والتعريف</h2>
                </div>
                
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-slate-600 px-1" style="font-family: 'Cairo', sans-serif;">اسم المصدر</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 px-6 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <input type="text" name="name" value="{{ $internetSource->name }}" required 
                            class="block w-full bg-slate-50 border border-slate-200 rounded-2xl py-4 pl-16 pr-4 text-slate-900 font-bold focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:bg-white transition-all duration-300"
                            placeholder="مثال: الخط الرئيسي 1">
                    </div>
                </div>
            </div>

            <!-- Section: Technical Config -->
            <div class="space-y-6">
                <div class="flex items-center gap-2 pb-2 border-b border-slate-100">
                    <span class="w-2 h-6 bg-blue-500 rounded-full"></span>
                    <h2 class="text-lg font-black text-slate-800" style="font-family: 'Cairo', sans-serif;">الإعدادات التقنية</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Type -->
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-slate-600 px-1" style="font-family: 'Cairo', sans-serif;">النوع</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 px-6 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-500 transition-colors">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" /></svg>
                            </div>
                            <select name="type" required class="block w-full bg-slate-50 border border-slate-200 rounded-2xl py-4 pl-16 pr-12 text-slate-900 font-bold focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 focus:bg-white transition-all duration-300 appearance-none">
                                <option value="fiber" {{ $internetSource->type == 'fiber' ? 'selected' : '' }}>Fiber Optic (فايبر)</option>
                                <option value="starlink" {{ $internetSource->type == 'starlink' ? 'selected' : '' }}>Starlink (ستارلينك)</option>
                                <option value="microwave" {{ $internetSource->type == 'microwave' ? 'selected' : '' }}>Microwave Link (مايكروويف)</option>
                                <option value="4g" {{ $internetSource->type == '4g' ? 'selected' : '' }}>4G / LTE (شبكة خلوية)</option>
                                <option value="other" {{ $internetSource->type == 'other' ? 'selected' : '' }}>Other (آخر)</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-6 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/></svg>
                            </div>
                        </div>
                    </div>

                    <!-- Connection Type -->
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-slate-600 px-1" style="font-family: 'Cairo', sans-serif;">نوع الوصل (Connection)</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 px-6 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-500 transition-colors">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /></svg>
                            </div>
                            <select name="connection_type" class="block w-full bg-slate-50 border border-slate-200 rounded-2xl py-4 pl-16 pr-12 text-slate-900 font-bold focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 focus:bg-white transition-all duration-300 appearance-none">
                                <option value="">غير محدد</option>
                                <option value="fiber" {{ $internetSource->connection_type == 'fiber' ? 'selected' : '' }}>فايبر (Fiber)</option>
                                <option value="cable" {{ $internetSource->connection_type == 'cable' ? 'selected' : '' }}>كابل (Cable)</option>
                                <option value="wireless" {{ $internetSource->connection_type == 'wireless' ? 'selected' : '' }}>لاسلكي (Wireless)</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-6 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/></svg>
                            </div>
                        </div>
                    </div>

                    <!-- Capacity -->
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-slate-600 px-1" style="font-family: 'Cairo', sans-serif;">السعة (Capacity)</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 px-6 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                            </div>
                            <input type="text" name="capacity" value="{{ $internetSource->capacity }}" 
                                class="block w-full bg-slate-50 border border-slate-200 rounded-2xl py-4 pl-16 pr-4 text-slate-900 font-mono font-bold focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:bg-white transition-all duration-300 dir-ltr text-right"
                                placeholder="100 Mbps">
                        </div>
                    </div>

                    <!-- Gateway IP -->
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-slate-600 px-1" style="font-family: 'Cairo', sans-serif;">Gateway IP</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 px-6 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" /></svg>
                            </div>
                            <input type="text" name="ip_gateway" value="{{ $internetSource->ip_gateway }}" 
                                class="block w-full bg-slate-50 border border-slate-200 rounded-2xl py-4 pl-16 pr-4 text-slate-900 font-mono font-bold focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:bg-white transition-all duration-300 dir-ltr"
                                placeholder="192.168.1.1">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section: Lifecycle -->
            <div class="space-y-6">
                <div class="flex items-center gap-2 pb-2 border-b border-slate-100">
                    <span class="w-2 h-6 bg-emerald-500 rounded-full"></span>
                    <h2 class="text-lg font-black text-slate-800" style="font-family: 'Cairo', sans-serif;">الحالة والتشغيل</h2>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-bold text-slate-600 px-1" style="font-family: 'Cairo', sans-serif;">الحالة التشغيلية</label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Online Option -->
                        <label class="relative flex items-center p-4 cursor-pointer rounded-2xl border-2 transition-all {{ $internetSource->status == 'online' ? 'bg-emerald-50 border-emerald-500' : 'bg-slate-50 border-slate-100 opacity-60' }}">
                            <input type="radio" name="status" value="online" class="hidden" {{ $internetSource->status == 'online' ? 'checked' : '' }}>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-emerald-500 rounded-lg flex items-center justify-center text-white shadow-lg shadow-emerald-200">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <span class="font-black text-slate-900" style="font-family: 'Cairo', sans-serif;">متصل (Online)</span>
                            </div>
                        </label>

                        <!-- Offline Option -->
                        <label class="relative flex items-center p-4 cursor-pointer rounded-2xl border-2 transition-all {{ $internetSource->status == 'offline' ? 'bg-rose-50 border-rose-500' : 'bg-slate-50 border-slate-100 opacity-60' }}">
                            <input type="radio" name="status" value="offline" class="hidden" {{ $internetSource->status == 'offline' ? 'checked' : '' }}>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-rose-500 rounded-lg flex items-center justify-center text-white shadow-lg shadow-rose-200">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                </div>
                                <span class="font-black text-slate-900" style="font-family: 'Cairo', sans-serif;">منقطع (Offline)</span>
                            </div>
                        </label>

                        <!-- Maintenance Option -->
                        <label class="relative flex items-center p-4 cursor-pointer rounded-2xl border-2 transition-all {{ $internetSource->status == 'maintenance' ? 'bg-orange-50 border-orange-500' : 'bg-slate-50 border-slate-100 opacity-60' }}">
                            <input type="radio" name="status" value="maintenance" class="hidden" {{ $internetSource->status == 'maintenance' ? 'checked' : '' }}>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-orange-500 rounded-lg flex items-center justify-center text-white shadow-lg shadow-orange-200">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </div>
                                <span class="font-black text-slate-900" style="font-family: 'Cairo', sans-serif;">صيانة (Maintenance)</span>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-12 flex flex-col sm:flex-row items-center justify-end gap-4 pt-10 border-t border-slate-100">
                <button type="button" @click="window.history.back()" class="w-full sm:w-auto px-8 py-4 text-slate-500 hover:text-slate-800 font-bold transition-colors" style="font-family: 'Cairo', sans-serif;">إلغاء التعديلات</button>
                <button type="submit" class="w-full sm:w-auto px-10 py-4 bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white rounded-[1.25rem] font-black text-lg shadow-xl shadow-indigo-200 hover:shadow-indigo-300 hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-3 group" style="font-family: 'Cairo', sans-serif;">
                    <span>حفظ التعديلات</span>
                    <svg class="w-6 h-6 group-hover:translate-x-[-4px] transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
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
                label.classList.remove('bg-emerald-50', 'border-emerald-500', 'bg-rose-50', 'border-rose-500', 'bg-orange-50', 'border-orange-500');
                label.classList.add('bg-slate-50', 'border-slate-100', 'opacity-60');
            });
            
            const label = this.closest('label');
            label.classList.remove('bg-slate-50', 'border-slate-100', 'opacity-60');
            
            if (this.value === 'online') label.classList.add('bg-emerald-50', 'border-emerald-500');
            if (this.value === 'offline') label.classList.add('bg-rose-50', 'border-rose-500');
            if (this.value === 'maintenance') label.classList.add('bg-orange-50', 'border-orange-500');
        });
    });
</script>
@endsection

