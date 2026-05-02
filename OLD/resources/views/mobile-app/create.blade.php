@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center gap-2 text-sm text-gray-500">
        <a href="{{ route('mobile-app.index') }}" class="hover:text-blue-600">تطبيق الموبايل</a>
        <span>›</span>
        <span class="text-gray-900">طلب جديد</span>
    </div>

    <div class="text-center mb-8">
        <h2 class="text-3xl font-bold text-gray-900">اطلب تطبيقك المخصص</h2>
        <p class="text-gray-500 mt-2">املأ النموذج وسنقوم بإنشاء تطبيق موبايل احترافي لعملائك</p>
    </div>

    <form action="{{ route('mobile-app.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6" x-data="{
        primaryColor: '#4F46E5',
        secondaryColor: '#4338ca',
        iconPreview: null
    }">
        @csrf

        <!-- App Identity -->
        <div class="bg-white rounded-xl shadow-sm border border-indigo-100 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <div class="w-1 h-6 bg-indigo-500 rounded-full"></div>
                معلومات التطبيق
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">اسم التطبيق (بالعربي) *</label>
                    <input type="text" name="app_name" required value="{{ old('app_name') }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" placeholder="مثال: شبكة النور">
                    @error('app_name')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">اسم التطبيق (English)</label>
                    <input type="text" name="app_name_en" value="{{ old('app_name_en') }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" placeholder="Ex: Al-Nour Network">
                    @error('app_name_en')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">وصف التطبيق</label>
                <textarea name="description" rows="3" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" placeholder="وصف قصير عن شركتك وخدماتك">{{ old('description') }}</textarea>
            </div>
        </div>

        <!--  App Icon -->
        <div class="bg-white rounded-xl shadow-sm border border-indigo-100 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <div class="w-1 h-6 bg-indigo-500 rounded-full"></div>
                أيقونة التطبيق *
            </h3>
            
            <div class="flex items-start gap-6">
                <div class="flex-shrink-0">
                    <div class="w-32 h-32 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-300 flex items-center justify-center overflow-hidden relative group">
                        <template x-if="iconPreview">
                            <img :src="iconPreview" class="w-full h-full object-cover">
                        </template>
                        <template x-if="!iconPreview">
                            <div class="text-center p-2">
                                <svg class="w-8 h-8 text-gray-400 mx-auto mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span class="text-xs text-gray-400">رفع صورة</span>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="flex-1">
                    <input type="file" name="icon" accept="image/png,image/jpg,image/jpeg" required
                        @change="iconPreview = URL.createObjectURL($event.target.files[0])"
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition cursor-pointer">
                    <p class="text-xs text-gray-500 mt-2">
                        📦 الحجم: 512×512 بكسل | الصيغة: PNG أو JPG | الحجم الأقصى: 2MB
                    </p>
                    @error('icon')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <!-- Colors -->
        <div class="bg-white rounded-xl shadow-sm border border-indigo-100 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <div class="w-1 h-6 bg-indigo-500 rounded-full"></div>
                ألوان العلامة التجارية *
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">اللون الأساسي</label>
                    <div class="flex items-center gap-3">
                        <input type="color" name="primary_color" x-model="primaryColor" class="w-16 h-12 rounded-lg border border-gray-200 cursor-pointer shadow-sm">
                        <input type="text" x-model="primaryColor" class="flex-1 px-4 py-2.5 border border-gray-200 rounded-lg font-mono text-sm focus:ring-2 focus:ring-indigo-500" readonly>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">اللون الثانوي</label>
                    <div class="flex items-center gap-3">
                        <input type="color" name="secondary_color" x-model="secondaryColor" class="w-16 h-12 rounded-lg border border-gray-200 cursor-pointer shadow-sm">
                        <input type="text" x-model="secondaryColor" class="flex-1 px-4 py-2.5 border border-gray-200 rounded-lg font-mono text-sm focus:ring-2 focus:ring-indigo-500" readonly>
                    </div>
                </div>
            </div>

            <div class="mt-4 p-6 rounded-xl shadow-lg transform transition-all duration-500" :style="`background: linear-gradient(135deg, ${primaryColor} 0%, ${secondaryColor} 100%)`">
                <p class="text-white font-bold text-center text-lg shadow-black/20 drop-shadow-md">Smart ISP Mobile App</p>
                <div class="mt-4 bg-white/20 backdrop-blur-sm rounded-lg p-3 text-white/90 text-sm text-center">
                    معاينة حية للمظهر
                </div>
            </div>
        </div>

        <!-- Contact Info -->
        <div class="bg-white rounded-xl shadow-sm border border-indigo-100 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <div class="w-1 h-6 bg-indigo-500 rounded-full"></div>
                معلومات الاتصال *
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">البريد الإلكتروني *</label>
                    <input type="email" name="contact_email" required value="{{ old('contact_email', auth()->user()->email) }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    @error('contact_email')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">رقم الهاتف *</label>
                    <input type="text" name="contact_phone" required value="{{ old('contact_phone') }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    @error('contact_phone')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">الموقع الإلكتروني</label>
                    <input type="url" name="website" value="{{ old('website') }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" placeholder="https://example.com">
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-indigo-50 to-blue-50 rounded-xl border border-indigo-200 p-6 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-500/5 rounded-full blur-2xl transform translate-x-10 -translate-y-10"></div>
            
            <div class="relative flex items-center justify-between">
                <div>
                    <p class="text-sm font-bold text-indigo-800 uppercase tracking-wider">التكلفة</p>
                    <div class="flex items-baseline gap-1 mt-1">
                        <span class="text-2xl font-extrabold text-indigo-900">مجاناً</span>
                        <span class="text-indigo-600 font-medium">/ لمشتركين باقة Pro</span>
                    </div>
                    <p class="text-sm text-indigo-600/80 mt-2 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        شامل الرفع على المتاجر والدعم الفني
                    </p>
                </div>
                <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center shadow-lg shadow-indigo-200">
                    <svg class="w-10 h-10 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>

        <!-- Submit -->
        <div class="flex items-center gap-3 pt-4">
            <button type="submit" class="flex-1 px-6 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-200 transition transform hover:-translate-y-0.5">
                🚀 إرسال الطلب والبدء
            </button>
            <a href="{{ route('mobile-app.index') }}" class="px-6 py-4 bg-gray-50 hover:bg-gray-100 text-gray-700 font-semibold rounded-xl border border-gray-200 transition">
                إلغاء
            </a>
        </div>
    </form>
</div>
@endsection
