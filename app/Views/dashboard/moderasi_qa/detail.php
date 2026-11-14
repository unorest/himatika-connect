<?= $this->extend('layout/admin') ?>

<?= $this->section('content') ?>

<div class="max-w-4xl mx-auto space-y-6">

    <!-- Back Button -->
    <a href="<?= site_url('dashboard/moderasi-qa') ?>" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-brand-600 transition">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Antrean Moderasi
    </a>

    <!-- Detail Aspirasi Card -->
    <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-sm space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-3 pb-4 border-b border-slate-100">
            <div class="flex items-center gap-2">
                <?php if ($question['status'] === 'answered'): ?>
                    <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-extrabold inline-flex items-center gap-1.5">
                        <i class="fa-solid fa-circle-check"></i> Sudah Terjawab Resmi
                    </span>
                <?php elseif ($question['status'] === 'pending'): ?>
                    <span class="px-3 py-1 rounded-full bg-amber-100 text-amber-800 text-xs font-extrabold">
                        Menunggu Tanggapan Pengurus
                    </span>
                <?php else: ?>
                    <span class="px-3 py-1 rounded-full bg-rose-100 text-rose-800 text-xs font-extrabold">
                        Ditolak / Arsip
                    </span>
                <?php endif; ?>

                <?php if (!empty($question['category_name'])): ?>
                    <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-semibold">
                        <?= esc($question['category_name']) ?>
                    </span>
                <?php endif; ?>
            </div>

            <div class="text-xs text-slate-500 font-bold flex items-center gap-1.5">
                <i class="fa-solid fa-thumbs-up text-brand-600"></i>
                <span><?= esc($question['upvotes']) ?> Dukungan Mahasiswa</span>
            </div>
        </div>

        <div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 leading-snug">
                <?= esc($question['title']) ?>
            </h1>
            <p class="text-sm text-slate-700 mt-4 leading-relaxed whitespace-pre-line bg-slate-50 p-4 rounded-2xl border border-slate-200/80">
                <?= esc($question['body']) ?>
            </p>
        </div>

        <!-- Audit Metadata Pengirim -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 p-4 rounded-2xl bg-slate-100/70 text-xs border border-slate-200/60">
            <div>
                <span class="text-slate-400 block text-[10px] font-bold uppercase">Nama Pengirim</span>
                <span class="font-bold text-slate-800 mt-0.5 block">
                    <?= $question['is_anonymous'] ? 'Mahasiswa Anonim' : esc($question['author_name']) ?>
                </span>
            </div>
            <div>
                <span class="text-slate-400 block text-[10px] font-bold uppercase">NIM Pengirim</span>
                <span class="font-bold text-slate-800 mt-0.5 block">
                    <?= !empty($question['author_nim']) ? esc($question['author_nim']) : '-' ?>
                </span>
            </div>
            <div>
                <span class="text-slate-400 block text-[10px] font-bold uppercase">Email Pengirim</span>
                <span class="font-bold text-slate-800 mt-0.5 block truncate">
                    <?= !empty($question['author_email']) ? esc($question['author_email']) : '-' ?>
                </span>
            </div>
            <div>
                <span class="text-slate-400 block text-[10px] font-bold uppercase">Waktu Pengajuan</span>
                <span class="font-bold text-slate-800 mt-0.5 block">
                    <?= date('d M Y, H:i', strtotime($question['created_at'])) ?>
                </span>
            </div>
        </div>
    </div>

    <!-- Riwayat Jawaban Sebelumnya (jika ada) -->
    <?php if (!empty($question['answers'])): ?>
        <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-sm space-y-4">
            <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-clock-rotate-left text-brand-600"></i>
                Jawaban Resmi yang Sedang Tayang di Portal
            </h3>

            <?php foreach ($question['answers'] as $ans): ?>
                <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-xs space-y-2">
                    <div class="flex items-center justify-between font-bold text-emerald-900">
                        <span class="flex items-center gap-1.5">
                            <i class="fa-solid fa-user-shield"></i>
                            <?= esc($ans['responder_name'] ?? 'Pengurus') ?> (<?= esc($ans['division_name'] ?? 'Divisi') ?>)
                        </span>
                        <span class="text-emerald-700 text-[10px] font-normal"><?= date('d M Y, H:i', strtotime($ans['created_at'])) ?></span>
                    </div>
                    <p class="text-slate-800 leading-relaxed whitespace-pre-line text-sm"><?= esc($ans['body']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Formulir Memberikan Tanggapan Resmi -->
    <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-sm space-y-6">
        <div>
            <span class="text-xs font-bold text-brand-600 uppercase tracking-widest block">Formulir Verifikasi</span>
            <h2 class="text-lg font-bold text-slate-900 mt-0.5">Beri Tanggapan / Publikasikan Jawaban Resmi</h2>
            <p class="text-xs text-slate-500 mt-1">Jawaban akan otomatis tayang di portal publik dengan tanda verifikasi resmi pengurus.</p>
        </div>

        <form action="<?= site_url('dashboard/moderasi-qa/answer') ?>" method="post" class="space-y-4">
            <?= csrf_field() ?>
            <input type="hidden" name="question_id" value="<?= $question['id'] ?>">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Tindakan Moderasi</label>
                    <select name="status" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-bold focus:outline-none focus:ring-2 focus:ring-brand-500 bg-white">
                        <option value="answered" <?= $question['status'] === 'answered' ? 'selected' : '' ?>>
                            ✓ Publikasikan Jawaban (Terjawab Resmi)
                        </option>
                        <option value="rejected" <?= $question['status'] === 'rejected' ? 'selected' : '' ?>>
                            ✕ Tolak / Tandai sebagai Spam (Tidak Tayang)
                        </option>
                    </select>
                </div>

                <div class="flex items-end pb-2">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_pinned" value="1" <?= $question['is_pinned'] ? 'checked' : '' ?> class="rounded text-brand-600 focus:ring-brand-500 w-4 h-4">
                        <span class="text-xs font-bold text-slate-700">Sematkan Pertanyaan di Halaman Utama (Pinned)</span>
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                    Teks Tanggapan / Jawaban Resmi <span class="text-rose-500">*</span>
                </label>
                <textarea name="body" rows="6" placeholder="Tuliskan jawaban yang komprehensif, santun, solutif, dan mengacu pada regulasi atau birokrasi kampus..." class="w-full p-4 rounded-2xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 leading-relaxed"></textarea>
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                <a href="<?= site_url('dashboard/moderasi-qa') ?>" class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs rounded-xl shadow-md shadow-brand-500/20 transition flex items-center gap-2">
                    <i class="fa-solid fa-paper-plane text-xs"></i>
                    <span>Simpan & Publikasikan Jawaban</span>
                </button>
            </div>
        </form>
    </div>

</div>

<?= $this->endSection() ?>
