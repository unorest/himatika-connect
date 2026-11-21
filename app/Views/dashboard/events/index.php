<?= $this->extend('layout/admin') ?>

<?= $this->section('content') ?>

<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <span class="text-xs font-bold text-brand-600 uppercase tracking-widest block">Kalender Terbuka Publik</span>
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight mt-0.5">Kelola Event & Workshop Publik</h1>
        <p class="text-xs text-slate-500 mt-1">Event yang ditandai publik & belum berakhir otomatis tampil di beranda dan daftar pilihan E-Sertifikat</p>
    </div>

    <?php if (!empty($canManageEvents)): ?>
        <div>
            <button type="button" onclick="openAddEventModal()" class="px-4 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs rounded-xl shadow-md shadow-brand-500/20 transition flex items-center gap-2">
                <i class="fa-solid fa-calendar-plus"></i>
                <span>Tambah Event Publik</span>
            </button>
        </div>
    <?php endif; ?>
</div>

<!-- Validation Errors -->
<?php if (session()->getFlashdata('errors')): ?>
    <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs shadow-sm">
        <div class="font-bold mb-1 flex items-center gap-2">
            <i class="fa-solid fa-triangle-exclamation text-rose-600"></i>
            <span>Terdapat kesalahan pengisian data event:</span>
        </div>
        <ul class="list-disc list-inside space-y-0.5 text-rose-700 ml-2">
            <?php foreach (session()->getFlashdata('errors') as $err): ?>
                <li><?= esc($err) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<!-- Quick Stats -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
        <div>
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Total Event Terdata</span>
            <span class="text-3xl font-extrabold text-slate-900 mt-1 block"><?= esc($stats['total']) ?></span>
        </div>
        <div class="w-11 h-11 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center text-lg">
            <i class="fa-regular fa-calendar"></i>
        </div>
    </div>
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
        <div>
            <span class="text-[11px] font-bold text-brand-500 uppercase tracking-wider block">Event Mendatang</span>
            <span class="text-3xl font-extrabold text-brand-600 mt-1 block"><?= esc($stats['upcoming']) ?></span>
        </div>
        <div class="w-11 h-11 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center text-lg">
            <i class="fa-solid fa-calendar-day"></i>
        </div>
    </div>
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
        <div>
            <span class="text-[11px] font-bold text-emerald-500 uppercase tracking-wider block">Tayang di Beranda</span>
            <span class="text-3xl font-extrabold text-emerald-600 mt-1 block"><?= esc($stats['public']) ?></span>
        </div>
        <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg">
            <i class="fa-solid fa-globe"></i>
        </div>
    </div>
</div>

<!-- Filter Bar -->
<div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm mb-6">
    <form action="<?= site_url('dashboard/events') ?>" method="get" class="flex flex-col sm:flex-row gap-3">
        <div class="relative flex-1">
            <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
            <input type="text" name="q" value="<?= esc($searchQuery ?? '') ?>" placeholder="Cari judul event, lokasi, atau deskripsi..." class="w-full pl-9 pr-4 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-brand-500">
        </div>

        <select name="status" onchange="this.form.submit()" class="py-2 px-3 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500 bg-white text-slate-700">
            <option value="all" <?= ($selectedStatus ?? 'all') === 'all' ? 'selected' : '' ?>>Semua Waktu</option>
            <option value="upcoming" <?= ($selectedStatus ?? '') === 'upcoming' ? 'selected' : '' ?>>Mendatang (Aktif)</option>
            <option value="past" <?= ($selectedStatus ?? '') === 'past' ? 'selected' : '' ?>>Sudah Selesai</option>
        </select>

        <button type="submit" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl transition">
            Filter
        </button>
    </form>
</div>

