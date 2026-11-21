<?= $this->extend('layout/admin') ?>

<?= $this->section('content') ?>

<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <span class="text-xs font-bold text-brand-600 uppercase tracking-widest block">Koordinasi & Kalender Kerja</span>
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight mt-0.5">Jadwal & Agenda Rapat Internal</h1>
        <p class="text-xs text-slate-500 mt-1">Kalender terpadu rapat rutin, piket sekretariat, dan batas waktu pelaporan divisi</p>
    </div>

    <div>
        <button type="button" onclick="openCreateScheduleModal()" class="px-4 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs rounded-xl shadow-md shadow-brand-500/20 transition flex items-center gap-2">
            <i class="fa-solid fa-calendar-plus"></i>
            <span>Jadwalkan Agenda Baru</span>
        </button>
    </div>
</div>

<!-- 3 Quick Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
        <div>
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Agenda Mendatang</span>
            <span class="text-3xl font-extrabold text-slate-900 mt-1 block"><?= esc($stats['total_upcoming']) ?></span>
            <span class="text-[11px] text-slate-500 mt-0.5 block">Rapat & deadline terjadwal</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center text-xl">
            <i class="fa-regular fa-calendar-days"></i>
        </div>
    </div>

    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
        <div>
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Konfirmasi Saya Hadir</span>
            <span class="text-3xl font-extrabold text-emerald-600 mt-1 block"><?= esc($stats['my_accepted']) ?></span>
            <span class="text-[11px] text-slate-500 mt-0.5 block">Telah menyatakan kesediaan</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
            <i class="fa-solid fa-user-check"></i>
        </div>
    </div>

    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
        <div>
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Perlu Konfirmasi (RSVP)</span>
            <span class="text-3xl font-extrabold text-amber-500 mt-1 block"><?= esc($stats['my_pending']) ?></span>
            <span class="text-[11px] text-slate-500 mt-0.5 block">Menunggu respon kehadiran</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center text-xl">
            <i class="fa-regular fa-circle-question"></i>
        </div>
    </div>
</div>

<!-- Filter Divisi Bar -->
<div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm mb-8">
    <div class="flex items-center gap-2 overflow-x-auto pb-1 text-xs">
        <span class="text-slate-400 font-bold uppercase tracking-wider text-[10px] mr-2">Filter Divisi:</span>
        <a href="<?= site_url('dashboard/schedules') ?>" class="px-3.5 py-1.5 rounded-full font-bold whitespace-nowrap transition <?= $selectedDivision === null ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' ?>">
            Semua Agenda
        </a>
        <a href="<?= site_url('dashboard/schedules?division=0') ?>" class="px-3.5 py-1.5 rounded-full font-bold whitespace-nowrap transition <?= $selectedDivision === 0 ? 'bg-brand-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' ?>">
            Pleno Himpunan (Semua)
        </a>
        <?php foreach ($divisions as $d): ?>
            <a href="<?= site_url('dashboard/schedules?division=' . $d['id']) ?>" class="px-3.5 py-1.5 rounded-full font-bold whitespace-nowrap transition flex items-center gap-1.5 <?= $selectedDivision == $d['id'] ? 'text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' ?>" style="<?= $selectedDivision == $d['id'] ? 'background-color:' . esc($d['color']) . ';' : '' ?>">
                <span class="w-2 h-2 rounded-full" style="background-color: <?= esc($d['color']) ?>;"></span>
                <span><?= esc($d['name']) ?></span>
            </a>
        <?php endforeach; ?>
    </div>
</div>

