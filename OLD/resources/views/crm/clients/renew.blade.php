@extends('layouts.admin')

@section('title', 'تجديد اشتراك | ' . $client->username)

@section('content')
<div class="max-w-4xl mx-auto space-y-10" x-data="{ 
    mode: 'extend', 
    usePackage: true,
    packages: @js($packages),
    selectedPackageId: '',
    duration: 30,
    price: 0,
    
    updateFromPackage() {
        const pkg = this.packages.find(p => p.id == this.selectedPackageId);
        if (pkg) {
            this.duration = pkg.duration_days || 30;
            this.price = pkg.price || 0;
        }
    }
}">
    <!-- Header Protocol -->
    <div class="flex items-center gap-6">
        <a href="{{ route('crm.clients.show', $client) }}" class="p-2 rounded-lg bg-surface-container-low text-primary hover:bg-primary hover:text-white transition-all border border-outline-variant/10 shadow-sm">
            <span class="material-symbols-outlined">arrow_forward</span>
        </a>
        <div>
            <h2 class="text-3xl font-black text-primary tracking-tight">بروتوكول تمديد الصلاحية</h2>
            <p class="text-sm font-bold text-slate-500 mt-1 uppercase tracking-widest font-headline">Subscriber Renewal Engine</p>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-8">
        <!-- Current Intelligence -->
        <div class="col-span-12 lg:col-span-4 space-y-6">
            <div class="bg-surface-container-low p-6 rounded-lg border border-outline-variant/10">
                <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6 font-headline italic border-b border-outline-variant/10 pb-2">Status Intelligence</h4>
                <div class="space-y-6">
                    <div>
                        <span class="text-[10px] font-bold text-slate-400 block mb-1">تاريخ الانتهاء الحالي</span>
                        <span class="text-xl font-manrope font-black text-primary">{{ $client->expires_at ? $client->expires_at->format('Y/m/d') : 'Non-Expiring' }}</span>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-slate-400 block mb-1">الحصة المستخدمة</span>
                        <span class="text-xl font-manrope font-black text-primary">{{ $client->data_limit ? round($client->data_limit / 1024 / 1024 / 1024, 2) . ' GB' : 'Unlimited' }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-primary p-6 rounded-lg text-white shadow-xl">
                 <p class="text-[10px] font-black uppercase tracking-widest opacity-60 mb-2">Target Settlement</p>
                 <div class="flex items-baseline gap-2">
                    <span class="text-4xl font-manrope font-black tracking-tighter" x-text="price"></span>
                    <span class="text-xs font-bold opacity-60">{{ $currency }}</span>
                 </div>
                 <p class="text-[9px] font-medium mt-4 leading-relaxed opacity-70">سيتم توليد فاتورة رسمية في النظام المالي فور تأكيد العملية.</p>
            </div>
        </div>

        <!-- Action Panel -->
        <div class="col-span-12 lg:col-span-8 bg-surface-container-lowest p-8 rounded-lg border border-outline-variant/10 shadow-sm h-fit">
            <form action="{{ route('crm.clients.renew.post', $client) }}" method="POST" class="space-y-10">
                @csrf
                
                <!-- Renewal Mode Selection -->
                <div class="space-y-4">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest font-headline">نوع العملية (Operational Mode)</label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="cursor-pointer group">
                            <input type="radio" name="renew_mode" value="extend" class="sr-only peer" x-model="mode">
                            <div class="p-5 bg-surface-container-low border border-outline-variant/10 rounded-lg transition-all peer-checked:bg-primary/5 peer-checked:border-primary group-hover:bg-slate-200">
                                <span class="block text-sm font-black text-primary uppercase">تجديد تمديد (Extend)</span>
                                <span class="block text-[10px] text-slate-400 font-bold mt-1">إضافة إلى الرصيد المتبقي</span>
                            </div>
                        </label>

                        <label class="cursor-pointer group">
                            <input type="radio" name="renew_mode" value="reset" class="sr-only peer" x-model="mode">
                            <div class="p-5 bg-surface-container-low border border-outline-variant/10 rounded-lg transition-all peer-checked:bg-error/5 peer-checked:border-error group-hover:bg-slate-200">
                                <span class="block text-sm font-black text-error uppercase">تصفير وتجديد (Reset)</span>
                                <span class="block text-[10px] text-slate-400 font-bold mt-1">بدء دورة ببيانات جديدة</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Package Selection -->
                <div class="space-y-4">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest font-headline">باقة التجديد المستهدفة</label>
                    <select x-model="selectedPackageId" @change="updateFromPackage()" class="w-full px-6 py-3.5 bg-surface-container-low border border-outline-variant/10 rounded-lg text-sm font-bold text-primary outline-none focus:border-primary transition-all">
                        <option value="">-- تخصيص يدوي --</option>
                        @foreach($packages as $package)
                            <option value="{{ $package->id }}">{{ $package->name }} ({{ number_format($package->price) }} {{ $currency }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Numerical Attributes -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-6 border-t border-outline-variant/10">
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest font-headline">مدة التجديد (أيام)</label>
                        <input type="number" name="duration_days" x-model="duration" class="w-full px-4 py-3 bg-white border border-outline-variant/20 rounded-lg text-sm font-manrope font-black text-primary outline-none">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest font-headline">البيانات الإضافية (GB)</label>
                        <input type="number" name="data_limit" class="w-full px-4 py-3 bg-white border border-outline-variant/20 rounded-lg text-sm font-manrope font-black text-primary outline-none" placeholder="0">
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest font-headline">المبلغ المطلوب تحصيله ({{ $currency }})</label>
                    <input type="number" step="0.01" name="price" x-model="price" class="w-full px-4 py-3 bg-white border border-outline-variant/20 rounded-lg text-sm font-manrope font-black text-primary outline-none text-xl" required>
                </div>

                <button type="submit" class="w-full py-5 bg-primary text-white font-black text-xs uppercase tracking-[0.2em] rounded-lg shadow-xl shadow-primary/10 hover:scale-[1.01] transition-all active:scale-[0.99]">
                    Confirm Subscription Renewal
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
