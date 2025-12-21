<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Task - DangDang Studio</title>
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

            <main class="flex-1 flex flex-col min-w-0 overflow-y-auto">
                <?php include_once '_topbar.php'; ?>

                <div class="p-6 md:p-10 flex-1">
                    <a href="project_timeline.php" class="inline-flex items-center gap-2 text-sm font-bold text-gray-400 hover:text-brandPrimary transition mb-6">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Timeline
                    </a>

                    <div class="max-w-3xl bg-white rounded-[32px] shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-8 border-b border-gray-50">
                            <h1 class="text-2xl font-bold text-brandPrimary">Create New Task</h1>
                            <p class="text-sm text-gray-500">Isi detail project di bawah ini untuk memulai timeline baru.</p>
                        </div>

                        <form action="process_add_timeline.php" method="POST" class="p-8 space-y-6">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Project Name</label>
                                <input type="text" name="project_name" placeholder="e.g. Branding Identity DangDang"
                                    class="w-full px-5 py-3 rounded-2xl border border-gray-200 focus:border-brandPrimary focus:ring-2 focus:ring-brandPrimary/10 outline-none transition">
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Client</label>
                                    <select name="client_name" class="w-full px-5 py-3 rounded-2xl border border-gray-200 focus:border-brandPrimary outline-none transition appearance-none">
                                        <option value="">Select Client</option>
                                        <option value="Internal">Internal Project</option>
                                        <option value="Client A">Client A</option>
                                        <option value="Client B">Client B</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Deadline</label>
                                    <input type="date" name="deadline" class="w-full px-5 py-3 rounded-2xl border border-gray-200 focus:border-brandPrimary outline-none transition">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Timeline Color Label</label>
                                <div class="flex gap-4">
                                    <label class="cursor-pointer">
                                        <input type="radio" name="color_label" value="brandTeal" class="hidden peer" checked>
                                        <div class="w-10 h-10 rounded-full bg-brandTeal border-4 border-transparent peer-checked:border-white peer-checked:ring-2 peer-checked:ring-brandTeal shadow-sm transition"></div>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="color_label" value="brandGold" class="hidden peer">
                                        <div class="w-10 h-10 rounded-full bg-brandGold border-4 border-transparent peer-checked:border-white peer-checked:ring-2 peer-checked:ring-brandGold shadow-sm transition"></div>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="color_label" value="brandAccent" class="hidden peer">
                                        <div class="w-10 h-10 rounded-full bg-brandAccent border-4 border-transparent peer-checked:border-white peer-checked:ring-2 peer-checked:ring-brandAccent shadow-sm transition"></div>
                                    </label>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Project Brief / Notes</label>
                                <textarea name="notes" rows="4" placeholder="Brief singkat tentang project ini..."
                                    class="w-full px-5 py-3 rounded-2xl border border-gray-200 focus:border-brandPrimary outline-none transition"></textarea>
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-3">Assign Team / Departments</label>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                    <?php
                                    $teams = [
                                        ['id' => 'ds', 'name' => 'Graphic Design', 'color' => 'bg-brandAccent'],
                                        ['id' => 'wd', 'name' => 'Web Dev', 'color' => 'bg-brandTeal'],
                                        ['id' => 'cw', 'name' => 'Copywriter', 'color' => 'bg-brandGold'],
                                        ['id' => 've', 'name' => 'Video Editor', 'color' => 'bg-brandPrimary'],
                                    ];

                                    foreach ($teams as $team) : ?>
                                        <label class="relative flex items-center group cursor-pointer">
                                            <input type="checkbox" name="team_tag[]" value="<?php echo $team['id']; ?>" class="peer hidden">
                                            <div class="w-full p-3 rounded-2xl border border-gray-100 bg-white peer-checked:border-brandPrimary peer-checked:bg-brandPrimary/5 transition-all flex items-center gap-2">
                                                <div class="w-2 h-2 rounded-full <?php echo $team['color']; ?>"></div>
                                                <span class="text-xs font-bold text-gray-500 peer-checked:text-brandPrimary"><?php echo $team['name']; ?></span>
                                                <svg class="w-4 h-4 ml-auto text-brandPrimary opacity-0 peer-checked:opacity-100 transition-opacity" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <div class="flex items-center justify-end gap-4 pt-4">
                                <button type="reset" class="px-6 py-3 text-sm font-bold text-gray-400 hover:text-brandAccent transition">Discard</button>
                                <button type="submit" class="px-8 py-3 bg-brandPrimary text-white rounded-2xl font-bold shadow-xl shadow-brandPrimary/20 hover:scale-105 transition-all">
                                    Create Timeline
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <?php include_once '_footer.php'; ?>
            </main>
        </div>
    </div>

    <script src="assets/script.js"></script>
</body>

</html>