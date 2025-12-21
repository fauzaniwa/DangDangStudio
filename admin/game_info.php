<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Portfolio - DangDang Studio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="assets/tailwind-config.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
    <script src="assets/script.js"></script>
</head>

<body class="bg-gray-50 text-slate-800">

    <div class="flex flex-col h-screen overflow-hidden">
        <?php include_once '_header.php'; ?>

        <div class="flex flex-1 overflow-hidden">
            <?php include_once '_aside.php'; ?>

            <main class="flex-1 flex flex-col min-w-0 overflow-y-auto">
                <?php include_once '_topbar.php'; ?>

                <div class="p-6 md:p-10 flex-1">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10">
                        <div>
                            <h1 class="text-2xl font-bold text-brandPrimary">Game Portfolio</h1>
                            <p class="text-sm text-gray-500 font-medium">Katalog game dan proyek kreatif DangDang Studio.</p>
                        </div>
                        <a href="game_add.php" class="flex items-center justify-center gap-2 px-6 py-3 bg-brandGold text-white rounded-2xl font-bold shadow-lg shadow-brandGold/20 hover:scale-105 transition-all text-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0h-3m-9-4.5a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0z"></path>
                            </svg>
                            Add New Game
                        </a>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                        <?php
                        // Contoh data game statis
                        $games = [
                            [
                                'title' => 'Mystic Forest Adventure',
                                'genre' => 'RPG / Adventure',
                                'platform' => 'PC & Mobile',
                                'status' => 'Released',
                                'cover' => 'https://images.unsplash.com/photo-1550745165-9bc0b252726f?auto=format&fit=crop&w=800&q=80',
                                'year' => '2024'
                            ],
                            [
                                'title' => 'Cyber Strike 2077',
                                'genre' => 'Action / Sci-Fi',
                                'platform' => 'Console',
                                'status' => 'In Development',
                                'cover' => 'https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&w=800&q=80',
                                'year' => '2025'
                            ],
                            [
                                'title' => 'DangDang Puzzle Mania',
                                'genre' => 'Casual / Puzzle',
                                'platform' => 'Web Browser',
                                'status' => 'Released',
                                'cover' => 'https://images.unsplash.com/photo-1611162617474-5b21e879e113?auto=format&fit=crop&w=800&q=80',
                                'year' => '2023'
                            ]
                        ];

                        foreach ($games as $game) : ?>
                            <div class="bg-white rounded-[32px] overflow-hidden shadow-sm border border-gray-100 group hover:shadow-xl transition-all duration-500">
                                <div class="relative h-56 overflow-hidden">
                                    <img src="<?php echo $game['cover']; ?>" alt="<?php echo $game['title']; ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                    <div class="absolute inset-0 bg-gradient-to-t from-brandPrimary/80 to-transparent opacity-60"></div>
                                    <div class="absolute top-4 left-4">
                                        <span class="px-3 py-1 rounded-full text-[9px] font-bold uppercase tracking-wider bg-white/20 backdrop-blur-md text-white border border-white/30">
                                            <?php echo $game['year']; ?>
                                        </span>
                                    </div>
                                    <div class="absolute bottom-4 left-6">
                                        <span class="text-[10px] font-bold text-brandTeal bg-white px-3 py-1 rounded-lg uppercase tracking-widest shadow-sm">
                                            <?php echo $game['status']; ?>
                                        </span>
                                    </div>
                                </div>

                                <div class="p-6">
                                    <h3 class="text-lg font-bold text-brandPrimary mb-1 group-hover:text-brandGold transition-colors"><?php echo $game['title']; ?></h3>
                                    <p class="text-xs text-gray-400 font-medium mb-4"><?php echo $game['genre']; ?></p>

                                    <div class="flex items-center gap-2 mb-6">
                                        <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                        <span class="text-[11px] font-bold text-gray-500 uppercase tracking-tight"><?php echo $game['platform']; ?></span>
                                    </div>

                                    <div class="flex items-center justify-between pt-4 border-t border-gray-50">
                                        <button class="text-xs font-bold text-brandPrimary hover:text-brandGold transition">Edit Details</button>
                                        <div class="flex gap-2">
                                            <button title="View Analytics" class="p-2 bg-gray-50 text-gray-400 hover:text-brandTeal hover:bg-brandTeal/10 rounded-xl transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2-2 2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                                </svg>
                                            </button>
                                            <button title="Delete Game" class="p-2 bg-gray-50 text-gray-400 hover:text-brandAccent hover:bg-brandAccent/10 rounded-xl transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </div>
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