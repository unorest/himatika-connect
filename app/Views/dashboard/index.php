<?= $this->extend('layout/admin') ?>

<?= $this->section('content') ?>

<!-- Welcome Banner -->
<div class="bg-gradient-to-r from-brand-700 via-brand-600 to-indigo-700 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-brand-500/10 mb-8 relative overflow-hidden">
    <div class="relative z-10 max-w-2xl">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/20 backdrop-blur text-[11px] font-bold uppercase tracking-wider mb-3">
            <i class="fa-solid fa-sparkles text-amber-300"></i> Workspace Terpadu HIMATIKA
        </div>
        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
            Selamat datang, <?= esc(session()->get('user_name')) ?>!
        </h1>
        <p class="text-xs sm:text-sm text-slate-100 mt-2 leading-relaxed">
            Anda masuk sebagai <strong class="uppercase text-amber-300 font-black"><?= esc(session()->get('user_role')) ?></strong> pada <strong class="text-white"><?= esc(session()->get('user_division_name')) ?></strong>. Pantau perkembangan proker dan selesaikan tugas tim Anda hari ini.
        </p>

        <!-- Quick Action Buttons -->
        <div class="flex flex-wrap items-center gap-3 mt-5">
            <a href="<?= site_url('dashboard/tasks?action=new_task') ?>" class="px-4 py-2.5 rounded-xl bg-white text-brand-700 hover:bg-slate-100 font-bold text-xs shadow-md shadow-black/10 transition inline-flex items-center gap-2">
                <i class="fa-solid fa-plus text-brand-600"></i>
                <span>Tambah Tugas Baru</span>
            </a>
            <?php if (in_array(session()->get('user_role'), ['admin', 'kadiv'], true)): ?>
                <a href="<?= site_url('dashboard/tasks?action=new_program') ?>" class="px-4 py-2.5 rounded-xl bg-slate-900/40 hover:bg-slate-900/60 border border-white/20 text-white font-bold text-xs backdrop-blur transition inline-flex items-center gap-2">
                    <i class="fa-solid fa-folder-plus text-amber-300"></i>
                    <span>Program Kerja Baru</span>
                </a>
            <?php endif; ?>
            <a href="<?= site_url('dashboard/tasks') ?>" class="px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs backdrop-blur transition inline-flex items-center gap-2">
                <i class="fa-solid fa-list-check"></i>
                <span>Papan Kanban</span>
            </a>
        </div>
    </div>
</div>

