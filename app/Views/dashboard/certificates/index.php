<?= $this->extend('layout/admin') ?>

<?= $this->section('content') ?>

<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <span class="text-xs font-bold text-brand-600 uppercase tracking-widest block">Evaluasi & Layanan Anggota</span>
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight mt-0.5">Rekap Feedback & E-Sertifikat Kegiatan</h1>
        <p class="text-xs text-slate-500 mt-1">Pantau survei kepuasan peserta, masukan kegiatan, dan riwayat penerbitan sertifikat digital</p>
    </div>

    <div>
        <a href="<?= site_url('sertifikat') ?>" target="_blank" class="px-4 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs rounded-xl shadow-md shadow-brand-500/20 transition flex items-center gap-2">
            <i class="fa-solid fa-arrow-up-right-from-square"></i>
            <span>Buka Form Publik</span>
        </a>
    </div>
</div>

<!-- KPI Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Total Feedback Masuk</span>
        <div class="text-2xl font-black text-slate-900 mt-1"><?= $stats['total'] ?></div>
        <span class="text-[10px] text-slate-500 font-medium">Sertifikat berhasil diterbitkan</span>
    </div>

    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
        <span class="text-[11px] font-bold text-amber-500 uppercase tracking-wider block">Rata-Rata Kepuasan Peserta</span>
        <div class="text-2xl font-black text-amber-600 mt-1 flex items-center gap-1.5">
            <span><?= $stats['avg_rating'] ?></span>
            <span class="text-base text-amber-400"><i class="fa-solid fa-star"></i></span>
            <span class="text-xs text-slate-400 font-normal">/ 5.0</span>
        </div>
        <span class="text-[10px] text-slate-500 font-medium">Berdasarkan ulasan peserta</span>
    </div>

    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
        <span class="text-[11px] font-bold text-emerald-500 uppercase tracking-wider block">Total Unduhan PDF</span>
        <div class="text-2xl font-black text-emerald-600 mt-1"><?= $stats['total_downloads'] ?></div>
        <span class="text-[10px] text-slate-500 font-medium">Frekuensi download peserta</span>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm mb-6">
    <form action="<?= site_url('dashboard/certificates') ?>" method="get" class="flex flex-col sm:flex-row gap-3">
        <div class="relative flex-1">
            <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
            <input type="text" name="q" value="<?= esc($searchQuery ?? '') ?>" placeholder="Cari nama peserta, email, nomor sertifikat, kegiatan..." class="w-full pl-9 pr-4 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-brand-500">
        </div>

        <select name="event_id" onchange="this.form.submit()" class="py-2 px-3 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500 bg-white text-slate-700">
            <option value="">Semua Kegiatan / Event</option>
            <?php foreach ($events as $ev): ?>
                <option value="<?= $ev['id'] ?>" <?= ($selectedEvent == $ev['id']) ? 'selected' : '' ?>>
                    <?= esc($ev['title']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <select name="rating" onchange="this.form.submit()" class="py-2 px-3 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500 bg-white text-slate-700">
            <option value="">Semua Rating</option>
            <option value="5" <?= ($selectedRating == 5) ? 'selected' : '' ?>>⭐⭐⭐⭐⭐ (5 Bintang)</option>
            <option value="4" <?= ($selectedRating == 4) ? 'selected' : '' ?>>⭐⭐⭐⭐ (4 Bintang)</option>
            <option value="3" <?= ($selectedRating == 3) ? 'selected' : '' ?>>⭐⭐⭐ (3 Bintang)</option>
            <option value="2" <?= ($selectedRating == 2) ? 'selected' : '' ?>>⭐⭐ (2 Bintang)</option>
            <option value="1" <?= ($selectedRating == 1) ? 'selected' : '' ?>>⭐ (1 Bintang)</option>
        </select>

        <?php if (!empty($searchQuery) || !empty($selectedEvent) || !empty($selectedRating)): ?>
            <a href="<?= site_url('dashboard/certificates') ?>" class="px-3.5 py-2 rounded-xl text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition flex items-center justify-center">
                Reset
            </a>
        <?php endif; ?>
    </form>
</div>

<!-- Table Card -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-xs">
            <thead>
                <tr class="border-b border-slate-200 bg-slate-50/75 text-slate-500 font-bold tracking-wider uppercase text-[10px]">
                    <th class="py-3.5 px-4">Peserta & Identitas</th>
                    <th class="py-3.5 px-4">Kegiatan / Acara</th>
                    <th class="py-3.5 px-4">Nomor & Validasi</th>
                    <th class="py-3.5 px-4 text-center">Rating</th>
                    <th class="py-3.5 px-4">Ulasan / Masukan</th>
                    <th class="py-3.5 px-4 text-right">Aksi PDF</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if (empty($certificates)): ?>
                    <tr>
                        <td colspan="6" class="py-12 text-center text-slate-400">
                            <i class="fa-solid fa-certificate text-3xl mb-2 block text-slate-300"></i>
                            <span class="font-semibold">Belum ada feedback / sertifikat yang terbit.</span>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($certificates as $cert): ?>
                        <tr class="hover:bg-slate-50/75 transition">
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-slate-900"><?= esc($cert['participant_name']) ?></div>
                                <div class="text-[11px] text-slate-400">
                                    <?= !empty($cert['identifier']) ? esc($cert['identifier']) . ' • ' : '' ?>
                                    <?= esc($cert['email']) ?>
                                </div>
                                <?php if (!empty($cert['institution'])): ?>
                                    <div class="text-[10px] text-slate-500 font-medium"><?= esc($cert['institution']) ?></div>
                                <?php endif; ?>
                            </td>

                            <td class="py-3.5 px-4">
                                <div class="font-semibold text-slate-800 max-w-xs leading-snug"><?= esc($cert['event_name']) ?></div>
                                <div class="text-[10px] text-slate-400 mt-0.5"><?= date('d M Y H:i', strtotime($cert['created_at'])) ?></div>
                            </td>

                            <td class="py-3.5 px-4">
                                <div class="font-mono font-bold text-slate-800"><?= esc($cert['certificate_no']) ?></div>
                                <span class="inline-block mt-0.5 font-mono text-[10px] px-1.5 py-0.5 rounded bg-slate-100 text-slate-600 border border-slate-200">
                                    <?= esc($cert['verification_code']) ?>
                                </span>
                            </td>

                            <td class="py-3.5 px-4 text-center">
                                <div class="flex items-center justify-center gap-0.5 text-amber-400 text-xs">
                                    <?php for ($s = 1; $s <= 5; $s++): ?>
                                        <i class="fa-solid fa-star <?= ($s <= (int)$cert['rating']) ? 'text-amber-400' : 'text-slate-200' ?>"></i>
                                    <?php endfor; ?>
                                </div>
                                <span class="text-[10px] text-slate-400 font-semibold">(<?= $cert['rating'] ?>/5)</span>
                            </td>

                            <td class="py-3.5 px-4">
                                <p class="text-slate-700 italic max-w-xs line-clamp-2">"<?= esc($cert['feedback']) ?>"</p>
                                <?php if (!empty($cert['suggestions'])): ?>
                                    <div class="text-[10px] text-slate-400 mt-0.5 line-clamp-1">Saran: <?= esc($cert['suggestions']) ?></div>
                                <?php endif; ?>
                            </td>

                            <td class="py-3.5 px-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="<?= site_url('sertifikat/preview/' . $cert['verification_code']) ?>" target="_blank"
                                       class="p-2 text-slate-500 hover:text-brand-600 hover:bg-brand-50 rounded-lg transition" title="Lihat Pratinjau">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <a href="<?= site_url('sertifikat/download/' . $cert['verification_code']) ?>"
                                       class="p-2 text-slate-500 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition" title="Unduh File PDF">
                                        <i class="fa-solid fa-download"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
