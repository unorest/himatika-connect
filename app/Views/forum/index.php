<?= $this->extend('layout/default') ?>

<?= $this->section('content') ?>

<div class="bg-gradient-to-b from-brand-50/50 to-transparent py-10 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl">
            <span class="text-xs font-bold text-brand-600 uppercase tracking-widest flex items-center gap-2">
                <i class="fa-solid fa-comments"></i> Forum Tanya Jawab & Aspirasi
            </span>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mt-2 tracking-tight">
                Ruang Suara Mahasiswa Terkurasi
            </h1>
            <p class="text-slate-600 text-sm mt-3 leading-relaxed">
                Tanyakan apa saja seputar perkuliahan, birokrasi, atau usulan fasilitas kampus. Mahasiswa dapat bertanya tanpa login akun. Jawaban resmi akan diverifikasi dan dipublikasikan oleh pengurus HIMATIKA.
            </p>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

        <!-- Sisi Kiri: Filter & Feed Q&A (8 Kolom) -->
        <div class="lg:col-span-8 space-y-6">

            <!-- Search & Filters -->
            <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                <form action="<?= site_url('forum') ?>" method="get" class="flex flex-col sm:flex-row gap-3">
                    <div class="relative flex-1">
                        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="text" name="q" value="<?= esc($searchQuery ?? '') ?>" placeholder="Cari pertanyaan, kata kunci, topik..." class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>
                    <?php if (!empty($selectedCategory)): ?>
                        <input type="hidden" name="category" value="<?= esc($selectedCategory) ?>">
                    <?php endif; ?>
                    <select name="status" class="py-2.5 px-4 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 bg-white">
                        <option value="">Semua Status</option>
                        <option value="answered" <?= ($selectedStatus ?? '') === 'answered' ? 'selected' : '' ?>>Sudah Terjawab</option>
                        <option value="pending" <?= ($selectedStatus ?? '') === 'pending' ? 'selected' : '' ?>>Menunggu Jawaban</option>
                    </select>
                    <button type="submit" class="px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-bold text-sm rounded-xl transition">
                        Cari
                    </button>
                </form>

                <!-- Kategori Pills -->
                <div class="flex items-center gap-2 overflow-x-auto pb-1 text-xs pt-1">
                    <a href="<?= site_url('forum') ?>" class="px-3.5 py-1.5 rounded-full font-semibold whitespace-nowrap transition <?= empty($selectedCategory) ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' ?>">
                        Semua Kategori
                    </a>
                    <?php foreach ($categories as $cat): ?>
                        <a href="<?= site_url('forum?category=' . $cat['id']) ?>" class="px-3.5 py-1.5 rounded-full font-semibold whitespace-nowrap transition <?= ($selectedCategory ?? 0) == $cat['id'] ? 'bg-brand-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' ?>">
                            <?= esc($cat['name']) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- List Pertanyaan -->
            <div class="space-y-4">
                <?php if (!empty($questions)): ?>
                    <?php foreach ($questions as $q): ?>
                        <div class="bg-white rounded-2xl border border-slate-200 p-5 sm:p-6 hover:border-slate-300 hover:shadow-md transition">
                            <div class="flex items-start justify-between gap-4">
                                <div class="space-y-2 flex-1">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <?php if ($q['is_pinned']): ?>
                                            <span class="px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800 text-[11px] font-bold inline-flex items-center gap-1">
                                                <i class="fa-solid fa-thumbtack text-[10px]"></i> Disematkan
                                            </span>
                                        <?php endif; ?>

                                        <?php if ($q['status'] === 'answered'): ?>
                                            <span class="px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-700 text-[11px] font-bold inline-flex items-center gap-1">
                                                <i class="fa-solid fa-circle-check text-[10px]"></i> Terjawab Resmi
                                            </span>
                                        <?php else: ?>
                                            <span class="px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-700 border border-amber-200 text-[11px] font-bold">
                                                Menunggu Jawaban
                                            </span>
                                        <?php endif; ?>

                                        <?php if (!empty($q['category_name'])): ?>
                                            <span class="text-[11px] font-semibold text-slate-500">
                                                • <?= esc($q['category_name']) ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>

                                    <h3 class="text-base sm:text-lg font-bold text-slate-900 leading-snug">
                                        <a href="<?= site_url('forum/detail/' . $q['id']) ?>" class="hover:text-brand-600 transition">
                                            <?= esc($q['title']) ?>
                                        </a>
                                    </h3>

                                    <p class="text-xs sm:text-sm text-slate-600 line-clamp-2 leading-relaxed">
                                        <?= esc($q['body']) ?>
                                    </p>
                                </div>

                                <!-- Upvote Box -->
                                <form action="<?= site_url('forum/upvote/' . $q['id']) ?>" method="post" class="flex-shrink-0">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="flex flex-col items-center justify-center w-12 h-14 rounded-xl border border-slate-200 bg-slate-50 hover:bg-brand-50 hover:border-brand-200 hover:text-brand-600 transition group p-1" title="Dukung pertanyaan ini agar dijawab lebih cepat">
                                        <i class="fa-solid fa-chevron-up text-xs text-slate-400 group-hover:text-brand-600 group-hover:-translate-y-0.5 transition-transform"></i>
                                        <span class="text-xs font-bold text-slate-700 group-hover:text-brand-600 mt-1"><?= esc($q['upvotes']) ?></span>
                                    </button>
                                </form>
                            </div>

                            <div class="mt-5 pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400">
                                <div class="flex items-center gap-2">
                                    <span class="font-medium text-slate-600">
                                        <i class="fa-regular fa-user mr-1 text-slate-400"></i>
                                        <?= $q['is_anonymous'] ? 'Mahasiswa Anonim' : esc($q['author_name']) ?>
                                    </span>
                                    <span>•</span>
                                    <span><?= date('d M Y, H:i', strtotime($q['created_at'])) ?></span>
                                </div>

                                <a href="<?= site_url('forum/detail/' . $q['id']) ?>" class="inline-flex items-center gap-1 font-bold text-brand-600 hover:text-brand-700">
                                    <span>Lihat Tanggapan (<?= esc($q['answer_count'] ?? 0) ?>)</span>
                                    <i class="fa-solid fa-chevron-right text-[10px]"></i>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center text-slate-400 space-y-3">
                        <i class="fa-regular fa-comments text-4xl text-slate-300"></i>
                        <div class="font-bold text-slate-700">Belum ada pertanyaan pada filter ini.</div>
                        <p class="text-xs text-slate-500">Jadilah yang pertama mengajukan aspirasi atau pertanyaan menggunakan formulir di samping!</p>
                    </div>
                <?php endif; ?>
            </div>

        </div>

        <!-- Sisi Kanan: Form Kirim Pertanyaan Tanpa Akun (4 Kolom) -->
        <div id="tanya" class="lg:col-span-4 sticky top-28">
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-lg shadow-slate-200/50 space-y-5">
                <div>
                    <span class="text-[11px] font-bold text-brand-600 uppercase tracking-wider block">Kirim Cepat</span>
                    <h2 class="text-xl font-bold text-slate-900 mt-1">Sampaikan Aspirasi / Q&A</h2>
                    <p class="text-xs text-slate-500 mt-1">Tidak perlu login akun. Kamu bisa memilih untuk mengirim sebagai anonim.</p>
                </div>

                <form action="<?= site_url('forum/store') ?>" method="post" class="space-y-4">
                    <?= csrf_field() ?>

                    <!-- Honeypot trap anti-spam bot -->
                    <div class="hidden">
                        <input type="text" name="website_url" tabindex="-1" autocomplete="off">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Judul / Topik Pertanyaan <span class="text-rose-500">*</span></label>
                        <input type="text" name="title" required value="<?= old('title') ?>" placeholder="Contoh: Alur pendaftaran beasiswa komputasi" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Kategori Topik</label>
                        <select name="category_id" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 bg-white">
                            <option value="">Pilih Kategori Aspirasi...</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>" <?= old('category_id') == $cat['id'] ? 'selected' : '' ?>>
                                    <?= esc($cat['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Isi Pertanyaan / Aspirasi <span class="text-rose-500">*</span></label>
                        <textarea name="body" rows="4" required placeholder="Jelaskan secara rinci permasalahan, usulan, atau pertanyaan yang ingin ditanyakan..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500"><?= old('body') ?></textarea>
                    </div>

                    <!-- Identitas Pengirim (Opsional / Anonim) -->
                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80 space-y-3">
                        <div class="flex items-center gap-2">
                            <input type="checkbox" id="anonToggle" name="is_anonymous" value="1" class="rounded text-brand-600 focus:ring-brand-500" <?= old('is_anonymous') ? 'checked' : '' ?>>
                            <label for="anonToggle" class="text-xs font-bold text-slate-700 cursor-pointer">Kirim sebagai Anonim</label>
                        </div>

                        <div id="authorFields" class="space-y-2">
                            <input type="text" name="author_name" value="<?= old('author_name') ?>" placeholder="Nama Lengkap (Opsional)" class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-brand-500 bg-white">
                            <div class="grid grid-cols-2 gap-2">
                                <input type="text" name="author_nim" value="<?= old('author_nim') ?>" placeholder="NIM (Opsional)" class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-brand-500 bg-white">
                                <input type="email" name="author_email" value="<?= old('author_email') ?>" placeholder="Email (Opsional)" class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-brand-500 bg-white">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-3 bg-brand-600 hover:bg-brand-700 text-white font-bold text-sm rounded-xl shadow-md transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-paper-plane text-xs"></i>
                        <span>Kirim Pertanyaan</span>
                    </button>

                    <p class="text-[11px] text-slate-400 text-center leading-normal">
                        Semua aspirasi yang masuk akan dimoderasi secara terstruktur oleh Divisi Advokasi HIMATIKA.
                    </p>
                </form>
            </div>
        </div>

    </div>
</div>

<script>
    const anonToggle = document.getElementById('anonToggle');
    const authorFields = document.getElementById('authorFields');

    function updateAnonState() {
        if (anonToggle.checked) {
            authorFields.classList.add('opacity-40', 'pointer-events-none');
        } else {
            authorFields.classList.remove('opacity-40', 'pointer-events-none');
        }
    }

    anonToggle.addEventListener('change', updateAnonState);
    updateAnonState();
</script>

<?= $this->endSection() ?>
