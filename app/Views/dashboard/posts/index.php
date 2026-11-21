<?= $this->extend('layout/admin') ?>

<?= $this->section('content') ?>

<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <span class="text-xs font-bold text-brand-600 uppercase tracking-widest block">Publikasi & Jurnalistik</span>
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight mt-0.5">Kelola Berita & Artikel Kegiatan</h1>
        <p class="text-xs text-slate-500 mt-1">Publikasikan liputan acara, rilis pers, dan artikel teknologi mahasiswa</p>
    </div>

    <div>
        <a href="<?= site_url('dashboard/posts/create') ?>" class="px-4 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs rounded-xl shadow-md shadow-brand-500/20 transition flex items-center gap-2">
            <i class="fa-solid fa-plus"></i>
            <span>Tulis Berita Baru</span>
        </a>
    </div>
</div>

<!-- Filters & Search Bar -->
<div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm mb-6">
    <form action="<?= site_url('dashboard/posts') ?>" method="get" class="flex flex-col sm:flex-row gap-3">
        <div class="relative flex-1">
            <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
            <input type="text" name="q" value="<?= esc($searchQuery ?? '') ?>" placeholder="Cari judul berita, ringkasan..." class="w-full pl-9 pr-4 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-brand-500">
        </div>

        <select name="status" onchange="this.form.submit()" class="py-2 px-3 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500 bg-white text-slate-700">
            <option value="">Semua Status</option>
            <option value="published" <?= ($selectedStatus ?? '') === 'published' ? 'selected' : '' ?>>Published (Tayang)</option>
            <option value="draft" <?= ($selectedStatus ?? '') === 'draft' ? 'selected' : '' ?>>Draft (Draf)</option>
            <option value="archived" <?= ($selectedStatus ?? '') === 'archived' ? 'selected' : '' ?>>Archived (Diarsipkan)</option>
        </select>

        <select name="category" onchange="this.form.submit()" class="py-2 px-3 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500 bg-white text-slate-700">
            <option value="">Semua Kategori</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= ($selectedCategory ?? 0) == $cat['id'] ? 'selected' : '' ?>>
                    <?= esc($cat['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl transition">
            Filter
        </button>
    </form>
</div>

<!-- Tabel Berita -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider border-b border-slate-200">
                <tr>
                    <th class="py-3.5 px-4 sm:px-6">Judul Berita</th>
                    <th class="py-3.5 px-4">Kategori</th>
                    <th class="py-3.5 px-4">Penulis</th>
                    <th class="py-3.5 px-4">Status</th>
                    <th class="py-3.5 px-4 text-center">Views</th>
                    <th class="py-3.5 px-4">Tanggal Rilis</th>
                    <th class="py-3.5 px-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if (!empty($posts)): ?>
                    <?php
                    $currentUserRole = session()->get('user_role');
                    $currentUserId   = (int) session()->get('user_id');
                    ?>
                    <?php foreach ($posts as $p): ?>
                        <?php $canManage = in_array($currentUserRole, ['admin', 'kadiv'], true) || (int) $p['author_id'] === $currentUserId; ?>
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-4 px-4 sm:px-6 font-bold text-slate-900 max-w-xs sm:max-w-md">
                                <a href="<?= site_url('berita/' . $p['slug']) ?>" target="_blank" class="hover:text-brand-600 transition flex items-center gap-1.5">
                                    <span class="truncate"><?= esc($p['title']) ?></span>
                                    <i class="fa-solid fa-arrow-up-right-from-square text-[9px] text-slate-400"></i>
                                </a>
                                <span class="text-[10px] text-slate-400 font-normal block mt-0.5">
                                    <i class="fa-regular fa-clock mr-1"></i><?= esc($p['read_time']) ?> menit baca
                                </span>
                            </td>
                            <td class="py-4 px-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold text-white shadow-sm" style="background-color: <?= esc($p['category_color'] ?? '#3b82f6') ?>;">
                                    <?= esc($p['category_name'] ?? 'Umum') ?>
                                </span>
                            </td>
                            <td class="py-4 px-4 text-slate-600 font-medium">
                                <?= esc($p['author_name'] ?? 'Pengurus') ?>
                            </td>
                            <td class="py-4 px-4">
                                <?php if ($p['status'] === 'published'): ?>
                                    <span class="px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-700 text-[10px] font-bold">
                                        Tayang (Published)
                                    </span>
                                <?php elseif ($p['status'] === 'draft'): ?>
                                    <span class="px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-700 text-[10px] font-bold">
                                        Draf (Draft)
                                    </span>
                                <?php else: ?>
                                    <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 text-[10px] font-bold">
                                        Arsip
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="py-4 px-4 text-center font-bold text-slate-700">
                                <?= esc($p['views']) ?>
                            </td>
                            <td class="py-4 px-4 text-slate-400">
                                <?= $p['published_at'] ? date('d M Y', strtotime($p['published_at'])) : '-' ?>
                            </td>
                            <td class="py-4 px-4 text-right">
                                <?php if ($canManage): ?>
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="<?= site_url('dashboard/posts/edit/' . $p['id']) ?>" class="p-1.5 text-slate-500 hover:text-brand-600 hover:bg-slate-100 rounded-lg transition" title="Edit Berita">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <form action="<?= site_url('dashboard/posts/delete/' . $p['id']) ?>" method="post" onsubmit="return confirm('Hapus berita ini?')">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Hapus Berita">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                <?php else: ?>
                                    <span class="text-[10px] text-slate-400 italic">Hanya baca</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="py-12 text-center text-slate-400">
                            <i class="fa-regular fa-newspaper text-3xl text-slate-300 block mb-2"></i>
                            Belum ada berita yang tersimpan. Klik tombol "Tulis Berita Baru" untuk memulai!
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
