@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold text-gray-900">مراقبة كروت الهوت سبوت 🎫</h2>
            <p class="text-gray-500 mt-1">عرض وإدارة جميع الكروت المطبوعة</p>
        </div>
        <a href="{{ route('hotspot.vouchers.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white font-bold rounded-xl shadow-lg transform transition hover:scale-105">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            إنشاء كروت جديدة
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="glass rounded-2xl shadow-lg border border-white/30 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">إجمالي الكروت</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($stats['total']) }}</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center text-white text-2xl">
                    🎫
                </div>
            </div>
        </div>

        <div class="glass rounded-2xl shadow-lg border border-white/30 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">كروت نشطة</p>
                    <p class="text-3xl font-bold text-green-600 mt-2">{{ number_format($stats['active']) }}</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-green-400 to-green-600 rounded-xl flex items-center justify-center text-white text-2xl">
                    ✅
                </div>
            </div>
        </div>

        <div class="glass rounded-2xl shadow-lg border border-white/30 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">كروت معطلة</p>
                    <p class="text-3xl font-bold text-red-600 mt-2">{{ number_format($stats['inactive']) }}</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-red-400 to-red-600 rounded-xl flex items-center justify-center text-white text-2xl">
                    ⛔
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="glass rounded-2xl shadow-lg border border-white/30 p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <!-- Search -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">بحث</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="اسم المستخدم..." class="w-full px-4 py-2.5 border-2 border-purple-200 rounded-xl focus:ring-2 focus:ring-purple-500 bg-white/80">
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">الحالة</label>
                <select name="status" class="w-full px-4 py-2.5 border-2 border-purple-200 rounded-xl focus:ring-2 focus:ring-purple-500 bg-white/80">
                    <option value="">الكل</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>معطل</option>
                </select>
            </div>

            <!-- Package Filter -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">الباقة</label>
                <select name="package_id" class="w-full px-4 py-2.5 border-2 border-purple-200 rounded-xl focus:ring-2 focus:ring-purple-500 bg-white/80">
                    <option value="">الكل</option>
                    @foreach($packages as $package)
                        <option value="{{ $package->id }}" {{ request('package_id') == $package->id ? 'selected' : '' }}>{{ $package->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Date From -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">من تاريخ</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full px-4 py-2.5 border-2 border-purple-200 rounded-xl focus:ring-2 focus:ring-purple-500 bg-white/80">
            </div>

            <!-- Date To -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">إلى تاريخ</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full px-4 py-2.5 border-2 border-purple-200 rounded-xl focus:ring-2 focus:ring-purple-500 bg-white/80">
            </div>

            <div class="md:col-span-5 flex gap-3">
                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white font-bold rounded-xl shadow-lg transition">
                    🔍 بحث وتصفية
                </button>
                <a href="{{ route('hotspot.vouchers.index') }}" class="px-6 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold rounded-xl transition">
                    إعادة تعيين
                </a>
            </div>
        </form>
    </div>

    <!-- Actions and Bulk Controls -->
    <div class="flex justify-between items-center mb-4 px-2" x-data="{ count: 0, 
        updateCount() { 
            this.count = document.querySelectorAll('.bulk-item:checked').length; 
        },
        toggleAll(e) {
            document.querySelectorAll('.bulk-item').forEach(el => {
                el.checked = e.target.checked;
            });
            this.updateCount();
        }
    }">
        <div class="flex gap-3">
             <form action="{{ route('hotspot.vouchers.reprint_last') }}" method="GET" target="_blank">
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg shadow transition">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    طباعة آخر دفعة
                </button>
             </form>
        </div>
        
        <div x-show="count > 0" style="display: none;" class="flex gap-2 items-center bg-yellow-50 px-4 py-2 rounded-lg border border-yellow-200">
            <span class="text-sm font-bold text-yellow-800" x-text="count + ' عنصر محدد'"></span>
            <button form="bulk-form" name="action" value="disable" class="text-xs px-3 py-1.5 bg-red-600 text-white rounded hover:bg-red-700 font-bold" onclick="return confirm('هل أنت متأكد من تعطيل الكروت المحددة؟')">
                تعطيل المحدد
            </button>
            <button form="bulk-form" name="action" value="delete" class="text-xs px-3 py-1.5 bg-gray-800 text-white rounded hover:bg-gray-900 font-bold" onclick="return confirm('هل أنت متأكد من حذف الكروت المحددة نهائياً؟')">
                حذف المحدد
            </button>
        </div>
    </div>

    <!-- Vouchers Table -->
    <div class="glass rounded-2xl shadow-lg border border-white/30 overflow-hidden">
        <form id="bulk-form" action="{{ route('hotspot.vouchers.bulk_action') }}" method="POST">
            @csrf
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-purple-600 to-blue-600 text-white">
                    <tr>
                        <th class="px-6 py-4 text-right">
                            <input type="checkbox" @change="toggleAll($event)" class="w-4 h-4 rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                        </th>
                        <th class="px-6 py-4 text-right text-sm font-bold">#</th>
                        <th class="px-6 py-4 text-right text-sm font-bold">اسم المستخدم</th>
                        <th class="px-6 py-4 text-right text-sm font-bold">كلمة المرور</th>
                        <th class="px-6 py-4 text-right text-sm font-bold">الباقة</th>
                        <th class="px-6 py-4 text-right text-sm font-bold">السيرفر</th>
                        <th class="px-6 py-4 text-right text-sm font-bold">الحالة</th>
                        <th class="px-6 py-4 text-right text-sm font-bold">تاريخ الإنشاء</th>
                        <th class="px-6 py-4 text-center text-sm font-bold">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($vouchers as $voucher)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <input type="checkbox" name="ids[]" value="{{ $voucher->id }}" @change="updateCount()" class="bulk-item w-4 h-4 rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $loop->iteration + ($vouchers->currentPage() - 1) * $vouchers->perPage() }}</td>
                        <td class="px-6 py-4">
                            <span class="font-mono font-bold text-purple-600">{{ $voucher->username }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-mono text-sm bg-gray-100 px-3 py-1 rounded-lg">{{ $voucher->password }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">
                            {{ $voucher->package->name ?? 'غير محدد' }}
                            @if($voucher->package)
                                <span class="text-xs text-gray-500">({{ number_format($voucher->package->price) }} ل.س)</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $voucher->mikrotikServer->name ?? 'غير محدد' }}</td>
                        <td class="px-6 py-4">
                            @if($voucher->status == 'active')
                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold">
                                    <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                    نشط
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold">
                                    <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                                    معطل
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $voucher->created_at->format('Y-m-d H:i') }}
                            <span class="text-xs text-gray-400">({{ $voucher->created_at->diffForHumans() }})</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('hotspot.users.print', $voucher->id) }}" target="_blank" class="p-2 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-lg transition" title="طباعة الكرت">
                                    🖨️
                                </a>
                                <a href="{{ route('crm.clients.show', $voucher->id) }}" class="p-2 bg-purple-100 hover:bg-purple-200 text-purple-700 rounded-lg transition" title="عرض التفاصيل">
                                    👁️
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center gap-4">
                                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center text-4xl">
                                    🎫
                                </div>
                                <div>
                                    <p class="text-gray-500 font-medium">لا توجد كروت بعد</p>
                                    <a href="{{ route('hotspot.vouchers.create') }}" class="text-blue-600 hover:text-blue-700 font-semibold text-sm mt-2 inline-block">
                                        ابدأ بإنشاء كروت جديدة
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        </form>

        <!-- Pagination -->
        @if($vouchers->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $vouchers->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
