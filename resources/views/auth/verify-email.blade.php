@extends('layouts.guest')

@section('content')
<div class="max-w-md w-full space-y-8 bg-white p-8 rounded-2xl shadow-xl text-center">
    <div>
        <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
            تأكيد البريد الإلكتروني
        </h2>
        <p class="mt-2 text-sm text-gray-600">
            شكراً لتسجيلك! قبل البدء، هل يمكننا التحقق من عنوان بريدك الإلكتروني من خلال النقر على الرابط الذي أرسلناه إليك للتو؟ إذا لم تتلق البريد الإلكتروني، سنقوم بإرسال واحد آخر لك بكل سرور.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="bg-green-50 text-green-700 p-4 rounded-lg mb-6 border border-green-100">
            تم إرسال رابط تحقق جديد إلى عنوان البريد الإلكتروني الذي قدمته أثناء التسجيل.
        </div>
    @endif

    <div class="mt-8 flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="text-white bg-indigo-600 hover:bg-indigo-700 font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition">
                إعادة إرسال بريد التحقق
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-sm text-gray-600 hover:text-gray-900 underline">
                تسجيل الخروج
            </button>
        </form>
    </div>
</div>
@endsection
