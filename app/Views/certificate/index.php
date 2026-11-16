<?= $this->extend('layout/default') ?>

<?= $this->section('content') ?>

<div class="py-12 bg-gradient-to-b from-brand-50/50 via-slate-50 to-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header Section -->
        <div class="text-center mb-10">
            <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold bg-brand-100 text-brand-700 mb-3 shadow-sm border border-brand-200">
                <i class="fa-solid fa-certificate"></i> E-Sertifikat & Feedback Portal
            </span>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                Feedback Kegiatan & <span class="bg-gradient-to-r from-brand-600 to-indigo-600 bg-clip-text text-transparent">Penerbitan Sertifikat</span>
            </h1>
            <p class="mt-3 text-sm text-slate-600 max-w-2xl mx-auto leading-relaxed">
                Silakan berikan ulasan dan penilaian Anda terhadap kegiatan yang telah diikuti. Sistem kami akan secara otomatis memvalidasi data dan menerbitkan <strong>E-Sertifikat Resmi berpenomoran unik</strong> dengan nama Anda.
            </p>
        </div>

        <!-- Flash Alerts -->
        <?php if (session()->getFlashdata('error')): ?>
            <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs flex items-center gap-3">
                <i class="fa-solid fa-circle-exclamation text-rose-600 text-base"></i>
                <span><?= session()->getFlashdata('error') ?></span>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('errors')): ?>
            <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs">
                <div class="font-bold mb-1.5 flex items-center gap-2">
                    <i class="fa-solid fa-triangle-exclamation text-rose-600"></i>
                    <span>Harap perbaiki beberapa isian berikut:</span>
                </div>
                <ul class="list-disc list-inside space-y-0.5 text-rose-700 ml-2">
                    <?php foreach (session()->getFlashdata('errors') as $err): ?>
                        <li><?= esc($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Form Card -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-xl shadow-slate-200/50 overflow-hidden">
            
            <!-- Banner Guide -->
            <div class="bg-gradient-to-r from-brand-600 to-indigo-700 text-white p-6 sm:p-8">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-white/10 backdrop-blur flex items-center justify-center text-2xl flex-shrink-0 text-amber-300">
                        <i class="fa-solid fa-award"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold">Klaim E-Sertifikat Resmi Partisipasi</h2>
                        <p class="text-xs text-white/80 mt-1 leading-relaxed">
                            Pastikan penulisan <strong>Nama Lengkap</strong> sudah benar karena akan dicetak langsung pada sertifikat digital. Setelah formulir dikirim, Anda dapat langsung mengunduh sertifikat dalam format PDF.
                        </p>
                    </div>
                </div>
            </div>

            <form action="<?= site_url('sertifikat/submit') ?>" method="post" class="p-6 sm:p-8 space-y-6">
                <?= csrf_field() ?>

                <!-- Honeypot -->
                <div class="hidden">
                    <input type="text" name="website_url" autocomplete="off" tabindex="-1">
                </div>

                <!-- Bagian 1: Data Peserta -->
                <div>
                    <h3 class="text-xs font-extrabold uppercase tracking-widest text-brand-600 mb-4 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-brand-100 text-brand-700 inline-flex items-center justify-center text-[11px]">1</span>
                        Data Identitas Peserta
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">
                                Nama Lengkap & Gelar (Bila Ada) <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="participant_name" value="<?= old('participant_name') ?>" required 
                                   placeholder="Contoh: Muhammad Budi Santoso, S.Kom." 
                                   class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent transition">
                            <span class="text-[11px] text-slate-400 mt-1 block">Nama ini yang akan dicetak tepat di tengah lembar sertifikat resmi.</span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">
                                    NIM / NIK / No. Identitas <span class="text-slate-400 font-normal">(Opsional)</span>
                                </label>
                                <input type="text" name="identifier" value="<?= old('identifier') ?>" 
                                       placeholder="Contoh: 22010045" 
                                       class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-brand-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">
                                    Alamat Email Aktif <span class="text-rose-500">*</span>
                                </label>
                                <input type="email" name="email" value="<?= old('email') ?>" required 
                                       placeholder="nama@email.com" 
                                       class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">
                                Asal Instansi / Universitas / Program Studi
                            </label>
                            <input type="text" name="institution" value="<?= old('institution') ?>" 
                                   placeholder="Contoh: Universitas Negeri / Program Studi Teknik Informatika" 
                                   class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                        </div>
                    </div>
                </div>

                <hr class="border-slate-100">

                <!-- Bagian 2: Pilihan Kegiatan -->
                <div>
                    <h3 class="text-xs font-extrabold uppercase tracking-widest text-brand-600 mb-4 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-brand-100 text-brand-700 inline-flex items-center justify-center text-[11px]">2</span>
                        Pilihan Kegiatan / Acara
                    </h3>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Pilih Acara yang Telah Anda Ikuti <span class="text-rose-500">*</span>
                        </label>
                        <select id="event_select" onchange="syncEventTitle()" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500 bg-white text-slate-800">
                            <option value="">-- Pilih dari Daftar Kegiatan Resmi HIMATIKA --</option>
                            <?php foreach ($events as $ev): ?>
                                <option value="<?= esc($ev['title']) ?>" data-id="<?= $ev['id'] ?>" <?= ($selectedEventId == $ev['id']) ? 'selected' : '' ?>>
                                    <?= esc($ev['title']) ?> (<?= date('d M Y', strtotime($ev['start_datetime'])) ?>)
                                </option>
                            <?php endforeach; ?>
                            <option value="custom">-- Kegiatan Lainnya (Ketik Manual) --</option>
                        </select>
                    </div>

                    <div id="custom_event_wrapper" class="mt-3 <?= ($selectedEventId ? 'hidden' : '') ?>">
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Judul Acara / Topik Webinar <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="event_title_input" name="event_title" value="<?= old('event_title', 'HIMATIKA Hackfest 2026: AI & Web Innovation Challenge') ?>" required 
                               placeholder="Contoh: Workshop Cloud Computing & DevOps Essentials" 
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                        <input type="hidden" id="event_id_input" name="event_id" value="<?= old('event_id', $selectedEventId) ?>">
                    </div>
                </div>

                <hr class="border-slate-100">

                <!-- Bagian 3: Feedback & Penilaian -->
                <div>
                    <h3 class="text-xs font-extrabold uppercase tracking-widest text-brand-600 mb-4 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-brand-100 text-brand-700 inline-flex items-center justify-center text-[11px]">3</span>
                        Feedback & Evaluasi Kegiatan
                    </h3>

                    <!-- Star Rating -->
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-slate-700 mb-2">
                            Tingkat Kepuasan & Manfaat Kegiatan <span class="text-rose-500">*</span>
                        </label>
                        <div class="flex items-center gap-3">
                            <div class="flex items-center gap-2 text-2xl text-slate-300" id="star_container">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <button type="button" onclick="setRating(<?= $i ?>)" class="focus:outline-none transition hover:scale-110" data-star="<?= $i ?>">
                                        <i class="fa-solid fa-star transition"></i>
                                    </button>
                                <?php endfor; ?>
                            </div>
                            <span id="rating_text" class="text-xs font-bold text-amber-600 ml-2">Sangat Memuaskan (5/5)</span>
                            <input type="hidden" name="rating" id="rating_input" value="<?= old('rating', 5) ?>">
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">
                                Ulasan / Materi yang Paling Bermanfaat <span class="text-rose-500">*</span>
                            </label>
                            <textarea name="feedback" rows="3" required 
                                      placeholder="Ceritakan pengalaman Anda, kejelasan penyampaian pemateri, atau materi yang paling berkesan..." 
                                      class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500"><?= old('feedback') ?></textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">
                                Kritik & Saran untuk Kegiatan HIMATIKA Berikutnya <span class="text-slate-400 font-normal">(Opsional)</span>
                            </label>
                            <textarea name="suggestions" rows="2" 
                                      placeholder="Topik apa yang ingin Anda pelajari selanjutnya? Masukan untuk panitia..." 
                                      class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500"><?= old('suggestions') ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-2 text-xs text-slate-500">
                        <i class="fa-solid fa-shield-halved text-emerald-600 text-sm"></i>
                        <span>E-Sertifikat terenkripsi & dilengkapi kode verifikasi unik.</span>
                    </div>

                    <button type="submit" class="w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-brand-600 to-indigo-600 hover:from-brand-700 hover:to-indigo-700 text-white font-extrabold text-sm rounded-2xl shadow-xl shadow-brand-500/25 hover:shadow-brand-500/40 transition flex items-center justify-center gap-2.5 group">
                        <i class="fa-solid fa-paper-plane group-hover:translate-x-1 transition-transform"></i>
                        <span>Kirim Feedback & Terbitkan Sertifikat</span>
                    </button>
                </div>

            </form>
        </div>

        <!-- Security / Verification Footer Note -->
        <div class="mt-8 text-center text-xs text-slate-400">
            Punya sertifikat dan ingin memeriksa keasliannya? 
            <a href="<?= site_url('sertifikat/verify') ?>" class="text-brand-600 font-bold hover:underline">
                Cek Verifikasi E-Sertifikat di sini &rarr;
            </a>
        </div>

    </div>
</div>

<script>
    function setRating(val) {
        document.getElementById('rating_input').value = val;
        const stars = document.querySelectorAll('#star_container button');
        stars.forEach((btn, idx) => {
            const icon = btn.querySelector('i');
            if (idx < val) {
                icon.classList.remove('text-slate-300');
                icon.classList.add('text-amber-400');
            } else {
                icon.classList.remove('text-amber-400');
                icon.classList.add('text-slate-300');
            }
        });

        const labels = {
            1: 'Kurang Puas (1/5)',
            2: 'Cukup (2/5)',
            3: 'Baik (3/5)',
            4: 'Memuaskan (4/5)',
            5: 'Sangat Memuaskan (5/5)'
        };
        document.getElementById('rating_text').innerText = labels[val] || '';
    }

    function syncEventTitle() {
        const select = document.getElementById('event_select');
        const customWrapper = document.getElementById('custom_event_wrapper');
        const titleInput = document.getElementById('event_title_input');
        const idInput = document.getElementById('event_id_input');

        if (select.value === 'custom' || select.value === '') {
            customWrapper.classList.remove('hidden');
            if (select.value === 'custom') {
                titleInput.value = '';
                titleInput.focus();
            }
            idInput.value = '';
        } else {
            customWrapper.classList.remove('hidden');
            titleInput.value = select.value;
            const selectedOpt = select.options[select.selectedIndex];
            idInput.value = selectedOpt.getAttribute('data-id') || '';
        }
    }

    // Init rating
    document.addEventListener('DOMContentLoaded', function() {
        setRating(<?= (int)old('rating', 5) ?>);
    });
</script>

<?= $this->endSection() ?>
