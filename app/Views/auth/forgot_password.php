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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
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

            <?php if (session()->getFlashdata('reset_sent')): ?>
                <!-- STATUS TERKIRIM (GENERIC CONFIRMATION SCREEN) -->
                <div class="text-center space-y-4">
                    <div class="w-16 h-16 rounded-3xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center text-3xl mx-auto shadow-xl shadow-emerald-500/10">
                        <i class="fa-solid fa-paper-plane"></i>
                    </div>

                    <div class="space-y-1.5">
                        <h2 class="text-2xl font-extrabold text-white tracking-tight">Permintaan Diproses</h2>
                        <p class="text-xs text-slate-400 leading-relaxed">
                            <?= esc(session()->getFlashdata('success') ?? 'Jika data cocok dengan akun aktif, tautan & kode verifikasi telah dikirimkan.') ?>
                        </p>
                        <div class="inline-block px-3.5 py-1.5 rounded-xl bg-slate-800 border border-slate-700 font-mono text-xs font-bold text-brand-300 mt-1">
                            <?php if (session()->getFlashdata('reset_channel') === 'whatsapp'): ?>
                                <i class="fa-brands fa-whatsapp text-emerald-400 mr-1.5"></i>
                            <?php else: ?>
                                <i class="fa-regular fa-envelope text-brand-400 mr-1.5"></i>
                            <?php endif; ?>
                            <?= esc(session()->getFlashdata('reset_target')) ?>
                        </div>
                    </div>

                    <!-- Notice Box -->
                    <div class="p-4 rounded-2xl bg-slate-950/70 border border-slate-800/80 text-left space-y-2.5 text-xs">
                        <div class="flex items-center gap-2 text-amber-400 font-bold text-[11px]">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                            <span>Masa Berlaku Terbatas: 15 Menit</span>
                        </div>
                        <p class="text-[11px] text-slate-400 leading-relaxed">
                            Periksa pesan resmi pada saluran terdaftar Anda. Jika Anda memilih WhatsApp namun belum mendaftarkan nomor di profil akun, silakan coba metode <strong>Email Resmi</strong> atau hubungi BPH.
                        </p>
                        <div class="pt-2 border-t border-slate-800 text-[10px] text-slate-500 flex items-center gap-1.5">
                            <i class="fa-solid fa-shield-halved text-brand-400"></i>
                            <span>Token kriptografi acak (CSPRNG 256-bit) sekali pakai.</span>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="space-y-2.5 pt-2">
                        <a href="<?= site_url('reset-password') ?>" class="w-full py-3.5 bg-gradient-to-r from-brand-600 to-indigo-600 hover:from-brand-500 hover:to-indigo-500 text-white font-bold text-xs rounded-xl shadow-lg shadow-brand-500/25 transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-key"></i>
                            <span>Punya Kode / Buka Halaman Input Token</span>
                        </a>

                        <a href="<?= site_url('forgot-password') ?>" class="w-full py-2.5 bg-slate-800/60 hover:bg-slate-800 text-slate-300 font-semibold text-xs rounded-xl border border-slate-700/60 transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-rotate-right text-[11px]"></i>
                            <span>Kirim Ulang / Gunakan Metode Lain</span>
                        </a>
                    </div>
                </div>

            <?php else: ?>
                <!-- FORM PERMINTAAN TOKEN RESET -->
                <div class="text-center space-y-2 mb-6">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-brand-600 to-indigo-600 text-white flex items-center justify-center text-xl font-black mx-auto shadow-lg shadow-brand-500/25">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <h1 class="text-2xl font-extrabold text-white tracking-tight">Lupa Kata Sandi</h1>
                    <p class="text-xs text-slate-400">Tautan reset dikirim hanya ke kontak resmi yang terdaftar di akun Anda (berlaku 15 menit)</p>
                </div>

                <!-- Flash Alerts -->
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="mb-5 p-3.5 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs flex items-center gap-2.5">
                        <i class="fa-solid fa-circle-check text-sm"></i>
                        <span><?= esc(session()->getFlashdata('success')) ?></span>
                    </div>
                <?php endif; ?>

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

                <form action="<?= site_url('forgot-password') ?>" method="post" class="space-y-4">
                    <?= csrf_field() ?>

                    <!-- Pilihan Metode Pengiriman Token (WhatsApp Terdaftar vs Email Resmi) -->
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-2">Metode Pengiriman Token <span class="text-rose-400">*</span></label>
                        <div class="grid grid-cols-2 gap-2.5">
                            <!-- WhatsApp Option -->
                            <label class="relative flex flex-col p-3 rounded-2xl border cursor-pointer transition select-none has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-500/10 border-slate-700 bg-slate-900/60 hover:bg-slate-800/80">
                                <input type="radio" name="channel" value="whatsapp" <?= old('channel', 'whatsapp') === 'whatsapp' ? 'checked' : '' ?> onchange="toggleChannel(this.value)" class="sr-only">
                                <div class="flex items-center gap-2 text-emerald-400 font-bold text-xs mb-1">
                                    <i class="fa-brands fa-whatsapp text-sm"></i>
                                    <span>WhatsApp</span>
                                </div>
                                <span class="text-[10px] text-slate-400">Ke nomor terdaftar di profil</span>
                            </label>

                            <!-- Email Option -->
                            <label class="relative flex flex-col p-3 rounded-2xl border cursor-pointer transition select-none has-[:checked]:border-brand-500 has-[:checked]:bg-brand-500/10 border-slate-700 bg-slate-900/60 hover:bg-slate-800/80">
                                <input type="radio" name="channel" value="email" <?= old('channel') === 'email' ? 'checked' : '' ?> onchange="toggleChannel(this.value)" class="sr-only">
                                <div class="flex items-center gap-2 text-brand-400 font-bold text-xs mb-1">
                                    <i class="fa-regular fa-envelope text-sm"></i>
                                    <span>Email Resmi</span>
                                </div>
                                <span class="text-[10px] text-slate-400">Ke inbox email akun</span>
                            </label>
                        </div>
                    </div>

                    <!-- Email Pengurus -->
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1.5">Email Pengurus Terdaftar <span class="text-rose-400">*</span></label>
                        <div class="relative">
                            <i class="fa-regular fa-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                            <input type="email" name="email" required value="<?= esc(old('email')) ?>" placeholder="contoh: rendy@himatika.org" class="w-full pl-10 pr-4 py-3 rounded-xl bg-slate-900/80 border border-slate-700 text-white text-xs sm:text-sm focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 placeholder:text-slate-500">
                        </div>
                    </div>

                    <!-- NIM Pengurus -->
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1.5">Nomor Induk Mahasiswa (NIM) <span class="text-rose-400">*</span></label>
                        <div class="relative">
                            <i class="fa-regular fa-id-badge absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                            <input type="text" name="nim" required value="<?= esc(old('nim')) ?>" placeholder="contoh: 240401010288" class="w-full pl-10 pr-4 py-3 rounded-xl bg-slate-900/80 border border-slate-700 text-white text-xs sm:text-sm focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 placeholder:text-slate-500">
                        </div>
                        <p class="text-[10px] text-slate-500 mt-1">Verifikasi identitas awal sebelum pengiriman token ke kontak terdaftar.</p>
                    </div>

                    <!-- Info Pengiriman WhatsApp Terdaftar (Tanpa Input Bebas) -->
                    <div id="waInfoBox" class="<?= old('channel', 'whatsapp') === 'whatsapp' ? '' : 'hidden' ?> p-3 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-[11px] text-emerald-300 flex items-start gap-2.5">
                        <i class="fa-brands fa-whatsapp text-emerald-400 text-sm mt-0.5"></i>
                        <span>Demi keamanan akun, tautan & kode reset hanya dikirim ke <strong>nomor WhatsApp yang sudah tersimpan di Profil Anda</strong>. Jika belum terdaftar, pilih metode <strong>Email Resmi</strong>.</span>
                    </div>

                    <!-- Security Specifications Banner -->
                    <div class="p-3.5 rounded-2xl bg-slate-950/60 border border-slate-800 text-[11px] text-slate-400 space-y-1.5">
                        <div class="font-bold text-slate-300 flex items-center gap-1.5">
                            <i class="fa-solid fa-lock text-brand-400"></i>
                            <span>Protokol Keamanan Reset Kata Sandi:</span>
                        </div>
                        <ul class="space-y-1 text-[10px] text-slate-400 pl-4 list-disc">
                            <li><strong>Kontak Terdaftar:</strong> Token hanya dikirim ke email/WhatsApp yang tercatat di database akun.</li>
                            <li><strong>CSPRNG 256-bit & Masa Berlaku 15 Menit:</strong> Token terenkripsi hash SHA-256 dan otomatis kedaluwarsa.</li>
                            <li><strong>Single-Use & Rate-Limited:</strong> Langsung hangus setelah dipakai dan terlindungi batas percobaan.</li>
                        </ul>
                    </div>

                    <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-brand-600 to-indigo-600 hover:from-brand-500 hover:to-indigo-500 text-white font-bold text-xs sm:text-sm rounded-xl shadow-lg shadow-brand-500/25 transition mt-3 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-paper-plane text-xs"></i>
                        <span>Kirim Tautan & Kode Reset</span>
                    </button>
                </form>

                <div class="mt-6 pt-5 border-t border-slate-800 flex items-center justify-between text-xs">
                    <a href="<?= site_url('login') ?>" class="font-bold text-slate-400 hover:text-white transition inline-flex items-center gap-1.5">
                        <i class="fa-solid fa-arrow-left text-[10px]"></i>
                        <span>Kembali ke Login</span>
                    </a>

                    <a href="<?= site_url('reset-password') ?>" class="text-brand-400 hover:text-brand-300 font-semibold text-[11px] inline-flex items-center gap-1">
                        <span>Punya Token? Masukkan di Sini</span>
                        <i class="fa-solid fa-chevron-right text-[9px]"></i>
                    </a>
                </div>

            <?php endif; ?>

        </div>

        <p class="text-xs text-slate-500 text-center">
            Mengalami kesulitan verifikasi? Hubungi Administrator BPH HIMATIKA.
        </p>

    </div>

    <script>
        function toggleChannel(channel) {
            const waInfoBox = document.getElementById('waInfoBox');
            if (!waInfoBox) return;
            if (channel === 'whatsapp') {
                waInfoBox.classList.remove('hidden');
            } else {
                waInfoBox.classList.add('hidden');
            }
        }
    </script>
</body>
</html>
