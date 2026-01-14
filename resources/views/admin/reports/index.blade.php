@extends('layouts.admin')

@section('content')
    <h2 class="text-2xl font-bold text-white mb-6">مركز التقارير</h2>
    <p class="text-gray-400 mb-8 max-w-2xl">
        استعرض تقارير تفصيلية حول أداء النظام المالي، نمو الشركات، ونشاط المستخدمين النهائيين.
    </p>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        
        <!-- Financial Report -->
        <a href="{{ route('admin.reports.financial') }}" class="group bg-gray-800 p-6 rounded-xl border border-gray-700 hover:border-green-500/50 transition-all hover:bg-gray-800/80">
            <div class="w-12 h-12 bg-green-500/10 rounded-lg flex items-center justify-center text-green-500 mb-4 group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h3 class="text-lg font-bold text-white mb-2">التقارير المالية</h3>
            <p class="text-sm text-gray-400">سجل الفواتير، المدفوعات، والإيرادات حسب الفترة الزمنية.</p>
        </a>

        <!-- Tenants Report -->
        <a href="{{ route('admin.reports.tenants') }}" class="group bg-gray-800 p-6 rounded-xl border border-gray-700 hover:border-indigo-500/50 transition-all hover:bg-gray-800/80">
            <div class="w-12 h-12 bg-indigo-500/10 rounded-lg flex items-center justify-center text-indigo-500 mb-4 group-hover:scale-110 transition-transform">
                 <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
            <h3 class="text-lg font-bold text-white mb-2">تقارير الشركات</h3>
            <p class="text-sm text-gray-400">قائمة الشركات، حالات الاشتراك، ونمو قاعدة العملاء.</p>
        </a>

         <!-- Clients Report -->
        <a href="{{ route('admin.reports.clients') }}" class="group bg-gray-800 p-6 rounded-xl border border-gray-700 hover:border-cyan-500/50 transition-all hover:bg-gray-800/80">
            <div class="w-12 h-12 bg-cyan-500/10 rounded-lg flex items-center justify-center text-cyan-500 mb-4 group-hover:scale-110 transition-transform">
                 <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
            <h3 class="text-lg font-bold text-white mb-2">المستخدمين النهائيين</h3>
            <p class="text-sm text-gray-400">تحليلات المشتركين (End Users) ونشاط الشبكة عبر النظام.</p>
        </a>

    </div>
@endsection
