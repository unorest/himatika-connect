<?= $this->extend('layout/default') ?>

<?= $this->section('content') ?>

<div class="py-12 bg-slate-50 min-h-[70vh]">
    <div class="max-w-2xl mx-auto px-4 sm:px-6">

        <!-- Title -->
        <div class="text-center mb-8">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 mb-2">
                <i class="fa-solid fa-shield-check"></i> Public Security Verification
            </span>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                Verifikasi Keaslian E-Sertifikat
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-2">
                Masukkan Kode Verifikasi (contoh: <code>HMT-A89F2C</code>) atau Nomor Sertifikat resmi untuk memeriksa validitas dokumen.
            </p>
        </div>

        <!-- Search Box -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm mb-6">
            <form action="<?= site_url('sertifikat/verify') ?>" method="get" class="flex gap-2">
                <div class="relative flex-1">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" name="code" value="<?= esc($code ?? '') ?>" required 
                           placeholder="Masukkan Kode Verifikasi (HMT-XXXXXX) atau Nomor Sertifikat..." 
                           class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-slate-200 text-xs font-mono uppercase focus:outline-none focus:ring-2 focus:ring-brand-500">
                </div>
                <button type="submit" class="px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs rounded-xl shadow-md shadow-brand-500/20 transition">
                    Verifikasi
                </button>
            </form>
        </div>

        <?php if (!empty($code)): ?>
            <?php if (!empty($certificate)): ?>
                <!-- Result Card: VERIFIED -->
                <div class="bg-white rounded-3xl border-2 border-emerald-500 p-6 sm:p-8 shadow-xl shadow-emerald-500/10">
                    <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-2xl flex-shrink-0">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>
                        <div>
                            <span class="text-[10px] font-extrabold tracking-widest uppercase bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded-md border border-emerald-200">
                                DOKUMEN RESMI TERVERIFIKASI
                            </span>
                            <h2 class="text-base font-extrabold text-slate-900 mt-0.5">Sertifikat Sah & Terdaftar</h2>
                        </div>
                    </div>

                    <div class="mt-4 space-y-3 text-xs">
                        <div class="flex flex-col sm:flex-row sm:justify-between py-2 border-b border-slate-100">
                            <span class="text-slate-400 font-medium">Nama Penerima</span>
                            <span class="font-bold text-slate-900 text-sm"><?= esc($certificate['participant_name']) ?></span>
                        </div>

                        <?php if (!empty($certificate['identifier'])): ?>
                            <div class="flex flex-col sm:flex-row sm:justify-between py-2 border-b border-slate-100">
                                <span class="text-slate-400 font-medium">NIM / Identitas</span>
                                <span class="font-mono font-bold text-slate-800"><?= esc($certificate['identifier']) ?></span>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($certificate['institution'])): ?>
                            <div class="flex flex-col sm:flex-row sm:justify-between py-2 border-b border-slate-100">
                                <span class="text-slate-400 font-medium">Instansi / Kampus</span>
                                <span class="font-semibold text-slate-800"><?= esc($certificate['institution']) ?></span>
                            </div>
                        <?php endif; ?>

                        <div class="flex flex-col sm:flex-row sm:justify-between py-2 border-b border-slate-100">
                            <span class="text-slate-400 font-medium">Nama Kegiatan</span>
                            <span class="font-bold text-brand-600 sm:text-right max-w-sm"><?= esc($certificate['event_name']) ?></span>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:justify-between py-2 border-b border-slate-100">
                            <span class="text-slate-400 font-medium">Peran / Status</span>
                            <span class="font-bold text-slate-800"><?= esc($certificate['role']) ?></span>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:justify-between py-2 border-b border-slate-100">
                            <span class="text-slate-400 font-medium">Nomor Sertifikat</span>
                            <span class="font-mono font-bold text-slate-800"><?= esc($certificate['certificate_no']) ?></span>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:justify-between py-2">
                            <span class="text-slate-400 font-medium">Tanggal Diterbitkan</span>
                            <span class="font-semibold text-slate-800"><?= date('d F Y', strtotime($certificate['created_at'])) ?></span>
                        </div>
                    </div>

                    <div class="mt-6 pt-4 border-t border-slate-100 flex flex-col sm:flex-row gap-2">
                        <a href="<?= site_url('sertifikat/download/' . $certificate['verification_code']) ?>" 
                           class="flex-1 py-2.5 px-4 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-download"></i> Unduh File PDF Resmi
                        </a>
                        <a href="<?= site_url('sertifikat/preview/' . $certificate['verification_code']) ?>" target="_blank"
                           class="py-2.5 px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-eye"></i> Pratinjau
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <!-- Result Card: NOT FOUND -->
                <div class="bg-white rounded-3xl border border-rose-200 p-8 shadow-sm text-center">
                    <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center text-2xl mx-auto mb-3">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <h3 class="text-base font-bold text-slate-900">Sertifikat Tidak Ditemukan</h3>
                    <p class="text-xs text-slate-500 max-w-md mx-auto mt-1 leading-relaxed">
                        Kode <strong>"<?= esc($code) ?>"</strong> tidak terdaftar pada sistem HIMATIKA. Pastikan penulisan kode atau nomor sertifikat sudah benar tanpa spasi tambahan.
                    </p>
                </div>
            <?php endif; ?>
        <?php endif; ?>

    </div>
</div>

<?= $this->endSection() ?>
