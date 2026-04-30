@extends('layouts.admin')

@section('title', 'بروتوكول الدعم الفني | Handshake Support Hub')

@section('content')
<div class="space-y-12 pb-24">
    
    <!-- Radiant Hub Header -->
    <div class="glass-panel p-12 rounded-[3.5rem] relative overflow-hidden !bg-slate-900 text-white border-white/5 shadow-neon-purple/20">
        <div class="absolute top-0 right-0 w-96 h-96 bg-accent-gradient opacity-10 blur-3xl -mr-48 -mt-48"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-neon-cyan opacity-5 blur-3xl -ml-32 -mb-32"></div>
        
        <div class="relative flex flex-col md:flex-row items-center gap-12 z-10">
            <div class="w-28 h-28 bg-accent-flow rounded-[2.5rem] flex items-center justify-center text-white shadow-glow-purple group hover:scale-110 transition-transform duration-500">
                <span class="material-symbols-outlined text-5xl group-hover:rotate-180 transition-transform duration-700">support_agent</span>
            </div>
            <div class="text-center md:text-right space-y-3">
                <div class="flex items-center justify-center md:justify-start gap-3">
                    <span class="w-10 h-1 bg-neon-cyan rounded-full"></span>
                    <p class="text-[10px] font-black text-neon-cyan uppercase tracking-[0.4em] font-headline">Operations Command Center</p>
                </div>
                <h1 class="text-5xl font-black tracking-tighter italic uppercase">بروتوكول الدعم والمساندة</h1>
                <p class="text-slate-400 font-bold uppercase tracking-widest text-[11px] font-headline opacity-80">Synchronized Technical Handshake & Infrastructure Assistance Matrix</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        <!-- Contact Channel Matrix -->
        <div class="lg:col-span-4 space-y-8">
            <div class="px-6">
                <h4 class="text-[11px] font-black text-slate-400 uppercase tracking-[0.3em] italic mb-6">قنوات الاتصال المباشر</h4>
            </div>
            
            <div class="space-y-6">
                <!-- WhatsApp Channel -->
                <a href="https://wa.me/963951555555" target="_blank" class="glass-panel p-8 rounded-[2.5rem] flex items-center gap-6 group hover:translate-x-[-10px] transition-all !bg-white/60">
                    <div class="w-16 h-16 bg-emerald-500/10 rounded-2xl flex items-center justify-center text-emerald-600 border border-emerald-500/20 shadow-sm group-hover:bg-emerald-500 group-hover:text-white transition-all">
                        <span class="material-symbols-outlined text-3xl">chat</span>
                    </div>
                    <div class="flex-1">
                        <h5 class="text-xs font-black text-slate-900 uppercase italic tracking-tighter">Handshake: WhatsApp</h5>
                        <p class="text-[9px] font-black text-emerald-600 uppercase tracking-widest mt-1 opacity-70 italic">الاستجابة الفورية</p>
                    </div>
                    <span class="material-symbols-outlined text-slate-300 group-hover:text-emerald-500 transition-colors">arrow_back_ios</span>
                </a>

                <!-- Email Protocol -->
                <a href="mailto:support@madaaq.com" class="glass-panel p-8 rounded-[2.5rem] flex items-center gap-6 group hover:translate-x-[-10px] transition-all !bg-white/60">
                    <div class="w-16 h-16 bg-blue-500/10 rounded-2xl flex items-center justify-center text-blue-600 border border-blue-500/20 shadow-sm group-hover:bg-blue-500 group-hover:text-white transition-all">
                        <span class="material-symbols-outlined text-3xl">alternate_email</span>
                    </div>
                    <div class="flex-1">
                        <h5 class="text-xs font-black text-slate-900 uppercase italic tracking-tighter">Official Correspondence</h5>
                        <p class="text-[9px] font-black text-blue-600 uppercase tracking-widest mt-1 opacity-70 italic">الدعم الرسمي</p>
                    </div>
                    <span class="material-symbols-outlined text-slate-300 group-hover:text-blue-500 transition-colors">arrow_back_ios</span>
                </a>

                <!-- Telegram Hub -->
                <div class="glass-panel p-8 rounded-[2.5rem] flex items-center gap-6 group hover:translate-x-[-10px] transition-all !bg-white/60">
                    <div class="w-16 h-16 bg-neon-cyan/10 rounded-2xl flex items-center justify-center text-neon-cyan border border-neon-cyan/20 shadow-sm group-hover:bg-neon-cyan group-hover:text-white transition-all">
                        <span class="material-symbols-outlined text-3xl">send</span>
                    </div>
                    <div class="flex-1">
                        <h5 class="text-xs font-black text-slate-900 uppercase italic tracking-tighter">Telegram Update Node</h5>
                        <p class="text-[9px] font-black text-neon-cyan uppercase tracking-widest mt-1 opacity-70 italic">قناة التحديثات</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inquiry Transmission Matrix -->
        <div class="lg:col-span-8">
            <div class="glass-panel p-12 rounded-[3.5rem] !bg-white/80 border-slate-100 shadow-2xl relative overflow-hidden group">
                <div class="absolute -bottom-24 -right-24 w-64 h-64 bg-primary/5 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700"></div>
                
                <div class="relative z-10">
                    <div class="flex items-center gap-6 mb-12">
                        <div class="w-14 h-14 bg-primary/10 rounded-2xl flex items-center justify-center text-primary border border-primary/20 shadow-inner">
                            <span class="material-symbols-outlined text-3xl">mark_as_unread</span>
                        </div>
                        <div class="space-y-1">
                            <h3 class="text-2xl font-black text-slate-900 uppercase tracking-tighter italic">إرسال طلب استعلام فني</h3>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] italic">Transmitting Operational Inquiry Packet to Base Matrix</p>
                        </div>
                    </div>

                    <form action="{{ route('support.send') }}" method="POST" class="space-y-10">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pr-4 italic">بروتوكول الطلب</label>
                                <div class="relative group">
                                    <select name="subject" class="input-radiant !py-4 pr-12 text-[11px] font-black uppercase italic appearance-none !bg-white">
                                        <option value="technical">Technical Infrastructure breach - مشكلة تقنية</option>
                                        <option value="accounting">Fiscal Ledger Discrepancy - استفسار مالي</option>
                                        <option value="feature">Architectural Enhancement - اقتراح ميزة</option>
                                        <option value="other">General Protocol Error - أخرى</option>
                                    </select>
                                    <span class="material-symbols-outlined absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 pointer-events-none group-focus-within:text-primary">expand_more</span>
                                </div>
                            </div>
                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pr-4 italic">رقم التواصل المعتمد</label>
                                <div class="relative">
                                    <input type="text" name="phone" value="{{ auth()->user()->phone ?? '' }}" class="input-radiant !py-4 pr-12 text-[11px] font-black font-manrope tracking-[0.2em] !bg-white" placeholder="+963 ...">
                                    <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-slate-300">phone_iphone</span>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pr-4 italic">بيانات الاستفسار التفصيلية</label>
                            <textarea name="message" rows="6" class="input-radiant !p-8 text-sm font-bold text-slate-700 !bg-white leading-relaxed placeholder:text-slate-300" placeholder="ما هي طبيعة التحدي الذي تواجهه في المنظومة حالياً؟"></textarea>
                        </div>

                        <div class="pt-6">
                            <button type="submit" class="w-full py-6 bg-slate-900 text-white rounded-[2.5rem] font-black text-xs uppercase tracking-[0.4em] shadow-glow-purple hover:scale-[1.03] active:scale-[0.97] transition-all flex items-center justify-center gap-6 italic group">
                                <span class="material-symbols-outlined text-2xl group-hover:translate-x-[-10px] transition-transform">rocket_launch</span>
                                إرسال إلى مركز العمليات
                                <span class="w-4 h-1 bg-neon-cyan rounded-full animate-pulse shadow-glow-cyan"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
