<?= $this->extend('layout/default') ?>

<?= $this->section('content') ?>

<article class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-2 text-xs font-semibold text-slate-400 mb-6">
        <a href="<?= site_url('/') ?>" class="hover:text-brand-600 transition">Beranda</a>
        <span>/</span>
        <a href="<?= site_url('berita') ?>" class="hover:text-brand-600 transition">Berita & Warta</a>
        <span>/</span>
        <span class="text-slate-600 truncate max-w-xs"><?= esc($post['title']) ?></span>
    </nav>

    <!-- Header Artikel -->
    <header class="space-y-4 mb-8">
        <div class="flex items-center gap-2">
            <span class="px-3 py-1 rounded-full text-xs font-bold text-white shadow-sm" style="background-color: <?= esc($post['category_color'] ?? '#3b82f6') ?>;">
                <?= esc($post['category_name'] ?? 'Umum') ?>
            </span>
            <span class="text-xs text-slate-400 font-medium">
                <i class="fa-regular fa-clock mr-1"></i> <?= esc($post['read_time']) ?> menit baca
            </span>
            <span class="text-xs text-slate-400 font-medium">•</span>
            <span class="text-xs text-slate-400 font-medium">
                <i class="fa-regular fa-eye mr-1"></i> <?= esc($post['views']) ?> kali dibaca
            </span>
        </div>

        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-slate-900 tracking-tight leading-tight">
            <?= esc($post['title']) ?>
        </h1>

        <!-- Penulis & Waktu -->
        <div class="flex items-center gap-3 pt-2">
            <div class="w-10 h-10 rounded-full bg-brand-600 text-white flex items-center justify-center font-bold text-sm shadow">
                <i class="fa-regular fa-user"></i>
            </div>
            <div>
                <h4 class="text-sm font-bold text-slate-800"><?= esc($post['author_name'] ?? 'Pengurus HIMATIKA') ?></h4>
                <div class="text-xs text-slate-400">
                    <span><?= esc($post['division_name'] ?? 'HIMATIKA Connect') ?></span>
                    <span>•</span>
                    <span><?= date('d F Y', strtotime($post['published_at'] ?? $post['created_at'])) ?></span>
                </div>
            </div>
        </div>
    </header>

    <!-- Gambar Sampul (Jika ada) -->
    <?php if (!empty($post['thumbnail'])): ?>
        <div class="rounded-3xl overflow-hidden mb-10 shadow-lg border border-slate-200">
            <img src="<?= esc($post['thumbnail']) ?>" alt="<?= esc($post['title']) ?>" class="w-full max-h-[450px] object-cover">
        </div>
    <?php endif; ?>

    <!-- Ringkasan / Excerpt Highlight -->
    <?php if (!empty($post['excerpt'])): ?>
        <div class="p-6 rounded-2xl bg-brand-50/60 border-l-4 border-brand-600 text-slate-700 text-sm sm:text-base font-medium italic leading-relaxed mb-8">
            <?= esc($post['excerpt']) ?>
        </div>
    <?php endif; ?>

    <!-- Konten Artikel Utama -->
    <div class="text-slate-800 text-sm sm:text-base leading-relaxed space-y-4 prose max-w-none border-b border-slate-200 pb-12">
        <?= $post['body'] ?>
    </div>

    <!-- Rekomendasi Berita Lainnya -->
    <?php if (!empty($relatedPosts)): ?>
        <section class="mt-16">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-xl font-bold text-slate-900">Kabar Lainnya dari HIMATIKA</h3>
                <a href="<?= site_url('berita') ?>" class="text-xs font-bold text-brand-600 hover:underline">
                    Lihat Semua Berita →
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <?php foreach ($relatedPosts as $rel): ?>
                    <div class="bg-white rounded-2xl border border-slate-200 p-5 hover:border-slate-300 hover:shadow-md transition space-y-2">
                        <span class="text-[10px] font-bold text-brand-600 uppercase tracking-wider block">
                            <?= date('d M Y', strtotime($rel['published_at'] ?? $rel['created_at'])) ?>
                        </span>
                        <h4 class="font-bold text-sm text-slate-900 line-clamp-2 hover:text-brand-600 transition leading-snug">
                            <a href="<?= site_url('berita/' . $rel['slug']) ?>">
                                <?= esc($rel['title']) ?>
                            </a>
                        </h4>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>

</article>

<?= $this->endSection() ?>
