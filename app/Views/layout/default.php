<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'HIMATIKA Connect') ?></title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            900: '#1e3a8a',
                        }
                    }
                }
            }
        }
    </script>
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 flex flex-col min-h-screen antialiased selection:bg-brand-500 selection:text-white">

    <!-- Top Announcement Bar -->
    <div class="bg-gradient-to-r from-brand-700 via-brand-600 to-indigo-700 text-white text-xs py-2 px-4 text-center font-medium shadow-sm">
        <span class="inline-flex items-center gap-2">
            <span class="bg-white/20 px-2 py-0.5 rounded-full text-[10px] tracking-wide font-bold uppercase">Official</span>
            Portal Resmi Satu Pintu Informasi, Kegiatan & Aspirasi HIMATIKA
        </span>
    </div>

    <!-- Main Navigation -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur border-b border-slate-200 transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <!-- Brand Logo -->
            <a href="<?= site_url('/') ?>" class="flex items-center gap-3 group">
                <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-brand-600 to-indigo-600 flex items-center justify-center text-white text-xl shadow-md group-hover:scale-105 transition-transform">
                    <i class="fa-solid fa-code-merge"></i>
                </div>
                <div>
                    <span class="text-xl font-extrabold tracking-tight text-slate-900 flex items-center gap-1">
                        HIMATIKA <span class="text-brand-600 font-black">Hub</span>
                    </span>
                    <span class="text-[11px] block font-semibold text-slate-400 -mt-1 tracking-wider uppercase">Informatics Community</span>
                </div>
            </a>

            <!-- Desktop Nav Links -->
            <nav class="hidden md:flex items-center gap-8 text-sm font-semibold text-slate-600">
                <a href="<?= site_url('/') ?>" class="hover:text-brand-600 transition <?= uri_string() === '' ? 'text-brand-600' : '' ?>">Beranda</a>
                <a href="<?= site_url('berita') ?>" class="hover:text-brand-600 transition <?= str_contains(uri_string(), 'berita') ? 'text-brand-600' : '' ?>">Berita & Warta</a>
                <a href="<?= site_url('/#event') ?>" class="hover:text-brand-600 transition">Agenda Event</a>
                <a href="<?= site_url('forum') ?>" class="hover:text-brand-600 flex items-center gap-1.5 transition <?= str_contains(uri_string(), 'forum') ? 'text-brand-600' : '' ?>">
                    <span>Forum Aspirasi (Q&A)</span>
                    <span class="bg-rose-100 text-rose-600 text-[10px] px-2 py-0.5 rounded-full font-bold">Publik</span>
                </a>
            </nav>

            <!-- Action Buttons -->
            <div class="flex items-center gap-3">
                <a href="<?= site_url('forum#tanya') ?>" class="hidden sm:inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 transition border border-slate-200">
                    <i class="fa-solid fa-pen-nib text-brand-600"></i> Kirim Aspirasi
                </a>
                <a href="<?= site_url('login') ?>" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-brand-600 to-indigo-600 hover:from-brand-700 hover:to-indigo-700 shadow-md shadow-brand-500/20 hover:shadow-lg transition">
                    <i class="fa-solid fa-lock"></i> Portal Pengurus
                </a>
            </div>
        </div>
    </header>

    <!-- Global Alerts -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-3 shadow-sm mb-4">
                <i class="fa-solid fa-circle-check text-emerald-600 text-lg"></i>
                <div><?= session()->getFlashdata('success') ?></div>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('warning')): ?>
            <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-sm flex items-center gap-3 shadow-sm mb-4">
                <i class="fa-solid fa-triangle-exclamation text-amber-600 text-lg"></i>
                <div><?= session()->getFlashdata('warning') ?></div>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error') || session()->getFlashdata('errors')): ?>
            <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-center gap-3 shadow-sm mb-4">
                <i class="fa-solid fa-circle-xmark text-rose-600 text-lg"></i>
                <div>
                    <?= session()->getFlashdata('error') ?>
                    <?php if (is_array(session()->getFlashdata('errors'))): ?>
                        <ul class="list-disc pl-5 mt-1 space-y-0.5">
                            <?php foreach (session()->getFlashdata('errors') as $err): ?>
                                <li><?= esc($err) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Main Content Injection -->
    <main class="flex-1">
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 pt-16 pb-12 border-t border-slate-800 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10 mb-12">
                <div class="md:col-span-2 space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-brand-600 text-white flex items-center justify-center font-bold text-lg">
                            <i class="fa-solid fa-code-merge"></i>
                        </div>
                        <span class="text-xl font-bold text-white tracking-tight">HIMATIKA Connect</span>
                    </div>
                    <p class="text-sm text-slate-400 leading-relaxed max-w-md">
                        Platform wadah terintegrasi untuk keterbukaan informasi, publikasi karya, serta penyaluran aspirasi mahasiswa Matematika dan Informatika secara transparan dan terstruktur.
                    </p>
                    <div class="flex items-center gap-3 text-slate-400 pt-2">
                        <a href="#" class="w-9 h-9 rounded-lg bg-slate-800 hover:bg-brand-600 hover:text-white flex items-center justify-center transition"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#" class="w-9 h-9 rounded-lg bg-slate-800 hover:bg-brand-600 hover:text-white flex items-center justify-center transition"><i class="fa-brands fa-github"></i></a>
                        <a href="#" class="w-9 h-9 rounded-lg bg-slate-800 hover:bg-brand-600 hover:text-white flex items-center justify-center transition"><i class="fa-brands fa-linkedin-in"></i></a>
                        <a href="#" class="w-9 h-9 rounded-lg bg-slate-800 hover:bg-brand-600 hover:text-white flex items-center justify-center transition"><i class="fa-brands fa-discord"></i></a>
                    </div>
                </div>

                <div>
                    <h4 class="text-white text-sm font-bold uppercase tracking-wider mb-4">Navigasi Utama</h4>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="<?= site_url('/') ?>" class="hover:text-white transition">Beranda Utama</a></li>
                        <li><a href="<?= site_url('/#berita') ?>" class="hover:text-white transition">Warta & Liputan</a></li>
                        <li><a href="<?= site_url('/#event') ?>" class="hover:text-white transition">Kalender Event</a></li>
                        <li><a href="<?= site_url('forum') ?>" class="hover:text-white transition">Forum Aspirasi (Q&A)</a></li>
                        <li><a href="<?= site_url('login') ?>" class="hover:text-white transition">Login Pengurus</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-white text-sm font-bold uppercase tracking-wider mb-4">Sekretariat</h4>
                    <p class="text-sm leading-relaxed mb-3">
                        Gedung Pusat Kegiatan Mahasiswa (PKM) Lantai 2, Kampus Terpadu Informatika.
                    </p>
                    <div class="text-xs space-y-1 text-slate-400">
                        <p><i class="fa-regular fa-envelope mr-1.5 text-brand-400"></i> aspirasi@himatika.org</p>
                        <p><i class="fa-solid fa-phone mr-1.5 text-brand-400"></i> (021) 555-0199</p>
                    </div>
                </div>
            </div>

            <div class="pt-8 border-t border-slate-800 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 gap-4">
                <p>&copy; <?= date('Y') ?> HIMATIKA. Dikelola oleh Divisi Teknologi & Humas.</p>
                <div class="flex items-center gap-6">
                    <a href="#" class="hover:text-slate-400">Kebijakan Privasi</a>
                    <a href="#" class="hover:text-slate-400">Ketentuan Layanan</a>
                    <a href="#" class="hover:text-slate-400">Pedoman Aspirasi</a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
