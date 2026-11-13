@props(['target', 'type'])

@php
    $comments = $target->revisionComments()->with('user.lecturer')->oldest()->get();
    $status = $target->verification_status ?? $target->status;
    $isRevision = in_array($status, ['revision']);
    $user = auth()->user();
    $isOwner = ($type === 'incentive') 
        ? ($user->lecturer && $target->lecturer_id === $user->lecturer->id)
        : ($target->user_id === $user->id);
    $canManage = $user->isVerifikator() || $user->isAdminLppm();
@endphp

<div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden mt-6">
    <!-- Header Box -->
    <div class="p-4 sm:p-5 bg-slate-50/70 border-b border-slate-100 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                </svg>
            </div>
            <div>
                <h3 class="text-sm sm:text-base font-bold text-slate-900">Catatan Revisi &amp; Dialog Verifikasi</h3>
                <p class="text-sm text-slate-500">Komunikasi dua arah dan riwayat berkas perbaikan antara Verifikator LPPM dan Dosen pengusul.</p>
            </div>
        </div>
        <span class="px-2.5 py-1 rounded-full text-sm font-bold {{ $isRevision ? 'bg-amber-100 text-amber-800 border border-amber-300' : 'bg-slate-100 text-slate-600' }}">
            {{ $comments->count() }} Catatan
        </span>
    </div>

    <!-- Alert Status Revisi -->
    @if($isRevision)
        <div class="p-4 bg-amber-50/80 border-b border-amber-200 flex items-start gap-3">
            <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <div class="text-sm text-amber-900">
                <span class="font-bold">Perhatian: Usulan Ini Memerlukan Perbaikan Berkas!</span>
                <p class="mt-0.5 text-sm text-amber-800 leading-relaxed">
                    Silakan baca catatan verifikator di bawah, berikan penjelasan tanggapan atau unggah berkas perbaikan, lalu kirimkan kembali untuk diajukan ulang ke antrian verifikator LPPM.
                </p>
            </div>
        </div>
    @endif

    <!-- Timeline Body -->
    <div class="p-4 sm:p-6 space-y-4">
        @if($comments->isEmpty())
            <div class="text-center py-6 text-slate-400">
                <svg class="w-10 h-10 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                </svg>
                <p class="text-sm text-slate-500 font-medium">Belum ada catatan revisi pada usulan ini.</p>
                <p class="text-[11px] text-slate-400 mt-0.5">Semua komunikasi perbaikan berkas akan tercatat di sini secara transparan.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($comments as $comment)
                    @php
                        $isMe = $comment->user_id === auth()->id();
                        $commentUser = $comment->user;
                        $roleLabel = match($commentUser->role) {
                            'admin_lppm' => 'Admin LPPM',
                            'verifikator' => 'Verifikator LPPM',
                            default => 'Dosen Pengusul',
                        };
                        $roleColor = match($commentUser->role) {
                            'admin_lppm' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                            'verifikator' => 'bg-amber-50 text-amber-700 border-amber-200',
                            default => 'bg-blue-50 text-blue-700 border-blue-200',
                        };
                    @endphp
                    <div class="p-4 rounded-2xl border {{ $isMe ? 'bg-blue-50/40 border-blue-200/80 ml-0 sm:ml-6' : 'bg-slate-50/70 border-slate-200 mr-0 sm:mr-6' }} space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                @if($commentUser->photo_url)
                                    <img src="{{ $commentUser->photo_url }}" alt="{{ $commentUser->name }}" class="w-7 h-7 rounded-full object-cover">
                                @else
                                    <div class="w-7 h-7 rounded-full bg-slate-700 text-white font-bold text-[10px] flex items-center justify-center">
                                        {{ $commentUser->initials() }}
                                    </div>
                                @endif
                                <div>
                                    <span class="font-bold text-sm text-slate-900">{{ $commentUser->name }}</span>
                                    <span class="ml-1.5 px-2 py-0.5 rounded text-[10px] font-bold border {{ $roleColor }}">
                                        {{ $roleLabel }}
                                    </span>
                                </div>
                            </div>
                            <span class="text-[11px] text-slate-400">
                                {{ $comment->created_at->diffForHumans() }} ({{ $comment->created_at->format('d/m/Y H:i') }})
                            </span>
                        </div>

                        <!-- Comment Message -->
                        <div class="text-sm text-slate-700 leading-relaxed whitespace-pre-line pl-9">
                            {{ $comment->comment }}
                        </div>

                        <!-- Attachment File (If any) -->
                        @if($comment->attachment_path)
                            <div class="pl-9 pt-1">
                                <a href="{{ route('revisi.lampiran', $comment->id) }}"
                                   class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-white border border-slate-200 text-blue-600 hover:text-blue-800 hover:border-blue-400 font-semibold text-sm shadow-2xs transition">
                                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                    </svg>
                                    <span class="truncate max-w-xs">{{ $comment->attachment_name }}</span>
                                    @if($comment->attachment_size)
                                        <span class="text-[10px] text-slate-400 font-normal">({{ $comment->attachment_size }})</span>
                                    @endif
                                </a>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Form Tambah Catatan / Tanggapan Revisi -->
        <div class="pt-5 border-t border-slate-100">
            <h4 class="font-bold text-sm text-slate-900 mb-2">
                {{ $isOwner ? 'Kirim Tanggapan atau Unggah Berkas Perbaikan' : 'Tulis Catatan Verifikator' }}
            </h4>
            <form method="POST" action="{{ route('revisi.komentar.store') }}" enctype="multipart/form-data" class="space-y-3.5">
                @csrf
                <input type="hidden" name="verifiable_type" value="{{ $type }}">
                <input type="hidden" name="verifiable_id" value="{{ $target->id }}">

                <div>
                    <textarea name="comment" rows="3" required
                              placeholder="{{ $isOwner ? 'Tulis penjelasan revisi yang telah Anda lakukan, klarifikasi isi usulan, atau rincian berkas yang dilampirkan...' : 'Tuliskan poin-poin yang harus diperbaiki oleh dosen pengusul secara spesifik...' }}"
                              class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-hidden transition"></textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 items-center">
                    <div>
                        <label class="block text-sm font-semibold text-slate-600 mb-1">Unggah Lampiran Berkas Revisi (Opsional)</label>
                        <input type="file" name="attachment" accept=".pdf,.doc,.docx,.zip,.png,.jpg,.jpeg"
                               class="w-full px-3 py-1.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-700 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:bg-slate-700 file:text-white file:text-sm file:font-semibold hover:file:bg-slate-800 transition">
                        <span class="text-[10px] text-slate-400">PDF, Word DOCX, ZIP, Gambar (Maks. 10 MB).</span>
                    </div>

                    <div class="flex items-center justify-end gap-2.5 pt-4 sm:pt-0">
                        @if($isOwner)
                            <button type="submit" name="just_comment" value="1"
                                    class="px-4 py-2 rounded-xl border border-slate-300 hover:bg-slate-50 text-slate-700 font-semibold text-sm transition cursor-pointer">
                                Kirim Tanggapan
                            </button>
                            @if($isRevision)
                                <button type="submit" name="mark_as_resubmitted" value="1"
                                        class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm shadow-md shadow-emerald-600/20 transition cursor-pointer flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>Kirim &amp; Ajukan Ulang</span>
                                </button>
                            @endif
                        @else
                            <button type="submit" name="just_comment" value="1"
                                    class="px-4 py-2 rounded-xl border border-slate-300 hover:bg-slate-50 text-slate-700 font-semibold text-sm transition cursor-pointer">
                                Kirim Catatan
                            </button>
                            @if(!$isRevision)
                                <button type="submit" name="mark_as_revision_request" value="1"
                                        class="px-4 py-2 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-bold text-sm shadow-md shadow-amber-600/20 transition cursor-pointer flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                    <span>Minta Revisi Berkas</span>
                                </button>
                            @endif
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
