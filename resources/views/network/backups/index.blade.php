@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">💾 النسخ الاحتياطية</h2>
            <p class="text-gray-500 mt-1">أرشيف النسخ الاحتياطية لجميع السيرفرات (آخر 10 أيام)</p>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
        <p class="text-green-800 font-semibold">{{ session('success') }}</p>
    </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-right">
                <thead class="bg-gray-50 text-gray-500 text-sm uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-3 font-medium">السيرفر</th>
                        <th class="px-6 py-3 font-medium">اسم الملف</th>
                        <th class="px-6 py-3 font-medium">النوع</th>
                        <th class="px-6 py-3 font-medium">التاريخ</th>
                        <th class="px-6 py-3 font-medium">الحجم</th>
                        <th class="px-6 py-3 font-medium">تحميل</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($backups as $backup)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">{{ $backup->server->name ?? 'Deleted Server' }}</div>
                            <div class="text-xs text-gray-500">{{ $backup->server->ip ?? '' }}</div>
                        </td>
                        <td class="px-6 py-4 text-gray-700 font-mono text-sm">{{ $backup->filename }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded text-xs font-semibold {{ $backup->type == 'backup' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                {{ strtoupper($backup->type) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-500 text-sm" dir="ltr">
                            {{ $backup->created_at->format('Y-m-d H:i') }}
                            <div class="text-xs text-gray-400">{{ $backup->created_at->diffForHumans() }}</div>
                        </td>
                        <td class="px-6 py-4 text-gray-500 text-sm" dir="ltr">{{ round($backup->size / 1024 / 1024, 2) }} MB</td>
                        <td class="px-6 py-4">
                            <a href="{{ route('servers.backups.download', $backup) }}" class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-800 font-medium text-sm">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                تحميل
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                            <p>لا توجد نسخ احتياطية متاحة حتى الآن.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($backups->hasPages())
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
            {{ $backups->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
