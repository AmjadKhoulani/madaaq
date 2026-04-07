@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header with Search -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
        <div class="relative">
            <div class="absolute -top-4 -right-4 w-24 h-24 bg-indigo-500/10 rounded-full blur-2xl -z-10"></div>
            <h2 class="text-3xl font-black text-gray-900 tracking-tight">إدارة العملاء</h2>
            <p class="text-xs font-bold text-indigo-600 uppercase tracking-widest mt-1 flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-indigo-600 animate-pulse"></span>
                CRM Relations Management
            </p>
        </div>
        <a href="{{ route('crm.clients.create') }}" class="inline-flex items-center gap-3 px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-black rounded-2xl shadow-xl shadow-indigo-200/50 transition-all transform hover:scale-105 active:scale-95 group">
            <svg class="w-5 h-5 group-hover:rotate-90 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            <span>عميل جديد</span>
        </a>
    </div>

    <!-- Filters & Search -->
    <div class="glass-panel rounded-3xl p-6 mb-8 relative overflow-hidden group">
        <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-indigo-500/5 rounded-full blur-3xl transition-all group-hover:bg-indigo-500/10"></div>
        <form method="GET" class="flex flex-col md:flex-row items-center gap-4 relative z-10">
            <div class="flex-1 w-full text-right">
                <div class="relative group">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="بحث بالاسم، البريد، الهاتف..." class="w-full pl-6 pr-12 py-3.5 bg-white/50 backdrop-blur-md border border-white/20 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:bg-white focus:border-indigo-500/30 transition-all font-bold text-gray-900 placeholder-gray-400">
                    <span class="absolute right-4 top-3.5 text-gray-400 group-focus-within:text-indigo-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </span>
                </div>
            </div>
            
            <div class="flex gap-4 w-full md:w-auto">
                <select name="status" class="w-full md:w-44 py-3.5 px-6 bg-white/50 backdrop-blur-md border border-white/20 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:bg-white focus:border-indigo-500/30 transition-all font-bold text-gray-700 appearance-none">
                    <option value="">كل الحالات</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                </select>
                
                <select name="type" class="w-full md:w-44 py-3.5 px-6 bg-white/50 backdrop-blur-md border border-white/20 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:bg-white focus:border-indigo-500/30 transition-all font-bold text-gray-700 appearance-none">
                    <option value="">كل الأنواع</option>
                    <option value="pppoe" {{ request('type') == 'pppoe' ? 'selected' : '' }}>برودباند</option>
                    <option value="hotspot" {{ request('type') == 'hotspot' ? 'selected' : '' }}>هوت سبوت</option>
                </select>
            </div>

            <button type="submit" class="w-full md:w-auto px-10 py-3.5 bg-gray-900 hover:bg-black text-white font-black rounded-2xl transition-all shadow-xl hover:shadow-gray-900/20 active:scale-95">
                تصفية
            </button>
        </form>
    </div>

    <!-- Clients Table -->
    <div class="glass-panel rounded-[2rem] overflow-hidden transition-all duration-500 hover:shadow-2xl hover:shadow-indigo-500/5">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="bg-white/30 backdrop-blur-lg border-b border-white/20">
                        <th class="px-8 py-5 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">العميل</th>
                        <th class="px-8 py-5 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">النوع</th>
                        <th class="px-8 py-5 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">الباقة</th>
                        <th class="px-8 py-5 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">الحالة</th>
                        <th class="px-8 py-5 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">تاريخ الانتهاء</th>
                        <th class="px-8 py-5 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10">
                    @forelse($clients as $client)
                    <tr class="hover:bg-white/40 transition-all duration-300 group">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-50 to-white flex items-center justify-center text-indigo-700 font-black border border-white/50 shadow-sm transition-transform group-hover:scale-110 group-hover:rotate-3">
                                    {{ strtoupper(substr($client->username, 0, 2)) }}
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2 font-black text-gray-900 leading-none">
                                        <span class="truncate max-w-[200px]">{{ $client->username }}</span>
                                        @if(auth()->user()->tenant->is_subdomain_enabled && empty($client->password))
                                            <svg class="w-4 h-4 text-orange-500 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                        @endif
                                    </div>
                                    @if($client->name)
                                        <div class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter mt-1">{{ $client->name }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            @if($client->type === 'pppoe')
                                <span class="px-3 py-1 rounded-xl text-[10px] font-black uppercase tracking-widest bg-blue-500/10 text-blue-600 border border-blue-500/10">PPPoE</span>
                            @else
                                <span class="px-3 py-1 rounded-xl text-[10px] font-black uppercase tracking-widest bg-purple-500/10 text-purple-600 border border-purple-500/10">Hotspot</span>
                            @endif
                        </td>
                        <td class="px-8 py-6">
                            <span class="text-xs font-black text-gray-700 tracking-tight">{{ $client->package->name ?? 'Custom Plan' }}</span>
                        </td>
                        <td class="px-8 py-6">
                            @if($client->status === 'active')
                                <span class="inline-flex items-center px-4 py-1.5 rounded-2xl text-[10px] font-black uppercase tracking-widest bg-green-500/5 text-green-600 border border-green-500/10">
                                    <span class="w-2 h-2 bg-green-600 rounded-full ml-2 animate-pulse shadow-[0_0_8px_rgba(34,197,94,0.5)]"></span>
                                    Active
                                </span>
                            @else
                                <span class="inline-flex items-center px-4 py-1.5 rounded-2xl text-[10px] font-black uppercase tracking-widest bg-gray-500/5 text-gray-500 border border-gray-500/10">
                                    <span class="w-2 h-2 bg-gray-400 rounded-full ml-2"></span>
                                    Disabled
                                </span>
                            @endif
                        </td>
                        <td class="px-8 py-6 text-xs font-black text-gray-600 tracking-tighter">
                            {{ $client->expires_at ? $client->expires_at->format('Y-m-d') : 'Permanent' }}
                        </td>
                        <td class="px-8 py-6 text-left">
                            <a href="{{ route('crm.clients.show', $client) }}" class="inline-flex items-center justify-center w-11 h-11 rounded-2xl bg-white/50 backdrop-blur-sm text-indigo-600 hover:bg-indigo-600 hover:text-white transition-all duration-300 border border-white/40 shadow-sm hover:shadow-indigo-500/20 group-hover:translate-x-[-4px]">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center gap-6">
                                <div class="w-20 h-20 bg-white/50 backdrop-blur-md rounded-[2rem] flex items-center justify-center border border-white/30 shadow-lg shadow-gray-200/50">
                                    <svg class="w-10 h-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                </div>
                                <div class="relative">
                                    <h3 class="text-xl font-black text-gray-900">لا يوجد عملاء متاحين</h3>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-2 text-center">No active client records found matching your criteria</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($clients->hasPages())
        <div class="px-8 py-6 border-t border-white/10 bg-white/20 backdrop-blur-lg">
            {{ $clients->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
