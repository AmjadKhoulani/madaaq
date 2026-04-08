@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto pb-12">
    <!-- Header Protocol -->
    <div class="glass-panel border border-white/40 rounded-[3rem] p-10 md:p-12 shadow-2xl relative overflow-hidden mb-12">
        <div class="absolute -top-24 -right-24 w-64 h-64 bg-indigo-600/5 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-emerald-600/5 rounded-full blur-3xl"></div>

        <div class="relative flex flex-col md:flex-row items-center gap-10">
            <div class="w-24 h-24 bg-indigo-600 rounded-[2rem] flex items-center justify-center text-white shadow-2xl shadow-indigo-200">
                <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
            <div class="text-center md:text-right">
                <h1 class="text-4xl font-black text-gray-900 tracking-tighter mb-2">بروتوكول الدعم الفني</h1>
                <p class="text-lg text-gray-500 font-medium">نحن هنا لضمان استقرار عملياتك وتوسيع نطاق شبكتك. تواصل معنا عبر القنوات المعتمدة.</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Contact Cards -->
        <div class="lg:col-span-1 space-y-6">
            <h4 class="text-xs font-black text-indigo-600 uppercase tracking-[0.2em] px-4 mb-4">قنوات الاتصال المباشر</h4>
            
            <!-- WhatsApp Card -->
            <a href="https://wa.me/963951555555" target="_blank" class="glass-panel border border-white/60 p-6 rounded-[2.5rem] flex items-center gap-6 group hover:scale-[1.02] transition-all shadow-xl block">
                <div class="w-16 h-16 bg-emerald-500/10 rounded-2xl flex items-center justify-center text-emerald-600 border border-emerald-500/20 group-hover:bg-emerald-500 group-hover:text-white transition-all">
                    <span class="text-3xl">💬</span>
                </div>
                <div>
                    <h5 class="text-sm font-black text-gray-900 uppercase">WhatsApp</h5>
                    <p class="text-[10px] font-bold text-emerald-600 uppercase tracking-widest mt-0.5">الاستجابة الفورية</p>
                </div>
                <div class="mr-auto opacity-0 group-hover:opacity-100 transition-all">
                    <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </div>
            </a>

            <!-- Email Card -->
            <a href="mailto:support@madaaq.com" class="glass-panel border border-white/60 p-6 rounded-[2.5rem] flex items-center gap-6 group hover:scale-[1.02] transition-all shadow-xl block">
                <div class="w-16 h-16 bg-blue-500/10 rounded-2xl flex items-center justify-center text-blue-600 border border-blue-500/20 group-hover:bg-blue-500 group-hover:text-white transition-all">
                    <span class="text-3xl">📧</span>
                </div>
                <div>
                    <h5 class="text-sm font-black text-gray-900 uppercase">Email Protocol</h5>
                    <p class="text-[10px] font-bold text-blue-600 uppercase tracking-widest mt-0.5">المراسلات الرسمية</p>
                </div>
                <div class="mr-auto opacity-0 group-hover:opacity-100 transition-all">
                    <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </div>
            </a>

            <!-- Telegram Card -->
            <div class="glass-panel border border-white/60 p-6 rounded-[2.5rem] flex items-center gap-6 group hover:scale-[1.02] transition-all shadow-xl">
                <div class="w-16 h-16 bg-sky-500/10 rounded-2xl flex items-center justify-center text-sky-600 border border-sky-500/20 group-hover:bg-sky-500 group-hover:text-white transition-all">
                    <span class="text-3xl">✈️</span>
                </div>
                <div>
                    <h5 class="text-sm font-black text-gray-900 uppercase">Telegram Hub</h5>
                    <p class="text-[10px] font-bold text-sky-600 uppercase tracking-widest mt-0.5">قناة التحديثات</p>
                </div>
            </div>
        </div>

        <!-- Support Inquiry Form -->
        <div class="lg:col-span-2">
            <div class="glass-panel border border-white/40 rounded-[3rem] p-8 md:p-12 shadow-2xl relative overflow-hidden h-full">
                <div class="relative z-10">
                    <div class="flex items-center gap-4 mb-10">
                        <div class="w-12 h-12 bg-indigo-600/10 rounded-2xl flex items-center justify-center text-indigo-600">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                        </div>
                        <h3 class="text-2xl font-black text-gray-900 tracking-tight uppercase">إرسال طلب مساعدة</h3>
                    </div>

                    <form action="{{ route('support.send') }}" method="POST" class="space-y-8">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mr-4">نوع الطلب</label>
                                <select name="subject" class="w-full bg-white/50 backdrop-blur-md border border-white/80 rounded-2xl px-6 py-4 outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-bold text-gray-900">
                                    <option value="technical">مشكلة تقنية في السيرفر</option>
                                    <option value="accounting">استفسار مالي أو فاتورة</option>
                                    <option value="feature">اقتراح ميزة جديدة</option>
                                    <option value="other">أخرى</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mr-4">رقم الموبايل للتواصل</label>
                                <input type="text" value="{{ auth()->user()->phone ?? '' }}" class="w-full bg-white/50 backdrop-blur-md border border-white/80 rounded-2xl px-6 py-4 outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-mono font-bold text-gray-900" placeholder="+963 ...">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mr-4">تفاصيل الاستفسار</label>
                            <textarea name="message" rows="5" class="w-full bg-white/50 backdrop-blur-md border border-white/80 rounded-3xl px-6 py-4 outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-medium text-gray-900" placeholder="كيف يمكننا مساعدتك اليوم؟"></textarea>
                        </div>

                        <div class="pt-4">
                            <button type="submit" class="w-full py-5 bg-indigo-600 text-white rounded-[2rem] font-black text-xs uppercase tracking-[0.2em] shadow-2xl shadow-indigo-200 hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-4">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                إرسال إلى مركز العمليات
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Decorative Element -->
                <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-indigo-600/5 rounded-full blur-3xl"></div>
            </div>
        </div>
    </div>
</div>
@endsection
