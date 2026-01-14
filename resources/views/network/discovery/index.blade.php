@extends('layouts.app')

@section('content')
<div class="space-y-6" x-data="{
    selectedServer: '',
    isScanning: false,
    neighbors: [],
    error: null,
    async scan() {
        if (!this.selectedServer) return;
        this.isScanning = true;
        this.error = null;
        this.neighbors = [];
        
        try {
            const response = await fetch(`/network/discovery/scan/${this.selectedServer}`);
            const data = await response.json();
            if (response.ok) {
                this.neighbors = data;
            } else {
                this.error = data.error || 'فشل الفحص';
            }
        } catch (e) {
            this.error = 'حدث خطأ أثناء الاتصال بالخادم';
        } finally {
            this.isScanning = false;
        }
    },
    async link(neighbor) {
        if (!confirm('هل تريد ربط هذا الجهاز وتحديث عنوان IP الخاص به؟')) return;
        
        try {
            const response = await fetch('/network/discovery/link', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    device_id: neighbor.device_id,
                    neighbor_ip: neighbor.address || neighbor.address4,
                    device_type: neighbor.device_type
                })
            });
            
            if (response.ok) {
                alert('تم الربط وتحديث البيانات بنجاح!');
                this.scan(); // Refresh
            }
        } catch (e) {
            alert('فشل عملية الربط');
        }
    }
}">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">استكشاف الشبكة (Discovery)</h2>
            <p class="text-gray-500 mt-1">اكتشاف الأجهزة المحيطة وربطها آلياً عبر بروتوكول MNDP</p>
        </div>
    </div>

    <div class="glass rounded-2xl p-6 shadow-lg border border-white/30">
        <div class="flex items-end gap-4">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">اختر السيرفر للفحص من خلاله</label>
                <select x-model="selectedServer" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                    <option value="">-- اختر سيرفر --</option>
                    @foreach($servers as $server)
                        <option value="{{ $server->id }}">{{ $server->name }} ({{ $server->ip }})</option>
                    @endforeach
                </select>
            </div>
            <button @click="scan()" :disabled="!selectedServer || isScanning" class="px-6 py-2 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white font-bold rounded-xl shadow-lg transition disabled:opacity-50">
                <span x-show="!isScanning">🔍 ابدأ الفحص</span>
                <span x-show="isScanning">⏳ جاري الفحص...</span>
            </button>
        </div>
    </div>

    <div x-show="error" class="p-4 bg-red-50 border-r-4 border-red-500 text-red-700 rounded-lg" style="display: none;">
        <p x-text="error"></p>
    </div>

    <div class="glass rounded-2xl shadow-lg border border-white/30 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الجهاز</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">IP Address</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">MAC Address</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الموديل / الإصدار</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الحالة في النظام</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الإجراء</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <template x-for="neighbor in neighbors" :key="neighbor['mac-address']">
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-900" x-text="neighbor.identity || 'بدون اسم'"></div>
                            <div class="text-xs text-blue-500 font-bold" x-text="neighbor.source"></div>
                            <div class="text-xs text-gray-400" x-text="neighbor.interface"></div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-mono bg-gray-100 px-2 py-1 rounded text-sm" x-text="neighbor.address || '--'"></span>
                            <template x-if="neighbor.address">
                                <a :href="'/network/discovery/webfig/' + neighbor.server_id + '/' + neighbor.address" target="_blank" class="ml-2 inline-flex items-center text-purple-600 hover:text-purple-800" title="دخول Webfig عن بُعد">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                    <span class="text-xs font-bold mr-1">Webfig</span>
                                </a>
                            </template>
                        </td>
                        <td class="px-6 py-4 text-xs text-gray-600 font-mono" x-text="neighbor['mac-address']"></td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900" x-text="neighbor.platform || '--'"></div>
                            <template x-if="neighbor.age">
                                <div class="text-xs text-gray-400" x-text="'العمر: ' + neighbor.age"></div>
                            </template>
                        </td>
                        <td class="px-6 py-4">
                            <template x-if="neighbor.is_linked">
                                <div class="flex flex-col gap-1">
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-[10px] font-bold text-center w-fit">
                                        مرتبط: <span x-text="neighbor.db_name"></span>
                                    </span>
                                    <template x-if="neighbor.db_ip !== neighbor.address">
                                        <button @click="link(neighbor)" class="text-blue-600 hover:text-blue-800 text-[10px] font-bold underline flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                            تحديث الـ IP في النظام
                                        </button>
                                    </template>
                                </div>
                            </template>
                            <template x-if="!neighbor.is_linked">
                                <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-[10px] font-bold">غير مسجل</span>
                            </template>
                        </td>
                        <td class="px-6 py-4">
                             <template x-if="!neighbor.is_linked">
                                <span class="text-gray-400 text-xs italic">يتطلب تسجيله كراوتر</span>
                            </template>
                        </td>
                    </tr>
                </template>
                <template x-if="neighbors.length === 0 && !isScanning">
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                            لا توجد نتائج. اضغط على زر الفحص للبدء.
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
</div>
@endsection
