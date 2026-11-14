<?= $this->extend('layout/default') ?>

<?= $this->section('content') ?>

<div class="bg-gradient-to-b from-brand-50/50 to-transparent py-12 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl">
            <span class="text-xs font-bold text-brand-600 uppercase tracking-widest flex items-center gap-2">
                <i class="fa-regular fa-newspaper"></i> HIMATIKA Journal & News
            </span>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mt-2 tracking-tight">
                Warta & Liputan Kegiatan Himpunan
            </h1>
            <p class="text-slate-600 text-sm mt-3 leading-relaxed">
                Ikuti liputan kegiatan terbaru, rilis pers organisasi, catatan rapat terbuka, serta artikel edukasi dan wawasan komputasi dari sivitas mahasiswa.
            </p>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <!-- Search & Filter Bar -->
    <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-sm space-y-4 mb-10">
        <form action="<?= site_url('berita') ?>" method="get" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                <input type="text" name="q" value="<?= esc($searchQuery ?? '') ?>" placeholder="Cari topik berita, kata kunci, liputan..." class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>

            <?php if (!empty($selectedCategory)): ?>
                <input type="hidden" name="category" value="<?= esc($selectedCategory) ?>">
            <?php endif; ?>

            <button type="submit" class="px-6 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-bold text-sm rounded-xl transition">
                Cari Berita
            </button>
        </form>

        <!-- Kategori Pills -->
        <div class="flex items-center gap-2 overflow-x-auto pb-1 text-xs pt-1">
            <a href="<?= site_url('berita') ?>" class="px-3.5 py-1.5 rounded-full font-semibold whitespace-nowrap transition <?= empty($selectedCategory) ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' ?>">
                Semua Kategori
            </a>
            <?php foreach ($categories as $cat): ?>
                <a href="<?= site_url('berita?category=' . $cat['id']) ?>" class="px-3.5 py-1.5 rounded-full font-semibold whitespace-nowrap transition <?= ($selectedCategory ?? 0) == $cat['id'] ? 'bg-brand-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' ?>">
                    <?= esc($cat['name']) ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Grid Berita -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php if (!empty($posts)): ?>
            <?php foreach ($posts as $post): ?>
                <article class="bg-white rounded-3xl border border-slate-200 overflow-hidden flex flex-col hover:shadow-xl hover:-translate-y-1 transition duration-300">
                    <?php if (!empty($post['thumbnail'])): ?>
                        <div class="h-48 overflow-hidden relative">
                            <img src="<?= esc($post['thumbnail']) ?>" alt="<?= esc($post['title']) ?>" class="w-full h-full object-cover">
                            <span class="absolute top-4 left-4 text-[11px] font-bold px-3 py-1 rounded-full text-white shadow" style="background-color: <?= esc($post['category_color'] ?? '#3b82f6') ?>;">
                                <?= esc($post['category_name'] ?? 'Umum') ?>
                            </span>
                        </div>
                    <?php else: ?>
                        <div class="h-48 bg-gradient-to-tr from-slate-900 via-slate-800 to-brand-900 p-6 flex flex-col justify-between text-white relative">
                            <span class="self-start text-[11px] font-bold px-3 py-1 rounded-full text-white shadow" style="background-color: <?= esc($post['category_color'] ?? '#3b82f6') ?>;">
                                <?= esc($post['category_name'] ?? 'Umum') ?>
                            </span>
                            <div class="text-xs text-slate-300 flex items-center gap-2">
                                <i class="fa-regular fa-clock"></i>
                                <?= esc($post['read_time'] ?? 3) ?> menit baca
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
                        <div class="space-y-2">
                            <h2 class="font-bold text-lg text-slate-900 line-clamp-2 hover:text-brand-600 transition leading-snug">
                                <a href="<?= site_url('berita/' . $post['slug']) ?>">
                                    <?= esc($post['title']) ?>
                                </a>
                            </h2>
                            <p class="text-xs text-slate-500 line-clamp-3 leading-relaxed">
                                <?= esc($post['excerpt'] ?? strip_tags($post['body'])) ?>
                            </p>
                        </div>

                        <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400">
                            <span><i class="fa-regular fa-user mr-1 text-slate-400"></i> <?= esc($post['author_name'] ?? 'Pengurus') ?></span>
                            <span><?= date('d M Y', strtotime($post['published_at'] ?? $post['created_at'])) ?></span>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-span-3 text-center py-16 text-slate-400 space-y-3">
                <i class="fa-regular fa-newspaper text-4xl text-slate-300"></i>
                <h3 class="font-bold text-base text-slate-700">Belum ada artikel berita pada topik ini</h3>
                <p class="text-xs text-slate-500">Coba gunakan filter kategori lain atau hapus kata kunci pencarian.</p>
            </div>
        <?php endif; ?>
    </div>

</div>

<?= $this->endSection() ?>
