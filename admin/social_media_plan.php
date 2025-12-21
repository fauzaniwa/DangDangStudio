<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Media Plan - DangDang Studio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="assets/tailwind-config.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
    <style>
        .animate-fade-in { animation: fadeIn 0.3s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="bg-[#FBFBFB] text-slate-800">

    <div class="flex flex-col h-screen overflow-hidden">
        <?php include_once '_header.php'; ?>
        <div class="flex flex-1 overflow-hidden">
            <?php include_once '_aside.php'; ?>
            <main class="flex-1 flex flex-col min-w-0 overflow-y-auto">
                <?php include_once '_topbar.php'; ?>

                <div class="p-6 md:p-10 flex-1">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
                        <div>
                            <h1 class="text-3xl font-extrabold text-brandPrimary tracking-tight">Social Media Plan</h1>
                            <p class="text-sm text-gray-500 font-medium">Jadwalkan dan pantau konten promosi studio di berbagai platform.</p>
                        </div>
                        <a href="social_media_add.php" class="flex items-center gap-2 px-6 py-3 bg-brandPrimary text-white rounded-2xl font-bold shadow-lg shadow-brandPrimary/20 hover:scale-105 transition-all text-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Create Plan
                        </a>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10">
                        <div class="bg-white p-6 rounded-[28px] border border-gray-100 shadow-sm">
                            <p class="text-[10px] font-bold text-gray-400 uppercase mb-1 tracking-widest">Total Campaign</p>
                            <p class="text-2xl font-extrabold text-brandPrimary">12</p>
                        </div>
                        <div class="bg-white p-6 rounded-[28px] border border-gray-100 shadow-sm border-l-4 border-l-brandGold">
                            <p class="text-[10px] font-bold text-gray-400 uppercase mb-1 tracking-widest">Upcoming</p>
                            <p class="text-2xl font-extrabold text-brandGold">5</p>
                        </div>
                        <div class="bg-white p-6 rounded-[28px] border border-gray-100 shadow-sm border-l-4 border-l-brandTeal">
                            <p class="text-[10px] font-bold text-gray-400 uppercase mb-1 tracking-widest">Live</p>
                            <p class="text-2xl font-extrabold text-brandTeal">4</p>
                        </div>
                        <div class="bg-white p-6 rounded-[28px] border border-gray-100 shadow-sm border-l-4 border-l-brandAccent">
                            <p class="text-[10px] font-bold text-gray-400 uppercase mb-1 tracking-widest">Draft</p>
                            <p class="text-2xl font-extrabold text-brandAccent">3</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <?php
                        $plans = [
                            [
                                'id' => 201,
                                'title' => 'Project X Teaser Trailer',
                                'platforms' => ['YouTube', 'Instagram', 'TikTok'],
                                'publish_date' => '25 Des 2025',
                                'publish_time' => '19:00',
                                'status' => 'Scheduled',
                                'thumbnail' => 'https://images.unsplash.com/photo-1542751371-adc38448a05e?q=80&w=200'
                            ],
                            [
                                'id' => 202,
                                'title' => 'Behind the Scene: Audio Recording',
                                'platforms' => ['Instagram', 'X'],
                                'publish_date' => '27 Des 2025',
                                'publish_time' => '10:00',
                                'status' => 'Draft',
                                'thumbnail' => 'https://images.unsplash.com/photo-1598488035139-bdbb2231ce04?q=80&w=200'
                            ]
                        ];

                        foreach ($plans as $plan): ?>
                            <div class="group bg-white rounded-[32px] p-4 flex flex-col md:flex-row items-center gap-6 border border-gray-100 hover:border-brandGold/30 hover:shadow-xl hover:shadow-brandPrimary/5 transition-all duration-500">
                                <div class="w-full md:w-40 h-24 rounded-[20px] overflow-hidden bg-gray-100 flex-shrink-0 relative">
                                    <img src="<?php echo $plan['thumbnail']; ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                                </div>

                                <div class="flex-1 space-y-3">
                                    <div class="flex flex-wrap gap-1.5">
                                        <?php foreach($plan['platforms'] as $p): ?>
                                            <span class="text-[9px] font-bold px-2.5 py-1 rounded-lg bg-gray-50 text-gray-400 border border-gray-100 uppercase"><?php echo $p; ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                    <h3 class="text-lg font-bold text-brandPrimary group-hover:text-brandGold transition-colors"><?php echo $plan['title']; ?></h3>
                                    <div class="flex items-center gap-4 text-[11px] text-gray-400 font-bold uppercase tracking-tight">
                                        <span class="flex items-center gap-1.5">
                                            <svg class="w-4 h-4 text-brandGold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            <?php echo $plan['publish_date']; ?>
                                        </span>
                                        <span class="flex items-center gap-1.5">
                                            <svg class="w-4 h-4 text-brandGold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            <?php echo $plan['publish_time']; ?> WIB
                                        </span>
                                    </div>
                                </div>

                                <div class="flex items-center gap-6 w-full md:w-auto pr-4">
                                    <div class="text-right">
                                        <?php if($plan['status'] == 'Scheduled'): ?>
                                            <span class="flex items-center gap-1.5 text-brandGold text-[10px] font-bold uppercase tracking-widest">
                                                <span class="w-1.5 h-1.5 rounded-full bg-brandGold animate-pulse"></span> Scheduled
                                            </span>
                                        <?php else: ?>
                                            <span class="flex items-center gap-1.5 text-gray-300 text-[10px] font-bold uppercase tracking-widest">
                                                <span class="w-1.5 h-1.5 rounded-full bg-gray-200"></span> Draft
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="flex gap-2">
                                        <a href="social_media_edit.php?id=<?php echo $plan['id']; ?>" class="w-11 h-11 rounded-xl bg-gray-50 flex items-center justify-center text-gray-400 hover:text-brandPrimary hover:bg-brandPrimary/5 transition-all shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>
                                        <button onclick="confirmDeleteArticle('<?php echo $plan['id']; ?>', '<?php echo $plan['title']; ?>')" class="w-11 h-11 rounded-xl bg-gray-50 flex items-center justify-center text-gray-400 hover:text-brandAccent hover:bg-brandAccent/5 transition-all shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <?php include_once '_footer.php'; ?>
            </main>
        </div>
    </div>

    <div id="modal-delete" class="fixed inset-0 z-[100] hidden overflow-y-auto">
        <div class="fixed inset-0 bg-brandPrimary/40 backdrop-blur-sm transition-opacity" onclick="closeModalDelete()"></div>
        <div class="flex min-h-full items-center justify-center p-4 text-center">
            <div class="relative transform overflow-hidden rounded-[32px] bg-white p-8 text-left shadow-2xl transition-all w-full max-w-md animate-fade-in">
                <div class="flex flex-col items-center">
                    <div class="w-20 h-20 bg-red-50 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-10 h-10 text-brandAccent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-brandPrimary mb-2 text-center">Hapus Rencana Konten?</h3>
                    <p class="text-sm text-gray-500 text-center mb-8 px-4">
                        Tindakan ini akan menghapus jadwal konten <span id="delete-article-title" class="font-bold text-brandPrimary"></span> secara permanen.
                    </p>
                    <div class="flex gap-3 w-full">
                        <button onclick="closeModalDelete()" class="flex-1 py-4 bg-gray-50 text-gray-400 rounded-2xl font-bold hover:bg-gray-100 transition">Batal</button>
                        <a id="btn-confirm-delete" href="#" class="flex-1 py-4 bg-brandAccent text-white rounded-2xl font-bold shadow-lg shadow-brandAccent/20 hover:scale-[1.02] transition-all text-center">Ya, Hapus</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/script.js"></script>
</body>
</html>