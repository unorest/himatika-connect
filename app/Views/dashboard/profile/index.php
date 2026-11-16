<?= $this->extend('layout/admin') ?>

<?= $this->section('content') ?>
<div class="space-y-6 max-w-5xl">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-brand-600 uppercase tracking-wider mb-1">
                <i class="fa-solid fa-user-shield"></i>
                <span>Keamanan Akun & Pengaturan</span>
            </div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Profil & Ganti Kata Sandi</h1>
            <p class="text-xs text-slate-500 mt-1">Kelola data profil pengurus dan perbarui kata sandi akun Anda secara berkala.</p>
        </div>

        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200">
                <i class="fa-regular fa-clock text-slate-400"></i>
                Terakhir Login: <?= $user['last_login'] ? date('d M Y, H:i', strtotime($user['last_login'])) : 'Sesi Ini' ?>
            </span>
        </div>
    </div>

    <!-- Error Validation Display -->
    <?php if (session()->getFlashdata('errors')): ?>
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs shadow-sm">
            <div class="flex items-center gap-2 font-bold mb-1.5 text-rose-700">
                <i class="fa-solid fa-triangle-exclamation text-sm"></i>
                <span>Terdapat kesalahan pengisian formulir:</span>
            </div>
            <ul class="list-disc list-inside space-y-1 text-slate-600 pl-2">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- SISI KIRI: Kartu Identitas Akun -->
        <div class="lg:col-span-4 space-y-6">

            <!-- Card Profil -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="h-24 bg-gradient-to-r from-brand-600 via-indigo-600 to-sky-500 relative">
                    <div class="absolute -bottom-9 left-6">
                        <div class="w-18 h-18 rounded-2xl bg-white p-1 shadow-md">
                            <div class="w-full h-full rounded-xl bg-gradient-to-tr from-brand-700 to-indigo-600 flex items-center justify-center text-white text-2xl font-black shadow-inner">
                                <?= strtoupper(substr($user['name'], 0, 1)) ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-12 p-6">
                    <h3 class="text-base font-extrabold text-slate-800 leading-snug"><?= esc($user['name']) ?></h3>
                    <p class="text-xs font-mono font-medium text-slate-500 mt-0.5">NIM: <?= esc($user['nim']) ?></p>

                    <div class="flex flex-wrap items-center gap-1.5 mt-3">
                        <?php
                        $roleBadges = [
                            'admin' => ['label' => 'Super Admin (BPH)', 'bg' => 'bg-rose-500/10 text-rose-600 border-rose-500/20'],
                            'kadiv' => ['label' => 'Kepala Divisi', 'bg' => 'bg-emerald-500/10 text-emerald-600 border-emerald-500/20'],
                            'staff' => ['label' => 'Anggota (Staff)', 'bg' => 'bg-blue-500/10 text-blue-600 border-blue-500/20'],
                        ];
                        $roleInfo = $roleBadges[$user['role']] ?? ['label' => $user['role'], 'bg' => 'bg-slate-100 text-slate-600 border-slate-200'];
                        ?>
                        <span class="px-2.5 py-1 rounded-lg text-[10px] font-extrabold uppercase border tracking-wider <?= $roleInfo['bg'] ?>">
                            <?= $roleInfo['label'] ?>
                        </span>

                        <?php if (!empty($user['division_name'])): ?>
                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                <?= esc($user['division_name']) ?>
                            </span>
                        <?php else: ?>
                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-slate-100 text-slate-500 border border-slate-200">
                                Badan Pengurus Harian
                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="mt-6 pt-5 border-t border-slate-100 space-y-3 text-xs">
                        <div class="flex items-center justify-between text-slate-600">
                            <span class="text-slate-400 flex items-center gap-2">
                                <i class="fa-regular fa-envelope w-4 text-center"></i> Email
                            </span>
                            <span class="font-semibold truncate max-w-[170px]" title="<?= esc($user['email']) ?>"><?= esc($user['email']) ?></span>
                        </div>
                        <div class="flex items-center justify-between text-slate-600">
                            <span class="text-slate-400 flex items-center gap-2">
                                <i class="fa-regular fa-id-badge w-4 text-center"></i> Status Akun
                            </span>
                            <span class="font-bold text-emerald-600 flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                Aktif
                            </span>
                        </div>
                        <div class="flex items-center justify-between text-slate-600">
                            <span class="text-slate-400 flex items-center gap-2">
                                <i class="fa-regular fa-calendar-plus w-4 text-center"></i> Terdaftar Sejak
                            </span>
                            <span class="font-medium text-slate-700"><?= $user['created_at'] ? date('d M Y', strtotime($user['created_at'])) : '-' ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kartu Tips Keamanan -->
            <div class="bg-amber-50/70 border border-amber-200/80 rounded-2xl p-5 text-amber-900 text-xs shadow-sm">
                <div class="flex items-center gap-2 font-bold mb-2 text-amber-800">
                    <i class="fa-solid fa-shield-halved text-amber-600 text-sm"></i>
                    <span>Tips Keamanan Sandi</span>
                </div>
                <ul class="space-y-1.5 text-amber-800/90 text-[11px] list-disc list-inside">
                    <li>Gunakan minimal <strong>6 karakter</strong> atau lebih.</li>
                    <li>Kombinasikan huruf besar, huruf kecil, angka, dan simbol.</li>
                    <li>Jangan membagikan kata sandi kepada orang lain demi integritas data himpunan.</li>
                </ul>
            </div>

        </div>

        <!-- SISI KANAN: Form Ganti Kata Sandi & Ubah Profil -->
        <div class="lg:col-span-8 space-y-6">

            <!-- Card 1: Form Ganti Kata Sandi (UTAMA) -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-5 sm:p-6 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-600 flex items-center justify-center font-bold text-base">
                            <i class="fa-solid fa-key"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-slate-800">Ganti Kata Sandi (Password)</h2>
                            <p class="text-xs text-slate-400">Pastikan Anda mengingat kata sandi baru yang akan disimpan.</p>
                        </div>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                        Keamanan
                    </span>
                </div>

                <form action="<?= site_url('dashboard/profile/password') ?>" method="POST" class="p-5 sm:p-6 space-y-5">
                    <?= csrf_field() ?>

                    <!-- Kata Sandi Saat Ini -->
                    <div>
                        <label for="current_password" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Kata Sandi Saat Ini <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" id="current_password" name="current_password" required
                                placeholder="Masukkan kata sandi yang sedang digunakan"
                                class="w-full px-4 py-2.5 pr-11 text-xs rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                            <button type="button" onclick="togglePasswordVisibility('current_password', this)" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 focus:outline-none">
                                <i class="fa-regular fa-eye text-xs"></i>
                            </button>
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">Jika belum pernah diubah, kata sandi bawaan adalah <code class="bg-slate-100 px-1 py-0.5 rounded text-slate-600">password123</code>.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-1">
                        <!-- Kata Sandi Baru -->
                        <div>
                            <label for="new_password" class="block text-xs font-bold text-slate-700 mb-1.5">
                                Kata Sandi Baru <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="password" id="new_password" name="new_password" required minlength="6"
                                    placeholder="Minimal 6 karakter"
                                    class="w-full px-4 py-2.5 pr-11 text-xs rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                                <button type="button" onclick="togglePasswordVisibility('new_password', this)" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 focus:outline-none">
                                    <i class="fa-regular fa-eye text-xs"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Konfirmasi Kata Sandi Baru -->
                        <div>
                            <label for="confirm_password" class="block text-xs font-bold text-slate-700 mb-1.5">
                                Ulangi Kata Sandi Baru <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="password" id="confirm_password" name="confirm_password" required minlength="6"
                                    placeholder="Ketik ulang kata sandi baru"
                                    class="w-full px-4 py-2.5 pr-11 text-xs rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                                <button type="button" onclick="togglePasswordVisibility('confirm_password', this)" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 focus:outline-none">
                                    <i class="fa-regular fa-eye text-xs"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="pt-2 flex justify-end">
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs shadow-md shadow-amber-500/20 transition flex items-center gap-2">
                            <i class="fa-solid fa-lock"></i>
                            <span>Simpan Kata Sandi Baru</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Card 2: Form Perbarui Informasi Akun -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-5 sm:p-6 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-brand-500/10 text-brand-600 flex items-center justify-center font-bold text-base">
                            <i class="fa-solid fa-user-pen"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-slate-800">Informasi Pribadi & Kontak</h2>
                            <p class="text-xs text-slate-400">Perbarui nama tampilan dan alamat email koordinasi Anda.</p>
                        </div>
                    </div>
                </div>

                <form action="<?= site_url('dashboard/profile/update') ?>" method="POST" class="p-5 sm:p-6 space-y-4">
                    <?= csrf_field() ?>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Nama Lengkap -->
                        <div>
                            <label for="name" class="block text-xs font-bold text-slate-700 mb-1.5">
                                Nama Lengkap <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="name" name="name" required value="<?= esc(old('name', $user['name'])) ?>"
                                class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-xs font-bold text-slate-700 mb-1.5">
                                Alamat Email Login <span class="text-rose-500">*</span>
                            </label>
                            <input type="email" id="email" name="email" required value="<?= esc(old('email', $user['email'])) ?>"
                                class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-1">
                        <!-- NIM (Terkunci) -->
                        <div>
                            <label class="block text-xs font-bold text-slate-400 mb-1.5">
                                Nomor Induk Mahasiswa (NIM)
                            </label>
                            <div class="relative">
                                <input type="text" value="<?= esc($user['nim']) ?>" disabled
                                    class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 bg-slate-100 text-slate-500 cursor-not-allowed">
                                <span class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs">
                                    <i class="fa-solid fa-lock"></i>
                                </span>
                            </div>
                            <p class="text-[10px] text-slate-400 mt-1">Hubungi Administrator BPH jika terdapat kesalahan pencatatan NIM.</p>
                        </div>

                        <!-- Jabatan / Divisi (Terkunci) -->
                        <div>
                            <label class="block text-xs font-bold text-slate-400 mb-1.5">
                                Divisi Penugasan
                            </label>
                            <div class="relative">
                                <input type="text" value="<?= esc($user['division_name'] ?? 'Badan Pengurus Harian (BPH)') ?>" disabled
                                    class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 bg-slate-100 text-slate-500 cursor-not-allowed">
                                <span class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs">
                                    <i class="fa-solid fa-lock"></i>
                                </span>
                            </div>
                            <p class="text-[10px] text-slate-400 mt-1">Struktur divisi diatur oleh Ketua / BPH pada menu Kelola Pengurus.</p>
                        </div>
                    </div>

                    <div class="pt-2 flex justify-end">
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-md shadow-brand-600/20 transition flex items-center gap-2">
                            <i class="fa-solid fa-check"></i>
                            <span>Simpan Perubahan Profil</span>
                        </button>
                    </div>
                </form>
            </div>

        </div>

    </div>

</div>

<script>
function togglePasswordVisibility(fieldId, btn) {
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
<?= $this->endSection() ?>
