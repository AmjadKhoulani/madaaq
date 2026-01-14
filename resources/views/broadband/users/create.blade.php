@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">إضافة مشترك برودباند</h2>
        <p class="text-gray-500 mt-1">إنشاء مستخدم PPPoE جديد مع إمكانية تخصيص الباقة</p>
    </div>

    <form action="{{ route('broadband.users.store') }}" method="POST" class="space-y-6" x-data="userForm()">
        @csrf
        
        <!-- Customer Selection -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6" x-data="{ mode: 'existing' }">
            <h3 class="text-lg font-bold text-gray-900 mb-4">بيانات العميل</h3>
            
            <div class="flex gap-4 mb-6">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="mode" value="existing" x-model="mode" class="text-blue-600 focus:ring-blue-500">
                    <span class="font-semibold text-gray-700">عميل حالي</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="mode" value="new" x-model="mode" class="text-blue-600 focus:ring-blue-500">
                    <span class="font-semibold text-gray-700">عميل جديد</span>
                </label>
            </div>

            <!-- Existing Customer -->
            <div x-show="mode === 'existing'">
                <label class="block text-sm font-semibold text-gray-700 mb-2">اختر العميل</label>
                <select name="customer_id" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 select2">
                    <option value="">-- ابحث عن عميل (الاسم أو الهاتف) --</option>
                    @foreach($customers as $customer)
                    <option value="{{ $customer->id }}">{{ $customer->name }} - {{ $customer->phone }}</option>
                    @endforeach
                </select>
            </div>

            <!-- New Customer -->
            <div x-show="mode === 'new'" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">اسم العميل *</label>
                    <input type="text" name="name" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="الاسم الكامل">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">رقم الهاتف *</label>
                    <input type="text" name="phone" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="05xxxxxxxx">
                </div>
            </div>
        </div>

        <!-- Account Info -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-4">
            <h3 class="text-lg font-bold text-gray-900 mb-4">معلومات الحساب (الاشتراك)</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Tower Selection -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">البرج *</label>
                    <select name="tower_id" x-model="selectedTower" @change="updateSsids()" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                        <option value="">-- اختر البرج --</option>
                        @foreach($towers as $tower)
                        <option value="{{ $tower->id }}" :data-ssids="JSON.stringify({{ $tower->ssids->map(function($ssid) { 
                            return ['id' => $ssid->id, 'name' => $ssid->ssid_name, 'router_id' => $ssid->router_id ?? '']; 
                        }) }})">
                            🗼 {{ $tower->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- SSID Selection -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">شبكة WiFi (SSID) *</label>
                    <select name="ssid_id" x-model="selectedSsid" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required :disabled="!selectedTower">
                        <option value="">-- اختر الشبكة --</option>
                        <template x-for="ssid in availableSsids" :key="ssid.id">
                            <option :value="ssid.id" x-text="ssid.name"></option>
                        </template>
                    </select>
                </div>

                <!-- IP Address -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">IP Address (مخصص)</label>
                    <input type="text" name="ip" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="10.x.x.x (اختياري)">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">اسم المستخدم *</label>
                    <input type="text" name="username" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" placeholder="user123">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">كلمة المرور *</label>
                    <input type="text" name="password" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" value="{{ Str::random(10) }}">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">الباقة الأساسية</label>
                <select name="package_id" x-model="packageId" @change="loadPackageDefaults()" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    <option value="">-- اختر الباقة --</option>
                    @foreach($packages as $package)
                        <option value="{{ $package->id }}" 
                                data-speed-down="{{ $package->speed_down }}"
                                data-speed-up="{{ $package->speed_up }}"
                                data-duration="{{ $package->duration_days }}" 
                                data-limit="{{ $package->data_limit_mb }}"
                                data-price="{{ $package->price }}">
                            {{ $package->name }} ({{ $package->speed_down }}M/{{ $package->speed_up }}M)
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-500 mt-1">يمكنك تخصيص القيم في الأسفل</p>
            </div>
        </div>

        <!-- Custom Settings -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-4">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900">تخصيص الباقة</h3>
                <span class="text-xs text-gray-500 bg-gray-100 px-3 py-1 rounded-full">اختياري</span>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">المدة (يوم)</label>
                    <input type="number" name="custom_duration_days" x-model="customDuration" @input="updateExpiry()" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" placeholder="31">
                    <p class="text-xs text-gray-500 mt-1" x-show="packageDuration">
                        افتراضي: <span x-text="packageDuration"></span> يوم
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">حجم البيانات (MB)</label>
                    <input type="number" name="custom_data_limit_mb" x-model="customDataLimit" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" placeholder="1000">
                    <p class="text-xs text-gray-500 mt-1" x-show="packageDataLimit">
                        افتراضي: <span x-text="(packageDataLimit / 1024).toFixed(1)"></span> GB
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">السعر ({{ $currency }})</label>
                    <input type="number" step="0.01" name="custom_price" x-model="customPrice" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" placeholder="150.00">
                    <p class="text-xs text-gray-500 mt-1" x-show="packagePrice">
                        افتراضي: <span x-text="packagePrice"></span>
                    </p>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">تاريخ الانتهاء</label>
                <input type="date" name="expires_at" x-model="expiryDate" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                <p class="text-xs text-gray-500 mt-1">سيتم حسابه تلقائياً بناءً على المدة</p>
            </div>
        </div>

        <!-- Summary Card -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200 p-6" x-show="packageId">
            <h4 class="font-bold text-gray-900 mb-3">ملخص الاشتراك</h4>
            <div class="grid grid-cols-2 gap-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">المدة:</span>
                    <span class="font-semibold text-gray-900" x-text="(customDuration || packageDuration || '-') + ' يوم'"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">حجم البيانات:</span>
                    <span class="font-semibold text-gray-900" x-text="((customDataLimit || packageDataLimit) ? ((customDataLimit || packageDataLimit) / 1024).toFixed(1) + ' GB' : 'غير محدود')"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">السعر:</span>
                    <span class="font-bold text-blue-600 text-base" x-text="(customPrice || packagePrice || 0) + ' {{ $currency }}'"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">ينتهي في:</span>
                    <span class="font-semibold text-gray-900" x-text="expiryDate || '-'"></span>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-sm transition">
                حفظ المشترك
            </button>
            <a href="{{ route('broadband.users.index') }}" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg transition">
                إلغاء
            </a>
        </div>
    </form>
</div>

<script>
function userForm() {
    return {
        packageId: '',
        packageDuration: null,
        packageDataLimit: null,
        packagePrice: null,
        customDuration: '',
        customDataLimit: '',
        customPrice: '',
        expiryDate: '',
        
        selectedTower: '',
        selectedSsid: '',
        availableSsids: [],

        updateSsids() {
            const select = document.querySelector('select[name="tower_id"]');
            const option = select.options[select.selectedIndex];
            if (option && option.dataset.ssids) {
                this.availableSsids = JSON.parse(option.dataset.ssids);
            } else {
                this.availableSsids = [];
            }
            this.selectedSsid = '';
        },

        loadPackageDefaults() {
            const select = document.querySelector('select[name="package_id"]');
            const option = select.options[select.selectedIndex];
            
            if (option.value) {
                this.packageDuration = option.dataset.duration || null;
                this.packageDataLimit = option.dataset.limit || null;
                this.packagePrice = option.dataset.price || null;
                
                // Auto-calculate expiry if duration exists
                if (this.packageDuration) {
                    this.updateExpiry();
                }
            }
        },

        updateExpiry() {
            const duration = this.customDuration || this.packageDuration;
            if (duration && duration > 0) {
                const today = new Date();
                today.setDate(today.getDate() + parseInt(duration));
                this.expiryDate = today.toISOString().split('T')[0];
            }
        }
    }
}
</script>
@endsection
