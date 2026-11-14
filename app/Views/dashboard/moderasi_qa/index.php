<?= $this->extend('layout/admin') ?>

<?= $this->section('content') ?>

<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <span class="text-xs font-bold text-brand-600 uppercase tracking-widest block">Layanan Suara Mahasiswa</span>
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight mt-0.5">Moderasi Forum Aspirasi & Q&A</h1>
        <p class="text-xs text-slate-500 mt-1">Kelola, verifikasi, dan berikan tanggapan resmi atas pertanyaan mahasiswa umum</p>
    </div>
</div>

<!-- Status Tabs & Filter -->
<div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm space-y-4 mb-6">
    <!-- Tabs Navigation -->
    <div class="flex items-center gap-3 border-b border-slate-100 pb-4 text-xs font-bold overflow-x-auto">
        <a href="<?= site_url('dashboard/moderasi-qa?status=pending') ?>" class="px-4 py-2 rounded-xl transition flex items-center gap-2 <?= $activeStatus === 'pending' ? 'bg-amber-500 text-white shadow-md shadow-amber-500/20' : 'text-slate-500 hover:bg-slate-100' ?>">
            <i class="fa-regular fa-clock"></i>
            <span>Menunggu Respon</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] <?= $activeStatus === 'pending' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700' ?>">
                <?= esc($counts['pending']) ?>
            </span>
        </a>

        <a href="<?= site_url('dashboard/moderasi-qa?status=answered') ?>" class="px-4 py-2 rounded-xl transition flex items-center gap-2 <?= $activeStatus === 'answered' ? 'bg-emerald-600 text-white shadow-md shadow-emerald-500/20' : 'text-slate-500 hover:bg-slate-100' ?>">
            <i class="fa-solid fa-circle-check"></i>
            <span>Terjawab Resmi</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] <?= $activeStatus === 'answered' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700' ?>">
                <?= esc($counts['answered']) ?>
            </span>
        </a>

        <a href="<?= site_url('dashboard/moderasi-qa?status=rejected') ?>" class="px-4 py-2 rounded-xl transition flex items-center gap-2 <?= $activeStatus === 'rejected' ? 'bg-rose-600 text-white shadow-md shadow-rose-500/20' : 'text-slate-500 hover:bg-slate-100' ?>">
            <i class="fa-solid fa-ban"></i>
            <span>Ditolak / Arsip</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] <?= $activeStatus === 'rejected' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700' ?>">
                <?= esc($counts['rejected']) ?>
            </span>
        </a>
    </div>

    <!-- Search & Category Filter -->
    <form action="<?= site_url('dashboard/moderasi-qa') ?>" method="get" class="flex flex-col sm:flex-row gap-3 pt-1">
        <input type="hidden" name="status" value="<?= esc($activeStatus) ?>">
        <div class="relative flex-1">
            <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
            <input type="text" name="q" value="<?= esc($searchQuery ?? '') ?>" placeholder="Cari judul pertanyaan, isi, nama penanya..." class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-brand-500">
        </div>

        <select name="category" onchange="this.form.submit()" class="py-2.5 px-4 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500 bg-white text-slate-700">
            <option value="">Semua Kategori Topik</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= ($selectedCategory ?? 0) == $cat['id'] ? 'selected' : '' ?>>
                    <?= esc($cat['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit" class="px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs rounded-xl transition">
            Filter
        </button>
    </form>
</div>

<!-- List Aspirasi Mahasiswa -->
<div class="space-y-4">
    <?php if (!empty($questions)): ?>
        <?php foreach ($questions as $q): ?>
            <div class="bg-white rounded-2xl border border-slate-200 p-5 sm:p-6 hover:border-slate-300 transition shadow-sm space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                    <div class="space-y-2 flex-1">
                        <div class="flex flex-wrap items-center gap-2">
                            <?php if ($q['is_pinned']): ?>
                                <span class="px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800 text-[10px] font-extrabold inline-flex items-center gap-1">
                                    <i class="fa-solid fa-thumbtack"></i> Disematkan di Beranda
                                </span>
                            <?php endif; ?>

                            <?php if ($q['status'] === 'answered'): ?>
                                <span class="px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-extrabold">
                                    Terjawab Resmi
                                </span>
                            <?php elseif ($q['status'] === 'pending'): ?>
                                <span class="px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800 text-[10px] font-extrabold">
                                    Menunggu Tanggapan
                                </span>
                            <?php else: ?>
                                <span class="px-2.5 py-0.5 rounded-full bg-rose-100 text-rose-800 text-[10px] font-extrabold">
                                    Ditolak
                                </span>
                            <?php endif; ?>

                            <?php if (!empty($q['category_name'])): ?>
                                <span class="text-xs font-semibold text-slate-500">
                                    • <?= esc($q['category_name']) ?>
                                </span>
                            <?php endif; ?>
                        </div>

                        <h3 class="text-base font-bold text-slate-900 leading-snug">
                            <a href="<?= site_url('dashboard/moderasi-qa/detail/' . $q['id']) ?>" class="hover:text-brand-600 transition">
                                <?= esc($q['title']) ?>
                            </a>
                        </h3>

                        <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed">
                            <?= esc($q['body']) ?>
                        </p>
                    </div>

                    <!-- Counter Upvote Box -->
                    <div class="flex-shrink-0 flex items-center sm:flex-col justify-center px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-center">
                        <i class="fa-solid fa-thumbs-up text-xs text-brand-600"></i>
                        <span class="text-xs font-black text-slate-800 ml-1.5 sm:ml-0 sm:mt-1"><?= esc($q['upvotes']) ?></span>
                        <span class="text-[9px] text-slate-400 font-bold uppercase block">Dukungan</span>
                    </div>
                </div>

                <!-- Footer Info & Actions -->
                <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between text-xs text-slate-400 gap-3">
                    <div class="flex items-center gap-2">
                        <span>Diajukan:</span>
                        <strong class="text-slate-700">
                            <?= $q['is_anonymous'] ? 'Mahasiswa Anonim' : esc($q['author_name']) ?>
                            <?php if (!$q['is_anonymous'] && !empty($q['author_nim'])): ?>
                                (<?= esc($q['author_nim']) ?>)
                            <?php endif; ?>
                        </strong>
                        <span>•</span>
                        <span><?= date('d M Y, H:i', strtotime($q['created_at'])) ?></span>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center gap-2">
                        <!-- Toggle Pin -->
                        <form action="<?= site_url('dashboard/moderasi-qa/toggle-pin/' . $q['id']) ?>" method="post">
                            <?= csrf_field() ?>
                            <button type="submit" class="px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-100 transition" title="Sematkan di Beranda Publik">
                                <i class="fa-solid fa-thumbtack mr-1 <?= $q['is_pinned'] ? 'text-amber-500' : 'text-slate-400' ?>"></i>
                                <?= $q['is_pinned'] ? 'Lepas Pin' : 'Sematkan' ?>
                            </button>
                        </form>

                        <!-- Tombol Tinjau / Balas -->
                        <a href="<?= site_url('dashboard/moderasi-qa/detail/' . $q['id']) ?>" class="px-4 py-1.5 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold shadow-sm transition flex items-center gap-1.5">
                            <i class="fa-solid fa-reply"></i>
                            <span><?= $q['status'] === 'answered' ? 'Perbarui Balasan' : 'Beri Jawaban Resmi' ?></span>
                        </a>

                        <!-- Hapus -->
                        <form action="<?= site_url('dashboard/moderasi-qa/delete/' . $q['id']) ?>" method="post" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pertanyaan ini?')">
                            <?= csrf_field() ?>
                            <button type="submit" class="p-2 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition" title="Hapus">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center text-slate-400 space-y-3">
            <i class="fa-solid fa-clipboard-check text-4xl text-slate-300"></i>
            <h4 class="font-bold text-sm text-slate-700">Tidak ada pertanyaan pada status ini</h4>
            <p class="text-xs text-slate-500">Semua aspirasi mahasiswa pada filter ini telah tertangani dengan baik.</p>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
