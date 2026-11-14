<?= $this->extend('layout/default') ?>

<?= $this->section('content') ?>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <!-- Back Navigation -->
    <a href="<?= site_url('forum') ?>" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-brand-600 transition mb-6">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Semua Aspirasi
    </a>

    <!-- Kartu Pertanyaan Utama -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-sm space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-3 pb-4 border-b border-slate-100">
            <div class="flex items-center gap-2">
                <?php if ($question['status'] === 'answered'): ?>
                    <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold inline-flex items-center gap-1.5">
                        <i class="fa-solid fa-circle-check"></i> Sudah Terjawab Resmi
                    </span>
                <?php else: ?>
                    <span class="px-3 py-1 rounded-full bg-amber-50 text-amber-800 border border-amber-200 text-xs font-bold">
                        Menunggu Jawaban Pengurus
                    </span>
                <?php endif; ?>

                <?php if (!empty($question['category_name'])): ?>
                    <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-semibold">
                        <?= esc($question['category_name']) ?>
                    </span>
                <?php endif; ?>
            </div>

            <!-- Upvote Action -->
            <form action="<?= site_url('forum/upvote/' . $question['id']) ?>" method="post">
                <?= csrf_field() ?>
                <button type="submit" class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl border border-slate-200 bg-slate-50 hover:bg-brand-50 hover:border-brand-300 hover:text-brand-600 transition text-xs font-bold text-slate-700">
                    <i class="fa-solid fa-chevron-up"></i>
                    <span>Dukung (<?= esc($question['upvotes']) ?>)</span>
                </button>
            </form>
        </div>

        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 leading-tight">
                <?= esc($question['title']) ?>
            </h1>

            <div class="flex items-center gap-2 text-xs text-slate-400 mt-2">
                <span>Diajukan oleh: <strong class="text-slate-700"><?= $question['is_anonymous'] ? 'Mahasiswa Anonim' : esc($question['author_name']) ?></strong></span>
                <span>•</span>
                <span><?= date('d F Y, H:i', strtotime($question['created_at'])) ?> WIB</span>
            </div>
        </div>

        <div class="text-sm sm:text-base text-slate-700 leading-relaxed whitespace-pre-line pt-2">
            <?= esc($question['body']) ?>
        </div>
    </div>

    <!-- Sesi Tanggapan / Jawaban -->
    <div class="mt-8 space-y-6">
        <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
            <i class="fa-solid fa-reply text-brand-600"></i>
            Tanggapan & Klarifikasi Resmi (<?= count($question['answers']) ?>)
        </h2>

        <?php if (!empty($question['answers'])): ?>
            <?php foreach ($question['answers'] as $ans): ?>
                <div class="bg-gradient-to-br from-brand-50/70 to-white rounded-2xl border-2 border-brand-200 p-6 sm:p-7 shadow-sm space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-brand-600 text-white flex items-center justify-center font-bold text-sm shadow">
                                <i class="fa-solid fa-user-shield"></i>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h4 class="font-bold text-sm text-slate-900"><?= esc($ans['responder_name'] ?? 'Pengurus HIMATIKA') ?></h4>
                                    <span class="px-2 py-0.5 rounded bg-brand-100 text-brand-700 text-[10px] font-bold uppercase">Resmi</span>
                                </div>
                                <span class="text-xs text-slate-500">
                                    <?= esc($ans['division_name'] ?? 'HIMATIKA Connect') ?>
                                </span>
                            </div>
                        </div>

                        <span class="text-xs text-slate-400">
                            <?= date('d M Y, H:i', strtotime($ans['created_at'])) ?>
                        </span>
                    </div>

                    <div class="text-sm text-slate-800 leading-relaxed whitespace-pre-line pl-1 sm:pl-13">
                        <?= esc($ans['body']) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="bg-amber-50/60 rounded-2xl border border-amber-200 p-8 text-center space-y-2">
                <i class="fa-regular fa-hourglass-half text-amber-500 text-2xl"></i>
                <h3 class="font-bold text-sm text-slate-900">Belum Ada Jawaban Resmi</h3>
                <p class="text-xs text-slate-500 max-w-md mx-auto">
                    Pertanyaan ini sudah masuk ke antrean kurasi pengurus HIMATIKA dan sedang dikoordinasikan dengan divisi atau pihak terkait.
                </p>
            </div>
        <?php endif; ?>
    </div>

</div>

<?= $this->endSection() ?>
