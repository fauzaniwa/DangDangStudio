<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Manager - DangDang Studio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="assets/tailwind-config.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
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
                            <h1 class="text-3xl font-extrabold text-brandPrimary tracking-tight">Project Management</h1>
                            <p class="text-sm text-gray-500 font-medium">Pantau siklus pengembangan dan rilis game DangDang Studio.</p>
                        </div>
                        <a href="project_add.php" class="flex items-center gap-2 px-6 py-3 bg-brandPrimary text-white rounded-2xl font-bold shadow-lg shadow-brandPrimary/20 hover:scale-105 transition-all text-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Add New Project
                        </a>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <?php
                        $projects = [
                            [
                                'id' => 1,
                                'name' => 'Project: Cyber Nusantara',
                                'status' => 'In Development',
                                'progress' => 65,
                                'genre' => 'Action RPG',
                                'color' => 'brandGold',
                                'image' => 'https://images.unsplash.com/photo-1542751371-adc38448a05e?q=80&w=400'
                            ],
                            [
                                'id' => 2,
                                'name' => 'DangDang Dash',
                                'status' => 'Post-Launch',
                                'progress' => 100,
                                'genre' => 'Endless Runner',
                                'color' => 'brandTeal',
                                'image' => 'https://images.unsplash.com/photo-1550745165-9bc0b252726f?q=80&w=400'
                            ]
                        ];

                        foreach ($projects as $pj): ?>
                            <div class="bg-white rounded-[32px] p-6 border border-gray-100 shadow-sm hover:shadow-xl hover:shadow-brandPrimary/5 transition-all duration-300 group">
                                <div class="flex gap-6">
                                    <div class="w-32 h-32 rounded-[24px] overflow-hidden flex-shrink-0 bg-gray-100">
                                        <img src="<?php echo $pj['image']; ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                    </div>
                                    
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest"><?php echo $pj['genre']; ?></span>
                                            <div class="flex gap-2">
                                                <a href="game_edit.php?id=<?php echo $pj['id']; ?>" class="text-gray-300 hover:text-brandPrimary transition">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                </a>
                                            </div>
                                        </div>
                                        <h3 class="text-xl font-extrabold text-brandPrimary mb-4"><?php echo $pj['name']; ?></h3>
                                        
                                        <div class="space-y-2">
                                            <div class="flex justify-between text-[10px] font-bold uppercase">
                                                <span class="text-gray-400"><?php echo $pj['status']; ?></span>
                                                <span class="text-brandPrimary"><?php echo $pj['progress']; ?>%</span>
                                            </div>
                                            <div class="w-full h-3 bg-gray-50 rounded-full overflow-hidden border border-gray-100">
                                                <div class="h-full bg-<?php echo $pj['color']; ?> rounded-full transition-all duration-1000" style="width: <?php echo $pj['progress']; ?>%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-6 pt-6 border-t border-gray-50 flex items-center justify-between">
                                    <div class="flex -space-x-3">
                                        <img class="w-8 h-8 rounded-full border-4 border-white" src="https://i.pravatar.cc/100?u=1" alt="">
                                        <img class="w-8 h-8 rounded-full border-4 border-white" src="https://i.pravatar.cc/100?u=2" alt="">
                                        <div class="w-8 h-8 rounded-full border-4 border-white bg-gray-100 flex items-center justify-center text-[10px] font-bold text-gray-400">+3</div>
                                    </div>
                                    <button onclick="confirmDeleteArticle('<?php echo $pj['id']; ?>', '<?php echo $pj['name']; ?>')" class="px-4 py-2 text-xs font-bold text-red-400 hover:text-red-600 transition uppercase tracking-tighter">
                                        Archive Project
                                    </button>
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
        <div class="fixed inset-0 bg-brandPrimary/40 backdrop-blur-sm" onclick="closeModalDelete()"></div>
        <div class="flex min-h-full items-center justify-center p-4 text-center">
            <div class="relative transform overflow-hidden rounded-[32px] bg-white p-8 text-left shadow-2xl transition-all w-full max-w-md animate-fade-in">
                <div class="flex flex-col items-center">
                    <div class="w-20 h-20 bg-red-50 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-10 h-10 text-brandAccent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-brandPrimary mb-2">Arsipkan Project?</h3>
                    <p class="text-sm text-gray-500 text-center mb-8"><span id="delete-article-title" class="font-bold"></span> akan dipindahkan ke folder arsip dan tidak ditampilkan di website publik.</p>
                    <div class="flex gap-3 w-full">
                        <button onclick="closeModalDelete()" class="flex-1 py-4 bg-gray-50 text-gray-400 rounded-2xl font-bold">Batal</button>
                        <a id="btn-confirm-delete" href="#" class="flex-1 py-4 bg-brandAccent text-white rounded-2xl font-bold text-center">Ya, Arsipkan</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/script.js"></script>
</body>
</html>