<!-- Main 2-Column Content -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

    <!-- Timeline List Agenda (7 Kolom) -->
    <div class="lg:col-span-7 space-y-4">
        <div class="flex items-center justify-between pb-2">
            <h2 class="text-base font-bold text-slate-900">Linimasa Agenda Mendatang</h2>
            <span class="text-xs text-slate-400 font-semibold"><?= count($schedules) ?> jadwal terdata</span>
        </div>

        <?php if (!empty($schedules)): ?>
            <?php foreach ($schedules as $sch): ?>
                <div class="bg-white rounded-2xl border border-slate-200 p-5 sm:p-6 shadow-sm hover:border-slate-300 transition space-y-4 relative overflow-hidden">
                    <!-- Color Tag Strip -->
                    <div class="absolute top-0 left-0 bottom-0 w-2" style="background-color: <?= esc($sch['color'] ?? $sch['division_color'] ?? '#3b82f6') ?>;"></div>

                    <div class="pl-2 space-y-3">
                        <!-- Top Meta: Waktu & Divisi -->
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <span class="px-2.5 py-0.5 rounded-full text-white text-[10px] font-extrabold uppercase shadow-sm" style="background-color: <?= esc($sch['color'] ?? $sch['division_color'] ?? '#3b82f6') ?>;">
                                <?= esc($sch['division_name'] ?? 'Pleno Himpunan') ?>
                            </span>

                            <div class="text-xs font-bold text-slate-500 flex items-center gap-1.5">
                                <i class="fa-regular fa-clock text-brand-600"></i>
                                <span><?= date('d M Y', strtotime($sch['start_datetime'])) ?> • <?= date('H:i', strtotime($sch['start_datetime'])) ?> - <?= date('H:i', strtotime($sch['end_datetime'])) ?> WIB</span>
                            </div>
                        </div>

                        <!-- Judul & Lokasi -->
                        <div>
                            <h3 class="text-lg font-bold text-slate-900 leading-snug">
                                <?= esc($sch['title']) ?>
                            </h3>
                            <div class="flex items-center gap-2 text-xs text-slate-500 mt-1">
                                <i class="fa-solid fa-location-dot text-rose-500"></i>
                                <span><?= esc($sch['location'] ?? 'Sekretariat HIMATIKA') ?></span>
                            </div>
                        </div>

                        <?php if (!empty($sch['description'])): ?>
                            <p class="text-xs text-slate-600 leading-relaxed bg-slate-50 p-3 rounded-xl border border-slate-100">
                                <?= esc($sch['description']) ?>
                            </p>
                        <?php endif; ?>

                        <!-- RSVP Status Bar & Kehadiran Saya -->
                        <div class="pt-3 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                            <!-- Ringkasan Kehadiran Pengurus -->
                            <div class="flex items-center gap-3 text-[11px] text-slate-500">
                                <span class="flex items-center gap-1 text-emerald-700 font-bold">
                                    <i class="fa-solid fa-circle-check"></i> Hadir: <?= $sch['rsvp_summary']['accepted'] ?>
                                </span>
                                <span>•</span>
                                <span class="flex items-center gap-1 text-rose-700 font-bold">
                                    <i class="fa-solid fa-circle-xmark"></i> Izin: <?= $sch['rsvp_summary']['declined'] ?>
                                </span>
                                <span>•</span>
                                <span class="flex items-center gap-1 text-slate-400 font-bold">
                                    Belum: <?= $sch['rsvp_summary']['pending'] ?>
                                </span>
                            </div>

                            <!-- Tombol Konfirmasi Saya -->
                            <div class="flex items-center gap-2">
                                <form action="<?= site_url('dashboard/schedules/rsvp') ?>" method="post" class="inline">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="schedule_id" value="<?= $sch['id'] ?>">
                                    <input type="hidden" name="rsvp" value="accepted">
                                    <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1 <?= $sch['my_rsvp'] === 'accepted' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-slate-100 hover:bg-emerald-50 text-slate-700 hover:text-emerald-700' ?>">
                                        <i class="fa-solid fa-check"></i>
                                        <span><?= $sch['my_rsvp'] === 'accepted' ? 'Saya Hadir' : 'Konfirmasi Hadir' ?></span>
                                    </button>
                                </form>

                                <form action="<?= site_url('dashboard/schedules/rsvp') ?>" method="post" class="inline">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="schedule_id" value="<?= $sch['id'] ?>">
                                    <input type="hidden" name="rsvp" value="declined">
                                    <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1 <?= $sch['my_rsvp'] === 'declined' ? 'bg-rose-600 text-white shadow-sm' : 'bg-slate-100 hover:bg-rose-50 text-slate-700 hover:text-rose-700' ?>">
                                        <i class="fa-solid fa-xmark"></i>
                                        <span><?= $sch['my_rsvp'] === 'declined' ? 'Saya Izin' : 'Izin' ?></span>
                                    </button>
                                </form>

                                <!-- Hapus / Batalkan Jadwal (Admin, Kadiv, atau pembuat agenda) -->
                                <?php if (in_array(session()->get('user_role'), ['admin', 'kadiv'], true) || (int) session()->get('user_id') === (int) $sch['created_by']): ?>
                                    <form action="<?= site_url('dashboard/schedules/delete/' . $sch['id']) ?>" method="post" onsubmit="return confirm('Hapus/batalkan jadwal agenda ini?')">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 transition" title="Batalkan Agenda">
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center text-slate-400 space-y-3">
                <i class="fa-regular fa-calendar-xmark text-4xl text-slate-300"></i>
                <h4 class="font-bold text-sm text-slate-700">Belum ada agenda pada filter ini</h4>
                <p class="text-xs text-slate-500">Gunakan tombol "Jadwalkan Agenda Baru" untuk menambahkan rapat atau jadwal piket.</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Kalender Mini & Jadwal Piket (5 Kolom) -->
    <div class="lg:col-span-5 space-y-6 sticky top-28">

        <!-- Jadwal Piket Sekretariat Pekanan -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-bold text-brand-600 uppercase tracking-widest block">Operasional Sekretariat</span>
                    <h3 class="text-sm font-bold text-slate-900 mt-0.5">Jadwal Piket Divisi Pekan Ini</h3>
                </div>
                <div class="w-8 h-8 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-door-open"></i>
                </div>
            </div>

            <div class="space-y-2.5 text-xs">
                <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                    <div>
                        <strong class="text-slate-800 block font-bold">Senin</strong>
                        <span class="text-slate-500 text-[11px]">10.00 - 15.00 WIB</span>
                    </div>
                    <span class="px-2.5 py-1 rounded-lg bg-blue-100 text-blue-700 font-bold text-[10px]">
                        Divisi Akademik
                    </span>
                </div>

                <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                    <div>
                        <strong class="text-slate-800 block font-bold">Selasa</strong>
                        <span class="text-slate-500 text-[11px]">10.00 - 15.00 WIB</span>
                    </div>
                    <span class="px-2.5 py-1 rounded-lg bg-amber-100 text-amber-700 font-bold text-[10px]">
                        Divisi Humas & Komunikasi
                    </span>
                </div>

                <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                    <div>
                        <strong class="text-slate-800 block font-bold">Rabu</strong>
                        <span class="text-slate-500 text-[11px]">10.00 - 15.00 WIB</span>
                    </div>
                    <span class="px-2.5 py-1 rounded-lg bg-rose-100 text-rose-700 font-bold text-[10px]">
                        Divisi Advokasi & Kesra
                    </span>
                </div>

                <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                    <div>
                        <strong class="text-slate-800 block font-bold">Kamis</strong>
                        <span class="text-slate-500 text-[11px]">10.00 - 15.00 WIB</span>
                    </div>
                    <span class="px-2.5 py-1 rounded-lg bg-purple-100 text-purple-700 font-bold text-[10px]">
                        Divisi PSDM
                    </span>
                </div>

                <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                    <div>
                        <strong class="text-slate-800 block font-bold">Jumat</strong>
                        <span class="text-slate-500 text-[11px]">13.30 - 17.00 WIB</span>
                    </div>
                    <span class="px-2.5 py-1 rounded-lg bg-cyan-100 text-cyan-700 font-bold text-[10px]">
                        Divisi Teknologi & Inovasi
                    </span>
                </div>
            </div>
        </div>

        <!-- Panduan & Tata Tertib Rapat -->
        <div class="bg-gradient-to-br from-slate-900 to-slate-800 text-white rounded-2xl p-6 shadow-md space-y-3">
            <h4 class="font-bold text-xs uppercase tracking-wider text-brand-400 flex items-center gap-1.5">
                <i class="fa-solid fa-circle-info"></i> Tata Tertib Rapat Internal
            </h4>
            <ul class="text-xs text-slate-300 space-y-2 leading-relaxed list-disc pl-4">
                <li>Konfirmasi kehadiran (RSVP) maksimal <strong>H-1 sebelum rapat</strong> dimulai.</li>
                <li>Jika berhalangan, pengurus wajib menyampaikan alasan dan melimpahkan laporan divisi ke rekan piket.</li>
                <li>Notulensi rapat dan daftar hadir wajib diarsipkan ke dokumen Google Drive divisi.</li>
            </ul>
        </div>

    </div>

