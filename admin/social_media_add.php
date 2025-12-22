<?php
require_once './process/config.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Social Media Plan - DangDang Studio</title>
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
                    <div class="max-w-4xl mx-auto">
                        <a href="social_media_plan.php" class="inline-flex items-center gap-2 text-sm font-bold text-gray-400 hover:text-brandPrimary transition mb-8">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            Back to Planning
                        </a>

                        <form action="process/process_add_social.php" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-8 pb-20">
                            
                            <div class="lg:col-span-1 space-y-6">
                                <div class="space-y-4">
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-2">Content Preview</label>
                                    <div class="relative aspect-video bg-white rounded-[28px] border-2 border-dashed border-gray-200 flex items-center justify-center overflow-hidden group hover:border-brandGold transition-all">
                                        <input type="file" name="thumbnail" accept="image/*" onchange="previewThumb(this)" class="absolute inset-0 opacity-0 cursor-pointer z-20">
                                        <img id="thumb-prev" class="absolute inset-0 w-full h-full object-cover hidden z-10">
                                        <div id="thumb-placeholder" class="text-center p-4">
                                            <svg class="w-8 h-8 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            <p class="text-[9px] font-bold text-gray-400 uppercase">Upload Preview</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-white p-6 rounded-[32px] border border-gray-100 shadow-sm space-y-4">
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest">Target Platforms</label>
                                    <div class="grid grid-cols-2 gap-3">
                                        <?php 
                                        $platforms = ['Instagram', 'TikTok', 'YouTube', 'X / Twitter', 'Facebook', 'Discord'];
                                        foreach($platforms as $plt): ?>
                                            <label class="relative flex items-center group cursor-pointer">
                                                <input type="checkbox" name="platforms[]" value="<?php echo $plt; ?>" class="peer hidden">
                                                <div class="w-full py-3 px-4 rounded-xl border border-gray-100 bg-gray-50 text-[10px] font-bold text-gray-400 text-center peer-checked:bg-brandPrimary peer-checked:text-white peer-checked:border-brandPrimary transition-all group-hover:border-brandGold/50">
                                                    <?php echo $plt; ?>
                                                </div>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="lg:col-span-2 space-y-6">
                                <div class="bg-white rounded-[32px] p-8 shadow-sm border border-gray-100 space-y-6">
                                    <h2 class="text-xl font-bold text-brandPrimary mb-4 flex items-center gap-3">
                                        <span class="w-2 h-8 bg-brandGold rounded-full"></span> Campaign Details
                                    </h2>

                                    <div>
                                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Campaign / Post Title</label>
                                        <input type="text" name="title" required placeholder="Contoh: Reveal Trailer Project X" class="w-full px-6 py-4 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-brandGold outline-none transition font-bold text-brandPrimary">
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Publish Date</label>
                                            <input type="date" name="publish_date" class="w-full px-6 py-4 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-brandGold outline-none transition font-bold text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Publish Time (WIB)</label>
                                            <input type="time" name="publish_time" class="w-full px-6 py-4 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-brandGold outline-none transition font-bold text-sm">
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Caption / Content Brief</label>
                                        <textarea name="caption" rows="6" placeholder="Tulis caption atau instruksi konten di sini..." class="w-full px-6 py-5 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-brandGold outline-none transition text-sm text-gray-600 leading-relaxed"></textarea>
                                    </div>

                                    <div>
                                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Publication Status</label>
                                        <div class="relative">
                                            <select name="status" required class="w-full px-6 py-4 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-brandGold outline-none transition font-bold text-sm appearance-none cursor-pointer text-brandPrimary">
                                                <option value="Draft">Draft</option>
                                                <option value="Scheduled">Scheduled</option>
                                                <option value="Published">Published</option>
                                            </select>
                                            <div class="absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="pt-4">
                                        <button type="submit" class="w-full py-5 bg-brandPrimary text-white rounded-[24px] font-bold text-lg shadow-2xl shadow-brandPrimary/20 hover:scale-[1.02] transition-all">
                                            Create Campaign Plan
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
        function previewThumb(input) {
            const preview = document.getElementById('thumb-prev');
            const placeholder = document.getElementById('thumb-placeholder');
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
    </script>
</body>
</html>