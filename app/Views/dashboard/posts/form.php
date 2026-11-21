<?= $this->extend('layout/admin') ?>

<?= $this->section('content') ?>

<div class="max-w-4xl mx-auto space-y-6">

    <!-- Back Button -->
    <a href="<?= site_url('dashboard/posts') ?>" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-brand-600 transition">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Berita
    </a>

    <!-- Validation Errors Alert -->
    <?php if (session()->getFlashdata('errors')): ?>
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs shadow-sm">
            <div class="flex items-center gap-2 font-bold mb-1.5 text-rose-700">
                <i class="fa-solid fa-triangle-exclamation text-sm"></i>
                <span>Berita belum dapat disimpan karena beberapa isian perlu diperbaiki:</span>
            </div>
            <ul class="list-disc list-inside space-y-1 text-rose-700 pl-2">
                <?php foreach (session()->getFlashdata('errors') as $err): ?>
                    <li><?= esc($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- Form Card -->
    <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-sm space-y-6">
        <div>
            <span class="text-xs font-bold text-brand-600 uppercase tracking-widest block">Editor Berita</span>
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 mt-1">
                <?= !empty($post) ? 'Edit Berita: ' . esc($post['title']) : 'Tulis Berita / Warta Baru' ?>
            </h1>
        </div>

        <form action="<?= esc($action) ?>" method="post" class="space-y-5">
            <?= csrf_field() ?>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Judul Berita / Warta <span class="text-rose-500">*</span></label>
                <input type="text" name="title" required minlength="5" maxlength="255" value="<?= esc(old('title', $post['title'] ?? '')) ?>" placeholder="Contoh: Mahasiswa Informatika Sabet Medali Emas Gemastik 2026" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Kategori Berita <span class="text-rose-500">*</span></label>
                    <select name="category_id" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500 bg-white text-slate-700">
                        <option value="">Pilih Kategori...</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= old('category_id', $post['category_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>>
                                <?= esc($cat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Status Publikasi</label>
                    <select name="status" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-bold focus:outline-none focus:ring-2 focus:ring-brand-500 bg-white text-slate-700">
                        <option value="published" <?= old('status', $post['status'] ?? 'published') === 'published' ? 'selected' : '' ?>>
                            Langsung Tayang (Published)
                        </option>
                        <option value="draft" <?= old('status', $post['status'] ?? '') === 'draft' ? 'selected' : '' ?>>
                            Simpan Sebagai Draf (Draft)
                        </option>
                        <option value="archived" <?= old('status', $post['status'] ?? '') === 'archived' ? 'selected' : '' ?>>
                            Diarsipkan (Archived)
                        </option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Estimasi Baca (Menit)</label>
                    <input type="number" name="read_time" min="1" max="60" value="<?= esc(old('read_time', $post['read_time'] ?? 3)) ?>" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500 bg-white">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Tautan Gambar Sampul (Thumbnail URL)</label>
                <div class="relative">
                    <i class="fa-regular fa-image absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="url" name="thumbnail" value="<?= esc(old('thumbnail', $post['thumbnail'] ?? '')) ?>" placeholder="https://images.unsplash.com/... atau URL gambar Google Drive" class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-brand-500">
                </div>
                <span class="text-[10px] text-slate-400 mt-1 block">Opsional. Jika dikosongkan, kartu berita akan menggunakan background gradient modern.</span>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Ringkasan Singkat (Excerpt)</label>
                <textarea name="excerpt" rows="2" placeholder="Satu atau dua kalimat pembuka yang menarik pembaca..." class="w-full p-3 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-brand-500"><?= esc(old('excerpt', $post['excerpt'] ?? '')) ?></textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Isi Konten Berita Lengkap <span class="text-rose-500">*</span> <span class="text-slate-400 font-normal">(minimal 20 karakter)</span></label>
                <textarea name="body" rows="12" required minlength="20" placeholder="Tuliskan isi berita, rilis kegiatan, atau catatan liputan secara komprehensif (minimal 20 karakter). Anda dapat menggunakan format paragraf standar HTML (<p>, <strong>, <em>, <ul>, <li>) atau teks biasa..." class="w-full p-4 rounded-2xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 font-normal leading-relaxed"><?= esc(old('body', $post['body'] ?? '')) ?></textarea>
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                <a href="<?= site_url('dashboard/posts') ?>" class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs rounded-xl shadow-md shadow-brand-500/20 transition flex items-center gap-2">
                    <i class="fa-solid fa-paper-plane text-xs"></i>
                    <span><?= !empty($post) ? 'Simpan Perubahan' : 'Publikasikan Berita' ?></span>
                </button>
            </div>
        </form>
    </div>

</div>

<?= $this->endSection() ?>