</div>

<!-- Modal: Tambah Jadwal / Agenda Baru -->
<div id="createScheduleModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl space-y-5 animate-in fade-in duration-200">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div>
                <h3 class="text-lg font-bold text-slate-900">Jadwalkan Agenda / Rapat Baru</h3>
                <p class="text-xs text-slate-500">Anggota divisi akan otomatis terdaftar dalam daftar hadir</p>
            </div>
            <button onclick="closeCreateScheduleModal()" class="w-8 h-8 rounded-lg bg-slate-100 text-slate-400 hover:text-slate-600 flex items-center justify-center">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form action="<?= site_url('dashboard/schedules/store') ?>" method="post" class="space-y-4">
            <?= csrf_field() ?>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Nama Agenda / Topik Rapat <span class="text-rose-500">*</span></label>
                <input type="text" name="title" required placeholder="Contoh: Rapat Koordinasi Persiapan Hackfest 2026" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Divisi Penyelenggara</label>
                    <select name="division_id" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-brand-500 bg-white">
                        <option value="">Pleno Himpunan (Semua Anggota)</option>
                        <?php foreach ($divisions as $d): ?>
                            <option value="<?= $d['id'] ?>" <?= session()->get('user_division_id') == $d['id'] ? 'selected' : '' ?>>
                                <?= esc($d['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Lokasi / Tautan</label>
                    <input type="text" name="location" placeholder="Ruang PKM Lt.2 / Google Meet" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-brand-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Waktu Mulai <span class="text-rose-500">*</span></label>
                    <input type="datetime-local" name="start_datetime" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-brand-500 bg-white">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Waktu Selesai <span class="text-rose-500">*</span></label>
                    <input type="datetime-local" name="end_datetime" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-brand-500 bg-white">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Keterangan & Rincian Agenda</label>
                <textarea name="description" rows="3" placeholder="Poin-poin pembahasan rapat, berkas yang harus dibawa, dll..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-brand-500"></textarea>
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                <button type="button" onclick="closeCreateScheduleModal()" class="px-4 py-2.5 text-xs font-bold text-slate-600 hover:bg-slate-100 rounded-xl transition">Batal</button>
                <button type="submit" class="px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs rounded-xl shadow-md transition">Jadwalkan Agenda</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openCreateScheduleModal() {
        document.getElementById('createScheduleModal').classList.remove('hidden');
    }
    function closeCreateScheduleModal() {
        document.getElementById('createScheduleModal').classList.add('hidden');
    }
</script>

<?= $this->endSection() ?>
