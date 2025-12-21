<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Timeline - DangDang Studio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="assets/tailwind-config.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
</head>

<body class="bg-gray-50 text-slate-800">

    <div class="flex flex-col h-screen overflow-hidden">
        <?php include_once '_header.php'; ?>

        <div class="flex flex-1 overflow-hidden">
            <?php include_once '_aside.php'; ?>

            <div id="overlay" class="fixed inset-0 bg-brandPrimary/60 backdrop-blur-sm z-40 hidden md:hidden"></div>

            <main class="flex-1 flex flex-col min-w-0 overflow-y-auto">
                <?php include_once '_topbar.php'; ?>

                <div class="p-6 md:p-10 flex-1">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                        <div>
                            <h1 class="text-2xl font-bold text-brandPrimary">Project Timeline</h1>
                            <p class="text-sm text-gray-500">Pantau jadwal dan progres pengerjaan proyek DangDang Studio.</p>
                        </div>
                        <div class="flex gap-2">
                            <button class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm font-semibold text-gray-600 hover:bg-gray-50 transition">Filter</button>
                            <button class="px-4 py-2 bg-brandPrimary text-white rounded-xl text-sm font-bold shadow-lg shadow-brandPrimary/20 hover:scale-105 transition-all">+ New Task</button>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <?php
                        $projects = [
                            [
                                'title' => 'Redesign Mobile App E-Commerce',
                                'client' => 'PT. Maju Bersama',
                                'progress' => 75,
                                'deadline' => '25 Des 2025',
                                'status' => 'In Progress',
                                'color' => 'brandTeal'
                            ],
                            [
                                'title' => 'Web Development Dashboard Admin',
                                'client' => 'DangDang Internal',
                                'progress' => 40,
                                'deadline' => '10 Jan 2026',
                                'status' => 'Review',
                                'color' => 'brandGold'
                            ],
                            [
                                'title' => 'Motion Graphic Video Launching',
                                'client' => 'StartUp Digital',
                                'progress' => 90,
                                'deadline' => '28 Des 2025',
                                'status' => 'Finishing',
                                'color' => 'brandAccent'
                            ]
                        ];

                        foreach ($projects as $pj) : ?>
                            <div class="bg-white p-6 rounded-[24px] shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                                    <div class="flex items-start gap-4">
                                        <div class="w-12 h-12 rounded-2xl bg-gray-50 flex items-center justify-center text-brandPrimary border border-gray-100">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-brandPrimary"><?php echo $pj['title']; ?></h3>
                                            <p class="text-xs text-gray-400 font-medium italic">Client: <?php echo $pj['client']; ?></p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="px-4 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-gray-100 text-gray-500">
                                            <?php echo $pj['status']; ?>
                                        </span>
                                        <p class="text-[11px] text-brandAccent font-bold mt-2 italic">Deadline: <?php echo $pj['deadline']; ?></p>
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <div class="flex justify-between items-center text-xs font-bold text-gray-500">
                                        <span>Completion Progress</span>
                                        <span><?php echo $pj['progress']; ?>%</span>
                                    </div>
                                    <div class="w-full h-3 bg-gray-100 rounded-full overflow-hidden">
                                        <div class="h-full transition-all duration-1000 ease-out bg-<?php echo $pj['color']; ?>"
                                            style="width: <?php echo $pj['progress']; ?>%"></div>
                                    </div>
                                </div>

                                <div class="mt-6 flex flex-col md:flex-row md:items-center justify-between pt-4 border-t border-gray-50 gap-4">
                                    <div class="flex flex-wrap gap-2">
                                        <span class="px-2 py-1 rounded-md bg-brandTeal/10 text-brandTeal text-[9px] font-bold uppercase tracking-wider">Web Dev</span>
                                        <span class="px-2 py-1 rounded-md bg-brandAccent/10 text-brandAccent text-[9px] font-bold uppercase tracking-wider">Design</span>
                                    </div>

                                    <div class="flex items-center gap-4">
                                        <div class="flex -space-x-2">
                                            <div title="Admin" class="w-8 h-8 rounded-full border-2 border-white bg-brandPrimary flex items-center justify-center text-[10px] text-white font-bold">AD</div>
                                            <div title="Siti" class="w-8 h-8 rounded-full border-2 border-white bg-brandTeal flex items-center justify-center text-[10px] text-white font-bold">SC</div>
                                        </div>
                                        <button class="text-xs font-bold text-brandPrimary hover:underline">Details →</button>
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

    <script src="assets/script.js"></script>
</body>

</html>