<?= $this->extend('layout/default') ?>

<?= $this->section('content') ?>

<!-- Hero Section -->
<section class="relative overflow-hidden pt-12 pb-20 lg:pt-20 lg:pb-28">
    <!-- Decorative Radial Glows -->
    <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-brand-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute top-10 right-10 w-96 h-96 bg-indigo-500/10 rounded-full blur-2xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="text-center max-w-3xl mx-auto space-y-6">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-brand-50 border border-brand-200 text-brand-700 text-xs font-bold shadow-sm">
                <i class="fa-solid fa-sparkles text-brand-500"></i>
                HIMATIKA Connect 2.0 • Digitalisasi & Keterbukaan Himpunan
            </div>

            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-slate-900 tracking-tight leading-[1.15]">
                Pusat Informasi & <span class="bg-gradient-to-r from-brand-600 via-indigo-600 to-purple-600 bg-clip-text text-transparent">Aspirasi Terbuka</span> Mahasiswa
            </h1>

            <p class="text-lg text-slate-600 leading-relaxed max-w-2xl mx-auto">
                Wadah resmi civitas mahasiswa untuk memantau agenda kegiatan, mengakses informasi akademik, dan menyampaikan pertanyaan serta masukan langsung kepada pengurus himpunan.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-4">
                <a href="<?= site_url('forum#tanya') ?>" class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 px-7 py-3.5 rounded-xl font-bold text-white bg-brand-600 hover:bg-brand-700 shadow-lg shadow-brand-500/25 hover:shadow-xl transition group">
                    <span>Ajukan Pertanyaan / Aspirasi</span>
                    <i class="fa-solid fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                </a>
                <a href="#event" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-7 py-3.5 rounded-xl font-bold text-slate-700 bg-white hover:bg-slate-100 border border-slate-300 shadow-sm transition">
                    <i class="fa-regular fa-calendar-days text-brand-600"></i>
                    <span>Agenda Acara</span>
                </a>
            </div>
        </div>

        <!-- Statistics Banner -->
        <div class="mt-16 max-w-5xl mx-auto grid grid-cols-2 lg:grid-cols-4 gap-4 p-4 sm:p-6 rounded-2xl bg-white border border-slate-200 shadow-xl shadow-slate-200/50">
            <div class="p-4 text-center border-r border-slate-100 last:border-none">
                <div class="text-3xl sm:text-4xl font-black text-brand-600"><?= esc($stats['total_members']) ?>+</div>
                <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider mt-1">Pengurus & Anggota</div>
            </div>
            <div class="p-4 text-center border-r border-slate-100 last:border-none">
                <div class="text-3xl sm:text-4xl font-black text-indigo-600"><?= esc($stats['total_divisions']) ?></div>
                <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider mt-1">Divisi Penggerak</div>
            </div>
            <div class="p-4 text-center border-r border-slate-100 last:border-none">
                <div class="text-3xl sm:text-4xl font-black text-emerald-600"><?= esc($stats['active_prokers']) ?></div>
                <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider mt-1">Program Kerja</div>
            </div>
            <div class="p-4 text-center">
                <div class="text-3xl sm:text-4xl font-black text-amber-500"><?= esc($stats['answered_qa']) ?>+</div>
                <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider mt-1">Aspirasi Terjawab</div>
            </div>
        </div>
    </div>
</section>

<!-- Divisi Pengurus Showcase -->
<section class="py-16 bg-slate-100/70 border-y border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-10">
            <div>
                <span class="text-xs font-bold text-brand-600 uppercase tracking-widest">Struktur Organisasi</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-1">Divisi & Bidang Kerja</h2>
            </div>
            <p class="text-sm text-slate-500 max-w-md mt-2 md:mt-0">
                Setiap divisi berdedikasi mengawal aspirasi mahasiswa, pengembangan skill teknologi, hingga pengabdian nyata.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach ($divisions as $div): ?>
                <div class="bg-white p-6 rounded-2xl border border-slate-200 hover:border-brand-300 hover:shadow-lg transition group">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center text-white mb-4 shadow-sm" style="background-color: <?= esc($div['color']) ?>;">
                        <i class="fa-solid fa-<?= esc($div['icon'] ?? 'layer-group') ?> text-lg"></i>
                    </div>
                    <h3 class="font-bold text-slate-900 group-hover:text-brand-600 transition"><?= esc($div['name']) ?></h3>
                    <p class="text-xs text-slate-500 mt-2 line-clamp-2 leading-relaxed">
                        <?= esc($div['description'] ?? 'Berfokus pada pelaksanaan proker dan pelayanan mahasiswa.') ?>
                    </p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Berita & Jurnal Kegiatan -->
