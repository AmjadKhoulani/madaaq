@extends('layouts.admin')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-bold text-white">تسجيل شركة جديدة</h2>
            <a href="{{ route('admin.tenants.index') }}" class="text-gray-400 hover:text-white transition-colors">عودة للقائمة</a>
        </div>

        <div class="bg-gray-800 rounded-xl border border-gray-700 p-6 shadow-xl">
            <form action="{{ route('admin.tenants.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <!-- Section 1: Tenant Info -->
                <div>
                    <h3 class="text-lg font-medium text-indigo-400 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        بيانات الشركة
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">اسم الشركة</label>
                            <input type="text" name="name" required class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors" placeholder="مثال: النور للاتصالات">
                            @error('name') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">النطاق الخاص (Domain)</label>
                            <div class="relative">
                                <input type="text" name="domain" required class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors dir-ltr text-left" placeholder="vendor.localhost">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">يمكن استخدام دومين فرعي (vendor.localhost) أو دومين كامل.</p>
                            @error('domain') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <hr class="border-gray-700">

                <!-- Section 2: Owner Info -->
                <div>
                    <h3 class="text-lg font-medium text-indigo-400 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        بيانات المدير (المالك)
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">اسم المدير</label>
                            <input type="text" name="owner_name" required class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">البريد الإلكتروني</label>
                            <input type="email" name="email" required class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors text-left dir-ltr">
                            @error('email') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">رقم الهاتف</label>
                            <input type="text" name="phone" class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors text-left dir-ltr">
                            @error('phone') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-1">كلمة المرور</label>
                                <input type="password" name="password" required class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-1">تأكيد كلمة المرور</label>
                                <input type="password" name="password_confirmation" required class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors">
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="border-gray-700">

                <!-- Section 3: Billing & Contact -->
                <div>
                     <h3 class="text-lg font-medium text-indigo-400 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        بيانات الفوترة والتواصل
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">جهة اتصال الدعم (Support Contact)</label>
                            <input type="text" name="support_contact" class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors" placeholder="support@company.com">
                        </div>
                         <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">الرقم الضريبي (Tax Number)</label>
                            <input type="text" name="tax_number" class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors">
                        </div>
                        <div class="md:col-span-2">
                             <label class="block text-sm font-medium text-gray-400 mb-1">عنوان الفوترة (Billing Address)</label>
                             <textarea name="billing_address" rows="2" class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors"></textarea>
                        </div>
                    </div>
                </div>

                <div class="pt-6">
                    <button type="submit" class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold py-3 px-4 rounded-xl shadow-lg transform transition hover:scale-[1.01]">
                        إنشاء الشركة والحساب
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
