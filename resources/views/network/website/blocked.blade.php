@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">المواقع المحظورة</h2>
            <p class="text-gray-500 mt-1">إدارة قائمة المواقع والكلمات المفتاحية المحظورة</p>
        </div>
        
        <button onclick="document.getElementById('addModal').classList.remove('hidden')" class="px-4 py-2 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white font-bold rounded-xl shadow-lg transition">
            + حظر موقع جديد
        </button>
    </div>

    <!-- Blocked List -->
    <div class="glass rounded-2xl shadow-lg border border-white/30 overflow-hidden">
        <table class="w-full">
            <thead class="bg-gradient-to-r from-red-600 to-orange-600 text-white">
                <tr>
                    <th class="px-6 py-4 text-right text-sm font-bold">النطاق/الكلمة</th>
                    <th class="px-6 py-4 text-right text-sm font-bold">النوع</th>
                    <th class="px-6 py-4 text-right text-sm font-bold">السبب</th>
                    <th class="px-6 py-4 text-right text-sm font-bold">الحالة</th>
                    <th class="px-6 py-4 text-right text-sm font-bold">إجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-red-100">
                @forelse($blocked as $item)
                <tr class="hover:bg-red-50 transition">
                    <td class="px-6 py-4 font-semibold text-gray-900">{{ $item->domain }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-bold">
                            {{ $item->type === 'domain' ? 'نطاق' : 'كلمة مفتاحية' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-600 text-sm">{{ $item->reason ?? '--' }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded-full text-xs font-bold {{ $item->is_active ? 'bg-red-200 text-red-800' : 'bg-gray-200 text-gray-600' }}">
                            {{ $item->is_active ? 'فعّال' : 'معطل' }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2">
                            <form action="{{ route('network.website.toggle', $item) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-lg transition">
                                    {{ $item->is_active ? 'تعطيل' : 'تفعيل' }}
                                </button>
                            </form>
                            
                            <form action="{{ route('network.website.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('متأكد من الحذف؟')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-sm font-bold rounded-lg transition">
                                    حذف
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                        لا توجد مواقع محظورة. أضف موقع جديد للحظر.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="p-4 border-t border-red-100">
            {{ $blocked->links() }}
        </div>
    </div>
</div>

<!-- Add Modal -->
<div id="addModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" onclick="if(event.target === this) this.classList.add('hidden')">
    <div class="glass rounded-2xl p-6 max-w-md w-full mx-4 shadow-2xl border border-white/30">
        <h3 class="text-xl font-bold text-gray-900 mb-4">حظر موقع جديد</h3>
        
        <form action="{{ route('network.website.block') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">النطاق أو الكلمة المفتاحية</label>
                <input type="text" name="domain" required placeholder="example.com أو كلمة" class="w-full px-4 py-2 border-2 border-purple-200 rounded-xl focus:ring-2 focus:ring-purple-500">
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">النوع</label>
                <select name="type" class="w-full px-4 py-2 border-2 border-purple-200 rounded-xl focus:ring-2 focus:ring-purple-500">
                    <option value="domain">نطاق كامل</option>
                    <option value="keyword">كلمة مفتاحية</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">السبب (اختياري)</label>
                <textarea name="reason" rows="2" placeholder="سبب الحظر..." class="w-full px-4 py-2 border-2 border-purple-200 rounded-xl focus:ring-2 focus:ring-purple-500"></textarea>
            </div>
            
            <div class="flex gap-3">
                <button type="submit" class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-bold rounded-lg transition">
                    🚫 حظر
                </button>
                <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg transition">
                    إلغاء
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
