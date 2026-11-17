<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Login Pengurus — HIMATIKA Hub') ?></title>
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen flex items-center justify-center p-4 antialiased relative overflow-hidden">

    <!-- Decorative Glows -->
    <div class="absolute top-1/4 left-1/4 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-brand-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-10 right-10 w-80 h-80 bg-indigo-500/10 rounded-full blur-2xl pointer-events-none"></div>

    <div class="w-full max-w-md relative z-10 space-y-6">

        <!-- Back to Home Button -->
        <div class="text-center">
            <a href="<?= site_url('/') ?>" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-400 hover:text-white transition">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Beranda Publik
            </a>
        </div>

        <!-- Login Card -->
        <div class="bg-slate-800/90 backdrop-blur border border-slate-700/80 rounded-3xl p-8 sm:p-10 shadow-2xl">
            <!-- Brand -->
            <div class="text-center space-y-2 mb-8">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-brand-600 to-indigo-600 mx-auto flex items-center justify-center text-white text-2xl shadow-lg shadow-brand-500/20">
                    <i class="fa-solid fa-code-merge"></i>
                </div>
                <h1 class="text-2xl font-extrabold text-white tracking-tight">HIMATIKA <span class="text-brand-400">Workspace</span></h1>
                <p class="text-xs text-slate-400">Masuk untuk mengelola Program Kerja, Kalender, dan Aspirasi Mahasiswa</p>
            </div>

            <!-- Flash Alerts -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="mb-5 p-3.5 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs flex items-center gap-2.5">
                    <i class="fa-solid fa-circle-check text-sm"></i>
                    <span><?= session()->getFlashdata('success') ?></span>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('warning')): ?>
                <div class="mb-5 p-3.5 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-400 text-xs flex items-center gap-2.5">
                    <i class="fa-solid fa-triangle-exclamation text-sm"></i>
                    <span><?= session()->getFlashdata('warning') ?></span>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error') || session()->getFlashdata('errors')): ?>
                <div class="mb-5 p-3.5 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 text-xs space-y-1">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-circle-xmark text-sm"></i>
                        <span><?= session()->getFlashdata('error') ?></span>
                    </div>
                    <?php if (is_array(session()->getFlashdata('errors'))): ?>
                        <ul class="list-disc pl-5">
                            <?php foreach (session()->getFlashdata('errors') as $err): ?>
                                <li><?= esc($err) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Form -->
            <form action="<?= site_url('login') ?>" method="post" class="space-y-4">
                <?= csrf_field() ?>

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">Email Pengurus / NIM</label>
                    <div class="relative">
                        <i class="fa-regular fa-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="text" name="login_id" id="login_id" required value="<?= old('login_id') ?>" placeholder="admin@himatika.org / 240401000000" class="w-full pl-10 pr-4 py-3 rounded-xl bg-slate-900/80 border border-slate-700 text-white text-sm focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 placeholder:text-slate-500">
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs font-bold text-slate-300">Kata Sandi</label>
                        <a href="<?= site_url('forgot-password') ?>" class="text-[11px] font-semibold text-brand-400 hover:text-brand-300 transition flex items-center gap-1">
                            <i class="fa-solid fa-key text-[10px]"></i>
                            <span>Lupa kata sandi?</span>
                        </a>
                    </div>
                    <div class="relative">
                        <i class="fa-solid fa-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="password" name="password" id="password" required placeholder="••••••••" class="w-full pl-10 pr-4 py-3 rounded-xl bg-slate-900/80 border border-slate-700 text-white text-sm focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 placeholder:text-slate-500">
                    </div>
                </div>

                <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-brand-600 to-indigo-600 hover:from-brand-500 hover:to-indigo-500 text-white font-bold text-sm rounded-xl shadow-lg shadow-brand-500/25 transition mt-2">
                    Masuk ke Dashboard
                </button>
            </form>

            <!-- Quick Demo Accounts Cheatsheet -->
            <div class="mt-8 pt-6 border-t border-slate-700/60">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-3 text-center">
                    Demo Akun Uji Coba (Klik Cepat)
                </span>
                <div class="grid grid-cols-3 gap-2 text-xs">
                    <button type="button" onclick="fillCredential('rabiah@himatika.org')" class="p-2.5 rounded-xl bg-slate-900/90 border border-slate-700 hover:border-brand-500 text-left transition group">
                        <div class="text-[10px] font-bold uppercase text-brand-400">Ketua (Admin)</div>
                        <div class="font-semibold text-slate-300 truncate mt-0.5">rabiah@</div>
                    </button>
                    <button type="button" onclick="fillCredential('dimas@himatika.org')" class="p-2.5 rounded-xl bg-slate-900/90 border border-slate-700 hover:border-emerald-500 text-left transition group">
                        <div class="text-[10px] font-bold uppercase text-emerald-400">Kadiv</div>
                        <div class="font-semibold text-slate-300 truncate mt-0.5">dimas@</div>
                    </button>
                    <button type="button" onclick="fillCredential('vina@himatika.org')" class="p-2.5 rounded-xl bg-slate-900/90 border border-slate-700 hover:border-purple-500 text-left transition group">
                        <div class="text-[10px] font-bold uppercase text-purple-400">Anggota</div>
                        <div class="font-semibold text-slate-300 truncate mt-0.5">vina@</div>
                    </button>
                </div>
            </div>
        </div>

        <p class="text-xs text-slate-500 text-center">
            Mengalami kendala akun? Hubungi Administrator Divisi Teknologi & Sistem Informasi.
        </p>
    </div>

    <script>
        function fillCredential(email) {
            document.getElementById('login_id').value = email;
            document.getElementById('password').value = 'password123';
        }
    </script>
</body>
</html>
