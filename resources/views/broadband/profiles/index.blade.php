@extends('layouts.app')

@section('content')
<div class="sm:flex sm:items-center sm:justify-between mb-8">
    <div>
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">باقات البرودباند</h2>
        <p class="mt-1 text-sm text-gray-500">إدارة باقات PPPoE والتحكم بالسرعات والأسعار.</p>
    </div>
    <div class="mt-4 sm:ml-4 sm:mt-0 sm:flex-none">
        <a href="{{ route('broadband.profiles.create') }}" class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-colors">
            <span class="flex items-center gap-2">
                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z"/></svg>
                إضافة باقة جديدة
            </span>
        </a>
    </div>
</div>

<div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
    @if($profiles->count() > 0)
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-300">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="py-3.5 pl-4 pr-4 text-right text-sm font-semibold text-gray-900 sm:pr-6">اسم الباقة</th>
                    <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">السرعة (Download/Upload)</th>
                    <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">سعة التحميل</th>
                    <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">المدة</th>
                    <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">السعر</th>
                    <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">الأجهزة المستهدفة</th>
                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                        <span class="sr-only">إجراءات</span>
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                @foreach($profiles as $profile)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="whitespace-nowrap py-4 pl-4 pr-4 text-sm font-medium text-gray-900 sm:pr-6">
                        <div class="flex items-center gap-3">
                            <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </div>
                            {{ $profile->name }}
                        </div>
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500" dir="ltr">
                        <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">{{ $profile->speed_down }}M</span>
                        <span class="text-gray-400 mx-1">/</span>
                        <span class="inline-flex items-center rounded-md bg-yellow-50 px-2 py-1 text-xs font-medium text-yellow-800 ring-1 ring-inset ring-yellow-600/20">{{ $profile->speed_up }}M</span>
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                        @if($profile->data_limit_mb)
                            {{ number_format($profile->data_limit_mb / 1024, 1) }} GB
                        @else
                            <span class="text-gray-400">غير محدود</span>
                        @endif
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                        @if($profile->duration_days)
                            {{ $profile->duration_days }} يوم
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm font-bold text-gray-900">
                        {{ number_format($profile->price, 0) }} {{ $currency ?? 'ر.ي' }}
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                        @php
                            $targetCount = $profile->routers->count() + $profile->mikrotikServers->count();
                        @endphp
                        @if($targetCount > 0)
                            <span class="inline-flex items-center rounded-full bg-indigo-50 px-2 py-1 text-xs font-medium text-indigo-700 ring-1 ring-inset ring-indigo-700/10">
                                {{ $targetCount }} أجهزة
                            </span>
                        @else
                           <span class="text-gray-400 text-xs">غير محدد</span>
                        @endif
                    </td>
                    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-left text-sm font-medium sm:pr-6">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('broadband.profiles.edit', $profile) }}" class="text-indigo-600 hover:text-indigo-900 p-1 rounded hover:bg-gray-100 transition" title="تعديل">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <form action="{{ route('broadband.profiles.destroy', $profile) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذه الباقة؟ سيتم إزالتها من المايكروتيك أيضاً.')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 p-1 rounded hover:bg-gray-100 transition" title="حذف">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
            <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
        </svg>
        <h3 class="mt-2 text-sm font-semibold text-gray-900">لا توجد باقات</h3>
        <p class="mt-1 text-sm text-gray-500">ابدأ بإنشاء باقة برودباند جديدة.</p>
        <div class="mt-6">
            <a href="{{ route('broadband.profiles.create') }}" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z"/></svg>
                باقة جديدة
            </a>
        </div>
    </div>
    @endif
</div>
@endsection
