@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">حملة تسويقية عبر WhatsApp</h2>
        <p class="text-gray-500 mt-1">أرسل رسائل جماعية للعملاء</p>
    </div>

    @php
        $isConfigured = (new \App\Services\NotificationService())->isWhatsAppConfigured();
    @endphp

    @if(!$isConfigured)
    <div class="bg-orange-100 border-l-4 border-orange-500 p-4 rounded-lg">
        <div class="flex items-center gap-3">
            <svg class="w-6 h-6 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <div>
                <p class="text-orange-800 font-bold">تنبيه: خدمة الواتساب غير مهيأة</p>
                <p class="text-orange-700 text-sm">يجب ضبط إعدادات Meta API أولاً لتتمكن من إرسال الحملات. <a href="{{ route('settings.index') }}" class="underline font-bold">انتقل للإعدادات</a></p>
            </div>
        </div>
    </div>
    @endif

    <form action="{{ route('crm.campaigns.store') }}" method="POST" class="space-y-6" x-data="{ targetType: 'all', selectedCount: 0 }">
        @csrf
        <input type="hidden" name="channel" value="whatsapp">

        <!-- Grid Layout: 2 columns -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <!-- Left Column -->
            <div class="space-y-6">
                <!-- Target Selection -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 h-full">
                    <div class="flex items-center gap-2 mb-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        <h3 class="text-lg font-bold text-gray-900">اختيار المستهدفين</h3>
                    </div>
                    
                    <div class="space-y-3">
                        <label class="flex items-center gap-3 p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 transition" :class="targetType === 'all' ? 'border-blue-500 bg-blue-50' : ''">
                            <input type="radio" name="target" value="all" x-model="targetType" class="w-4 h-4 text-blue-600">
                            <div class="flex-1">
                                <p class="font-semibold text-gray-900 text-sm">جميع العملاء</p>
                                <p class="text-xs text-gray-500">{{ \App\Models\Client::where('tenant_id', auth()->user()->tenant_id)->whereNotNull('phone')->count() }} عميل</p>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 transition" :class="targetType === 'active' ? 'border-blue-500 bg-blue-50' : ''">
                            <input type="radio" name="target" value="active" x-model="targetType" class="w-4 h-4 text-blue-600">
                            <div class="flex-1">
                                <p class="font-semibold text-gray-900 text-sm">العملاء النشطين</p>
                                <p class="text-xs text-gray-500">{{ \App\Models\Client::where('tenant_id', auth()->user()->tenant_id)->where('status', 'active')->whereNotNull('phone')->count() }} عميل</p>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 transition" :class="targetType === 'expiring' ? 'border-blue-500 bg-blue-50' : ''">
                            <input type="radio" name="target" value="expiring" x-model="targetType" class="w-4 h-4 text-blue-600">
                            <div class="flex-1">
                                <p class="font-semibold text-gray-900 text-sm">قريبي الانتهاء</p>
                                <p class="text-xs text-gray-500">خلال 7 أيام</p>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 transition" :class="targetType === 'inactive' ? 'border-blue-500 bg-blue-50' : ''">
                            <input type="radio" name="target" value="inactive" x-model="targetType" class="w-4 h-4 text-blue-600">
                            <div class="flex-1">
                                <p class="font-semibold text-gray-900 text-sm">غير النشطين</p>
                                <p class="text-xs text-gray-500">لإعادة التفعيل</p>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-6">
                <!-- Message Composer -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center gap-2 mb-4">
                        <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                        <h3 class="text-lg font-bold text-gray-900">نص الرسالة</h3>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">قالب جاهز</label>
                            <select class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" onchange="document.getElementById('message').value = this.value">
                                <option value="">-- مخصص --</option>
                                <option value="مرحباً @{{name}}، نقدم لك عرضاً خاصاً! احصل على خصم 20% عند تجديد اشتراكك اليوم.">عرض خصم 20%</option>
                                <option value="عزيزي @{{name}}، اشتراكك سينتهي قريباً. جدد الآن واحصل على شهر مجاناً!">تذكير تجديد</option>
                                <option value="@{{name}}، باقات جديدة بسرعات أعلى! اطلع عليها الآن.">باقات جديدة</option>
                                <option value="مرحباً @{{name}}، نفتقدك! عد واحصل على 30% خصم.">عودة عميل</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">الرسالة</label>
                            <textarea name="message" id="message" required rows="5" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 resize-none" placeholder="اكتب رسالتك... استخدم @{{name}} لإضافة الاسم"></textarea>
                            <p class="text-xs text-gray-500 mt-1">متغيرات: @{{name}}, @{{username}}, @{{package}}</p>
                        </div>

                        <!-- Preview -->
                        <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-lg p-4">
                            <div class="flex items-center gap-2 mb-2">
                                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                <span class="text-sm font-semibold text-green-900">معاينة</span>
                            </div>
                            <p class="text-sm text-gray-700 whitespace-pre-line" id="preview">اكتب رسالتك...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Full Width: Send Button -->
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl shadow-sm border border-green-200 p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">جاهز للإرسال؟</h3>
                        <p class="text-sm text-gray-600">سيتم إرسال الرسالة فوراً إلى المستهدفين</p>
                    </div>
                </div>
                <button type="submit" {{ !$isConfigured ? 'disabled title=يجب_ضبط_الإعدادات_أولاً' : '' }} :class="!{{ $isConfigured ? 'true' : 'false' }} ? 'opacity-50 cursor-not-allowed' : ''" class="px-8 py-4 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    إرسال الحملة
                </button>
            </div>
        </div>
    </form>
</div>

<script>
document.getElementById('message').addEventListener('input', function() {
    const preview = document.getElementById('preview');
    const text = this.value || 'اكتب رسالتك...';
    preview.textContent = text.replace(/\{\{name\}\}/g, 'أحمد').replace(/\{\{username\}\}/g, 'ahmad123').replace(/\{\{package\}\}/g, 'باقة 100 ميجا');
});
</script>
@endsection
