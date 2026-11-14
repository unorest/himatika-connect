<?= $this->extend('layout/admin') ?>

<?= $this->section('content') ?>

<!-- Kanban Header & Proker Selector -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <span class="text-xs font-bold text-brand-600 uppercase tracking-widest block">Manajemen Tugas Tim</span>
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight mt-0.5">Papan Kerja Kanban</h1>
    </div>

    <div class="flex flex-wrap items-center gap-3">
        <!-- Filter Program Kerja -->
        <form action="<?= site_url('dashboard/tasks') ?>" method="get" class="flex items-center gap-2">
            <select name="program_id" onchange="this.form.submit()" class="px-4 py-2.5 rounded-xl border border-slate-300 text-xs font-bold bg-white text-slate-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                <?php if (empty($programs)): ?>
                    <option value="">Belum ada program kerja</option>
                <?php else: ?>
                    <?php foreach ($programs as $prog): ?>
                        <option value="<?= $prog['id'] ?>" <?= ($selectedProgramId ?? 0) == $prog['id'] ? 'selected' : '' ?>>
                            <?= esc($prog['title']) ?> (<?= esc($prog['division_name'] ?? 'Divisi') ?>)
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </form>

        <!-- Tombol Tambah Tugas -->
        <?php if (!empty($activeProgram)): ?>
            <button type="button" onclick="openCreateModal()" class="px-4 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs rounded-xl shadow-md shadow-brand-500/20 transition flex items-center gap-2">
                <i class="fa-solid fa-plus"></i>
                <span>Tugas Baru</span>
            </button>
        <?php endif; ?>
    </div>
</div>

<!-- Active Proker Status Banner -->
<?php if (!empty($activeProgram)): ?>
    <div class="bg-white rounded-2xl border border-slate-200 p-5 mb-8 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="space-y-1">
            <div class="flex items-center gap-2">
                <span class="text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-brand-50 text-brand-700 border border-brand-200">
                    Monitoring Program Kerja
                </span>
                <span class="text-xs text-slate-400">Periode <?= esc($activeProgram['period_year']) ?></span>
            </div>
            <h2 class="text-lg font-bold text-slate-900"><?= esc($activeProgram['title']) ?></h2>
            <p class="text-xs text-slate-500 max-w-2xl leading-relaxed"><?= esc($activeProgram['description']) ?></p>
        </div>

        <div class="flex-shrink-0 md:text-right min-w-[200px] space-y-1.5">
            <div class="flex items-center justify-between md:justify-end gap-3 text-xs">
                <span class="font-bold text-slate-500">Penyelesaian:</span>
                <span class="font-extrabold text-brand-600 text-base"><?= esc($activeProgram['progress']) ?>%</span>
            </div>
            <div class="w-full h-2.5 rounded-full bg-slate-100 overflow-hidden border border-slate-200">
                <div class="h-full bg-gradient-to-r from-brand-600 to-indigo-600 rounded-full transition-all duration-500" style="width: <?= esc($activeProgram['progress']) ?>%;"></div>
            </div>
            <span class="text-[10px] text-slate-400 block">Otomatis dihitung dari tugas berstatus Done</span>
        </div>
    </div>
<?php endif; ?>

