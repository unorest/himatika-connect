<?= $this->extend('layout/admin') ?>

<?= $this->section('content') ?>

<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <span class="text-xs font-bold text-brand-600 uppercase tracking-widest block">Administrasi BPH</span>
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight mt-0.5">Kelola Pengurus & Hak Akses (RBAC)</h1>
        <p class="text-xs text-slate-500 mt-1">Atur data anggota pengurus, penempatan divisi kerja, dan tingkatan peran (Admin, Kadiv, Staff)</p>
    </div>

    <div>
        <button type="button" onclick="openAddModal()" class="px-4 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs rounded-xl shadow-md shadow-brand-500/20 transition flex items-center gap-2">
            <i class="fa-solid fa-user-plus"></i>
            <span>Tambah Pengurus Baru</span>
        </button>
    </div>
</div>

<!-- Flash Alerts -->
<?php if (session()->getFlashdata('success')): ?>
    <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center gap-3">
        <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
        <span><?= session()->getFlashdata('success') ?></span>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs flex items-center gap-3">
        <i class="fa-solid fa-circle-exclamation text-rose-600 text-base"></i>
        <span><?= session()->getFlashdata('error') ?></span>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('errors')): ?>
    <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs">
        <div class="font-bold mb-1 flex items-center gap-2">
            <i class="fa-solid fa-triangle-exclamation text-rose-600"></i>
            <span>Terdapat kesalahan pengisian data:</span>
        </div>
        <ul class="list-disc list-inside space-y-0.5 text-rose-700 ml-2">
            <?php foreach (session()->getFlashdata('errors') as $err): ?>
                <li><?= esc($err) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<!-- Quick Stats -->
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 mb-6">
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Total Pengurus</span>
        <div class="text-2xl font-black text-slate-900 mt-1"><?= $stats['total'] ?></div>
        <span class="text-[10px] text-slate-500 font-medium">Terdaftar di sistem</span>
    </div>
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
        <span class="text-[11px] font-bold text-purple-500 uppercase tracking-wider block">Admin / BPH</span>
        <div class="text-2xl font-black text-purple-600 mt-1"><?= $stats['admin_count'] ?></div>
        <span class="text-[10px] text-slate-500 font-medium">Akses penuh</span>
    </div>
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
        <span class="text-[11px] font-bold text-amber-500 uppercase tracking-wider block">Kepala Divisi</span>
        <div class="text-2xl font-black text-amber-600 mt-1"><?= $stats['kadiv_count'] ?></div>
        <span class="text-[10px] text-slate-500 font-medium">Koordinator bidang</span>
    </div>
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
        <span class="text-[11px] font-bold text-sky-500 uppercase tracking-wider block">Staff Divisi</span>
        <div class="text-2xl font-black text-sky-600 mt-1"><?= $stats['staff_count'] ?></div>
        <span class="text-[10px] text-slate-500 font-medium">Anggota pelaksana</span>
    </div>
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm col-span-2 sm:col-span-1">
        <span class="text-[11px] font-bold text-emerald-500 uppercase tracking-wider block">Akun Aktif</span>
        <div class="text-2xl font-black text-emerald-600 mt-1"><?= $stats['active_count'] ?></div>
        <span class="text-[10px] text-slate-500 font-medium">Dapat login sistem</span>
    </div>
</div>

