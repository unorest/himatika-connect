<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Lupa Kata Sandi — HIMATIKA Hub') ?></title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#eff6ff', 500: '#3b82f6', 600: '#2563eb', 700: '#1d4ed8',
                        }
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-slate-950 min-h-screen flex items-center justify-center p-4 sm:p-6 antialiased relative selection:bg-brand-500 selection:text-white">

    <!-- Ambient Glow Effects -->
    <div class="fixed top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-brand-600/15 rounded-full blur-3xl pointer-events-none"></div>
    <div class="fixed bottom-10 right-10 w-80 h-80 bg-indigo-600/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-md w-full space-y-6 relative z-10">

        <!-- Card Container -->
        <div class="bg-slate-900/90 border border-slate-800 rounded-3xl p-6 sm:p-8 shadow-2xl backdrop-blur-xl">

            <!-- Logo & Title -->
            <div class="text-center space-y-2 mb-6">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-brand-600 to-indigo-600 text-white flex items-center justify-center text-xl font-black mx-auto shadow-lg shadow-brand-500/25">
                    <i class="fa-solid fa-key"></i>
                </div>
                <h1 class="text-2xl font-extrabold text-white tracking-tight">Reset Kata Sandi</h1>
                <p class="text-xs text-slate-400">Verifikasi identitas NIM & Email pengurus untuk membuat kata sandi baru</p>
            </div>

            <!-- Flash Alerts -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="mb-5 p-3.5 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs flex items-center gap-2.5">
                    <i class="fa-solid fa-circle-check text-sm"></i>
                    <span><?= session()->getFlashdata('success') ?></span>
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
            <form action="<?= site_url('forgot-password') ?>" method="post" class="space-y-4">
                <?= csrf_field() ?>

                <!-- Email -->
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">Email Pengurus Terdaftar <span class="text-rose-400">*</span></label>
                    <div class="relative">
                        <i class="fa-regular fa-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="email" name="email" required value="<?= old('email') ?>" placeholder="contoh: rendy@himatika.org" class="w-full pl-10 pr-4 py-3 rounded-xl bg-slate-900/80 border border-slate-700 text-white text-sm focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 placeholder:text-slate-500">
                    </div>
                </div>

                <!-- NIM -->
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">Nomor Induk Mahasiswa (NIM) <span class="text-rose-400">*</span></label>
                    <div class="relative">
                        <i class="fa-regular fa-id-badge absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="text" name="nim" required value="<?= old('nim') ?>" placeholder="contoh: 240401010288" class="w-full pl-10 pr-4 py-3 rounded-xl bg-slate-900/80 border border-slate-700 text-white text-sm focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 placeholder:text-slate-500">
                    </div>
                    <p class="text-[10px] text-slate-500 mt-1">NIM digunakan sebagai verifikasi identitas resmi pengurus.</p>
                </div>

                <!-- Kata Sandi Baru -->
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">Kata Sandi Baru <span class="text-rose-400">*</span></label>
                    <div class="relative">
                        <i class="fa-solid fa-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="password" name="new_password" id="new_password" required minlength="6" placeholder="Minimal 6 karakter" class="w-full pl-10 pr-10 py-3 rounded-xl bg-slate-900/80 border border-slate-700 text-white text-sm focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 placeholder:text-slate-500">
                        <button type="button" onclick="togglePass('new_password', this)" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-200">
                            <i class="fa-regular fa-eye text-xs"></i>
                        </button>
                    </div>
                </div>

                <!-- Konfirmasi Kata Sandi Baru -->
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">Ulangi Kata Sandi Baru <span class="text-rose-400">*</span></label>
                    <div class="relative">
                        <i class="fa-solid fa-shield-halved absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="password" name="confirm_password" id="confirm_password" required minlength="6" placeholder="Ketik ulang kata sandi baru" class="w-full pl-10 pr-10 py-3 rounded-xl bg-slate-900/80 border border-slate-700 text-white text-sm focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 placeholder:text-slate-500">
                        <button type="button" onclick="togglePass('confirm_password', this)" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-200">
                            <i class="fa-regular fa-eye text-xs"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-brand-600 to-indigo-600 hover:from-brand-500 hover:to-indigo-500 text-white font-bold text-sm rounded-xl shadow-lg shadow-brand-500/25 transition mt-3 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>Simpan & Perbarui Kata Sandi</span>
                </button>
            </form>

            <div class="mt-6 pt-5 border-t border-slate-800 text-center">
                <a href="<?= site_url('login') ?>" class="text-xs font-bold text-slate-400 hover:text-white transition inline-flex items-center gap-1.5">
                    <i class="fa-solid fa-arrow-left text-[10px]"></i>
                    <span>Kembali ke Halaman Login</span>
                </a>
            </div>

        </div>

        <p class="text-xs text-slate-500 text-center">
            Mengalami kesulitan verifikasi? Hubungi Administrator BPH HIMATIKA.
        </p>

    </div>

    <script>
        function togglePass(fieldId, btn) {
            const input = document.getElementById(fieldId);
            const icon = btn.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
