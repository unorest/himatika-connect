<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Workspace Pengurus — HIMATIKA') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            900: '#1e3a8a',
                        }
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-slate-100 text-slate-800 antialiased min-h-screen flex flex-col md:flex-row">

    <!-- Sidebar Navigation -->
    <aside class="w-full md:w-64 bg-slate-900 text-slate-300 flex-shrink-0 flex flex-col justify-between border-r border-slate-800">
        <div>
            <!-- Sidebar Header / Brand -->
            <div class="h-20 flex items-center gap-3 px-6 border-b border-slate-800">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-brand-600 to-indigo-600 flex items-center justify-center text-white text-lg shadow-md">
                    <i class="fa-solid fa-code-merge"></i>
                </div>
                <div>
                    <span class="font-extrabold text-white text-base tracking-tight block">HIMATIKA <span class="text-brand-400">Hub</span></span>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block -mt-1">Internal Workspace</span>
                </div>
            </div>

            <!-- User Quick Card -->
            <div class="p-4 mx-4 mt-4 rounded-2xl bg-slate-800/80 border border-slate-700/80">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-brand-600/30 border border-brand-500/30 text-brand-400 flex items-center justify-center font-bold text-sm">
                        <i class="fa-solid fa-user-tie"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-xs font-bold text-white truncate"><?= esc(session()->get('user_name')) ?></h4>
                        <div class="flex items-center gap-1.5 mt-0.5">
                            <?php
                            $role = session()->get('user_role');
                            $roleBadges = [
                                'admin' => 'bg-rose-500/20 text-rose-400 border-rose-500/30',
                                'kadiv' => 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30',
                                'staff' => 'bg-blue-500/20 text-blue-400 border-blue-500/30',
                            ];
                            ?>
                            <span class="px-2 py-0.5 rounded text-[9px] font-extrabold uppercase border <?= $roleBadges[$role] ?? 'bg-slate-700 text-slate-300' ?>">
                                <?= esc($role) ?>
                            </span>
                            <span class="text-[10px] text-slate-400 truncate"><?= esc(session()->get('user_division_name')) ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Menu List -->
            <nav class="p-4 space-y-1.5 text-xs font-semibold">
                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest px-3 block pt-2 pb-1">Menu Utama</span>

                <a href="<?= site_url('dashboard') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition <?= uri_string() === 'dashboard' ? 'bg-brand-600 text-white shadow-md shadow-brand-600/30 font-bold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' ?>">
                    <i class="fa-solid fa-gauge-high w-4 text-center"></i>
                    <span>Ringkasan Dashboard</span>
                </a>

                <a href="<?= site_url('dashboard/tasks') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition <?= str_contains(uri_string(), 'dashboard/tasks') ? 'bg-brand-600 text-white shadow-md font-bold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' ?>">
                    <i class="fa-solid fa-list-check w-4 text-center"></i>
                    <span>Kanban & Task Tracker</span>
                </a>

                <a href="<?= site_url('dashboard/schedules') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition <?= str_contains(uri_string(), 'dashboard/schedules') ? 'bg-brand-600 text-white shadow-md font-bold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' ?>">
                    <i class="fa-regular fa-calendar-check w-4 text-center"></i>
                    <span>Agenda & Rapat Internal</span>
                </a>

                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest px-3 block pt-4 pb-1">Pelayanan Publik</span>

                <a href="<?= site_url('dashboard/posts') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition <?= str_contains(uri_string(), 'dashboard/posts') ? 'bg-brand-600 text-white shadow-md font-bold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' ?>">
                    <i class="fa-regular fa-newspaper w-4 text-center"></i>
                    <span>Kelola Berita & Artikel</span>
                </a>

                <a href="<?= site_url('dashboard/moderasi-qa') ?>" class="flex items-center justify-between px-3 py-2.5 rounded-xl transition <?= str_contains(uri_string(), 'dashboard/moderasi-qa') ? 'bg-brand-600 text-white shadow-md font-bold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' ?>">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-comments w-4 text-center"></i>
                        <span>Moderasi Q&A Mahasiswa</span>
                    </div>
                </a>

                <a href="<?= site_url('dashboard/certificates') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition <?= str_contains(uri_string(), 'dashboard/certificates') ? 'bg-brand-600 text-white shadow-md font-bold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' ?>">
                    <i class="fa-solid fa-award w-4 text-center"></i>
                    <span>Rekap E-Sertifikat & Feedback</span>
                </a>

                <?php if (session()->get('user_role') === 'admin'): ?>
                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest px-3 block pt-4 pb-1">Administrasi BPH</span>

                    <a href="<?= site_url('dashboard/pengurus') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition <?= str_contains(uri_string(), 'dashboard/pengurus') ? 'bg-brand-600 text-white shadow-md font-bold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' ?>">
                        <i class="fa-solid fa-users-gear w-4 text-center"></i>
                        <span>Kelola Pengurus & Role</span>
                    </a>
                <?php endif; ?>
            </nav>
        </div>

        <!-- Logout Bottom Section -->
        <div class="p-4 border-t border-slate-800">
            <a href="<?= site_url('logout') ?>" class="flex items-center justify-center gap-2 w-full py-2.5 rounded-xl text-xs font-bold text-rose-400 bg-rose-500/10 hover:bg-rose-500/20 border border-rose-500/20 transition">
                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                <span>Keluar (Logout)</span>
            </a>
        </div>
    </aside>

    <!-- Main Workspace -->
    <div class="flex-1 flex flex-col min-w-0">

        <!-- Top Header Bar -->
        <header class="h-20 bg-white border-b border-slate-200 px-6 sm:px-8 flex items-center justify-between sticky top-0 z-30 shadow-sm">
            <div class="flex items-center gap-3">
                <span class="text-xs font-medium text-slate-500 hidden sm:inline-block">
                    <i class="fa-regular fa-clock mr-1 text-slate-400"></i>
                    <?= date('l, d F Y') ?>
                </span>
            </div>

            <div class="flex items-center gap-3">
                <a href="<?= site_url('/') ?>" target="_blank" class="px-3.5 py-2 rounded-xl text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition flex items-center gap-1.5">
                    <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-brand-600"></i>
                    <span>Buka Portal Publik</span>
                </a>
            </div>
        </header>

        <!-- Main Body -->
        <main class="flex-1 p-6 sm:p-8 overflow-y-auto">

            <!-- Flash Alerts -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center gap-3 shadow-sm">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                    <div><?= session()->getFlashdata('success') ?></div>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold flex items-center gap-3 shadow-sm">
                    <i class="fa-solid fa-circle-xmark text-rose-600 text-base"></i>
                    <div><?= session()->getFlashdata('error') ?></div>
                </div>
            <?php endif; ?>

            <?= $this->renderSection('content') ?>
        </main>
    </div>

</body>
</html>