<!-- Kanban Board (4 Columns) -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 items-start">

    <!-- Kolom 1: To Do -->
    <div class="bg-slate-200/60 rounded-2xl p-4 border border-slate-300/70 flex flex-col min-h-[500px]">
        <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-300/80">
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-slate-500"></span>
                <h3 class="font-bold text-xs uppercase tracking-wider text-slate-700">To Do</h3>
            </div>
            <span class="text-xs font-extrabold px-2 py-0.5 rounded-full bg-white text-slate-700 shadow-sm">
                <?= count($kanban['todo']) ?>
            </span>
        </div>

        <div class="space-y-3 flex-1">
            <?php foreach ($kanban['todo'] as $task): ?>
                <?= view('dashboard/tasks/card', ['task' => $task]) ?>
            <?php endforeach; ?>
            <?php if (empty($kanban['todo'])): ?>
                <div class="h-32 border-2 border-dashed border-slate-300 rounded-xl flex items-center justify-center text-xs text-slate-400 font-medium">
                    Belum ada tugas To Do
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Kolom 2: In Progress -->
    <div class="bg-blue-50/60 rounded-2xl p-4 border border-blue-200/80 flex flex-col min-h-[500px]">
        <div class="flex items-center justify-between pb-3 mb-3 border-b border-blue-200">
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-brand-500 animate-pulse"></span>
                <h3 class="font-bold text-xs uppercase tracking-wider text-brand-800">In Progress</h3>
            </div>
            <span class="text-xs font-extrabold px-2 py-0.5 rounded-full bg-white text-brand-700 shadow-sm">
                <?= count($kanban['in_progress']) ?>
            </span>
        </div>

        <div class="space-y-3 flex-1">
            <?php foreach ($kanban['in_progress'] as $task): ?>
                <?= view('dashboard/tasks/card', ['task' => $task]) ?>
            <?php endforeach; ?>
            <?php if (empty($kanban['in_progress'])): ?>
                <div class="h-32 border-2 border-dashed border-blue-200 rounded-xl flex items-center justify-center text-xs text-blue-300 font-medium">
                    Tidak ada tugas berjalan
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Kolom 3: Review -->
    <div class="bg-amber-50/60 rounded-2xl p-4 border border-amber-200/80 flex flex-col min-h-[500px]">
        <div class="flex items-center justify-between pb-3 mb-3 border-b border-amber-200">
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                <h3 class="font-bold text-xs uppercase tracking-wider text-amber-800">Review</h3>
            </div>
            <span class="text-xs font-extrabold px-2 py-0.5 rounded-full bg-white text-amber-700 shadow-sm">
                <?= count($kanban['review']) ?>
            </span>
        </div>

        <div class="space-y-3 flex-1">
            <?php foreach ($kanban['review'] as $task): ?>
                <?= view('dashboard/tasks/card', ['task' => $task]) ?>
            <?php endforeach; ?>
            <?php if (empty($kanban['review'])): ?>
                <div class="h-32 border-2 border-dashed border-amber-200 rounded-xl flex items-center justify-center text-xs text-amber-300 font-medium">
                    Tidak ada yang perlu direview
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Kolom 4: Done -->
    <div class="bg-emerald-50/60 rounded-2xl p-4 border border-emerald-200/80 flex flex-col min-h-[500px]">
        <div class="flex items-center justify-between pb-3 mb-3 border-b border-emerald-200">
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                <h3 class="font-bold text-xs uppercase tracking-wider text-emerald-800">Done</h3>
            </div>
            <span class="text-xs font-extrabold px-2 py-0.5 rounded-full bg-white text-emerald-700 shadow-sm">
                <?= count($kanban['done']) ?>
            </span>
        </div>

        <div class="space-y-3 flex-1">
            <?php foreach ($kanban['done'] as $task): ?>
                <?= view('dashboard/tasks/card', ['task' => $task]) ?>
            <?php endforeach; ?>
            <?php if (empty($kanban['done'])): ?>
                <div class="h-32 border-2 border-dashed border-emerald-200 rounded-xl flex items-center justify-center text-xs text-emerald-300 font-medium">
                    Belum ada tugas selesai
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<!-- Modal 1: Tambah Tugas Baru -->
<div id="createTaskModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl space-y-5 animate-in fade-in duration-200">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div>
                <h3 class="text-lg font-bold text-slate-900">Tambah Tugas Baru</h3>
                <p class="text-xs text-slate-500">Tugas akan masuk ke kolom To Do</p>
            </div>
            <button onclick="closeCreateModal()" class="w-8 h-8 rounded-lg bg-slate-100 text-slate-400 hover:text-slate-600 flex items-center justify-center">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form action="<?= site_url('dashboard/tasks/store') ?>" method="post" class="space-y-4">
            <?= csrf_field() ?>
            <input type="hidden" name="work_program_id" value="<?= esc($selectedProgramId) ?>">

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Judul Tugas <span class="text-rose-500">*</span></label>
                <input type="text" name="title" required placeholder="Contoh: Pembuatan mockup poster dan feed IG" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Deskripsi & Rincian</label>
                <textarea name="description" rows="3" placeholder="Jelaskan kebutuhan spesifik atau kriteria selesai..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-brand-500"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Prioritas</label>
                    <select name="priority" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-brand-500 bg-white">
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                        <option value="critical">Critical</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Tenggat Waktu (Deadline)</label>
                    <input type="date" name="due_date" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-brand-500 bg-white">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Penanggung Jawab (PIC)</label>
                <select name="assigned_to" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-brand-500 bg-white">
                    <option value="">-- Pilih Anggota / PIC --</option>
                    <?php foreach ($members as $m): ?>
                        <option value="<?= $m['id'] ?>"><?= esc($m['name']) ?> (<?= esc($m['role']) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                <button type="button" onclick="closeCreateModal()" class="px-4 py-2.5 text-xs font-bold text-slate-600 hover:bg-slate-100 rounded-xl transition">Batal</button>
                <button type="submit" class="px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs rounded-xl shadow-md transition">Simpan Tugas</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal 2: Catat Log Progres & Lampiran Link -->
<div id="logProgressModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl space-y-5 animate-in fade-in duration-200">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div>
                <h3 class="text-lg font-bold text-slate-900">Catat Log Progres & Lampiran</h3>
                <p class="text-xs text-slate-500" id="logTaskTitle">Perbarui perkembangan harian tugas ini</p>
            </div>
            <button onclick="closeLogModal()" class="w-8 h-8 rounded-lg bg-slate-100 text-slate-400 hover:text-slate-600 flex items-center justify-center">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form action="<?= site_url('dashboard/tasks/add-log') ?>" method="post" class="space-y-4">
            <?= csrf_field() ?>
            <input type="hidden" name="task_id" id="logTaskId" value="">

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Update Status Papan (Opsional)</label>
                <select name="status_update" id="logTaskStatus" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-brand-500 bg-white">
                    <option value="">Tetap di status saat ini</option>
                    <option value="todo">Pindahkan ke: To Do</option>
                    <option value="in_progress">Pindahkan ke: In Progress</option>
                    <option value="review">Pindahkan ke: Review</option>
                    <option value="done">Pindahkan ke: Done</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Catatan Perkembangan / Kendala <span class="text-rose-500">*</span></label>
                <textarea name="body" rows="3" required placeholder="Contoh: Telah selesai drafting proposal bab 1-3. Sedang menunggu review pembina himpunan." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-brand-500"></textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Tautan Lampiran Dokumen (Google Drive / Docs / Figma)</label>
                <div class="relative">
                    <i class="fa-solid fa-link absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="url" name="attachment" placeholder="https://drive.google.com/..." class="w-full pl-9 pr-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-brand-500">
                </div>
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                <button type="button" onclick="closeLogModal()" class="px-4 py-2.5 text-xs font-bold text-slate-600 hover:bg-slate-100 rounded-xl transition">Batal</button>
                <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-md transition">Kirim Log Progres</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openCreateModal() {
        document.getElementById('createTaskModal').classList.remove('hidden');
    }
    function closeCreateModal() {
        document.getElementById('createTaskModal').classList.add('hidden');
    }

    function openLogModal(taskId, taskTitle, currentStatus) {
        document.getElementById('logTaskId').value = taskId;
        document.getElementById('logTaskTitle').innerText = taskTitle;
        document.getElementById('logTaskStatus').value = currentStatus;
        document.getElementById('logProgressModal').classList.remove('hidden');
    }
    function closeLogModal() {
        document.getElementById('logProgressModal').classList.add('hidden');
    }
</script>

<?= $this->endSection() ?>
