@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-8 flex items-center gap-4">
        <a href="{{ route('crm.clients.show', $client) }}" class="p-2 rounded-xl bg-white border border-gray-200 text-gray-500 hover:text-gray-700 hover:bg-gray-50 transition">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" class="transform rotate-180"/></svg>
        </a>
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">تجديد اشتراك</h2>
            <p class="mt-1 text-lg text-gray-500">{{ $client->name }} ({{ $client->username }})</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
        <div class="h-2 bg-gradient-to-r from-green-500 to-teal-500"></div>

        <form action="{{ route('crm.clients.renew.post', $client) }}" method="POST" class="p-8 space-y-8" x-data="{ 
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
            @csrf
            
            <!-- Current Status -->
            <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4">الوضع الحالي</h4>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <span class="text-xs text-gray-500 block">تاريخ الانتهاء</span>
                        <span class="font-mono font-bold text-gray-900">{{ $client->expires_at ? $client->expires_at->format('Y-m-d') : 'غير محدد' }}</span>
                    </div>
                    <div>
                        <span class="text-xs text-gray-500 block">حد البيانات</span>
                        <span class="font-mono font-bold text-gray-900">{{ $client->data_limit ? round($client->data_limit / 1024 / 1024 / 1024, 2) . ' GB' : 'غير محدود' }}</span>
                    </div>
                </div>
            </div>

            <!-- Renewal Mode -->
            <div>
                <label class="block text-sm font-bold text-gray-900 mb-3">طريقة التجديد</label>
                <div class="grid grid-cols-2 gap-4">
                    <label class="relative flex cursor-pointer rounded-xl border p-4 shadow-sm focus:outline-none hover:bg-gray-50 transition peer-checked:border-green-500 peer-checked:ring-1 peer-checked:ring-green-500">
                        <input type="radio" name="renew_mode" value="extend" class="sr-only peer" x-model="mode">
                        <span class="flex flex-col">
                            <span class="block text-sm font-bold text-gray-900 peer-checked:text-green-600">تمديد (Extend)</span>
                            <span class="mt-1 text-xs text-gray-500">إضافة فوق الرصيد الحالي</span>
                        </span>
                        <div class="absolute -inset-px rounded-xl border-2 border-transparent peer-checked:border-green-500 pointer-events-none"></div>
                    </label>

                    <label class="relative flex cursor-pointer rounded-xl border p-4 shadow-sm focus:outline-none hover:bg-gray-50 transition peer-checked:border-red-500 peer-checked:ring-1 peer-checked:ring-red-500">
                        <input type="radio" name="renew_mode" value="reset" class="sr-only peer" x-model="mode">
                        <span class="flex flex-col">
                            <span class="block text-sm font-bold text-gray-900 peer-checked:text-red-600">تصفير وتجديد (Reset)</span>
                            <span class="mt-1 text-xs text-gray-500">تجاهل القديم وبدء دورة جديدة</span>
                        </span>
                        <div class="absolute -inset-px rounded-xl border-2 border-transparent peer-checked:border-red-500 pointer-events-none"></div>
                    </label>
                </div>
            </div>

            <!-- Package Selection -->
            <div class="p-6 bg-blue-50 rounded-xl border border-blue-100">
                <label class="block text-sm font-bold text-blue-900 mb-3">اختر باقة للتجديد (اختياري)</label>
                <select x-model="selectedPackageId" @change="updateFromPackage()" class="block w-full py-3 px-4 border-blue-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 shadow-sm bg-white transition">
                    <option value="">تخصيص يدوي...</option>
                    @foreach($packages as $package)
                        <option value="{{ $package->id }}">{{ $package->name }} ({{ $package->price }} {{ $currency }})</option>
                    @endforeach
                </select>
                <p class="text-xs text-blue-600 mt-2">اختيار باقة سيقوم بتعبئة المدة والسعر تلقائياً.</p>
            </div>

            <!-- Options -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Duration -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">مدة التجديد (يوم)</label>
                    <input type="number" name="duration_days" x-model="duration" class="block w-full py-3 px-4 border-gray-300 rounded-xl focus:ring-green-500 focus:border-green-500 transition shadow-sm bg-gray-50 focus:bg-white" placeholder="30">
                </div>

                <!-- Data -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">البيانات الإضافية (GB)</label>
                    <input type="number" name="data_limit" class="block w-full py-3 px-4 border-gray-300 rounded-xl focus:ring-green-500 focus:border-green-500 transition shadow-sm bg-gray-50 focus:bg-white" placeholder="0">
                </div>

                <!-- Price -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">القيمة المدفوعة ({{ $currency }})</label>
                    <div class="relative">
                        <input type="number" step="0.01" name="price" x-model="price" class="block w-full pr-4 pl-12 py-3 border-gray-300 rounded-xl focus:ring-green-500 focus:border-green-500 transition shadow-sm bg-gray-50 focus:bg-white" placeholder="0.00">
                         <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 font-bold">{{ $currency }}</span>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">سيتم إنشاء فاتورة مدفوعة بهذه القيمة</p>
                </div>
            </div>

            <div class="pt-4 flex justify-end gap-3">
                 <button type="submit" class="px-8 py-3 bg-gradient-to-r from-green-600 to-teal-600 text-white font-bold rounded-xl hover:shadow-lg hover:from-green-700 hover:to-teal-700 transition transform hover:-translate-y-0.5 w-full md:w-auto">
                    تأكيد التجديد
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
