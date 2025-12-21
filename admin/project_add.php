<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Project - DangDang Studio</title>
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
                    <div class="max-w-5xl mx-auto">
                        <div class="flex items-center justify-between mb-8">
                            <a href="project_manager.php" class="inline-flex items-center gap-2 text-sm font-bold text-gray-400 hover:text-brandPrimary transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                                Back to Project Manager
                            </a>
                        </div>

                        <form action="process_add_project.php" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-12 gap-8 pb-20">
                            
                            <div class="lg:col-span-4 space-y-6">
                                <div class="bg-white p-6 rounded-[32px] border border-gray-100 shadow-sm">
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4 ml-2">Project Identity (Icon)</label>
                                    <div class="relative aspect-square rounded-[24px] border-2 border-dashed border-gray-200 bg-gray-50 flex items-center justify-center overflow-hidden group hover:border-brandGold transition-all">
                                        <input type="file" name="project_icon" accept="image/*" onchange="previewIcon(this)" class="absolute inset-0 opacity-0 cursor-pointer z-20">
                                        <img id="icon-prev" class="absolute inset-0 w-full h-full object-cover hidden z-10">
                                        <div id="icon-placeholder" class="text-center p-4">
                                            <svg class="w-10 h-10 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            <p class="text-[9px] font-bold text-gray-400 uppercase">Upload Icon</p>
                                        </div>
                                    </div>
                                    <p class="mt-4 text-[9px] text-gray-400 text-center italic">Disarankan ukuran 1:1 untuk icon game.</p>
                                </div>

                                <div class="bg-white p-6 rounded-[32px] border border-gray-100 shadow-sm">
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4 ml-2">Target Platforms</label>
                                    <div class="space-y-2">
                                        <?php 
                                        $platforms = ['PC / Steam', 'PlayStation', 'Xbox', 'Nintendo Switch', 'Android', 'iOS'];
                                        foreach($platforms as $plt): ?>
                                            <label class="flex items-center gap-3 p-3 rounded-xl border border-gray-50 hover:bg-gray-50 cursor-pointer transition">
                                                <input type="checkbox" name="platforms[]" value="<?php echo $plt; ?>" class="w-4 h-4 rounded border-gray-300 text-brandPrimary focus:ring-brandPrimary">
                                                <span class="text-xs font-bold text-brandPrimary"><?php echo $plt; ?></span>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="lg:col-span-8 space-y-6">
                                <div class="bg-white rounded-[32px] p-8 md:p-10 shadow-sm border border-gray-100 space-y-6">
                                    <h2 class="text-xl font-bold text-brandPrimary flex items-center gap-3">
                                        <span class="w-2 h-8 bg-brandGold rounded-full"></span> Development Intel
                                    </h2>

                                    <div>
                                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Project Name</label>
                                        <input type="text" name="project_name" required placeholder="E.g. Cyber Nusantara: Awakening" class="w-full px-6 py-4 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-brandGold outline-none transition font-bold text-brandPrimary">
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Game Genre</label>
                                            <select name="genre" class="w-full px-6 py-4 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-brandGold outline-none transition font-bold text-sm">
                                                <option>Action RPG</option>
                                                <option>Endless Runner</option>
                                                <option>Horror Survival</option>
                                                <option>Visual Novel</option>
                                                <option>Simulation</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Initial Status</label>
                                            <select name="status" class="w-full px-6 py-4 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-brandGold outline-none transition font-bold text-sm">
                                                <option>Prototyping</option>
                                                <option>In Development</option>
                                                <option>Alpha Testing</option>
                                                <option>Beta Testing</option>
                                                <option>Polishing</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div>
                                        <div class="flex justify-between items-center mb-3">
                                            <label class="block text-[10px] font-bold text-gray-400 uppercase ml-1">Current Progress</label>
                                            <span id="progress-val" class="px-3 py-1 bg-brandGold/10 text-brandGold rounded-lg text-xs font-black">0%</span>
                                        </div>
                                        <input type="range" name="progress" min="0" max="100" value="0" oninput="updateProgress(this.value)" class="w-full h-2 bg-gray-100 rounded-lg appearance-none cursor-pointer accent-brandGold">
                                    </div>

                                    <div>
                                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Development Brief / Roadmap</label>
                                        <textarea name="brief" rows="5" placeholder="Tuliskan objektif utama atau milestone saat ini..." class="w-full px-6 py-5 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-brandGold outline-none transition text-sm text-gray-600 leading-relaxed"></textarea>
                                    </div>

                                    <div class="pt-4">
                                        <button type="submit" class="w-full py-5 bg-brandPrimary text-white rounded-[24px] font-bold text-lg shadow-2xl shadow-brandPrimary/20 hover:scale-[1.01] active:scale-95 transition-all">
                                            Launch New Project
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <?php include_once '_footer.php'; ?>
            </main>
        </div>
    </div>

    <script>
        function previewIcon(input) {
            const preview = document.getElementById('icon-prev');
            const placeholder = document.getElementById('icon-placeholder');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function updateProgress(val) {
            document.getElementById('progress-val').innerText = val + '%';
        }
    </script>
</body>
</html>