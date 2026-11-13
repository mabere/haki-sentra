@extends('layouts.app', ['title' => 'Telaah Publikasi', 'header' => 'Lembar Verifikasi Publikasi Ilmiah'])

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">

        <!-- Header Navigation -->
        <div>
            <a href="{{ route('verification.publications.index') }}"
                class="text-sm font-semibold text-slate-500 hover:text-slate-800 flex items-center gap-1.5 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Kembali ke Antrian Verifikasi</span>
            </a>
            <h1 class="text-xl font-bold text-slate-900 mt-1">Telaah Keabsahan Publikasi Ilmiah</h1>
        </div>

        <!-- Dosen Pengusul Info Card -->
        <div
            class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div
                    class="h-12 w-12 rounded-full bg-indigo-50 text-indigo-700 flex items-center justify-center font-bold text-sm">
                    {{ $publication->user->initials() }}
                </div>
                <div>
                    <p class="text-sm text-slate-400 font-semibold uppercase tracking-wider">Dosen Pengusul</p>
                    <h3 class="text-sm font-bold text-slate-900">
                        {{ $publication->user->lecturer ? $publication->user->lecturer->full_name : $publication->user->name }}
                    </h3>
                    <p class="text-sm text-slate-500">
                        NIDN: {{ $publication->user->lecturer->nidn ?? '-' }} &bull;
                        {{ $publication->user->lecturer->department->name ?? '-' }}
                        ({{ $publication->user->lecturer->department->faculty->code ?? '' }})
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2 text-sm">
                @if ($publication->user->lecturer && $publication->user->lecturer->scopus_id)
                    <span class="px-2 py-1 rounded-lg bg-slate-100 text-slate-700 font-mono text-[11px]">
                        Scopus: {{ $publication->user->lecturer->scopus_id }}
                    </span>
                @endif
                @if ($publication->user->lecturer && $publication->user->lecturer->sinta_id)
                    <span class="px-2 py-1 rounded-lg bg-emerald-50 text-emerald-800 font-mono text-[11px]">
                        SINTA: {{ $publication->user->lecturer->sinta_id }}
                    </span>
                @endif
            </div>
        </div>

        <!-- Article Metadata Card -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
            <div class="flex items-start justify-between gap-4 border-b border-slate-100 pb-3">
                <div>
                    <span
                        class="inline-block px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-slate-100 text-slate-700 border border-slate-200 mb-1">
                        {{ str_replace('_', ' ', $publication->publication_type) }}
                    </span>
                    <h2 class="text-base font-bold text-slate-900 leading-snug">
                        {{ $publication->title }}
                    </h2>
                </div>
                <div class="shrink-0">
                    @if ($publication->quartile)
                        <span
                            class="px-2.5 py-1 rounded-lg text-sm font-extrabold bg-indigo-50 text-indigo-700 border border-indigo-200">
                            Scopus {{ $publication->quartile }}
                        </span>
                    @elseif($publication->sinta_grade)
                        <span
                            class="px-2.5 py-1 rounded-lg text-sm font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            {{ $publication->sinta_grade }}
                        </span>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
                <div>
                    <span class="text-slate-400 font-semibold block">Jurnal / Penerbit</span>
                    <span class="font-bold text-slate-800">{{ $publication->journal_name }}</span>
                </div>
                <div>
                    <span class="text-slate-400 font-semibold block">Tahun / Volume / Issue</span>
                    <span class="font-bold text-slate-800">
                        {{ $publication->publication_year }} &bull; Vol. {{ $publication->volume ?? '-' }}
                        ({{ $publication->issue ?? '-' }})
                    </span>
                </div>
                <div>
                    <span class="text-slate-400 font-semibold block">Tautan DOI</span>
                    @if ($publication->doi)
                        <a href="https://doi.org/{{ $publication->doi }}" target="_blank"
                            class="font-semibold text-blue-600 hover:underline">
                            {{ $publication->doi }} &rarr;
                        </a>
                    @else
                        <span class="text-slate-400">-</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Authors Table -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="p-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-700">Susunan Tim Penulis</h3>
                <span class="text-sm text-slate-500">{{ $publication->authors->count() }} Penulis</span>
            </div>
            <table class="w-full text-left text-sm text-slate-700">
                <thead class="bg-slate-50 text-[11px] font-semibold uppercase text-slate-500">
                    <tr>
                        <th class="py-2.5 px-4 w-12 text-center">Urutan</th>
                        <th class="py-2.5 px-4">Nama Penulis</th>
                        <th class="py-2.5 px-4">Peran Kepenulisan</th>
                        <th class="py-2.5 px-4">Kategori Afiliasi</th>
                        <th class="py-2.5 px-4">Institusi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($publication->authors as $author)
                        <tr>
                            <td class="py-2.5 px-4 text-center font-bold text-slate-400">#{{ $author->author_order }}</td>
                            <td class="py-2.5 px-4 font-semibold text-slate-900">{{ $author->author_name }}</td>
                            <td class="py-2.5 px-4">
                                @if ($author->author_role === 'first_author')
                                    <span
                                        class="px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-50 text-indigo-700">First
                                        Author</span>
                                @elseif($author->author_role === 'corresponding')
                                    <span
                                        class="px-2 py-0.5 rounded text-[10px] font-bold bg-purple-50 text-purple-700">Corresponding</span>
                                @else
                                    <span class="text-slate-500">Co-Author</span>
                                @endif
                            </td>
                            <td class="py-2.5 px-4">
                                @if ($author->author_type === 'internal_lecturer')
                                    <span class="text-emerald-700 font-medium">Dosen Internal</span>
                                @elseif($author->author_type === 'student')
                                    <span
                                        class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-700">Mahasiswa</span>
                                @else
                                    <span class="text-slate-600">Eksternal</span>
                                @endif
                            </td>
                            <td class="py-2.5 px-4 text-slate-600">{{ $author->affiliation ?? 'Universitas' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Documents Preview / Download -->
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div
                    class="h-10 w-10 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center font-bold text-sm">
                    PDF
                </div>
                <div>
                    <p class="text-sm font-bold text-slate-900">Naskah Lengkap Artikel Publikasi</p>
                    <p class="text-[11px] text-slate-500">Periksa isi naskah, kesesuaian afiliasi kampus, dan lampiran</p>
                </div>
            </div>

            @if ($publication->file_path)
                <a href="{{ route('verification.publications.file', [$publication, 'manuscript']) }}" target="_blank"
                    class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-semibold text-sm transition">
                    Lihat Naskah PDF &rarr;
                </a>
            @else
                <span class="text-sm text-rose-500 font-semibold italic">Dosen belum mengunggah naskah PDF</span>
            @endif
        </div>

        <!-- Verification Decision Form Card -->
        <div class="bg-white rounded-2xl border-2 border-amber-300 p-6 shadow-md space-y-4" x-data="{ decision: 'approve' }">
            <div class="flex items-center gap-2 border-b border-slate-100 pb-3">
                <span class="h-3 w-3 rounded-full bg-amber-500"></span>
                <h3 class="text-sm font-bold text-slate-900">Keputusan Validasi Verifikator LPPM</h3>
            </div>

            <form method="POST" action="{{ route('verification.publications.verify', $publication) }}" class="space-y-4">
                @csrf

                <!-- Choice Radio Buttons -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <label class="p-3.5 rounded-xl border-2 cursor-pointer transition flex items-center gap-3"
                        :class="decision === 'approve' ? 'border-emerald-500 bg-emerald-50/50 text-emerald-900' :
                            'border-slate-200 hover:bg-slate-50 text-slate-700'">
                        <input type="radio" name="action" value="approve" x-model="decision"
                            class="text-emerald-600 focus:ring-emerald-500">
                        <div>
                            <p class="text-sm font-bold">1. Setujui (Approve)</p>
                            <p class="text-[10px] text-slate-500">Berkas valid & sesuai ketentuan</p>
                        </div>
                    </label>

                    <label class="p-3.5 rounded-xl border-2 cursor-pointer transition flex items-center gap-3"
                        :class="decision === 'revision' ? 'border-amber-500 bg-amber-50/50 text-amber-900' :
                            'border-slate-200 hover:bg-slate-50 text-slate-700'">
                        <input type="radio" name="action" value="revision" x-model="decision"
                            class="text-amber-600 focus:ring-amber-500">
                        <div>
                            <p class="text-sm font-bold">2. Minta Revisi</p>
                            <p class="text-[10px] text-slate-500">Perlu perbaikan data/berkas</p>
                        </div>
                    </label>

                    <label class="p-3.5 rounded-xl border-2 cursor-pointer transition flex items-center gap-3"
                        :class="decision === 'reject' ? 'border-rose-500 bg-rose-50/50 text-rose-900' :
                            'border-slate-200 hover:bg-slate-50 text-slate-700'">
                        <input type="radio" name="action" value="reject" x-model="decision"
                            class="text-rose-600 focus:ring-rose-500">
                        <div>
                            <p class="text-sm font-bold">3. Tolak Publikasi</p>
                            <p class="text-[10px] text-slate-500">Tidak memenuhi syarat validasi</p>
                        </div>
                    </label>
                </div>

                <!-- Notes Textarea -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">
                        Catatan Verifikator / Alasan Revisi
                        <span x-show="decision !== 'approve'" class="text-rose-500">* (Wajib diisi)</span>
                    </label>
                    <textarea name="notes" rows="3"
                        placeholder="Tuliskan catatan perbaikan untuk dosen pengusul atau penjelasan persetujuan..."
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:bg-white transition">{{ old('notes', $publication->verification_note) }}</textarea>
                    @error('notes')
                        <p class="text-sm text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="submit"
                        class="px-6 py-2.5 rounded-xl text-white font-bold text-sm shadow-md transition cursor-pointer"
                        :class="decision === 'approve' ? 'bg-emerald-600 hover:bg-emerald-500 shadow-emerald-600/30' : (
                            decision === 'revision' ? 'bg-amber-600 hover:bg-amber-500 shadow-amber-600/30' :
                            'bg-rose-600 hover:bg-rose-500 shadow-rose-600/30')">
                        Kirim Keputusan Verifikasi
                    </button>
                </div>
            </form>
        </div>

        <!-- Verification Audit Log Timeline -->
        @if($publication->verificationLogs && $publication->verificationLogs->count() > 0)
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs space-y-3">
            <h3 class="text-sm font-bold uppercase tracking-wider text-slate-700 flex items-center gap-2">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Riwayat Telaah & Keputusan Verifikasi</span>
            </h3>

            <div class="space-y-3 divide-y divide-slate-100">
                @foreach($publication->verificationLogs as $log)
                <div class="pt-3 first:pt-0 flex items-start justify-between gap-3 text-sm">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-slate-900">{{ $log->reviewer->name ?? 'Sistem' }}</span>
                            <span class="text-slate-400">&bull;</span>
                            <span class="text-slate-500">{{ $log->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="mt-1 flex items-center gap-2 text-[11px]">
                            <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-600 font-medium uppercase">{{ $log->status_before }}</span>
                            <span class="text-slate-400">&rarr;</span>
                            <span class="px-2 py-0.5 rounded font-bold uppercase {{ $log->status_after === 'approved' ? 'bg-emerald-100 text-emerald-800' : ($log->status_after === 'revision' ? 'bg-amber-100 text-amber-800' : 'bg-rose-100 text-rose-800') }}">
                                {{ $log->status_after }}
                            </span>
                        </div>
                        <p class="text-slate-600 mt-1 italic">{{ $log->notes }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Dialog & Catatan Revisi Interaktif Verifikator <-> Dosen -->
        <x-revision-thread :target="$publication" type="publication" />

    </div>
@endsection