<!-- Tabel Event -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider border-b border-slate-200">
                <tr>
                    <th class="py-3.5 px-4 sm:px-6">Nama Event & Lokasi</th>
                    <th class="py-3.5 px-4">Kategori / Divisi</th>
                    <th class="py-3.5 px-4">Jadwal Pelaksanaan</th>
                    <th class="py-3.5 px-4 text-center">Kuota</th>
                    <th class="py-3.5 px-4">Status</th>
                    <th class="py-3.5 px-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if (!empty($events)): ?>
                    <?php $nowTs = time(); ?>
                    <?php foreach ($events as $ev): ?>
                        <?php $isUpcoming = strtotime($ev['end_datetime']) >= $nowTs; ?>
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-4 px-4 sm:px-6 max-w-xs">
                                <div class="font-bold text-slate-900"><?= esc($ev['title']) ?></div>
                                <div class="text-[11px] text-slate-500 mt-0.5 flex items-center gap-1.5">
                                    <i class="fa-solid fa-location-dot text-rose-400 text-[10px]"></i>
                                    <span class="truncate"><?= esc($ev['location'] ?? 'Kampus / Online') ?></span>
                                </div>
                            </td>
                            <td class="py-4 px-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-brand-50 text-brand-700 border border-brand-200 inline-block">
                                    <?= esc($ev['category_name'] ?? 'Event') ?>
                                </span>
                                <?php if (!empty($ev['division_name'])): ?>
                                    <span class="text-[10px] text-slate-400 block mt-1"><?= esc($ev['division_name']) ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="py-4 px-4 text-slate-600 whitespace-nowrap">
                                <div class="font-semibold"><?= date('d M Y, H:i', strtotime($ev['start_datetime'])) ?></div>
                                <div class="text-[10px] text-slate-400">s/d <?= date('d M Y, H:i', strtotime($ev['end_datetime'])) ?> WIB</div>
                            </td>
                            <td class="py-4 px-4 text-center font-bold text-slate-700">
                                <?= !empty($ev['max_participants']) ? esc($ev['max_participants']) . ' org' : '<span class="text-emerald-600">Umum</span>' ?>
                            </td>
                            <td class="py-4 px-4 space-y-1">
                                <?php if ($isUpcoming && (int) $ev['is_public'] === 1): ?>
                                    <span class="px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-700 text-[10px] font-bold inline-block">
                                        Tayang di Beranda
                                    </span>
                                <?php elseif (!$isUpcoming): ?>
                                    <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 text-[10px] font-bold inline-block">
                                        Sudah Selesai
                                    </span>
                                <?php else: ?>
                                    <span class="px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-700 text-[10px] font-bold inline-block">
                                        Tersembunyi
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="py-4 px-4 text-right">
                                <?php if (!empty($canManageEvents)): ?>
                                    <div class="flex items-center justify-end gap-2">
                                        <button type="button"
                                            onclick='openEditEventModal(<?= json_encode([
                                                "id"                    => $ev["id"],
                                                "title"                 => $ev["title"],
                                                "description"           => $ev["description"] ?? "",
                                                "location"              => $ev["location"] ?? "",
                                                "start_datetime"        => date("Y-m-d\TH:i", strtotime($ev["start_datetime"])),
                                                "end_datetime"          => date("Y-m-d\TH:i", strtotime($ev["end_datetime"])),
                                                "category_id"           => $ev["category_id"] ?? "",
                                                "division_id"           => $ev["division_id"] ?? "",
                                                "max_participants"      => $ev["max_participants"] ?? "",
                                                "is_public"             => (int) $ev["is_public"],
                                                "allow_registration"    => (int) $ev["allow_registration"],
                                                "registration_deadline" => !empty($ev["registration_deadline"]) ? date("Y-m-d\TH:i", strtotime($ev["registration_deadline"])) : "",
                                            ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>)'
                                            class="p-1.5 text-slate-500 hover:text-brand-600 hover:bg-slate-100 rounded-lg transition" title="Edit Event">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>

                                        <form action="<?= site_url('dashboard/events/delete/' . $ev['id']) ?>" method="post" onsubmit="return openConfirmModal(this, 'Yakin ingin menghapus event publik ini?')">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Hapus Event">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                <?php else: ?>
                                    <span class="text-[10px] text-slate-400 italic">Hanya baca</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="py-12 text-center text-slate-400">
                            <i class="fa-regular fa-calendar-xmark text-3xl text-slate-300 block mb-2"></i>
                            Belum ada event publik. Klik <strong>"Tambah Event Publik"</strong> untuk mendaftarkan acara/workshop mendatang!
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php if (!empty($canManageEvents)): ?>
<!-- Modal Tambah Event Publik -->
<div id="addEventModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm overflow-y-auto">
    <div class="min-h-full flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl border border-slate-200 shadow-2xl max-w-xl w-full p-6 sm:p-7">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">Tambah Event / Workshop Publik</h3>
                    <p class="text-xs text-slate-500">Event aktif & publik akan otomatis muncul di beranda utama</p>
                </div>
                <button type="button" onclick="closeAddEventModal()" class="w-8 h-8 rounded-lg bg-slate-100 text-slate-400 hover:text-slate-600 flex items-center justify-center">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form action="<?= site_url('dashboard/events/store') ?>" method="post" class="mt-4 space-y-4 text-xs">
                <?= csrf_field() ?>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Judul Event / Kegiatan <span class="text-rose-500">*</span></label>
                    <input type="text" name="title" required placeholder="Contoh: Workshop AI & Web Security 2026" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Waktu Mulai <span class="text-rose-500">*</span></label>
                        <input type="datetime-local" name="start_datetime" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Waktu Selesai <span class="text-rose-500">*</span></label>
                        <input type="datetime-local" name="end_datetime" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Lokasi / Tautan</label>
                        <input type="text" name="location" placeholder="Auditorium / Lab Komputer / Zoom" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Kuota Peserta (Opsional)</label>
                        <input type="number" name="max_participants" min="0" placeholder="Kosongkan jika terbuka umum" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Kategori Event</label>
                        <select name="category_id" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-brand-500">
                            <option value="">-- Pilih Kategori --</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>"><?= esc($cat['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Divisi Penyelenggara</label>
                        <select name="division_id" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-brand-500">
                            <option value="">-- HIMATIKA / BPH --</option>
                            <?php foreach ($divisions as $d): ?>
                                <option value="<?= $d['id'] ?>"><?= esc($d['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Deskripsi Kegiatan</label>
                    <textarea name="description" rows="3" placeholder="Jelaskan ringkasan acara, pemateri, dan manfaat bagi peserta..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500"></textarea>
                </div>

                <div class="flex flex-wrap items-center gap-6 pt-1">
                    <label class="inline-flex items-center gap-2 cursor-pointer font-semibold text-slate-700">
                        <input type="checkbox" name="is_public" value="1" checked class="rounded text-brand-600 focus:ring-brand-500 w-4 h-4">
                        <span>Tampilkan di Beranda Publik</span>
                    </label>
                    <label class="inline-flex items-center gap-2 cursor-pointer font-semibold text-slate-700">
                        <input type="checkbox" name="allow_registration" value="1" checked class="rounded text-brand-600 focus:ring-brand-500 w-4 h-4">
                        <span>Buka Pendaftaran / E-Sertifikat</span>
                    </label>
                </div>

                <div class="pt-3 flex items-center justify-end gap-2 border-t border-slate-100">
                    <button type="button" onclick="closeAddEventModal()" class="px-4 py-2.5 rounded-xl font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition">Batal</button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl font-bold text-white bg-brand-600 hover:bg-brand-700 shadow-md transition">Simpan Event</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Event Publik -->
<div id="editEventModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm overflow-y-auto">
    <div class="min-h-full flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl border border-slate-200 shadow-2xl max-w-xl w-full p-6 sm:p-7">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">Edit Event Publik</h3>
                    <p class="text-xs text-slate-500">Perbarui jadwal, lokasi, atau status tayang event</p>
                </div>
                <button type="button" onclick="closeEditEventModal()" class="w-8 h-8 rounded-lg bg-slate-100 text-slate-400 hover:text-slate-600 flex items-center justify-center">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form id="editEventForm" action="" method="post" class="mt-4 space-y-4 text-xs">
                <?= csrf_field() ?>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Judul Event / Kegiatan <span class="text-rose-500">*</span></label>
                    <input type="text" id="edit_ev_title" name="title" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Waktu Mulai <span class="text-rose-500">*</span></label>
                        <input type="datetime-local" id="edit_ev_start" name="start_datetime" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Waktu Selesai <span class="text-rose-500">*</span></label>
                        <input type="datetime-local" id="edit_ev_end" name="end_datetime" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Lokasi / Tautan</label>
                        <input type="text" id="edit_ev_location" name="location" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Kuota Peserta (Opsional)</label>
                        <input type="number" id="edit_ev_quota" name="max_participants" min="0" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Kategori Event</label>
                        <select id="edit_ev_category" name="category_id" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-brand-500">
                            <option value="">-- Pilih Kategori --</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>"><?= esc($cat['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Divisi Penyelenggara</label>
                        <select id="edit_ev_division" name="division_id" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-brand-500">
                            <option value="">-- HIMATIKA / BPH --</option>
                            <?php foreach ($divisions as $d): ?>
                                <option value="<?= $d['id'] ?>"><?= esc($d['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Deskripsi Kegiatan</label>
                    <textarea id="edit_ev_description" name="description" rows="3" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500"></textarea>
                </div>

                <div class="flex flex-wrap items-center gap-6 pt-1">
                    <label class="inline-flex items-center gap-2 cursor-pointer font-semibold text-slate-700">
                        <input type="checkbox" id="edit_ev_public" name="is_public" value="1" class="rounded text-brand-600 focus:ring-brand-500 w-4 h-4">
                        <span>Tampilkan di Beranda Publik</span>
                    </label>
                    <label class="inline-flex items-center gap-2 cursor-pointer font-semibold text-slate-700">
                        <input type="checkbox" id="edit_ev_reg" name="allow_registration" value="1" class="rounded text-brand-600 focus:ring-brand-500 w-4 h-4">
                        <span>Buka Pendaftaran / E-Sertifikat</span>
                    </label>
                </div>

                <div class="pt-3 flex items-center justify-end gap-2 border-t border-slate-100">
                    <button type="button" onclick="closeEditEventModal()" class="px-4 py-2.5 rounded-xl font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition">Batal</button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl font-bold text-white bg-amber-600 hover:bg-amber-700 shadow-md transition">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openAddEventModal() {
        document.getElementById('addEventModal').classList.remove('hidden');
    }
    function closeAddEventModal() {
        document.getElementById('addEventModal').classList.add('hidden');
    }
    function openEditEventModal(ev) {
        document.getElementById('editEventForm').action = '<?= site_url("dashboard/events/update/") ?>' + ev.id;
        document.getElementById('edit_ev_title').value = ev.title || '';
        document.getElementById('edit_ev_start').value = ev.start_datetime || '';
        document.getElementById('edit_ev_end').value = ev.end_datetime || '';
        document.getElementById('edit_ev_location').value = ev.location || '';
        document.getElementById('edit_ev_quota').value = ev.max_participants || '';
        document.getElementById('edit_ev_category').value = ev.category_id || '';
        document.getElementById('edit_ev_division').value = ev.division_id || '';
        document.getElementById('edit_ev_description').value = ev.description || '';
        document.getElementById('edit_ev_public').checked = ev.is_public === 1;
        document.getElementById('edit_ev_reg').checked = ev.allow_registration === 1;
        document.getElementById('editEventModal').classList.remove('hidden');
    }
    function closeEditEventModal() {
        document.getElementById('editEventModal').classList.add('hidden');
    }
</script>
<?php endif; ?>

<?= $this->endSection() ?>
