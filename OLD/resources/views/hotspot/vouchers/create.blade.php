@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto space-y-6" x-data="voucherPreview()">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">توليد كروت هوت سبوت (بالجملة)</h2>
        <p class="text-gray-500 mt-1">إنشاء وتوليد مجموعة من الكروت للطباعة والبيع</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Form -->
        <form action="{{ route('hotspot.vouchers.store') }}" method="POST" class="glass rounded-2xl shadow-lg border border-white/30 p-6 space-y-6">
            @csrf
            
            <!-- Server & Package -->
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">سيرفر مايكروتك *</label>
                    <select name="server_id" required class="w-full px-4 py-3 border-2 border-purple-200 rounded-xl focus:ring-2 focus:ring-purple-500 bg-white/80">
                        <option value="">-- اختر السيرفر --</option>
                        @foreach($servers as $server)
                        <option value="{{ $server->id }}">{{ $server->name }} ({{ $server->ip }})</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">الباقة *</label>
                    <select name="package_id" required x-model="selectedPackage" class="w-full px-4 py-3 border-2 border-purple-200 rounded-xl focus:ring-2 focus:ring-purple-500 bg-white/80">
                        <option value="">-- اختر الباقة --</option>
                        @foreach($packages as $package)
                        <option value="{{ $package->id }}" data-name="{{ $package->name }}" data-price="{{ $package->price }}">
                            {{ $package->name }} - {{ number_format($package->price) }} ل.س
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="border-t border-purple-100 pt-6"></div>

            <!-- Generation Settings -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">عدد الكروت *</label>
                    <input type="number" name="quantity" min="1" max="100" value="10" x-model="quantity" required class="w-full px-4 py-3 border-2 border-purple-200 rounded-xl focus:ring-2 focus:ring-purple-500 text-center text-lg font-bold bg-white/80">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">بادئة الاسم</label>
                    <input type="text" name="prefix" x-model="prefix" placeholder="WiFi-" maxlength="5" class="w-full px-4 py-3 border-2 border-purple-200 rounded-xl focus:ring-2 focus:ring-purple-500 bg-white/80">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">طول الرمز</label>
                    <input type="number" name="length" min="4" max="10" value="6" x-model="length" required class="w-full px-4 py-3 border-2 border-purple-200 rounded-xl focus:ring-2 focus:ring-purple-500 bg-white/80">
                </div>
            </div>

            <div class="bg-blue-50 border-l-4 border-blue-500 rounded-lg p-4 flex gap-3">
                <span class="text-2xl">⚡</span>
                <div>
                    <h4 class="font-bold text-blue-900 text-sm">ملاحظة هامة</h4>
                    <p class="text-blue-700 text-xs mt-1">عند الضغط على "توليد"، سيقوم النظام بإنشاء المستخدمين فوراً على الراوتر المحدد وحفظهم في قاعدة البيانات.</p>
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full py-3 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white font-bold rounded-xl shadow-lg transform transition hover:scale-105 active:scale-95 flex items-center justify-center gap-2">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    توليد <span x-text="quantity"></span> كرت ومزامنتها
                </button>
            </div>
        </form>

        <!-- Live Preview -->
        <div class="glass rounded-2xl shadow-lg border border-white/30 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-900">معاينة مباشرة 👁️</h3>
                <span class="text-xs text-gray-500 bg-purple-50 px-3 py-1 rounded-full">Live Preview</span>
            </div>

            <!-- Preview Card -->
            <div class="relative">
                <div class="card-preview">
                    <div class="card-preview-inner">
                        <div class="decorative-corner"></div>
                        
                        <div class="wifi-icon">
                            📶
                        </div>
                        
                        <div class="card-header">
                            {{ config('app.name', 'SmartISP') }} WiFi
                        </div>
                        
                        <div class="credentials">
                            <div class="credential-row">
                                <span class="credential-label">اسم المستخدم</span>
                                <span class="credential-value" x-text="previewUsername"></span>
                            </div>
                            <div class="credential-row">
                                <span class="credential-label">كلمة المرور</span>
                                <span class="credential-value" x-text="previewPassword"></span>
                            </div>
                        </div>
                        
                        <div class="card-footer">
                            <span x-text="packageName"></span>
                            <span class="price-badge" x-show="packagePrice" x-text="packagePrice + ' ل.س'"></span>
                        </div>
                        
                        <div class="decorative-dots">
                            <div class="dot"></div>
                            <div class="dot"></div>
                            <div class="dot"></div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 text-center text-sm text-gray-500">
                    سيتم توليد <strong x-text="quantity"></strong> كرت بهذا التصميم
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card-preview {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 16px;
        padding: 3px;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
        transition: transform 0.3s;
    }
    .card-preview:hover {
        transform: translateY(-5px);
    }
    .card-preview-inner {
        background: white;
        border-radius: 14px;
        padding: 20px;
        text-align: center;
        position: relative;
    }
    .wifi-icon {
        width: 50px;
        height: 50px;
        margin: 0 auto 15px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }
    .card-header {
        font-weight: 700;
        font-size: 16px;
        background: linear-gradient(90deg, #667eea, #764ba2);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        padding-bottom: 10px;
        margin-bottom: 15px;
        border-bottom: 2px solid #e8eaf6;
    }
    .credentials {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 15px;
        margin: 15px 0;
    }
    .credential-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin: 8px 0;
    }
    .credential-label {
        color: #6b7280;
        font-weight: 600;
        font-size: 11px;
        text-transform: uppercase;
    }
    .credential-value {
        font-family: 'Courier New', monospace;
        font-size: 14px;
        font-weight: bold;
        color: #111827;
        background: white;
        padding: 5px 10px;
        border-radius: 6px;
        border: 1px dashed #667eea;
    }
    .card-footer {
        font-size: 12px;
        color: white;
        background: linear-gradient(90deg, #667eea, #764ba2);
        padding: 8px;
        border-radius: 8px;
        margin-top: 10px;
        font-weight: 600;
    }
    .price-badge {
        display: inline-block;
        background: rgba(255, 255, 255, 0.2);
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 10px;
        margin-right: 5px;
    }
    .decorative-corner {
        position: absolute;
        top: 0;
        right: 0;
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, rgba(255,255,255,0.3) 0%, transparent 100%);
        border-radius: 0 14px 0 100%;
    }
    .decorative-dots {
        position: absolute;
        bottom: 10px;
        left: 10px;
        display: flex;
        gap: 3px;
    }
    .dot {
        width: 4px;
        height: 4px;
        background: #e8eaf6;
        border-radius: 50%;
    }
</style>

<script>
function voucherPreview() {
    return {
        prefix: 'WiFi-',
        length: 6,
        quantity: 10,
        selectedPackage: '',
        packageName: 'باقة إنترنت',
        packagePrice: '',
        
        get previewUsername() {
            return this.prefix + 'XXXX'.substring(0, this.length);
        },
        
        get previewPassword() {
            return 'X'.repeat(this.length);
        },
        
        init() {
            this.$watch('selectedPackage', value => {
                const select = document.querySelector('select[name="package_id"]');
                const option = select.options[select.selectedIndex];
                if (option && option.dataset.name) {
                    this.packageName = option.dataset.name;
                    this.packagePrice = new Intl.NumberFormat('ar-SY').format(option.dataset.price);
                }
            });
        }
    }
}
</script>
@endsection
