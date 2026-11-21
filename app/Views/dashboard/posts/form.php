<?= $this->extend('layout/admin') ?>

<?= $this->section('content') ?>

<!-- Quill Rich Text Editor Assets -->
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
<style>
    .ql-toolbar.ql-snow {
        border-color: #e2e8f0;
        border-top-left-radius: 1rem;
        border-top-right-radius: 1rem;
        background: #f8fafc;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
    .ql-container.ql-snow {
        border-color: #e2e8f0;
        border-bottom-left-radius: 1rem;
        border-bottom-right-radius: 1rem;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 0.875rem;
        min-height: 260px;
    }
    .ql-editor {
        min-height: 260px;
        line-height: 1.7;
    }
</style>

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

        <form id="postEditorForm" action="<?= esc($action) ?>" method="post" class="space-y-5">
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
                    <input type="url" name="thumbnail" id="thumbnailInput" value="<?= esc(old('thumbnail', $post['thumbnail'] ?? '')) ?>" placeholder="https://images.unsplash.com/..." class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-brand-500">
                </div>
                <span class="text-[10px] text-slate-400 mt-1 block">Opsional (wajib http:// atau https://). Jika dikosongkan, kartu berita menggunakan background gradient modern.</span>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Ringkasan Singkat (Excerpt)</label>
                <textarea name="excerpt" rows="2" placeholder="Satu atau dua kalimat pembuka yang menarik pembaca..." class="w-full p-3 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-brand-500"><?= esc(old('excerpt', $post['excerpt'] ?? '')) ?></textarea>
            </div>

            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-xs font-bold text-slate-700">Isi Konten Berita Lengkap <span class="text-rose-500">*</span> <span class="text-slate-400 font-normal">(minimal 20 karakter)</span></label>
                    <span id="charCountBadge" class="text-[10px] font-bold text-slate-400">0 karakter</span>
                </div>

                <!-- Visual Rich Text Editor -->
                <div id="quillEditor"></div>

                <!-- Hidden Textarea synced with Quill (fallback jika JS/CDN gagal) -->
                <textarea name="body" id="bodyTextarea" rows="12" class="hidden w-full p-4 rounded-2xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 font-normal leading-relaxed"><?= esc(old('body', $post['body'] ?? '')) ?></textarea>
                <p id="bodyClientError" class="hidden text-[11px] font-semibold text-rose-600 mt-1.5">Isi konten berita minimal 20 karakter.</p>
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

<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
<script>
    (function () {
        const textarea = document.getElementById('bodyTextarea');
        const editorEl = document.getElementById('quillEditor');
        const badge    = document.getElementById('charCountBadge');
        const errText  = document.getElementById('bodyClientError');
        const form     = document.getElementById('postEditorForm');

        if (typeof Quill === 'undefined') {
            editorEl.classList.add('hidden');
            textarea.classList.remove('hidden');
            textarea.required = true;
            textarea.minLength = 20;
            return;
        }

        const quill = new Quill('#quillEditor', {
            theme: 'snow',
            placeholder: 'Tuliskan isi berita, rilis kegiatan, atau liputan acara secara lengkap...',
            modules: {
                toolbar: [
                    [{ header: [2, 3, false] }],
                    ['bold', 'italic', 'underline'],
                    [{ list: 'ordered' }, { list: 'bullet' }],
                    ['blockquote', 'link'],
                    ['clean']
                ]
            }
        });

        // Muat konten awal (untuk mode Edit atau old() setelah error validasi)
        const initialHtml = textarea.value;
        if (initialHtml && initialHtml.trim() !== '') {
            quill.clipboard.dangerouslyPasteHTML(initialHtml);
        }

        function updateCounter() {
            const plainLen = quill.getText().trim().length;
            badge.textContent = plainLen + ' karakter';
            if (plainLen >= 20) {
                badge.className = 'text-[10px] font-bold text-emerald-600';
                errText.classList.add('hidden');
            } else {
                badge.className = 'text-[10px] font-bold text-amber-600';
            }
        }

        quill.on('text-change', updateCounter);
        updateCounter();

        form.addEventListener('submit', function (e) {
            const plainLen = quill.getText().trim().length;
            if (plainLen < 20) {
                e.preventDefault();
                errText.classList.remove('hidden');
                quill.focus();
                return;
            }
            textarea.value = quill.root.innerHTML;
        });
    })();
</script>

<?= $this->endSection() ?>
