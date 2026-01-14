@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('crm.clients.index') }}" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $client->username }}</h2>
                <p class="text-gray-500">{{ $client->name ?? 'معلومات العميل' }}</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('crm.clients.renew', $client) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-teal-600 text-white rounded-lg font-semibold text-xs hover:from-green-700 hover:to-teal-700 transition shadow-sm">
                <svg class="w-4 h-4 ml-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                تجديد الاشتراك
            </a>
            <form action="{{ route('crm.clients.toggle-status', $client) }}" method="POST" class="inline-block">
                @csrf
                @method('PATCH')
                @if($client->status === 'active')
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-50 border border-red-200 rounded-lg font-semibold text-xs text-red-700 hover:bg-red-100 transition shadow-sm" onclick="return confirm('هل أنت متأكد من تعطيل هذا الحساب؟ سيتم فصل الإنترنت عنه فوراً.')">
                        <svg class="w-4 h-4 ml-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                        </svg>
                        تعطيل الحساب
                    </button>
                @else
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-50 border border-green-200 rounded-lg font-semibold text-xs text-green-700 hover:bg-green-100 transition shadow-sm">
                        <svg class="w-4 h-4 ml-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        تفعيل الحساب
                    </button>
                @endif
            </form>

            <a href="{{ route('crm.clients.edit', $client) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-semibold text-xs text-gray-700 hover:bg-gray-50 transition shadow-sm">
                <svg class="w-4 h-4 ml-1.5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                تعديل
            </a>
            @if($client->status === 'active')
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                    <span class="w-2 h-2 bg-green-500 rounded-full ml-2"></span>
                    نشط
                </span>
            @else
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                    <span class="w-2 h-2 bg-gray-500 rounded-full ml-2"></span>
                    غير نشط
                </span>
            @endif
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">إجمالي الفواتير</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total_invoices'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">المدفوعات</p>
                    <p class="text-2xl font-bold text-green-600 mt-1">{{ number_format($stats['total_paid'], 2) }} {{ $currency }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">المعلق</p>
                    <p class="text-2xl font-bold text-orange-600 mt-1">{{ number_format($stats['pending_amount'], 2) }} {{ $currency }}</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">تاريخ الانتهاء</p>
                    <p class="text-lg font-bold text-gray-900 mt-1">{{ $client->expires_at ? $client->expires_at->format('Y-m-d') : 'غير محدد' }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Client Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">معلومات العميل</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">اسم المستخدم</p>
                        <p class="font-semibold text-gray-900 mt-1">{{ $client->username }}</p>
                    </div>
                    @if($client->name)
                    <div>
                        <p class="text-sm text-gray-500">الاسم الكامل</p>
                        <p class="font-semibold text-gray-900 mt-1">{{ $client->name }}</p>
                    </div>
                    @endif
                    @if($client->email)
                    <div>
                        <p class="text-sm text-gray-500">البريد الإلكتروني</p>
                        <p class="font-semibold text-gray-900 mt-1">{{ $client->email }}</p>
                    </div>
                    @endif
                    @if($client->phone)
                    <div>
                        <p class="text-sm text-gray-500">الهاتف</p>
                        <p class="font-semibold text-gray-900 mt-1">{{ $client->phone }}</p>
                    </div>
                    @endif
                    <div>
                        <p class="text-sm text-gray-500">النوع</p>
                        <p class="font-semibold text-gray-900 mt-1">{{ $client->type === 'pppoe' ? 'برودباند' : 'هوت سبوت' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">الباقة</p>
                        <p class="font-semibold text-gray-900 mt-1">{{ $client->package->name ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Client Portal Access -->
            @if(auth()->user()->tenant->is_subdomain_enabled)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900">بوابة المشتركين</h3>
                    @if(empty($client->password))
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            غير مفعل (لا يوجد كلمة مرور)
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            مفعل
                        </span>
                    @endif
                </div>

                <div class="space-y-4">
                    <!-- Read-only Info -->
                    <div class="bg-gray-50 rounded-lg p-3 space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">رابط البوابة:</span>
                            <span class="font-mono text-indigo-600 dir-ltr text-right select-all">
                                {{ 'http://' . (str_contains(auth()->user()->tenant->domain, '.') ? auth()->user()->tenant->domain : auth()->user()->tenant->domain . '.' . str_replace('www.', '', parse_url(config('app.url'), PHP_URL_HOST))) }}
                            </span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">اسم المستخدم:</span>
                            <span class="font-bold text-gray-900 select-all">{{ $client->username }}</span>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="grid grid-cols-2 gap-3">
                        <button onclick="copyCredentials()" class="flex items-center justify-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition text-sm font-medium">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            نسخ البيانات
                        </button>
                        
                        <form action="{{ route('crm.clients.send-credentials', $client) }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg transition text-sm font-medium">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                                إرسال واتساب
                            </button>
                        </form>
                    </div>

                    <!-- Update Password -->
                    <form action="{{ route('crm.clients.update-password', $client) }}" method="POST" class="pt-4 border-t border-gray-100">
                        @csrf
                        @method('PUT')
                        <label class="block text-sm font-medium text-gray-700 mb-2">تحديث كلمة المرور</label>
                        <div class="flex gap-2">
                            <input type="text" name="password" placeholder="كلمة مرور جديدة" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 bg-gray-50 focus:bg-white transition" minlength="6" required>
                            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition whitespace-nowrap">
                                حفظ
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endif

            <!-- Activity Timeline -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">سجل الأنشطة</h3>
                <div class="space-y-4">
                    @forelse($client->activities as $activity)
                    <div class="flex gap-3">
                        <div class="w-2 h-2 bg-blue-500 rounded-full mt-2 flex-shrink-0"></div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">{{ $activity->description }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $activity->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @empty
                    <p class="text-center text-gray-500 py-4">لا توجد أنشطة</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Add Note -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">إضافة ملاحظة</h3>
                <form action="{{ route('crm.clients.notes.store', $client) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <select name="type" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="general">عامة</option>
                            <option value="technical">تقنية</option>
                            <option value="billing">مالية</option>
                        </select>
                    </div>
                    <div>
                        <textarea name="content" required rows="3" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none" placeholder="اكتب ملاحظتك..."></textarea>
                    </div>
                    <button type="submit" class="w-full px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                        حفظ الملاحظة
                    </button>
                </form>
            </div>

            <!-- Notes List -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">الملاحظات</h3>
                <div class="space-y-3">
                    @forelse($client->clientNotes as $note)
                    <div class="border-r-4 {{ $note->type === 'technical' ? 'border-purple-500' : ($note->type === 'billing' ? 'border-green-500' : 'border-blue-500') }} pr-3 py-2">
                        <p class="text-sm text-gray-900">{{ $note->content }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $note->created_at->diffForHumans() }}</p>
                    </div>
                    @empty
                    <p class="text-center text-gray-500 py-4 text-sm">لا توجد ملاحظات</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyCredentials() {
    const link = "{{ 'http://' . (str_contains(auth()->user()->tenant->domain, '.') ? auth()->user()->tenant->domain : auth()->user()->tenant->domain . '.' . str_replace('www.', '', parse_url(config('app.url'), PHP_URL_HOST))) }}";
    const username = "{{ $client->username }}";
    
    const text = `رابط البوابة: ${link}\nاسم المستخدم: ${username}`;
    
    navigator.clipboard.writeText(text).then(() => {
        alert('تم نسخ بيانات الدخول!');
    }).catch(err => {
        console.error('فشل النسخ', err);
    });
}
</script>
@endsection
