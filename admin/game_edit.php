<?php
require_once './process/config.php';

// 1. Validasi ID
if (!isset($_GET['id'])) {
    header("Location: games.php");
    exit();
}

$id = mysqli_real_escape_string($conn, $_GET['id']);
$query = "SELECT * FROM games WHERE id = '$id'";
$result = mysqli_query($conn, $query);
$game = mysqli_fetch_assoc($result);

if (!$game) {
    header("Location: games.php");
    exit();
}

// Dekode data JSON
$existing_screenshots = json_decode($game['screenshots'], true) ?: [];
$existing_links = json_decode($game['distribution_links'], true) ?: [];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Game - <?php echo htmlspecialchars($game['title']); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="assets/tailwind-config.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .sortable-ghost { opacity: 0.3; background: #fef08a; border: 2px dashed #eab308; }
        .animate-fade-in { animation: fadeIn 0.3s ease-in-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    </style>
</head>

<body class="bg-gray-50 text-slate-800">
    <div class="flex flex-col h-screen overflow-hidden">
        <?php include_once '_header.php'; ?>
        <div class="flex flex-1 overflow-hidden">
            <?php include_once '_aside.php'; ?>
            <main class="flex-1 flex flex-col min-w-0 overflow-y-auto">
                <?php include_once '_topbar.php'; ?>

                <div class="p-6 md:p-10 flex-1">
                    <div class="max-w-5xl mx-auto">
                        
                        <a href="games.php" class="inline-flex items-center gap-2 text-sm font-bold text-gray-400 hover:text-brandGold transition mb-8">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            Back to Game Portfolio
                        </a>

                        <form id="gameForm" action="./process/process_edit_game.php" method="POST" enctype="multipart/form-data" class="space-y-8 pb-20">
                            <input type="hidden" name="id" value="<?php echo $game['id']; ?>">

                            <div class="bg-white rounded-[32px] p-8 shadow-sm border border-gray-100">
                                <h2 class="text-xl font-bold text-brandPrimary mb-8 flex items-center gap-3">
                                    <span class="w-2 h-8 bg-brandGold rounded-full"></span> 1. Visual Assets
                                </h2>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <div class="group relative">
                                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Header Image (16:9)</label>
                                        <div class="relative h-52 rounded-[24px] border-2 border-dashed border-gray-200 bg-gray-50 flex items-center justify-center overflow-hidden transition-all group-hover:border-brandGold/50">
                                            <input type="file" name="header_image" accept="image/*" onchange="previewSingle(this, 'header-prev')" class="absolute inset-0 opacity-0 cursor-pointer z-30">
                                            <img id="header-prev" src="../uploads/game/<?php echo $game['header_image']; ?>" class="absolute inset-0 w-full h-full object-cover z-10">
                                            <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition z-20 flex items-center justify-center">
                                                <span class="bg-white px-4 py-2 rounded-xl text-xs font-bold shadow-lg">Change Image</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="group relative">
                                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Game Logo (PNG)</label>
                                        <div class="relative h-52 rounded-[24px] border-2 border-dashed border-gray-200 bg-gray-50 flex items-center justify-center overflow-hidden transition-all group-hover:border-brandGold/50">
                                            <input type="file" name="game_logo" accept="image/*" onchange="previewSingle(this, 'logo-prev')" class="absolute inset-0 opacity-0 cursor-pointer z-30">
                                            <img id="logo-prev" src="../uploads/game/<?php echo $game['game_logo']; ?>" class="max-w-[70%] max-h-[70%] object-contain z-10">
                                            <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition z-20 flex items-center justify-center">
                                                <span class="bg-white px-4 py-2 rounded-xl text-xs font-bold shadow-lg">Change Logo</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-10">
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">Screenshots (Drag to reorder / Click X to delete)</label>
                                    <div class="relative mb-6">
                                        <input type="file" id="screenshot-input" multiple accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer z-20">
                                        <div class="py-10 border-2 border-dashed border-brandGold/30 rounded-[24px] bg-brandGold/[0.02] flex flex-col items-center justify-center group hover:bg-brandGold/[0.05] transition-all">
                                            <p class="text-sm font-bold text-brandGold">+ Add New Screenshots</p>
                                        </div>
                                    </div>
                                    <div id="screenshot-preview-container" class="grid grid-cols-2 md:grid-cols-5 gap-4">
                                        <?php foreach($existing_screenshots as $ss): ?>
                                            <div class="relative aspect-video rounded-2xl overflow-hidden bg-gray-100 cursor-move border-2 border-transparent hover:border-brandGold transition-all group animate-fade-in shadow-sm" 
                                                 data-type="existing" data-filename="<?php echo $ss; ?>">
                                                <img src="../uploads/game/<?php echo $ss; ?>" class="w-full h-full object-cover">
                                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                                    <button type="button" onclick="this.parentElement.parentElement.remove()" class="bg-red-500 text-white p-2 rounded-xl hover:scale-110 transition shadow-lg">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                                    </button>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-white rounded-[32px] p-8 shadow-sm border border-gray-100">
                                <h2 class="text-xl font-bold text-brandPrimary mb-8 flex items-center gap-3">
                                    <span class="w-2 h-8 bg-brandTeal rounded-full"></span> 2. Game Information
                                </h2>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="md:col-span-2">
                                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Game Title</label>
                                        <input type="text" name="title" required value="<?php echo htmlspecialchars($game['title']); ?>" class="w-full px-6 py-4 rounded-2xl border border-gray-100 bg-gray-50 outline-none font-bold text-brandPrimary">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Youtube Trailer URL</label>
                                        <input type="url" name="trailer_url" value="<?php echo htmlspecialchars($game['trailer_url']); ?>" class="w-full px-6 py-4 rounded-2xl border border-gray-100 bg-gray-50 outline-none text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Category</label>
                                        <select name="category" class="w-full px-6 py-4 rounded-2xl border border-gray-100 bg-gray-50 outline-none font-bold text-sm">
                                            <?php 
                                            $cats = ['Action', 'RPG', 'Adventure', 'Puzzle'];
                                            foreach($cats as $c) {
                                                $sel = ($game['category'] == $c) ? 'selected' : '';
                                                echo "<option value='$c' $sel>$c</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Short Description</label>
                                        <input type="text" name="short_desc" value="<?php echo htmlspecialchars($game['short_desc']); ?>" class="w-full px-6 py-4 rounded-2xl border border-gray-100 bg-gray-50 outline-none">
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Long Description</label>
                                        <textarea name="long_desc" rows="5" class="w-full px-6 py-4 rounded-2xl border border-gray-100 bg-gray-50 outline-none"><?php echo htmlspecialchars($game['long_desc']); ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white rounded-[32px] p-8 shadow-sm border border-gray-100">
                                <h2 class="text-xl font-bold text-brandPrimary mb-8 flex items-center gap-3">
                                    <span class="w-2 h-8 bg-brandAccent rounded-full"></span> 3. Distribution Links
                                </h2>
                                <div id="link-container" class="space-y-4">
                                    <?php foreach($existing_links as $i => $link): ?>
                                        <div class="flex flex-col md:flex-row items-center gap-4 p-5 bg-gray-50 rounded-[24px] border border-gray-100 animate-fade-in group">
                                            <div class="w-12 h-12 rounded-2xl bg-white flex items-center justify-center shadow-sm text-2xl border border-gray-50" id="platform-icon-<?php echo $i; ?>">
                                                <?php 
                                                $icons = ['Direct'=>'🌐', 'Steam'=>'🎮', 'PlayStore'=>'🤖', 'AppStore'=>'🍎'];
                                                echo $icons[$link['platform']] ?? '🌐';
                                                ?>
                                            </div>
                                            <select name="link_platform[]" onchange="updateIcon(this, <?php echo $i; ?>)" class="w-full md:w-40 bg-transparent font-bold text-sm outline-none">
                                                <?php foreach($icons as $p => $v): ?>
                                                    <option value="<?php echo $p; ?>" <?php echo ($link['platform']==$p)?'selected':''; ?>><?php echo $p; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <input type="url" name="link_url[]" value="<?php echo htmlspecialchars($link['url']); ?>" placeholder="URL Link" class="w-full flex-1 bg-white px-5 py-3 rounded-xl border border-gray-100 outline-none text-sm">
                                            <button type="button" onclick="this.parentElement.remove()" class="text-red-400 hover:text-red-600 p-2">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                            </button>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <button type="button" onclick="addLinkRow()" class="mt-6 text-xs font-bold text-brandTeal hover:underline">+ Add Link</button>
                            </div>

                            <button type="submit" class="w-full py-5 bg-brandTeal text-white rounded-[24px] font-bold text-lg shadow-2xl shadow-brandTeal/30 hover:scale-[1.01] transition-all">
                                Update Game Portfolio
                            </button>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        let newScreenshotFiles = []; // Penampung file baru
        const previewContainer = document.getElementById('screenshot-preview-container');

        // Preview Single
        function previewSingle(input, targetId) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => { document.getElementById(targetId).src = e.target.result; }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Logic Screenshot Baru
        document.getElementById('screenshot-input').addEventListener('change', function() {
            Array.from(this.files).forEach(file => {
                const fileId = 'new_' + Math.random().toString(36).substr(2, 9);
                newScreenshotFiles.push({ id: fileId, file: file });

                const reader = new FileReader();
                reader.onload = e => {
                    const div = document.createElement('div');
                    div.className = 'relative aspect-video rounded-2xl overflow-hidden bg-gray-100 cursor-move border-2 border-brandTeal hover:border-brandGold transition-all group animate-fade-in shadow-sm';
                    div.setAttribute('data-type', 'new');
                    div.setAttribute('data-id', fileId);
                    div.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-brandPrimary/60 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                            <button type="button" onclick="removeNewScreenshot('${fileId}', this)" class="bg-red-500 text-white p-2 rounded-xl hover:scale-110 transition shadow-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </button>
                        </div>`;
                    previewContainer.appendChild(div);
                }
                reader.readAsDataURL(file);
            });
            this.value = "";
        });

        function removeNewScreenshot(id, btn) {
            newScreenshotFiles = newScreenshotFiles.filter(i => i.id !== id);
            btn.closest('.relative').remove();
        }

        // Initialize Sortable
        new Sortable(previewContainer, { animation: 150, ghostClass: 'sortable-ghost' });

        // Platform Links
        const platformIcons = { 'Direct': '🌐', 'Steam': '🎮', 'PlayStore': '🤖', 'AppStore': '🍎' };
        function updateIcon(select, idx) {
            document.getElementById(`platform-icon-${idx}`).innerText = platformIcons[select.value] || '🌐';
        }

        let linkIdx = <?php echo count($existing_links); ?>;
        function addLinkRow() {
            const container = document.getElementById('link-container');
            container.insertAdjacentHTML('beforeend', `
                <div class="flex flex-col md:flex-row items-center gap-4 p-5 bg-gray-50 rounded-[24px] border border-gray-100 animate-fade-in group">
                    <div class="w-12 h-12 rounded-2xl bg-white flex items-center justify-center shadow-sm text-2xl border border-gray-50" id="platform-icon-${linkIdx}">🌐</div>
                    <select name="link_platform[]" onchange="updateIcon(this, ${linkIdx})" class="w-full md:w-40 bg-transparent font-bold text-sm outline-none">
                        <option value="Direct">Direct Link</option><option value="Steam">Steam</option><option value="PlayStore">PlayStore</option><option value="AppStore">AppStore</option>
                    </select>
                    <input type="url" name="link_url[]" placeholder="URL Link" class="w-full flex-1 bg-white px-5 py-3 rounded-xl border border-gray-100 outline-none text-sm">
                    <button type="button" onclick="this.parentElement.remove()" class="text-red-400 hover:text-red-600 p-2"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></button>
                </div>`);
            linkIdx++;
        }

        // SUBMIT HANDLER
        document.getElementById('gameForm').onsubmit = function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const allItems = Array.from(previewContainer.children);
            const dataTransfer = new DataTransfer();
            let finalOrder = [];

            allItems.forEach(el => {
                if (el.getAttribute('data-type') === 'existing') {
                    finalOrder.push({ type: 'existing', value: el.getAttribute('data-filename') });
                } else {
                    const id = el.getAttribute('data-id');
                    const fileObj = newScreenshotFiles.find(i => i.id === id);
                    if (fileObj) {
                        dataTransfer.items.add(fileObj.file);
                        finalOrder.push({ type: 'new', value: fileObj.file.name });
                    }
                }
            });

            // Masukkan file baru yang sudah terurut
            const files = dataTransfer.files;
            for (let i = 0; i < files.length; i++) { formData.append('screenshots[]', files[i]); }
            
            // Kirim JSON urutan akhir ke PHP
            formData.append('screenshot_order', JSON.stringify(finalOrder));

            // Kirim data menggunakan Fetch
            fetch(this.action, { method: 'POST', body: formData })
            .then(res => res.text())
            .then(data => {
                // Redirect ke games.php setelah sukses
                window.location.href = 'games.php?status=success';
            })
            .catch(err => alert("Error: " + err));
        };
    </script>
</body>
</html>