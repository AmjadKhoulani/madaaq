<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'MadaaQ') }} - Pro Portal</title>

    <!-- Google Font - Rubik & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body { 
            font-family: 'Rubik', 'Inter', sans-serif; 
        }
        .bg-premium-light {
            background-color: #f8fafc;
            background-image: 
                radial-gradient(at 0% 0%, rgba(124, 58, 237, 0.05) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(37, 99, 235, 0.05) 0px, transparent 50%);
            position: relative;
            overflow-x: hidden;
        }
        .brand-gradient {
            background: linear-gradient(135deg, #7c3aed 0%, #2563eb 100%);
        }
    </style>
</head>
<body class="bg-premium-light text-slate-900 antialiased selection:bg-indigo-100">
    <div class="min-h-screen flex flex-col items-center justify-center p-6 sm:p-12">
        <div class="mb-10 text-center transform transition-all duration-500 hover:scale-105">
            <a href="/" class="inline-flex flex-col items-center gap-4">
                <div class="w-16 h-16 brand-gradient rounded-3xl flex items-center justify-center shadow-xl shadow-indigo-200">
                    <svg class="w-10 h-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <div class="space-y-1">
                    <h1 class="text-4xl font-black brand-gradient bg-clip-text text-transparent tracking-tight uppercase">MadaaQ</h1>
                    <p class="text-slate-400 text-[10px] font-bold tracking-[0.3em] uppercase">ISP Management Experience</p>
                </div>
            </a>
        </div>

        <div class="w-full sm:max-w-md">
            @yield('content')
        </div>

        <div class="mt-12 text-slate-400 text-xs font-medium">
            &copy; {{ date('Y') }} MadaaQ Pro. <span class="mx-1 opacity-30">|</span> Powered by High Speed Tech
        </div>
    </div>
</body>
</html>
