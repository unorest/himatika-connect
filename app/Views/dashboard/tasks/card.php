<div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-sm hover:shadow-md transition space-y-3">
    <!-- Header: Prioritas & Quick Actions -->
    <div class="flex items-center justify-between gap-2">
        <?php
        $priorityBadge = [
            'low'      => 'bg-slate-100 text-slate-600',
            'medium'   => 'bg-blue-100 text-blue-700',
            'high'     => 'bg-amber-100 text-amber-700',
            'critical' => 'bg-rose-100 text-rose-700 font-extrabold',
        ];
        ?>
        <span class="text-[9px] font-extrabold uppercase px-2 py-0.5 rounded <?= $priorityBadge[$task['priority']] ?? 'bg-slate-100 text-slate-600' ?>">
            <?= esc($task['priority']) ?>
        </span>

        <!-- Quick Status Change Form -->
        <form action="<?= site_url('dashboard/tasks/update-status') ?>" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="task_id" value="<?= $task['id'] ?>">
            <select name="status" onchange="this.form.submit()" class="text-[10px] font-bold px-2 py-1 rounded-lg border border-slate-200 bg-slate-50 text-slate-600 cursor-pointer hover:bg-slate-100 focus:outline-none">
                <option value="todo" <?= $task['status'] === 'todo' ? 'selected' : '' ?>>→ To Do</option>
                <option value="in_progress" <?= $task['status'] === 'in_progress' ? 'selected' : '' ?>>→ In Progress</option>
                <option value="review" <?= $task['status'] === 'review' ? 'selected' : '' ?>>→ Review</option>
                <option value="done" <?= $task['status'] === 'done' ? 'selected' : '' ?>>→ Done</option>
            </select>
        </form>
    </div>

    <!-- Judul & Deskripsi -->
    <div>
        <h4 class="text-xs font-bold text-slate-900 leading-snug"><?= esc($task['title']) ?></h4>
        <?php if (!empty($task['description'])): ?>
            <p class="text-[11px] text-slate-500 mt-1 line-clamp-2 leading-relaxed">
                <?= esc($task['description']) ?>
            </p>
        <?php endif; ?>
    </div>

    <!-- PIC & Deadline -->
    <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-[11px]">
        <div class="flex items-center gap-1.5 text-slate-600">
            <div class="w-5 h-5 rounded-full bg-brand-100 text-brand-600 flex items-center justify-center font-bold text-[9px]">
                <?= strtoupper(substr($task['assignee_name'] ?? 'U', 0, 1)) ?>
            </div>
            <span class="truncate max-w-[85px] font-medium"><?= esc($task['assignee_name'] ?? 'Belum ada PIC') ?></span>
        </div>

        <?php if (!empty($task['due_date'])): ?>
            <span class="text-slate-400 font-medium">
                <i class="fa-regular fa-clock text-[10px] mr-0.5"></i>
                <?= date('d M', strtotime($task['due_date'])) ?>
            </span>
        <?php endif; ?>
    </div>

    <!-- Progress Logs Section -->
    <div class="pt-2 border-t border-slate-100 space-y-2">
        <div class="flex items-center justify-between">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                Log (<?= count($task['logs']) ?>)
            </span>
            <button type="button" onclick="openLogModal(<?= $task['id'] ?>, '<?= esc(addslashes($task['title'])) ?>', '<?= esc($task['status']) ?>')" class="text-[10px] font-bold text-brand-600 hover:text-brand-700 flex items-center gap-1">
                <i class="fa-solid fa-pen-to-square"></i>
                <span>+ Lapor Progres</span>
            </button>
        </div>

        <?php if (!empty($task['logs'])): ?>
            <div class="space-y-1.5 max-h-32 overflow-y-auto pr-1">
                <?php foreach (array_slice($task['logs'], 0, 2) as $log): ?>
                    <div class="p-2 rounded-lg bg-slate-50 border border-slate-100 text-[10px] text-slate-600 leading-snug">
                        <div class="flex items-center justify-between font-bold text-slate-700 mb-0.5">
                            <span><?= esc($log['author_name'] ?? 'Pengurus') ?></span>
                            <span class="text-[9px] text-slate-400 font-normal"><?= date('d/m H:i', strtotime($log['created_at'])) ?></span>
                        </div>
                        <p><?= esc($log['body']) ?></p>
                        <?php if (!empty($log['attachment'])): ?>
                            <a href="<?= esc($log['attachment']) ?>" target="_blank" class="mt-1 inline-flex items-center gap-1 text-[9px] font-bold text-brand-600 hover:underline">
                                <i class="fa-solid fa-link text-[8px]"></i> Dokumen Lampiran
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
