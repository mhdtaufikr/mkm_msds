<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $shop->name }} - MSDS</title>
    @vite('resources/css/app.css')
    <style>
        .dot-pattern {
            background-image: radial-gradient(rgba(255,255,255,0.08) 1px, transparent 1px);
            background-size: 20px 20px;
        }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #00677f40; border-radius: 99px; }
        ::-webkit-scrollbar-thumb:hover { background: #00677f80; }
        .doc-card { transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease; }
        .doc-card:hover { transform: translateY(-4px); }
        .color-bar { transform: scaleX(0); transform-origin: left; transition: transform 0.3s ease; }
        .doc-card:hover .color-bar { transform: scaleX(1); }
        .doc-icon { transition: background-color 0.3s ease; }
        .doc-card:hover .doc-icon { background-color: #0891b2; }
        .doc-icon svg { transition: color 0.3s ease; }
        .doc-card:hover .doc-icon svg { color: white; }
        .doc-title { transition: color 0.3s ease; }
        .doc-card:hover .doc-title { color: #0e7490; }
        .doc-btn { transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease; }
        .doc-card:hover .doc-btn { background-color: #0891b2; color: white; border-color: #0891b2; }
    </style>
</head>
<body class="bg-slate-100 min-h-screen font-sans antialiased text-slate-800">

    {{-- ===== HERO HEADER ===== --}}
    <div style="background: linear-gradient(135deg, #00677f 0%, #004a5c 40%, #003344 100%);">
        <div class="dot-pattern w-full">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">

                    {{-- Logo + Title --}}
                    <div class="flex items-center gap-5">
                        <div class="p-4 rounded-2xl border border-white/20 shadow-xl shrink-0 flex items-center justify-center"
                        style="background: rgba(255,255,255,0.15); backdrop-filter: blur(8px);">

                       <img src="{{ asset('img/logo_mkm.png') }}"
                            alt="MKM Logo"
                            class="h-9 w-auto object-contain">
                   </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] mb-1"
                               style="color: rgba(255,255,255,0.5);">
                                Material Safety Data Sheet
                            </p>

                            <h1 class="text-3xl sm:text-4xl font-black text-white tracking-tight leading-none">
                                {{ $shop->name }}
                            </h1>

                            {{-- SMALL LABEL --}}
                            <p class="text-xs text-white mt-1">
                                {{ $shop->documents->count() }} documents available
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-col items-center gap-2">

                        {{-- QR --}}
                        <div class="bg-white p-2 rounded-xl shadow border border-white/20">
                            {!! QrCode::format('svg')->size(80)->generate(url()->current()) !!}
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- ===== MAIN CONTENT ===== --}}
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pb-16 pt-4">
<br>
        {{-- Section Divider --}}
        <div class="flex items-center gap-3 mb-6">
            <div class="h-px flex-1 bg-slate-200"></div>
            <span class="text-xs font-bold text-slate-400 uppercase tracking-[0.2em]">Daftar Dokumen</span>
            <div class="h-px flex-1 bg-slate-200"></div>
        </div>

        {{-- Empty State --}}
        @if($shop->documents->isEmpty())
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm py-20 flex flex-col items-center justify-center text-center px-6">
                <div class="w-20 h-20 rounded-full bg-slate-100 flex items-center justify-center mb-5">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-slate-300">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-600 mb-1">Tidak ada dokumen</h3>
                <p class="text-slate-400 text-sm max-w-xs">
                    Belum ada file MSDS yang diunggah untuk shop ini. Hubungi administrator untuk informasi lebih lanjut.
                </p>
            </div>

        @else
            {{-- Document Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($shop->documents as $index => $doc)
                <div class="doc-card group bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-xl hover:border-cyan-300 flex flex-col overflow-hidden">

                    {{-- Top Color Bar --}}
                    <div class="color-bar h-1 w-full"
                         style="background: linear-gradient(to right, #00677f, #22d3ee, #2dd4bf);"></div>

                    {{-- Card Body --}}
                    <div class="p-5 flex-1 flex flex-col gap-4">

                        {{-- Icon + Number --}}
                        <div class="flex items-start justify-between">
                            <div class="doc-icon p-3 bg-cyan-50 rounded-xl">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.8" stroke="currentColor" class="w-7 h-7 text-cyan-600">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                </svg>
                            </div>
                            <span class="text-xs font-bold text-slate-300 tabular-nums">
                                #{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                            </span>
                        </div>

                        {{-- Title + Badge --}}
                        <div class="flex-1">
                            <h3 class="doc-title text-base font-bold text-slate-800 leading-snug break-words"
                                style="-webkit-line-clamp: 3; display: -webkit-box; -webkit-box-orient: vertical; overflow: hidden;"
                                title="{{ $doc->file_name }}">
                                {{ $doc->file_name }}
                            </h3>
                            <div class="mt-2">
                                <span class="inline-flex items-center gap-1 text-[10px] font-bold text-red-500 bg-red-50 px-2 py-0.5 rounded-full uppercase tracking-wider border border-red-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-2.5 h-2.5">
                                        <path fill-rule="evenodd" d="M4.5 2A1.5 1.5 0 003 3.5v13A1.5 1.5 0 004.5 18h11a1.5 1.5 0 001.5-1.5V7.621a1.5 1.5 0 00-.44-1.06l-4.12-4.122A1.5 1.5 0 0011.378 2H4.5zm4 9.5a.75.75 0 000 1.5h3a.75.75 0 000-1.5h-3zm-1-2.75a.75.75 0 01.75-.75h3.5a.75.75 0 010 1.5h-3.5a.75.75 0 01-.75-.75z" clip-rule="evenodd" />
                                    </svg>
                                    PDF
                                </span>
                            </div>
                        </div>

                        {{-- Action Button --}}
                        <a href="{{ asset('storage/'.$doc->file_path) }}" target="_blank"
                           class="doc-btn mt-auto w-full flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl text-sm font-semibold bg-slate-50 text-slate-600 border border-slate-200 active:scale-95">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                 stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Buka Dokumen
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        @endif

        {{-- Footer --}}
        <p class="text-center text-xs text-slate-400 mt-10">
            &copy; {{ date('Y') }} {{ $shop->name }} &mdash; Material Safety Data Sheet Portal
        </p>
    </div>

</body>
</html>