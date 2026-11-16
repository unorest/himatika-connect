<?= $this->extend('layout/default') ?>

<?= $this->section('content') ?>

<div class="py-12 bg-gradient-to-b from-emerald-50/40 via-slate-50 to-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Thank You Banner -->
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-3xl flex items-center justify-center text-3xl mx-auto mb-4 shadow-lg shadow-emerald-500/20 animate-bounce">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <span class="text-xs font-bold text-emerald-600 uppercase tracking-widest block mb-1">Feedback Terekam & Tervalidasi</span>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                Terima Kasih, <?= esc($certificate['participant_name']) ?>!
            </h1>
            <p class="text-xs sm:text-sm text-slate-600 max-w-xl mx-auto mt-2 leading-relaxed">
                Feedback Anda sangat berharga bagi peningkatan mutu kegiatan HIMATIKA. E-Sertifikat resmi Anda telah berhasil dibuat dan siap untuk diunduh.
            </p>
        </div>

        <!-- Certificate Visual Representation Card (A4 Aspect Ratio) -->
        <div class="relative bg-white rounded-3xl border-2 border-amber-400 p-6 sm:p-10 shadow-2xl shadow-slate-300/60 mb-8 overflow-hidden">
            <!-- Decorative Corners -->
            <div class="absolute top-0 left-0 w-24 h-24 bg-gradient-to-br from-brand-600 to-indigo-600 -translate-x-12 -translate-y-12 rotate-45"></div>
            <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-bl from-amber-500 to-amber-600 translate-x-12 -translate-y-12 rotate-45"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-gradient-to-tr from-amber-500 to-amber-600 -translate-x-12 translate-y-12 rotate-45"></div>
            <div class="absolute bottom-0 right-0 w-24 h-24 bg-gradient-to-tl from-brand-600 to-indigo-600 translate-x-12 translate-y-12 rotate-45"></div>

            <div class="relative z-10 text-center">
                <!-- Org Badge -->
                <div class="inline-flex items-center gap-2 text-[10px] font-extrabold uppercase tracking-widest text-brand-700 bg-brand-50 px-3 py-1 rounded-full border border-brand-200 mb-2">
                    <i class="fa-solid fa-code-merge"></i> Himpunan Mahasiswa Informatika & Matematika
                </div>

                <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-wide mt-2">
                    SERTIFIKAT PENGHARGAAN
                </h2>
                <p class="text-[11px] text-slate-400 italic">Certificate of Participation & Appreciation</p>

                <div class="text-[11px] font-mono text-slate-500 font-semibold mt-1">
                    No: <span class="text-slate-800"><?= esc($certificate['certificate_no']) ?></span>
                </div>

                <!-- Recipient Name -->
                <div class="my-6 sm:my-8 py-4 border-y border-dashed border-amber-200 bg-amber-50/40 rounded-2xl">
                    <span class="text-[11px] font-medium text-slate-500 block mb-1">Diberikan dengan bangga kepada:</span>
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-brand-700 tracking-tight">
                        <?= esc($certificate['participant_name']) ?>
                    </h3>
                    <?php if (!empty($certificate['identifier']) || !empty($certificate['institution'])): ?>
                        <p class="text-xs text-slate-500 font-medium mt-1">
                            <?= !empty($certificate['identifier']) ? 'NIM/ID: ' . esc($certificate['identifier']) : '' ?>
                            <?= (!empty($certificate['identifier']) && !empty($certificate['institution'])) ? ' • ' : '' ?>
                            <?= esc($certificate['institution']) ?>
                        </p>
                    <?php endif; ?>
                </div>

                <!-- Event Details -->
                <p class="text-xs text-slate-600 max-w-lg mx-auto">
                    Atas partisipasi aktif dan kontribusinya sebagai <strong class="text-slate-900 font-bold"><?= esc($certificate['role']) ?></strong> dalam kegiatan:
                </p>
                <h4 class="text-sm sm:text-base font-extrabold text-slate-900 mt-1 max-w-xl mx-auto leading-snug">
                    "<?= esc($certificate['event_name']) ?>"
                </h4>

                <!-- Bottom Metadata -->
                <div class="mt-8 pt-4 border-t border-slate-100 grid grid-cols-1 sm:grid-cols-2 gap-4 items-center text-left text-xs">
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-200">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Validasi Digital</span>
                        <div class="font-mono font-bold text-slate-800 mt-0.5"><?= esc($certificate['verification_code']) ?></div>
                        <div class="text-[10px] text-emerald-600 font-semibold flex items-center gap-1 mt-0.5">
                            <i class="fa-solid fa-circle-check"></i> Terverifikasi Resmi di Sistem HIMATIKA
                        </div>
                    </div>

                    <div class="sm:text-right text-slate-500 text-[11px]">
                        <div>Diterbitkan pada: <strong><?= date('d F Y', strtotime($certificate['created_at'])) ?></strong></div>
                        <div>Oleh: <strong>Dewan Pengurus HIMATIKA Connect</strong></div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Download & Distribution Action Cards -->
        <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-sm">
            <h3 class="text-sm font-extrabold text-slate-900 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-cloud-arrow-down text-brand-600"></i>
                <span>Pilihan Distribusi & Unduhan Sertifikat</span>
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <!-- Action 1: Force Download PDF -->
                <a href="<?= site_url('sertifikat/download/' . $certificate['verification_code']) ?>" 
                   class="flex flex-col items-center justify-center p-5 rounded-2xl bg-gradient-to-br from-brand-600 to-indigo-600 text-white hover:from-brand-700 hover:to-indigo-700 shadow-lg shadow-brand-500/25 transition group text-center">
                    <i class="fa-solid fa-file-pdf text-3xl mb-2 group-hover:scale-110 transition-transform text-amber-300"></i>
                    <span class="font-extrabold text-sm">Unduh Sertifikat (PDF)</span>
                    <span class="text-[10px] text-white/80 mt-0.5">Format A4 Siap Cetak Kualitas Tinggi</span>
                </a>

                <!-- Action 2: Preview in New Tab -->
                <a href="<?= site_url('sertifikat/preview/' . $certificate['verification_code']) ?>" target="_blank"
                   class="flex flex-col items-center justify-center p-5 rounded-2xl bg-slate-50 hover:bg-slate-100 text-slate-800 border border-slate-200 transition text-center group">
                    <i class="fa-solid fa-arrow-up-right-from-square text-2xl text-slate-600 mb-2 group-hover:text-brand-600 transition"></i>
                    <span class="font-bold text-sm">Lihat Pratinjau PDF</span>
                    <span class="text-[10px] text-slate-400 mt-0.5">Buka langsung di tab browser baru</span>
                </a>

                <!-- Action 3: Verification Link -->
                <button type="button" onclick="copyVerifyLink()" 
                        class="flex flex-col items-center justify-center p-5 rounded-2xl bg-slate-50 hover:bg-slate-100 text-slate-800 border border-slate-200 transition text-center group">
                    <i class="fa-solid fa-link text-2xl text-slate-600 mb-2 group-hover:text-emerald-600 transition"></i>
                    <span class="font-bold text-sm" id="btn_copy_text">Salin Tautan Verifikasi</span>
                    <span class="text-[10px] text-slate-400 mt-0.5">Untuk dicantumkan di CV / LinkedIn</span>
                </button>
            </div>

            <!-- Additional Links -->
            <div class="mt-6 pt-4 border-t border-slate-100 flex flex-wrap items-center justify-between gap-3 text-xs text-slate-500">
                <a href="<?= site_url('sertifikat') ?>" class="hover:text-brand-600 font-semibold flex items-center gap-1.5">
                    <i class="fa-solid fa-arrow-left"></i> Isi Feedback Acara Lain
                </a>

                <a href="<?= site_url('sertifikat/verify/' . $certificate['verification_code']) ?>" class="text-brand-600 font-bold hover:underline flex items-center gap-1">
                    <i class="fa-solid fa-shield-check"></i> Buka Halaman Verifikasi Publik &rarr;
                </a>
            </div>
        </div>

    </div>
</div>

<script>
    function copyVerifyLink() {
        const url = '<?= site_url("sertifikat/verify/" . $certificate["verification_code"]) ?>';
        navigator.clipboard.writeText(url).then(function() {
            const btnText = document.getElementById('btn_copy_text');
            btnText.innerText = 'Tautan Berhasil Disalin! ✓';
            setTimeout(() => {
                btnText.innerText = 'Salin Tautan Verifikasi';
            }, 3000);
        });
    }
</script>

<?= $this->endSection() ?>
