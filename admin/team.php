<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Team Roster - DangDang Studio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="assets/tailwind-config.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
    <script src="assets/script.js"></script>
</head>
<body class="bg-[#FBFBFB] text-slate-800">

    <div class="flex flex-col h-screen overflow-hidden">
        <?php include_once '_header.php'; ?>
        <div class="flex flex-1 overflow-hidden">
            <?php include_once '_aside.php'; ?>
            <main class="flex-1 flex flex-col min-w-0 overflow-y-auto">
                <?php include_once '_topbar.php'; ?>

                <div class="p-6 md:p-10">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12">
                        <div class="space-y-1">
                            <h1 class="text-3xl font-extrabold text-brandPrimary tracking-tight">Studio Roster</h1>
                            <p class="text-sm text-gray-500 font-medium">Manajemen data anggota dan kontak DangDang Studio.</p>
                        </div>
                        <a href="team_add.php" class="flex items-center gap-3 px-8 py-4 bg-brandPrimary text-white rounded-2xl font-bold shadow-xl shadow-brandPrimary/20 hover:scale-[1.03] transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Add New Staff
                        </a>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-8">
                        <?php
                        $team_members = [
                            [
                                'id' => 1,
                                'name' => 'Fadhil Pratama',
                                'division' => 'Technical Art',
                                'status' => 'Full-time',
                                'level' => 'Senior Staff',
                                'email' => 'fadhil@dangdang.com',
                                'phone' => '08123456789',
                                'image' => 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=600&h=800&q=80'
                            ],
                            [
                                'id' => 2,
                                'name' => 'Riana Putri',
                                'division' => 'UI/UX Design',
                                'status' => 'Intern',
                                'level' => 'Junior Designer',
                                'email' => 'riana@dangdang.com',
                                'phone' => '08987654321',
                                'image' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=600&h=800&q=80'
                            ],
                        ];

                        foreach ($team_members as $member) : ?>
                            <div class="group relative bg-white rounded-[32px] overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-500 border border-gray-100 flex flex-col">
                                
                                <div class="relative aspect-[3/4] overflow-hidden">
                                    <img src="<?php echo $member['image']; ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                    
                                    <div class="absolute top-4 left-4">
                                        <span class="px-3 py-1.5 rounded-xl text-[10px] font-bold uppercase tracking-wider bg-white/90 backdrop-blur-md text-brandPrimary shadow-sm border border-white/50">
                                            <?php echo $member['status']; ?>
                                        </span>
                                    </div>

                                    <div class="absolute inset-x-0 top-0 p-4 -translate-y-full group-hover:translate-y-0 transition-transform duration-300 bg-gradient-to-b from-brandPrimary/80 to-transparent flex justify-end gap-2">
                                        <a href="mailto:<?php echo $member['email']; ?>" class="w-8 h-8 rounded-lg bg-white/20 backdrop-blur-md text-white flex items-center justify-center hover:bg-white hover:text-brandPrimary transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                        </a>
                                        <a href="https://wa.me/<?php echo $member['phone']; ?>" target="_blank" class="w-8 h-8 rounded-lg bg-white/20 backdrop-blur-md text-white flex items-center justify-center hover:bg-green-500 hover:text-white transition">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                                        </a>
                                    </div>

                                    <div class="absolute inset-x-0 bottom-0 p-4 translate-y-full group-hover:translate-y-0 transition-transform duration-300 bg-gradient-to-t from-brandPrimary/90 to-transparent flex justify-center gap-3">
                                        <button class="w-10 h-10 rounded-xl bg-white text-brandPrimary flex items-center justify-center hover:bg-brandGold hover:text-white transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </button>
                                        <button class="w-10 h-10 rounded-xl bg-white text-brandAccent flex items-center justify-center hover:bg-brandAccent hover:text-white transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </div>

                                <div class="p-5 space-y-3 bg-white flex-1 flex flex-col justify-between">
                                    <div class="space-y-0.5">
                                        <p class="text-[10px] font-bold text-brandGold uppercase tracking-widest"><?php echo $member['division']; ?></p>
                                        <h3 class="text-lg font-bold text-brandPrimary truncate"><?php echo $member['name']; ?></h3>
                                        <p class="text-[11px] text-gray-400 font-medium truncate italic"><?php echo $member['email']; ?></p>
                                    </div>
                                    
                                    <div class="flex items-center gap-2 pt-3 border-t border-gray-50">
                                        <div class="w-1.5 h-1.5 rounded-full bg-brandTeal"></div>
                                        <span class="text-[11px] font-bold text-gray-400 uppercase"><?php echo $member['level']; ?></span>
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
</body>
</html>