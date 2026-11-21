<?= $this->extend('layout/admin') ?>

<?= $this->section('content') ?>

<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <span class="text-xs font-bold text-brand-600 uppercase tracking-widest block">Layanan Suara Mahasiswa</span>
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight mt-0.5">Moderasi Forum Aspirasi & Q&A</h1>
        <p class="text-xs text-slate-500 mt-1">Kelola, verifikasi, dan berikan tanggapan resmi atas pertanyaan mahasiswa umum</p>
    </div>

    <!-- WhatsApp Notification Status Badge (Pengaturan & Uji Kirim Khusus Admin BPH) -->
    <div class="flex items-center gap-2">
        <?php if (!empty($canManageWa)): ?>
            <?php if ($whatsappEnabled): ?>
                <button type="button" onclick="openTestWaModal()" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-bold bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-200 shadow-sm transition" title="Kelola notifikasi WhatsApp">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <i class="fa-brands fa-whatsapp text-emerald-600 text-base"></i>
                    <span>WA Notif: Aktif</span>
                </button>
            <?php else: ?>
                <button type="button" onclick="openTestWaModal()" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-600 border border-slate-200 shadow-sm transition" title="Klik untuk aktivasi WhatsApp">
                    <i class="fa-brands fa-whatsapp text-slate-400 text-base"></i>
                    <span>WA Notif: Belum Aktif</span>
                    <i class="fa-solid fa-circle-info text-[11px] text-slate-400"></i>
                </button>
            <?php endif; ?>
        <?php else: ?>
            <span class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-bold <?= $whatsappEnabled ? 'bg-emerald-50 text-emerald-800 border-emerald-200' : 'bg-slate-100 text-slate-500 border-slate-200' ?> border">
                <i class="fa-brands fa-whatsapp text-base"></i>
                <span>WA Notif: <?= $whatsappEnabled ? 'Aktif' : 'Nonaktif' ?></span>
            </span>
        <?php endif; ?>
    </div>
</div>

