<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MSDS Management</title>
    @vite('resources/css/app.css')
    <style>
        /* Custom Fluid Scrollbar untuk list dokumen */
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .custom-scrollbar:hover::-webkit-scrollbar-thumb { background: #94a3b8; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen font-sans text-slate-800 antialiased selection:bg-indigo-100 selection:text-indigo-900">

<div class="w-full max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-8">

    {{-- HEADER & CREATE SHOP ROW --}}
    <div class="flex flex-col lg:flex-row gap-6 items-stretch justify-between">

        {{-- TITLE --}}
        <div class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-slate-200 flex-1 flex items-center gap-4">
            <div class="p-2 bg-white rounded-xl shadow-sm border border-slate-200 shrink-0 flex items-center justify-center">
                <img src="{{ asset('img/logo_mkm.png') }}"
                     alt="MKM Logo"
                     class="h-10 w-auto object-contain">
            </div>
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight text-slate-900">MSDS Management</h1>
                <p class="text-sm text-slate-500 font-medium mt-1">Manage Material Safety Data Sheets across shops</p>
            </div>
        </div>

        {{-- CREATE SHOP FORM --}}
        <div class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-slate-200 flex-1 lg:max-w-md">
            <h2 class="text-sm font-bold text-slate-700 uppercase tracking-wider mb-4 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-indigo-500"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                Add New Shop
            </h2>
            <form action="{{ route('shop.store') }}" method="POST" class="flex flex-col sm:flex-row gap-3">
                @csrf
                <input type="text" name="name" placeholder="Enter shop name..." required
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-300 bg-slate-50 text-slate-900 placeholder-slate-400 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all duration-200 outline-none">

                <button class="shrink-0 bg-indigo-600 text-white font-semibold px-6 py-2.5 rounded-xl hover:bg-indigo-700 hover:shadow-lg hover:shadow-indigo-500/30 transition-all duration-200 active:scale-95">
                    Save
                </button>
            </form>
        </div>

    </div>

    {{-- SHOP LIST GRID --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 lg:gap-8">

        @foreach($shops as $shop)
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm flex flex-col hover:shadow-md transition-shadow duration-300 overflow-hidden group">

            {{-- HEADER CARD --}}
            <div class="bg-slate-50 border-b border-slate-100 p-5 flex justify-between items-center">
                <h3 class="font-bold text-lg text-slate-800 truncate pr-3">
                    <a href="{{ route('shop.show', $shop->id) }}" class="hover:text-indigo-600">
                        {{ $shop->name }}
                    </a>
                </h3>
                <div class="flex items-center gap-3">

                    {{-- QR CLICKABLE --}}
                    <a href="{{ url('/qr') }}?text={{ urlencode(route('shop.show', $shop->id)) }}"
                       download="qr-{{ $shop->name }}.svg"
                       class="bg-white p-1.5 rounded-lg border border-slate-200 shadow-sm hover:scale-105 transition"
                       title="Download QR">

                        {!! QrCode::format('svg')->size(50)->generate(route('shop.show', $shop->id)) !!}
                    </a>

                    {{-- COUNT --}}
                    <span class="shrink-0 inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-indigo-100 text-indigo-700">
                        {{ $shop->documents->count() }}
                    </span>

                </div>
            </div>



            {{-- UPLOAD MULTIPLE --}}
            <div class="p-5 border-b border-slate-100">
                <form action="{{ route('document.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-3">
                    @csrf
                    <input type="hidden" name="shop_id" value="{{ $shop->id }}">

                    <input type="file" name="files[]" multiple required
                        class="block w-full text-sm text-slate-500
                        file:mr-4 file:py-2.5 file:px-4
                        file:rounded-xl file:border-0
                        file:text-sm file:font-semibold
                        file:bg-slate-100 file:text-slate-700
                        hover:file:bg-slate-200 transition-colors cursor-pointer">

                    <button class="w-full flex justify-center items-center gap-2 bg-slate-800 text-white font-medium py-2.5 rounded-xl hover:bg-slate-700 transition-colors active:scale-[0.98]">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" /></svg>
                        Upload Files
                    </button>
                </form>
            </div>

            {{-- DOCUMENT LIST --}}
            <div class="p-5 flex-1 flex flex-col bg-white">
                <div class="flex-1 overflow-y-auto max-h-52 pr-2 space-y-2.5 custom-scrollbar">

                    @forelse($shop->documents as $doc)
                    <div class="group/item flex justify-between items-center bg-white border border-slate-200 p-3 rounded-xl hover:border-indigo-300 hover:shadow-sm transition-all duration-200">

                        {{-- FILE NAME --}}
                        <div class="flex items-center gap-3 overflow-hidden mr-2">
                            <div class="p-1.5 bg-emerald-50 text-emerald-600 rounded-lg shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4"><path fill-rule="evenodd" d="M4.498 13.535a.75.75 0 011.053-.198l1.455 1.05v-4.512a.75.75 0 011.5 0v4.512l1.455-1.05a.75.75 0 11.878 1.214l-2.84 2.05a.75.75 0 01-.878 0l-2.84-2.05a.75.75 0 01-.198-1.053zM10 2a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 2z" clip-rule="evenodd" /></svg>
                            </div>
                            <span class="text-sm font-medium text-slate-700 truncate group-hover/item:text-indigo-600 transition-colors" title="{{ $doc->file_name }}">
                                {{ $doc->file_name }}
                            </span>
                        </div>

                        {{-- ACTIONS --}}
                        <div class="flex items-center gap-1.5 shrink-0 opacity-100 sm:opacity-0 sm:group-hover/item:opacity-100 transition-opacity duration-200">

                            {{-- VIEW --}}
                            <a href="{{ asset('storage/'.$doc->file_path) }}" target="_blank"
                               class="p-1.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="View Document">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5"><path d="M10 12.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z" /><path fill-rule="evenodd" d="M.664 10.59a1.651 1.651 0 010-1.186A10.004 10.004 0 0110 3c4.257 0 7.874 2.62 9.336 6.404a1.651 1.651 0 010 1.186A10.004 10.004 0 0110 17c-4.257 0-7.874-2.62-9.336-6.404zM10 15a5 5 0 100-10 5 5 0 000 10z" clip-rule="evenodd" /></svg>
                            </a>

                            {{-- DELETE --}}
                            <form action="{{ route('document.delete', $doc->id) }}" method="POST" onsubmit="return confirm('Delete this file?')" class="flex">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Delete Document">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5"><path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.808a2.75 2.75 0 002.73-2.51l.841-10.52.149.023a.75.75 0 00.23-1.482A41.03 41.03 0 0014 4.193V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z" clip-rule="evenodd" /></svg>
                                </button>
                            </form>

                        </div>
                    </div>

                    @empty
                    <div class="flex flex-col items-center justify-center py-8 text-slate-400 h-full">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 mb-3 opacity-40"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                        <p class="text-sm font-medium">No documents uploaded</p>
                    </div>
                    @endforelse

                </div>
            </div>

        </div>
        @endforeach

    </div>

</div>

</body>
</html>