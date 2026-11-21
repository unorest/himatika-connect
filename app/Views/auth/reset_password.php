<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Reset Kata Sandi — HIMATIKA Hub') ?></title>

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

            <?php if (!$hasToken): ?>
                <!-- STATE 1: FORM INPUT TOKEN / KODE MANUAL -->
                <div class="text-center space-y-2 mb-6">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-brand-600 to-indigo-600 text-white flex items-center justify-center text-xl font-black mx-auto shadow-lg shadow-brand-500/25">
                        <i class="fa-solid fa-key"></i>
                    </div>
                    <h1 class="text-2xl font-extrabold text-white tracking-tight">Verifikasi Token Reset</h1>
                    <p class="text-xs text-slate-400">Masukkan email akun dan kode verifikasi 6-digit (atau token tautan) yang Anda terima via WhatsApp / Email</p>
                </div>

                <!-- Flash Alerts -->
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="mb-5 p-3.5 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 text-xs flex items-center gap-2.5">
                        <i class="fa-solid fa-circle-xmark text-sm"></i>
                        <span><?= esc(session()->getFlashdata('error')) ?></span>
                    </div>
                <?php endif; ?>

                <form action="<?= site_url('reset-password') ?>" method="get" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1.5">Email Pengurus Terdaftar <span class="text-rose-400">*</span></label>
                        <div class="relative">
                            <i class="fa-regular fa-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                            <input type="email" name="email" required value="<?= esc($email ?? '') ?>" placeholder="contoh: rendy@himatika.org" class="w-full pl-10 pr-4 py-3 rounded-xl bg-slate-900/80 border border-slate-700 text-white text-xs sm:text-sm focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 placeholder:text-slate-500">
                        </div>
                        <p class="text-[10px] text-slate-500 mt-1">Wajib diisi saat memverifikasi menggunakan kode 6-digit.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1.5">Kode 6-Digit atau Token Reset <span class="text-rose-400">*</span></label>
                        <div class="relative">
                            <i class="fa-solid fa-shield-halved absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                            <input type="text" name="token" required placeholder="Contoh: 849201 atau paste token acak" class="w-full pl-10 pr-4 py-3 rounded-xl bg-slate-900/80 border border-slate-700 text-white text-xs sm:text-sm font-mono focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 placeholder:text-slate-500">
                        </div>
                        <p class="text-[10px] text-slate-500 mt-1">Kode 6-digit tertera pada notifikasi WhatsApp / Email Anda.</p>
                    </div>

                    <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-brand-600 to-indigo-600 hover:from-brand-500 hover:to-indigo-500 text-white font-bold text-xs sm:text-sm rounded-xl shadow-lg shadow-brand-500/25 transition mt-2 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-arrow-right"></i>
                        <span>Verifikasi & Lanjutkan</span>
                    </button>
                </form>

                <div class="mt-6 pt-5 border-t border-slate-800 flex items-center justify-between text-xs">
                    <a href="<?= site_url('forgot-password') ?>" class="font-bold text-slate-400 hover:text-white transition inline-flex items-center gap-1.5">
                        <i class="fa-solid fa-arrow-left text-[10px]"></i>
                        <span>Minta Token Baru</span>
                    </a>
                    <a href="<?= site_url('login') ?>" class="text-brand-400 hover:text-brand-300 font-semibold text-[11px]">
                        Ke Halaman Login
                    </a>
                </div>

            <?php elseif (!$tokenValid): ?>
                <!-- STATE 2: TOKEN TIDAK VALID / KEDALUWARSA / SUDAH DIGUNAKAN -->
                <div class="text-center space-y-4 py-2">
                    <div class="w-16 h-16 rounded-3xl bg-rose-500/10 border border-rose-500/20 text-rose-400 flex items-center justify-center text-3xl mx-auto shadow-xl shadow-rose-500/10">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>

                    <div class="space-y-1.5">
                        <h2 class="text-xl font-extrabold text-white tracking-tight">Tautan Reset Tidak Dapat Digunakan</h2>
                        <p class="text-xs text-rose-300 bg-rose-500/10 border border-rose-500/20 p-3 rounded-xl leading-relaxed">
                            <?= esc($tokenError ?? 'Tautan atau token reset kata sandi tidak valid atau telah kedaluwarsa.') ?>
                        </p>
                    </div>

                    <div class="p-4 rounded-2xl bg-slate-950/70 border border-slate-800 text-left space-y-2 text-xs">
                        <span class="font-bold text-slate-300 block text-[11px]">Mengapa ini terjadi?</span>
                        <ul class="text-[10px] text-slate-400 space-y-1 list-disc pl-4">
                            <li>Masa berlaku tautan terbatas <strong>15 menit</strong> demi keamanan akun.</li>
                            <li>Tautan hanya dapat digunakan <strong>1 kali</strong> (single-use).</li>
                            <li>Permintaan reset baru otomatis membatalkan tautan reset lama.</li>
                        </ul>
                    </div>

                    <div class="space-y-2.5 pt-2">
                        <a href="<?= site_url('forgot-password') ?>" class="w-full py-3.5 bg-gradient-to-r from-brand-600 to-indigo-600 hover:from-brand-500 hover:to-indigo-500 text-white font-bold text-xs rounded-xl shadow-lg shadow-brand-500/25 transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-rotate-right"></i>
                            <span>Ajukan Permintaan Reset Baru</span>
                        </a>

                        <a href="<?= site_url('login') ?>" class="w-full py-2.5 bg-slate-800/60 hover:bg-slate-800 text-slate-300 font-semibold text-xs rounded-xl border border-slate-700/60 transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-arrow-left text-[11px]"></i>
                            <span>Kembali ke Halaman Login</span>
                        </a>
                    </div>
                </div>

            <?php else: ?>
                <!-- STATE 3: TOKEN VALID - FORM ATUR KATA SANDI BARU -->
                <div class="text-center space-y-2 mb-5">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-emerald-600 to-teal-600 text-white flex items-center justify-center text-xl font-black mx-auto shadow-lg shadow-emerald-500/25">
                        <i class="fa-solid fa-lock-open"></i>
                    </div>
                    <h1 class="text-2xl font-extrabold text-white tracking-tight">Buat Kata Sandi Baru</h1>
                    <p class="text-xs text-slate-400">Tautan terverifikasi. Masukkan kata sandi baru untuk akun Anda.</p>
                </div>

                <!-- Account & Expiration Status Badge -->
                <div class="p-3.5 rounded-2xl bg-slate-950/80 border border-slate-800 space-y-2 mb-5 text-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400 text-[11px]">Akun Pengurus:</span>
                        <span class="font-bold text-white text-[11px] truncate max-w-[200px]">
                            <?= esc($user['name'] ?? $record['email']) ?>
                        </span>
                    </div>
                    <div class="flex items-center justify-between border-t border-slate-800/80 pt-2">
                        <span class="text-slate-400 text-[11px]">Sisa Masa Berlaku:</span>
                        <span id="countdownBadge" class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                            <span id="countdownText"><?= esc($minutesRemaining) ?> Menit</span>
                        </span>
                    </div>
                </div>

                <!-- Flash Alerts -->
                <?php if (session()->getFlashdata('error') || session()->getFlashdata('errors')): ?>
                    <div class="mb-5 p-3.5 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 text-xs space-y-1">
                        <div class="flex items-center gap-2 font-bold">
                            <i class="fa-solid fa-circle-xmark text-sm"></i>
                            <span><?= esc(session()->getFlashdata('error')) ?></span>
                        </div>
                        <?php if (is_array(session()->getFlashdata('errors'))): ?>
                            <ul class="list-disc pl-5 text-[11px]">
                                <?php foreach (session()->getFlashdata('errors') as $err): ?>
                                    <li><?= esc($err) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <form action="<?= site_url('reset-password') ?>" method="post" class="space-y-4">
                    <?= csrf_field() ?>
                    <input type="hidden" name="token" value="<?= esc($token) ?>">
                    <input type="hidden" name="email" value="<?= esc($email ?? ($record['email'] ?? '')) ?>">

                    <!-- Kata Sandi Baru -->
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1.5">Kata Sandi Baru <span class="text-rose-400">*</span></label>
                        <div class="relative">
                            <i class="fa-solid fa-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                            <input type="password" name="new_password" id="new_password" required minlength="6" oninput="checkStrength(this.value)" placeholder="Minimal 6 karakter" class="w-full pl-10 pr-10 py-3 rounded-xl bg-slate-900/80 border border-slate-700 text-white text-xs sm:text-sm focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 placeholder:text-slate-500">
                            <button type="button" onclick="togglePass('new_password', this)" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-200">
                                <i class="fa-regular fa-eye text-xs"></i>
                            </button>
                        </div>

                        <!-- Strength Meter Bar -->
                        <div class="mt-2 space-y-1">
                            <div class="w-full bg-slate-800 h-1.5 rounded-full overflow-hidden">
                                <div id="strengthBar" class="h-full w-0 transition-all duration-300 bg-rose-500"></div>
                            </div>
                            <div class="flex items-center justify-between text-[10px] text-slate-400">
                                <span>Kekuatan Kata Sandi:</span>
                                <span id="strengthLabel" class="font-bold text-slate-400">Belum diisi</span>
                            </div>
                        </div>
                    </div>

                    <!-- Konfirmasi Kata Sandi Baru -->
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1.5">Konfirmasi Kata Sandi Baru <span class="text-rose-400">*</span></label>
                        <div class="relative">
                            <i class="fa-solid fa-shield-halved absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                            <input type="password" name="confirm_password" id="confirm_password" required minlength="6" oninput="checkMatch()" placeholder="Ketik ulang kata sandi baru" class="w-full pl-10 pr-10 py-3 rounded-xl bg-slate-900/80 border border-slate-700 text-white text-xs sm:text-sm focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 placeholder:text-slate-500">
                            <button type="button" onclick="togglePass('confirm_password', this)" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-200">
                                <i class="fa-regular fa-eye text-xs"></i>
                            </button>
                        </div>
                        <p id="matchText" class="text-[10px] text-slate-500 mt-1"></p>
                    </div>

                    <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold text-xs sm:text-sm rounded-xl shadow-lg shadow-emerald-500/25 transition mt-3 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-circle-check"></i>
                        <span>Simpan & Perbarui Kata Sandi</span>
                    </button>
                </form>

                <div class="mt-6 pt-5 border-t border-slate-800 text-center">
                    <a href="<?= site_url('login') ?>" class="text-xs font-bold text-slate-400 hover:text-white transition inline-flex items-center gap-1.5">
                        <i class="fa-solid fa-arrow-left text-[10px]"></i>
                        <span>Batal & Kembali ke Login</span>
                    </a>
                </div>

                <!-- Timer JavaScript Countdown -->
                <script>
                    let remainingSeconds = <?= (int) ($secondsRemaining ?? 900) ?>;

                    function updateTimer() {
                        if (remainingSeconds <= 0) {
                            document.getElementById('countdownText').textContent = 'Kedaluwarsa';
                            document.getElementById('countdownBadge').className = 'inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-500/20 text-rose-300 border border-rose-500/30';
                            alert('Masa berlaku tautan reset kata sandi telah habis. Halaman akan dimuat ulang.');
                            window.location.reload();
                            return;
                        }

                        const mins = Math.floor(remainingSeconds / 60);
                        const secs = remainingSeconds % 60;
                        document.getElementById('countdownText').textContent = `${mins}m ${secs < 10 ? '0' : ''}${secs}s`;

                        if (remainingSeconds < 180) {
                            document.getElementById('countdownBadge').className = 'inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-500/20 text-rose-300 border border-rose-500/30';
                        }

                        remainingSeconds--;
                    }

                    setInterval(updateTimer, 1000);
                    updateTimer();

                    function checkStrength(val) {
                        const bar = document.getElementById('strengthBar');
                        const label = document.getElementById('strengthLabel');

                        let score = 0;
                        if (val.length >= 6) score++;
                        if (val.length >= 10) score++;
                        if (/[A-Z]/.test(val) && /[a-z]/.test(val)) score++;
                        if (/[0-9]/.test(val)) score++;
                        if (/[^A-Za-z0-9]/.test(val)) score++;

                        if (val.length === 0) {
                            bar.style.width = '0%';
                            label.textContent = 'Belum diisi';
                            label.className = 'font-bold text-slate-400';
                        } else if (score <= 2) {
                            bar.style.width = '33%';
                            bar.className = 'h-full transition-all duration-300 bg-rose-500';
                            label.textContent = 'Lemah';
                            label.className = 'font-bold text-rose-400';
                        } else if (score <= 4) {
                            bar.style.width = '66%';
                            bar.className = 'h-full transition-all duration-300 bg-amber-500';
                            label.textContent = 'Sedang';
                            label.className = 'font-bold text-amber-400';
                        } else {
                            bar.style.width = '100%';
                            bar.className = 'h-full transition-all duration-300 bg-emerald-500';
                            label.textContent = 'Sangat Kuat';
                            label.className = 'font-bold text-emerald-400';
                        }
                    }

                    function checkMatch() {
                        const p1 = document.getElementById('new_password').value;
                        const p2 = document.getElementById('confirm_password').value;
                        const matchText = document.getElementById('matchText');

                        if (p2.length === 0) {
                            matchText.textContent = '';
                        } else if (p1 === p2) {
                            matchText.textContent = '✓ Kata sandi cocok';
                            matchText.className = 'text-[10px] text-emerald-400 mt-1 font-semibold';
                        } else {
                            matchText.textContent = '✗ Kata sandi belum cocok';
                            matchText.className = 'text-[10px] text-rose-400 mt-1 font-semibold';
                        }
                    }
                </script>

            <?php endif; ?>

        </div>

        <p class="text-xs text-slate-500 text-center">
            Pusat Keamanan Akun Portal Resmi HIMATIKA
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