<!-- Filter Bar -->
<div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm mb-6">
    <form action="<?= site_url('dashboard/pengurus') ?>" method="get" class="flex flex-col sm:flex-row gap-3">
        <div class="relative flex-1">
            <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
            <input type="text" name="q" value="<?= esc($searchQuery ?? '') ?>" placeholder="Cari nama pengurus, NIM, email..." class="w-full pl-9 pr-4 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-brand-500">
        </div>

        <select name="role" onchange="this.form.submit()" class="py-2 px-3 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500 bg-white text-slate-700">
            <option value="">Semua Tingkat Peran (Role)</option>
            <option value="admin" <?= ($selectedRole ?? '') === 'admin' ? 'selected' : '' ?>>Admin (BPH)</option>
            <option value="kadiv" <?= ($selectedRole ?? '') === 'kadiv' ? 'selected' : '' ?>>Kadiv (Kepala Divisi)</option>
            <option value="staff" <?= ($selectedRole ?? '') === 'staff' ? 'selected' : '' ?>>Staff (Anggota)</option>
        </select>

        <select name="division_id" onchange="this.form.submit()" class="py-2 px-3 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500 bg-white text-slate-700">
            <option value="">Semua Divisi</option>
            <?php foreach ($divisions as $d): ?>
                <option value="<?= $d['id'] ?>" <?= ($selectedDivision ?? 0) == $d['id'] ? 'selected' : '' ?>>
                    <?= esc($d['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <select name="status" onchange="this.form.submit()" class="py-2 px-3 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500 bg-white text-slate-700">
            <option value="">Semua Status</option>
            <option value="1" <?= ($selectedStatus ?? '') === '1' ? 'selected' : '' ?>>Aktif</option>
            <option value="0" <?= ($selectedStatus ?? '') === '0' ? 'selected' : '' ?>>Nonaktif</option>
        </select>

        <?php if (!empty($searchQuery) || !empty($selectedRole) || !empty($selectedDivision) || ($selectedStatus !== null && $selectedStatus !== '')): ?>
            <a href="<?= site_url('dashboard/pengurus') ?>" class="px-3.5 py-2 rounded-xl text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition flex items-center justify-center">
                Reset
            </a>
        <?php endif; ?>
    </form>
</div>

<!-- Table Card -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-xs">
            <thead>
                <tr class="border-b border-slate-200 bg-slate-50/75 text-slate-500 font-bold tracking-wider uppercase text-[10px]">
                    <th class="py-3.5 px-4">Pengurus</th>
                    <th class="py-3.5 px-4">Kontak / NIM</th>
                    <th class="py-3.5 px-4">Divisi Kerja</th>
                    <th class="py-3.5 px-4">Tingkat Peran</th>
                    <th class="py-3.5 px-4 text-center">Status</th>
                    <th class="py-3.5 px-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="6" class="py-12 text-center text-slate-400">
                            <i class="fa-solid fa-user-slash text-3xl mb-2 block text-slate-300"></i>
                            <span class="font-semibold">Tidak ada data pengurus yang sesuai filter.</span>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($users as $u): ?>
                        <tr class="hover:bg-slate-50/75 transition">
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl flex items-center justify-center font-extrabold text-xs shadow-sm <?= $u['role'] === 'admin' ? 'bg-purple-100 text-purple-700' : ($u['role'] === 'kadiv' ? 'bg-amber-100 text-amber-700' : 'bg-brand-100 text-brand-700') ?>">
                                        <?= strtoupper(substr($u['name'], 0, 2)) ?>
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-900"><?= esc($u['name']) ?></div>
                                        <?php if ((int)$u['id'] === (int)session()->get('user_id')): ?>
                                            <span class="text-[9px] font-bold bg-indigo-50 text-indigo-600 px-1.5 py-0.5 rounded">Akun Anda</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-mono text-slate-800 font-medium"><?= esc($u['nim']) ?></div>
                                <div class="text-[11px] text-slate-400"><?= esc($u['email']) ?></div>
                            </td>
                            <td class="py-3.5 px-4">
                                <?php if (!empty($u['division_name'])): ?>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                        <span class="w-2 h-2 rounded-full" style="background-color: <?= esc($u['division_color'] ?? '#6366f1') ?>;"></span>
                                        <?= esc($u['division_name']) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-slate-400 italic">BPH / Lintas Divisi</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3.5 px-4">
                                <?php if ($u['role'] === 'admin'): ?>
                                    <span class="px-2.5 py-1 rounded-lg text-[10px] font-extrabold uppercase bg-purple-100 text-purple-700 border border-purple-200">
                                        <i class="fa-solid fa-crown mr-1"></i> Admin BPH
                                    </span>
                                <?php elseif ($u['role'] === 'kadiv'): ?>
                                    <span class="px-2.5 py-1 rounded-lg text-[10px] font-extrabold uppercase bg-amber-100 text-amber-800 border border-amber-200">
                                        <i class="fa-solid fa-star mr-1"></i> Kadiv
                                    </span>
                                <?php else: ?>
                                    <span class="px-2.5 py-1 rounded-lg text-[10px] font-extrabold uppercase bg-sky-100 text-sky-800 border border-sky-200">
                                        <i class="fa-solid fa-user mr-1"></i> Staff
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <?php if ($u['is_active']): ?>
                                    <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                        Aktif
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1 text-[11px] font-bold text-rose-600 bg-rose-50 px-2 py-0.5 rounded-full border border-rose-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        Nonaktif
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <button type="button" 
                                            onclick='openEditModal(<?= json_encode([
                                                'id' => $u['id'],
                                                'name' => $u['name'],
                                                'nim' => $u['nim'],
                                                'email' => $u['email'],
                                                'role' => $u['role'],
                                                'division_id' => $u['division_id'] ?? '',
                                                'is_active' => (int)$u['is_active']
                                            ]) ?>)'
                                            class="p-2 text-slate-500 hover:text-brand-600 hover:bg-brand-50 rounded-lg transition" 
                                            title="Edit Pengurus">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>

                                    <?php if ((int)$u['id'] !== (int)session()->get('user_id')): ?>
                                        <form action="<?= site_url('dashboard/pengurus/toggle-status/' . $u['id']) ?>" method="post" class="inline">
                                            <?= csrf_field() ?>
                                            <button type="submit" 
                                                    class="p-2 <?= $u['is_active'] ? 'text-slate-500 hover:text-amber-600 hover:bg-amber-50' : 'text-slate-500 hover:text-emerald-600 hover:bg-emerald-50' ?> rounded-lg transition" 
                                                    title="<?= $u['is_active'] ? 'Nonaktifkan Akun' : 'Aktifkan Akun' ?>">
                                                <i class="fa-solid <?= $u['is_active'] ? 'fa-user-lock' : 'fa-user-check' ?>"></i>
                                            </button>
                                        </form>

                                        <form action="<?= site_url('dashboard/pengurus/delete/' . $u['id']) ?>" method="post" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun pengurus <?= esc($u['name']) ?>?')">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="p-2 text-slate-500 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Hapus Pengurus">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah Pengurus -->