<!-- 3 Quick Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
        <div>
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Tugas Ditugaskan ke Saya</span>
            <span class="text-3xl font-extrabold text-slate-900 mt-1 block"><?= esc($stats['my_tasks_count']) ?></span>
            <span class="text-[11px] text-slate-500 mt-1 block">Tugas aktif dalam tanggung jawab</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center text-xl">
            <i class="fa-solid fa-clipboard-check"></i>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
        <div>
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Program Kerja Aktif</span>
            <span class="text-3xl font-extrabold text-indigo-600 mt-1 block"><?= esc($stats['proker_count']) ?></span>
            <span class="text-[11px] text-slate-500 mt-1 block">Proker terdaftar periode ini</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl">
            <i class="fa-solid fa-diagram-project"></i>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
        <div>
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Aspirasi Mahasiswa Masuk</span>
            <span class="text-3xl font-extrabold text-amber-500 mt-1 block"><?= esc($stats['pending_qa_count']) ?></span>
            <span class="text-[11px] text-slate-500 mt-1 block">Menunggu verifikasi & balasan</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center text-xl">
            <i class="fa-solid fa-inbox"></i>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

    <!-- Progress Program Kerja (7 Kolom) -->
    <div class="lg:col-span-7 space-y-6">
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Program Kerja (Monitoring Progres)</h2>
                    <p class="text-xs text-slate-500">Status dan kemajuan pelaksanaan proker</p>
                </div>
                <div class="flex items-center gap-2">
                    <?php if (in_array(session()->get('user_role'), ['admin', 'kadiv'], true)): ?>
                        <a href="<?= site_url('dashboard/tasks?action=new_program') ?>" class="px-3 py-1.5 rounded-xl bg-brand-50 hover:bg-brand-100 text-brand-700 text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                            <i class="fa-solid fa-plus text-[10px]"></i>
                            <span>Proker Baru</span>
                        </a>
                    <?php endif; ?>
                    <a href="<?= site_url('dashboard/tasks') ?>" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition flex items-center gap-1.5">
                        <i class="fa-solid fa-list-check text-[10px]"></i>
                        <span>Buka Kanban</span>
                    </a>
                </div>
            </div>

            <div class="space-y-4">
                <?php if (!empty($workPrograms)): ?>
                    <?php foreach ($workPrograms as $wp): ?>
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 space-y-3">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <h3 class="font-bold text-sm text-slate-900"><?= esc($wp['title']) ?></h3>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded text-white" style="background-color: <?= esc($wp['division_color'] ?? '#3b82f6') ?>;">
                                            <?= esc($wp['division_name'] ?? 'Divisi') ?>
                                        </span>
                                        <span class="text-xs text-slate-400">• Periode <?= esc($wp['period_year']) ?></span>
                                    </div>
                                </div>
                                <span class="text-sm font-extrabold text-brand-600"><?= esc($wp['progress']) ?>%</span>
                            </div>

                            <!-- Progress Bar -->
                            <div class="w-full h-2 rounded-full bg-slate-200 overflow-hidden">
                                <div class="h-full bg-brand-600 rounded-full transition-all duration-500" style="width: <?= esc($wp['progress']) ?>%;"></div>
                            </div>

                            <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">
                                <?= esc($wp['description']) ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-8 text-slate-400 text-xs">Belum ada program kerja yang didaftarkan.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Tugas Saya (5 Kolom) -->
    <div class="lg:col-span-5 space-y-6">
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Daftar Tugas Saya</h2>
                    <p class="text-xs text-slate-500">To-do list aktif Anda</p>
                </div>
                <a href="<?= site_url('dashboard/tasks?action=new_task') ?>" class="px-3 py-1.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                    <i class="fa-solid fa-plus text-[10px]"></i>
                    <span>Tambah Tugas</span>
                </a>
            </div>

            <div class="space-y-3">
                <?php if (!empty($myTasks)): ?>
                    <?php foreach ($myTasks as $t): ?>
                        <div class="p-3.5 rounded-xl border border-slate-200 bg-white hover:border-slate-300 transition space-y-2">
                            <div class="flex items-center justify-between">
                                <?php
                                $priorityColors = [
                                    'low'      => 'bg-slate-100 text-slate-600',
                                    'medium'   => 'bg-blue-50 text-blue-700',
                                    'high'     => 'bg-amber-50 text-amber-700',
                                    'critical' => 'bg-rose-50 text-rose-700',
                                ];
                                $statusBadges = [
                                    'todo'        => 'To Do',
                                    'in_progress' => 'In Progress',
                                    'review'      => 'Review',
                                    'done'        => 'Done',
                                ];
                                ?>
                                <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded <?= $priorityColors[$t['priority']] ?? 'bg-slate-100 text-slate-600' ?>">
                                    <?= esc($t['priority']) ?>
                                </span>
                                <span class="text-[11px] font-semibold text-slate-500">
                                    <?= $statusBadges[$t['status']] ?? esc($t['status']) ?>
                                </span>
                            </div>

                            <h4 class="text-xs font-bold text-slate-900 leading-snug"><?= esc($t['title']) ?></h4>

                            <div class="flex items-center justify-between text-[11px] text-slate-400 pt-1 border-t border-slate-100">
                                <span><i class="fa-regular fa-calendar mr-1"></i> <?= $t['due_date'] ? date('d M Y', strtotime($t['due_date'])) : '-' ?></span>
                                <span class="truncate max-w-[120px] text-slate-500"><?= esc($t['program_title'] ?? '') ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-8 text-slate-400 text-xs">
                        <i class="fa-solid fa-circle-check text-2xl text-emerald-500 block mb-2"></i>
                        Semua tugas Anda telah terselesaikan!
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>

<?= $this->endSection() ?>