<!-- Status Tabs & Filter -->
<div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm space-y-4 mb-6">
    <!-- Tabs Navigation -->
    <div class="flex items-center gap-3 border-b border-slate-100 pb-4 text-xs font-bold overflow-x-auto">
        <a href="<?= site_url('dashboard/moderasi-qa?status=pending') ?>" class="px-4 py-2 rounded-xl transition flex items-center gap-2 <?= $activeStatus === 'pending' ? 'bg-amber-500 text-white shadow-md shadow-amber-500/20' : 'text-slate-500 hover:bg-slate-100' ?>">
            <i class="fa-regular fa-clock"></i>
            <span>Menunggu Respon</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] <?= $activeStatus === 'pending' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700' ?>">
                <?= esc($counts['pending']) ?>
            </span>
        </a>

        <a href="<?= site_url('dashboard/moderasi-qa?status=answered') ?>" class="px-4 py-2 rounded-xl transition flex items-center gap-2 <?= $activeStatus === 'answered' ? 'bg-emerald-600 text-white shadow-md shadow-emerald-500/20' : 'text-slate-500 hover:bg-slate-100' ?>">
            <i class="fa-solid fa-circle-check"></i>
            <span>Terjawab Resmi</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] <?= $activeStatus === 'answered' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700' ?>">
                <?= esc($counts['answered']) ?>
            </span>
        </a>

        <a href="<?= site_url('dashboard/moderasi-qa?status=rejected') ?>" class="px-4 py-2 rounded-xl transition flex items-center gap-2 <?= $activeStatus === 'rejected' ? 'bg-rose-600 text-white shadow-md shadow-rose-500/20' : 'text-slate-500 hover:bg-slate-100' ?>">
            <i class="fa-solid fa-ban"></i>
            <span>Ditolak / Arsip</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] <?= $activeStatus === 'rejected' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700' ?>">
                <?= esc($counts['rejected']) ?>
            </span>
        </a>
    </div>

    <!-- Search & Category Filter -->
    <form action="<?= site_url('dashboard/moderasi-qa') ?>" method="get" class="flex flex-col sm:flex-row gap-3 pt-1">
        <input type="hidden" name="status" value="<?= esc($activeStatus) ?>">
        <div class="relative flex-1">
            <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
            <input type="text" name="q" value="<?= esc($searchQuery ?? '') ?>" placeholder="Cari judul pertanyaan, isi, nama penanya..." class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-brand-500">
        </div>

        <select name="category" onchange="this.form.submit()" class="py-2.5 px-4 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500 bg-white text-slate-700">
            <option value="">Semua Kategori Topik</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= ($selectedCategory ?? 0) == $cat['id'] ? 'selected' : '' ?>>
                    <?= esc($cat['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit" class="px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs rounded-xl transition">
            Filter
        </button>
    </form>
</div>

<!-- List Aspirasi Mahasiswa -->
<div class="space-y-4">
    <?php if (!empty($questions)): ?>
        <?php foreach ($questions as $q): ?>
            <div class="bg-white rounded-2xl border border-slate-200 p-5 sm:p-6 hover:border-slate-300 transition shadow-sm space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                    <div class="space-y-2 flex-1">
                        <div class="flex flex-wrap items-center gap-2">
                            <?php if ($q['is_pinned']): ?>
                                <span class="px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800 text-[10px] font-extrabold inline-flex items-center gap-1">
                                    <i class="fa-solid fa-thumbtack"></i> Disematkan di Beranda
                                </span>
                            <?php endif; ?>

                            <?php if ($q['status'] === 'answered'): ?>
                                <span class="px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-extrabold">
                                    Terjawab Resmi
                                </span>
                            <?php elseif ($q['status'] === 'pending'): ?>
                                <span class="px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800 text-[10px] font-extrabold">
                                    Menunggu Tanggapan
                                </span>
                            <?php else: ?>
                                <span class="px-2.5 py-0.5 rounded-full bg-rose-100 text-rose-800 text-[10px] font-extrabold">
                                    Ditolak
                                </span>
                            <?php endif; ?>

                            <?php if (!empty($q['category_name'])): ?>
                                <span class="text-xs font-semibold text-slate-500">
                                    • <?= esc($q['category_name']) ?>
                                </span>
                            <?php endif; ?>
                        </div>

                        <h3 class="text-base font-bold text-slate-900 leading-snug">
                            <a href="<?= site_url('dashboard/moderasi-qa/detail/' . $q['id']) ?>" class="hover:text-brand-600 transition">
                                <?= esc($q['title']) ?>
                            </a>
                        </h3>

                        <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed">
                            <?= esc($q['body']) ?>
                        </p>
                    </div>

                    <!-- Counter Upvote Box -->
                    <div class="flex-shrink-0 flex items-center sm:flex-col justify-center px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-center">
                        <i class="fa-solid fa-thumbs-up text-xs text-brand-600"></i>
                        <span class="text-xs font-black text-slate-800 ml-1.5 sm:ml-0 sm:mt-1"><?= esc($q['upvotes']) ?></span>
                        <span class="text-[9px] text-slate-400 font-bold uppercase block">Dukungan</span>
                    </div>
                </div>

                <!-- Footer Info & Actions -->
                <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between text-xs text-slate-400 gap-3">
                    <div class="flex items-center gap-2">
                        <span>Diajukan:</span>
                        <strong class="text-slate-700">
                            <?= $q['is_anonymous'] ? 'Mahasiswa Anonim' : esc($q['author_name']) ?>
                            <?php if (!$q['is_anonymous'] && !empty($q['author_nim'])): ?>
                                (<?= esc($q['author_nim']) ?>)
                            <?php endif; ?>
                        </strong>
                        <span>•</span>
                        <span><?= date('d M Y, H:i', strtotime($q['created_at'])) ?></span>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center gap-2">
                        <?php if (!empty($canModerateAdmin)): ?>
                            <!-- Toggle Pin (Khusus Admin & Kadiv) -->
                            <form action="<?= site_url('dashboard/moderasi-qa/toggle-pin/' . $q['id']) ?>" method="post">
                                <?= csrf_field() ?>
                                <button type="submit" class="px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-100 transition" title="Sematkan di Beranda Publik">
                                    <i class="fa-solid fa-thumbtack mr-1 <?= $q['is_pinned'] ? 'text-amber-500' : 'text-slate-400' ?>"></i>
                                    <?= $q['is_pinned'] ? 'Lepas Pin' : 'Sematkan' ?>
                                </button>
                            </form>
                        <?php endif; ?>

                        <!-- Tombol Tinjau / Balas -->
                        <a href="<?= site_url('dashboard/moderasi-qa/detail/' . $q['id']) ?>" class="px-4 py-1.5 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold shadow-sm transition flex items-center gap-1.5">
                            <i class="fa-solid fa-reply"></i>
                            <span><?= $q['status'] === 'answered' ? 'Perbarui Balasan' : 'Beri Jawaban Resmi' ?></span>
                        </a>

                        <?php if (!empty($canModerateAdmin)): ?>
                            <!-- Hapus (Khusus Admin & Kadiv) -->
                            <form action="<?= site_url('dashboard/moderasi-qa/delete/' . $q['id']) ?>" method="post" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pertanyaan ini?')">
                                <?= csrf_field() ?>
                                <button type="submit" class="p-2 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition" title="Hapus">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center text-slate-400 space-y-3">
            <i class="fa-solid fa-clipboard-check text-4xl text-slate-300"></i>
            <h4 class="font-bold text-sm text-slate-700">Tidak ada pertanyaan pada status ini</h4>
            <p class="text-xs text-slate-500">Semua aspirasi mahasiswa pada filter ini telah tertangani dengan baik.</p>
        </div>
    <?php endif; ?>
</div>

<?php if (!empty($canManageWa)): ?>
<!-- Modal Panduan, Pengaturan & Uji Kirim WhatsApp Notifikasi (Khusus Admin BPH) -->
<div id="testWaModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-xl w-full p-6 sm:p-7 shadow-2xl space-y-5 animate-in fade-in duration-200 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shadow-sm border border-emerald-100">
                    <i class="fa-brands fa-whatsapp"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-900">Notifikasi WhatsApp Aspirasi</h3>
                    <p class="text-xs text-slate-500">Kirim pemberitahuan instan setiap ada aspirasi baru</p>
                </div>
            </div>
            <button onclick="closeTestWaModal()" class="w-8 h-8 rounded-lg bg-slate-100 text-slate-400 hover:text-slate-600 flex items-center justify-center transition">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Status Overview Card -->
        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2.5 text-xs">
            <div class="flex items-center justify-between">
                <span class="font-bold text-slate-700">Status Notifikasi:</span>
                <?php if ($whatsappEnabled): ?>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 animate-pulse"></span>
                        Aktif & Siap Kirim
                    </span>
                <?php else: ?>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800">
                        <i class="fa-solid fa-triangle-exclamation text-[10px]"></i>
                        Belum Aktif / Token Kosong
                    </span>
                <?php endif; ?>
            </div>
            <div class="flex items-center justify-between text-[11px] text-slate-500 pt-1 border-t border-slate-200/60">
                <span>Target Tersimpan:</span>
                <code class="font-mono bg-white px-2 py-0.5 rounded border border-slate-200 text-slate-700 font-bold">
                    <?= esc($whatsappTarget ?: 'Belum diatur') ?>
                </code>
            </div>
        </div>

        <!-- Tab Switcher -->
        <div class="flex items-center gap-2 border-b border-slate-100 pb-3 text-xs font-bold">
            <button type="button" id="tabBtnSettings" onclick="switchWaTab('settings')" class="px-3.5 py-1.5 rounded-xl bg-slate-900 text-white transition flex items-center gap-1.5">
                <i class="fa-solid fa-gear"></i>
                <span>Pengaturan & Token</span>
            </button>
            <button type="button" id="tabBtnTest" onclick="switchWaTab('test')" class="px-3.5 py-1.5 rounded-xl bg-slate-100 text-slate-600 hover:bg-slate-200 transition flex items-center gap-1.5">
                <i class="fa-solid fa-paper-plane"></i>
                <span>Uji Coba Kirim (Test)</span>
            </button>
            <button type="button" id="tabBtnGuide" onclick="switchWaTab('guide')" class="px-3.5 py-1.5 rounded-xl bg-slate-100 text-slate-600 hover:bg-slate-200 transition flex items-center gap-1.5">
                <i class="fa-solid fa-book-open"></i>
                <span>Panduan Fonnte</span>
            </button>
        </div>

        <!-- Tab 1: Pengaturan WhatsApp Langsung dari Web -->
        <div id="waTabSettings" class="space-y-4">
            <form action="<?= site_url('dashboard/moderasi-qa/save-wa-settings') ?>" method="post" class="space-y-3.5 text-xs">
                <?= csrf_field() ?>

                <div class="p-3.5 rounded-xl bg-brand-50/60 border border-brand-100 flex items-start gap-3">
                    <input type="checkbox" id="whatsapp_enabled" name="whatsapp_enabled" value="1" <?= (!empty($waSettings['enabled'])) ? 'checked' : '' ?> class="mt-0.5 rounded text-brand-600 focus:ring-brand-500 w-4 h-4 cursor-pointer">
                    <label for="whatsapp_enabled" class="text-slate-700 cursor-pointer">
                        <span class="font-bold text-slate-900 block">Aktifkan Notifikasi WhatsApp Otomatis</span>
                        <span class="text-slate-500 text-[11px]">Kirim pesan WhatsApp otomatis ke pengurus setiap ada aspirasi baru yang dikirim mahasiswa.</span>
                    </label>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">
                        Token API Fonnte:
                        <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="password" id="wa_token_input" name="whatsapp_token" value="" autocomplete="new-password" placeholder="<?= !empty($waSettings['hasToken']) ? 'Token tersimpan (kosongkan jika tidak ingin mengubah)' : 'Paste token perangkat dari fonnte.com' ?>" class="w-full pl-3.5 pr-10 py-2.5 rounded-xl border border-slate-200 text-xs font-mono focus:outline-none focus:ring-2 focus:ring-brand-500">
                        <button type="button" onclick="toggleTokenVisibility()" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                            <i id="wa_token_eye" class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                    <p class="text-[11px] text-slate-400 mt-1">Demi keamanan, token yang sudah tersimpan tidak ditampilkan kembali. Dapatkan token di <a href="https://fonnte.com" target="_blank" rel="noopener noreferrer" class="text-brand-600 underline font-semibold">fonnte.com</a>.</p>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">
                        Target Nomor / ID Grup:
                        <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="whatsapp_target" value="<?= esc($waSettings['target'] ?? '') ?>" placeholder="Contoh: 081234567890 atau 1203630xxx@g.us" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-brand-500">
                    <p class="text-[11px] text-slate-400 mt-1">Bisa lebih dari 1 nomor dengan dipisah tanda koma (contoh: <code>081234...,089876...</code>).</p>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                    <button type="button" onclick="closeTestWaModal()" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold bg-brand-600 hover:bg-brand-700 text-white shadow-md transition flex items-center gap-1.5">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <span>Simpan Pengaturan</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Tab 2: Uji Coba Kirim Langsung -->
        <div id="waTabTest" class="space-y-4 hidden text-xs">
            <form action="<?= site_url('dashboard/moderasi-qa/test-wa') ?>" method="post" class="space-y-3.5">
                <?= csrf_field() ?>

                <div class="p-3 rounded-xl bg-slate-50 border border-slate-200 text-slate-600 text-[11px] space-y-1">
                    <span class="font-bold text-slate-800 flex items-center gap-1.5">
                        <i class="fa-solid fa-circle-info text-brand-600"></i> Cara Mengetes:
                    </span>
                    <p>Masukkan nomor WhatsApp Anda di bawah ini, lalu klik tombol Uji Kirim. Sistem akan menggunakan token Fonnte yang tersimpan.</p>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nomor WhatsApp Pengujian:</label>
                    <input type="text" name="target" value="<?= esc($whatsappTarget) ?>" placeholder="Contoh: 081234567890" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Token Fonnte (Opsional untuk uji coba langsung):</label>
                    <input type="password" name="token" value="" autocomplete="new-password" placeholder="Kosongkan untuk memakai token tersimpan" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-mono focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>

                <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                    <button type="button" onclick="closeTestWaModal()" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition">Tutup</button>
                    <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold bg-emerald-600 hover:bg-emerald-700 text-white shadow-md transition flex items-center gap-1.5">
                        <i class="fa-solid fa-paper-plane text-[11px]"></i>
                        <span>Kirim Pesan Uji Coba Sekarang</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Tab 3: Panduan Lengkap -->
        <div id="waTabGuide" class="space-y-3.5 hidden text-xs">
            <div class="space-y-2.5 p-4 rounded-2xl bg-amber-50/70 border border-amber-200/80 text-amber-950">
                <div class="font-bold text-amber-900 flex items-center gap-2 text-sm">
                    <i class="fa-solid fa-lightbulb text-amber-600"></i>
                    <span>3 Langkah Menghubungkan WhatsApp (Fonnte):</span>
                </div>
                <ol class="list-decimal list-inside space-y-2 text-[11px] leading-relaxed pt-1 text-slate-700">
                    <li>Buka situs <a href="https://fonnte.com" target="_blank" rel="noopener noreferrer" class="text-brand-600 font-bold underline">fonnte.com</a> dan buat akun baru gratis.</li>
                    <li>Di halaman <strong>Dashboard Fonnte</strong>, klik <strong>Tambah Perangkat (Add Device)</strong>.</li>
                    <li>Klik <strong>Scan QR</strong>, lalu buka WhatsApp di ponsel pengurus > klik <em>Perangkat Tertaut (Linked Devices)</em> > Scan QR tersebut sampai status menjadi <strong>Connect</strong>.</li>
                    <li>Salin <strong>Token</strong> yang tertera pada perangkat tersebut, lalu paste pada tab <strong>Pengaturan & Token</strong> di atas, lalu klik <strong>Simpan</strong>.</li>
                </ol>
            </div>

            <div class="p-3 rounded-xl bg-slate-50 border border-slate-200 text-slate-600 text-[11px]">
                <span class="font-bold text-slate-800 block mb-1">💡 Alternatif via file <code>.env</code> (untuk Server/Codespaces):</span>
                <pre class="bg-white p-2.5 rounded-lg border border-slate-200 font-mono text-[10px] text-slate-700 overflow-x-auto select-all">whatsapp.enabled = true
whatsapp.token   = "TOKEN_DARI_FONNTE"
whatsapp.target  = "081234567890"</pre>
            </div>
        </div>
    </div>
</div>

<script>
    function openTestWaModal() {
        document.getElementById('testWaModal').classList.remove('hidden');
    }
    function closeTestWaModal() {
        document.getElementById('testWaModal').classList.add('hidden');
    }

    function switchWaTab(tab) {
        const tabs = ['settings', 'test', 'guide'];
        tabs.forEach(t => {
            const el = document.getElementById('waTab' + t.charAt(0).toUpperCase() + t.slice(1));
            const btn = document.getElementById('tabBtn' + t.charAt(0).toUpperCase() + t.slice(1));
            if (t === tab) {
                el.classList.remove('hidden');
                btn.className = 'px-3.5 py-1.5 rounded-xl bg-slate-900 text-white transition flex items-center gap-1.5';
            } else {
                el.classList.add('hidden');
                btn.className = 'px-3.5 py-1.5 rounded-xl bg-slate-100 text-slate-600 hover:bg-slate-200 transition flex items-center gap-1.5';
            }
        });
    }

    function toggleTokenVisibility() {
        const input = document.getElementById('wa_token_input');
        const eye = document.getElementById('wa_token_eye');
        if (input.type === 'password') {
            input.type = 'text';
            eye.className = 'fa-solid fa-eye-slash';
        } else {
            input.type = 'password';
            eye.className = 'fa-solid fa-eye';
        }
    }
</script>
<?php endif; ?>

<?= $this->endSection() ?>
