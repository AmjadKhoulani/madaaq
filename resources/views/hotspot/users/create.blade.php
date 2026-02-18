@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">إضافة مشترك هوت سبوت</h2>
        <p class="text-gray-500 mt-1">إنشاء مستخدم نقطة وصول جديد</p>
    </div>

    <form action="{{ route('hotspot.users.store') }}" method="POST" class=" space-y-5" x-data="{ mode: 'existing', selectedPhone: '' }">
        @csrf
        
        <!-- Customer Selection -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-4">
            <h3 class="text-lg font-bold text-gray-900 mb-4">بيانات العميل</h3>
            
            <div class="flex gap-4 mb-6">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="mode" value="existing" x-model="mode" class="text-purple-600 focus:ring-purple-500">
                    <span class="font-semibold text-gray-700">عميل حالي</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="mode" value="new" x-model="mode" class="text-purple-600 focus:ring-purple-500">
                    <span class="font-semibold text-gray-700">عميل جديد</span>
                </label>
            </div>

            <!-- Existing Customer -->
            <div x-show="mode === 'existing'">
                <label class="block text-sm font-semibold text-gray-700 mb-2">اختر العميل</label>
                <select name="customer_id" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500" @change="selectedPhone = $event.target.options[$event.target.selectedIndex].dataset.phone || ''">
                    <option value="">-- ابحث عن عميل (الاسم أو الهاتف) --</option>
                    @foreach($customers as $customer)
                    <option value="{{ $customer->id }}" data-phone="{{ $customer->phone }}">{{ $customer->name }} - {{ $customer->phone }}</option>
                    @endforeach
                </select>
                <!-- Read-only Phone Display -->
                <div class="mt-3" x-show="selectedPhone" x-transition>
                     <label class="block text-xs font-semibold text-gray-500 mb-1">رقم الهاتف المسجل:</label>
                    <div class="flex items-center gap-2 text-gray-900 bg-gray-50 px-3 py-2 rounded-lg border border-gray-200 w-fit">
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        <span x-text="selectedPhone" class="font-mono font-medium"></span>
                    </div>
                </div>
            </div>

            <!-- New Customer -->
            <div x-show="mode === 'new'" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">اسم العميل *</label>
                    <input type="text" name="name" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500" placeholder="الاسم الكامل">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">رقم الهاتف *</label>
                    <input type="text" name="phone" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500" placeholder="05xxxxxxxx">
                </div>
            </div>
        </div>

        <!-- Account Info -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-4">
            <h3 class="text-lg font-bold text-gray-900 mb-4">معلومات الحساب (Hotspot)</h3>

            <!-- Server Selection -->
            <div>
                 <label class="block text-sm font-semibold text-gray-700 mb-2">سيرفر التحكم (Core) *</label>
                 <select name="server_id" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                    <option value="">-- اختر السيرفر --</option>
                    @foreach($servers as $server)
                    <option value="{{ $server->id }}">{{ $server->name }} ({{ $server->ip }})</option>
                    @endforeach
                 </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">اسم المستخدم *</label>
                    <input type="text" name="username" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500" placeholder="user123">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">كلمة المرور *</label>
                    <input type="text" name="password" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500" value="{{ Str::random(8) }}">
                </div>
            </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">الباقة</label>
            <select name="package_id" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                <option value="">-- اختر الباقة --</option>
                @foreach(\App\Models\Package::where('type', 'hotspot')->get() as $package)
                    <option value="{{ $package->id }}">{{ $package->name }} ({{ $package->speed_down }}M/{{ $package->speed_up }}M)</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">تاريخ الانتهاء</label>
            <input type="date" name="expires_at" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
        </div>

        </div> <!-- End Account Info -->

        <div class="flex items-center gap-3 pt-4">
            <button type="submit" class="px-6 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg shadow-sm transition">
                حفظ المشترك
            </button>
            <a href="{{ route('hotspot.users.index') }}" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg transition">
                إلغاء
            </a>
        </div>
    </form>
</div>
@endsection
