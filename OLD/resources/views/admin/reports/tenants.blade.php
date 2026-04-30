@extends('layouts.admin')

@section('content')
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl font-bold text-white">تقارير الشركات (Tenants)</h2>
            <p class="text-gray-400 mt-1">تحليل نمو قاعدة العملاء والشركات المسجلة</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-gray-800 rounded-xl border border-gray-700 p-4 mb-6">
        <form action="{{ route('admin.reports.tenants') }}" method="GET" class="flex flex-wrap items-end gap-4">
            
            <div class="flex-1 min-w-[150px]">
                <label class="block text-xs text-gray-400 mb-1">الحالة</label>
                <select name="status" class="w-full bg-gray-900 border border-gray-700 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-indigo-500">
                    <option value="">الكل</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                    <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>معلق</option>
                </select>
            </div>

            <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition-colors">
                تصفية
            </button>
             @if(request()->has('status'))
                <a href="{{ route('admin.reports.tenants') }}" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-gray-300 rounded-lg font-medium transition-colors">
                    إلغاء
                </a>
            @endif
        </form>
    </div>

    <!-- Table -->
    <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-right">
                <thead>
                    <tr class="bg-gray-750 border-b border-gray-700 text-gray-400 text-sm uppercase">
                        <th class="px-6 py-4 font-medium">اسم الشركة</th>
                        <th class="px-6 py-4 font-medium">النطاق</th>
                        <th class="px-6 py-4 font-medium">عدد الموظفين</th>
                        <th class="px-6 py-4 font-medium">الحالة</th>
                        <th class="px-6 py-4 font-medium">تاريخ التسجيل</th>
                        <th class="px-6 py-4 font-medium">إجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @forelse($tenants as $tenant)
                    <tr class="hover:bg-gray-700/50 transition-colors">
                        <td class="px-6 py-4 text-white font-medium">{{ $tenant->name }}</td>
                        <td class="px-6 py-4 font-mono text-indigo-400 text-sm">{{ $tenant->domain }}</td>
                        <td class="px-6 py-4 text-gray-300">{{ $tenant->users_count }}</td>
                        <td class="px-6 py-4">
                            @if($tenant->status == 'active')
                                <span class="bg-green-500/10 text-green-400 px-2 py-1 rounded-full text-xs font-medium border border-green-500/20">نشط</span>
                            @else
                                <span class="bg-red-500/10 text-red-400 px-2 py-1 rounded-full text-xs font-medium border border-red-500/20">{{ $tenant->status }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-400 text-sm font-mono">
                            {{ $tenant->created_at->format('Y-m-d') }}
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.tenants.show', $tenant->id) }}" class="text-gray-400 hover:text-white transition-colors">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                            لا توجد شركات.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-700 bg-gray-800">
            {{ $tenants->withQueryString()->links() }}
        </div>
    </div>
@endsection