<div id="modalAdd" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm overflow-y-auto">
    <div class="min-h-full flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl border border-slate-200 shadow-2xl max-w-lg w-full p-6 transition-all">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-brand-50 text-brand-600 flex items-center justify-center font-bold">
                        <i class="fa-solid fa-user-plus text-base"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900">Tambah Akun Pengurus Baru</h3>
                        <p class="text-xs text-slate-500">Daftarkan anggota atau pimpinan divisi baru</p>
                    </div>
                </div>
                <button type="button" onclick="closeAddModal()" class="text-slate-400 hover:text-slate-600 text-lg p-1">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form action="<?= site_url('dashboard/pengurus/store') ?>" method="post" class="mt-4 space-y-4">
                <?= csrf_field() ?>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap Pengurus <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" required placeholder="Contoh: Muhammad Budi Santoso" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-brand-500">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">NIM Mahasiswa <span class="text-rose-500">*</span></label>
                        <input type="text" name="nim" required placeholder="Contoh: 22010045" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-mono focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Email Resmi / Kampus <span class="text-rose-500">*</span></label>
                        <input type="email" name="email" required placeholder="budi@himatika.org" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Kata Sandi Awal <span class="text-rose-500">*</span></label>
                    <input type="password" name="password" required minlength="6" placeholder="Minimal 6 karakter" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-brand-500">
                    <span class="text-[10px] text-slate-400 mt-0.5 block">Pengurus dapat mengubah sandi kapan saja setelah berhasil login.</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Tingkatan Peran (Role) <span class="text-rose-500">*</span></label>
                        <select name="role" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500 bg-white text-slate-800">
                            <option value="staff" selected>Staff (Anggota Divisi)</option>
                            <option value="kadiv">Kadiv (Kepala Divisi)</option>
                            <option value="admin">Admin (BPH / Pengurus Harian)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Divisi Kerja</label>
                        <select name="division_id" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500 bg-white text-slate-800">
                            <option value="">-- Tanpa Divisi (BPH) --</option>
                            <?php foreach ($divisions as $d): ?>
                                <option value="<?= $d['id'] ?>"><?= esc($d['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="pt-2 flex items-center justify-end gap-2 border-t border-slate-100">
                    <button type="button" onclick="closeAddModal()" class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-brand-600 hover:bg-brand-700 shadow-md shadow-brand-500/20 transition">
                        Simpan Pengurus
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Pengurus -->
<div id="modalEdit" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm overflow-y-auto">
    <div class="min-h-full flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl border border-slate-200 shadow-2xl max-w-lg w-full p-6 transition-all">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
                        <i class="fa-solid fa-user-pen text-base"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900">Perbarui Data Pengurus</h3>
                        <p class="text-xs text-slate-500">Ubah peran, penempatan divisi, atau status akun</p>
                    </div>
                </div>
                <button type="button" onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600 text-lg p-1">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form id="formEdit" action="" method="post" class="mt-4 space-y-4">
                <?= csrf_field() ?>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap Pengurus <span class="text-rose-500">*</span></label>
                    <input type="text" id="edit_name" name="name" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-brand-500">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">NIM Mahasiswa <span class="text-rose-500">*</span></label>
                        <input type="text" id="edit_nim" name="nim" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-mono focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Email Pengurus <span class="text-rose-500">*</span></label>
                        <input type="email" id="edit_email" name="email" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Ubah Kata Sandi (Opsional)</label>
                    <input type="password" name="password" minlength="6" placeholder="Biarkan kosong jika tidak ingin mengubah sandi" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-brand-500">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Tingkatan Peran (Role) <span class="text-rose-500">*</span></label>
                        <select id="edit_role" name="role" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500 bg-white text-slate-800">
                            <option value="staff">Staff (Anggota Divisi)</option>
                            <option value="kadiv">Kadiv (Kepala Divisi)</option>
                            <option value="admin">Admin (BPH / Pengurus Harian)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Divisi Kerja</label>
                        <select id="edit_division_id" name="division_id" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500 bg-white text-slate-800">
                            <option value="">-- Tanpa Divisi (BPH) --</option>
                            <?php foreach ($divisions as $d): ?>
                                <option value="<?= $d['id'] ?>"><?= esc($d['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Status Keaktifan Akun</label>
                    <select id="edit_is_active" name="is_active" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500 bg-white text-slate-800">
                        <option value="1">Aktif (Dapat Login & Beraktivitas)</option>
                        <option value="0">Nonaktif (Akses Login Dibekukan)</option>
                    </select>
                </div>

                <div class="pt-2 flex items-center justify-end gap-2 border-t border-slate-100">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-amber-600 hover:bg-amber-700 shadow-md shadow-amber-500/20 transition">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openAddModal() {
        document.getElementById('modalAdd').classList.remove('hidden');
    }

    function closeAddModal() {
        document.getElementById('modalAdd').classList.add('hidden');
    }

    function openEditModal(userData) {
        const form = document.getElementById('formEdit');
        form.action = '<?= site_url("dashboard/pengurus/update/") ?>' + userData.id;

        document.getElementById('edit_name').value = userData.name || '';
        document.getElementById('edit_nim').value = userData.nim || '';
        document.getElementById('edit_email').value = userData.email || '';
        document.getElementById('edit_role').value = userData.role || 'staff';
        document.getElementById('edit_division_id').value = userData.division_id || '';
        document.getElementById('edit_is_active').value = userData.is_active;

        document.getElementById('modalEdit').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('modalEdit').classList.add('hidden');
    }
</script>

<?= $this->endSection() ?>