<section id="berita" class="py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12">
            <div>
                <span class="text-xs font-bold text-brand-600 uppercase tracking-widest">Publikasi Terkini</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-1">Warta & Berita Himpunan</h2>
            </div>
            <a href="<?= site_url('berita') ?>" class="inline-flex items-center gap-1.5 text-sm font-bold text-brand-600 hover:text-brand-700 mt-3 md:mt-0">
                <span>Lihat Semua Kabar</span>
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php if (!empty($latestPosts)): ?>
                <?php foreach ($latestPosts as $post): ?>
                    <article class="bg-white rounded-2xl border border-slate-200 overflow-hidden flex flex-col hover:shadow-xl hover:-translate-y-1 transition duration-300">
                        <div class="h-48 bg-gradient-to-tr from-slate-800 to-brand-900 p-6 flex flex-col justify-between text-white relative">
                            <span class="self-start text-[11px] font-bold px-3 py-1 rounded-full bg-white/20 backdrop-blur">
                                <?= esc($post['category_name'] ?? 'Umum') ?>
                            </span>
                            <div class="text-xs text-slate-300 flex items-center gap-2">
                                <i class="fa-regular fa-clock"></i>
                                <?= esc($post['read_time'] ?? 3) ?> menit baca
                            </div>
                        </div>
                        <div class="p-6 flex-1 flex flex-col justify-between">
                            <div class="space-y-2">
                                <h3 class="font-bold text-lg text-slate-900 line-clamp-2 hover:text-brand-600 transition">
                                    <a href="<?= site_url('berita/' . $post['slug']) ?>"><?= esc($post['title']) ?></a>
                                </h3>
                                <p class="text-xs text-slate-500 line-clamp-3 leading-relaxed">
                                    <?= esc($post['excerpt'] ?? strip_tags($post['body'])) ?>
                                </p>
                            </div>
                            <div class="pt-6 mt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400">
                                <span><i class="fa-regular fa-user mr-1"></i> <?= esc($post['author_name'] ?? 'Pengurus') ?></span>
                                <span><?= date('d M Y', strtotime($post['published_at'] ?? $post['created_at'])) ?></span>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-3 text-center py-12 text-slate-400">Belum ada berita yang dipublikasikan.</div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Kalender & Event Mendatang -->
<section id="event" class="py-20 bg-slate-900 text-white relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12">
            <div>
                <span class="text-xs font-bold text-brand-400 uppercase tracking-widest">Kalender Terbuka</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-white mt-1">Agenda & Workshop Mendatang</h2>
            </div>
            <p class="text-sm text-slate-400 max-w-md mt-2 md:mt-0">
                Terbuka untuk seluruh mahasiswa. Daftarkan dirimu segera sebelum kuota habis.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <?php if (!empty($upcomingEvents)): ?>
                <?php foreach ($upcomingEvents as $ev): ?>
                    <div class="p-7 rounded-2xl bg-slate-800/80 border border-slate-700/80 hover:border-brand-500/50 transition flex flex-col justify-between">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between text-xs">
                                <span class="px-3 py-1 rounded-full bg-brand-500/20 text-brand-300 font-bold border border-brand-500/30">
                                    <?= esc($ev['category_name'] ?? 'Event') ?>
                                </span>
                                <span class="text-slate-400 font-medium">
                                    <i class="fa-regular fa-calendar mr-1"></i>
                                    <?= date('d M Y, H:i', strtotime($ev['start_datetime'])) ?> WIB
                                </span>
                            </div>

                            <h3 class="text-xl font-bold text-white"><?= esc($ev['title']) ?></h3>
                            <p class="text-xs text-slate-300 leading-relaxed line-clamp-3">
                                <?= esc($ev['description']) ?>
                            </p>

                            <div class="text-xs text-slate-400 flex items-center gap-2">
                                <i class="fa-solid fa-location-dot text-brand-400"></i>
                                <?= esc($ev['location'] ?? 'Kampus / Online') ?>
                            </div>
                        </div>

                        <div class="mt-8 pt-6 border-t border-slate-700 flex items-center justify-between">
                            <div class="text-xs text-slate-400">
                                <?php if ($ev['max_participants']): ?>
                                    Kuota: <strong class="text-white"><?= esc($ev['max_participants']) ?> Peserta</strong>
                                <?php else: ?>
                                    <strong class="text-emerald-400">Terbuka Umum</strong>
                                <?php endif; ?>
                            </div>
                            <?php if ($ev['allow_registration']): ?>
                                <button class="px-4 py-2 rounded-xl text-xs font-bold bg-brand-600 hover:bg-brand-500 text-white shadow-md transition">
                                    Daftar Sekarang
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-2 text-center py-12 text-slate-400">Belum ada agenda dalam waktu dekat.</div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Forum Aspirasi & Q&A Preview -->
<section class="py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gradient-to-br from-brand-600 to-indigo-700 rounded-3xl p-8 sm:p-12 text-white shadow-2xl relative overflow-hidden">
            <div class="relative z-10 max-w-2xl space-y-4">
                <span class="bg-white/20 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">Forum Aspirasi 24/7</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight">Punya Masukan atau Pertanyaan Seputar Kampus?</h2>
                <p class="text-slate-100 text-sm leading-relaxed">
                    Sampaikan pertanyaan seputar akademik, fasilitas, maupun birokrasi tanpa ribet registrasi akun. Divisi terkait akan meninjau dan menjawab secara transparan.
                </p>
                <div class="pt-4 flex flex-wrap gap-4">
                    <a href="<?= site_url('forum#tanya') ?>" class="px-6 py-3.5 bg-white text-brand-700 hover:bg-slate-100 font-bold text-sm rounded-xl shadow-lg transition">
                        Tulis Pertanyaan / Aspirasi Sekarang
                    </a>
                    <a href="<?= site_url('forum') ?>" class="px-6 py-3.5 bg-brand-700/60 hover:bg-brand-700 text-white font-bold text-sm rounded-xl border border-white/20 transition">
                        Buka Forum Q&A Terkurasi
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
