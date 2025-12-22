<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Game - DangDang Studio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="assets/tailwind-config.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .sortable-ghost { opacity: 0.3; background: #fef08a; border: 2px dashed #eab308; }
        .animate-fade-in { animation: fadeIn 0.3s ease-in-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
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
                        <?php if(isset($_GET['status'])): ?>
                            <?php if($_GET['status'] == 'success'): ?>
                                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-600 rounded-2xl flex items-center gap-3 animate-fade-in">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"></path></svg>
                                    <span class="font-bold">Game successfully published!</span>
                                </div>
                            <?php elseif($_GET['status'] == 'error'): ?>
                                <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-600 rounded-2xl flex items-center gap-3 animate-fade-in">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"></path></svg>
                                    <span class="font-bold">Failed to save game. Please check your connection.</span>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>

                        <a href="games.php" class="inline-flex items-center gap-2 text-sm font-bold text-gray-400 hover:text-brandGold transition mb-8">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            Back to Game Portfolio
                        </a>

                        <form id="gameForm" action="./process/process_add_game.php" method="POST" enctype="multipart/form-data" class="space-y-8 pb-20">
                            <div class="bg-white rounded-[32px] p-8 shadow-sm border border-gray-100">
                                <h2 class="text-xl font-bold text-brandPrimary mb-8 flex items-center gap-3">
                                    <span class="w-2 h-8 bg-brandGold rounded-full"></span> 1. Visual Assets
                                </h2>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <div class="group relative">
                                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Header Image (16:9)</label>
                                        <div class="relative h-52 rounded-[24px] border-2 border-dashed border-gray-200 bg-gray-50 flex items-center justify-center overflow-hidden transition-all group-hover:border-brandGold/50">
                                            <button type="button" onclick="resetSinglePreview('header-input', 'header-prev', 'header-placeholder')" class="absolute top-3 right-3 z-40 bg-white/90 text-red-500 p-2 rounded-xl opacity-0 group-hover:opacity-100 transition shadow-sm">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </button>
                                            <input type="file" id="header-input" name="header_image" accept="image/*" onchange="previewSingle(this, 'header-prev')" class="absolute inset-0 opacity-0 cursor-pointer z-30" required>
                                            <img id="header-prev" class="absolute inset-0 w-full h-full object-cover hidden z-10">
                                            <div id="header-placeholder" class="text-center z-20">
                                                <svg class="w-8 h-8 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                <p class="text-[9px] font-bold text-gray-400 uppercase">Upload Header</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="group relative">
                                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Game Logo (PNG)</label>
                                        <div class="relative h-52 rounded-[24px] border-2 border-dashed border-gray-200 bg-gray-50 flex items-center justify-center overflow-hidden transition-all group-hover:border-brandGold/50">
                                            <button type="button" onclick="resetSinglePreview('logo-input', 'logo-prev', 'logo-placeholder')" class="absolute top-3 right-3 z-40 bg-white/90 text-red-500 p-2 rounded-xl opacity-0 group-hover:opacity-100 transition shadow-sm">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </button>
                                            <input type="file" id="logo-input" name="game_logo" accept="image/*" onchange="previewSingle(this, 'logo-prev')" class="absolute inset-0 opacity-0 cursor-pointer z-30" required>
                                            <img id="logo-prev" class="max-w-[70%] max-h-[70%] object-contain hidden z-10">
                                            <div id="logo-placeholder" class="text-center z-20">
                                                <svg class="w-8 h-8 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"></path></svg>
                                                <p class="text-[9px] font-bold text-gray-400 uppercase">Upload Logo</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-10">
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">Screenshots (Up to 10 - Drag to reorder)</label>
                                    <div class="relative mb-6">
                                        <input type="file" id="screenshot-input" multiple accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer z-20">
                                        <div class="py-10 border-2 border-dashed border-brandGold/30 rounded-[24px] bg-brandGold/[0.02] flex flex-col items-center justify-center group hover:bg-brandGold/[0.05] transition-all">
                                            <p class="text-sm font-bold text-brandGold">+ Select Game Screenshots</p>
                                        </div>
                                    </div>
                                    <div id="screenshot-preview-container" class="grid grid-cols-2 md:grid-cols-5 gap-4">
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
                                        <input type="text" name="title" required class="w-full px-6 py-4 rounded-2xl border border-gray-100 bg-gray-50 outline-none font-bold text-brandPrimary">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Youtube Trailer URL</label>
                                        <input type="url" name="trailer_url" class="w-full px-6 py-4 rounded-2xl border border-gray-100 bg-gray-50 outline-none text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Category</label>
                                        <select name="category" class="w-full px-6 py-4 rounded-2xl border border-gray-100 bg-gray-50 outline-none font-bold text-sm">
                                            <option>Action</option><option>RPG</option><option>Adventure</option><option>Puzzle</option>
                                        </select>
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Short Description</label>
                                        <input type="text" name="short_desc" class="w-full px-6 py-4 rounded-2xl border border-gray-100 bg-gray-50 outline-none">
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Long Description</label>
                                        <textarea name="long_desc" rows="5" class="w-full px-6 py-4 rounded-2xl border border-gray-100 bg-gray-50 outline-none"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white rounded-[32px] p-8 shadow-sm border border-gray-100">
                                <h2 class="text-xl font-bold text-brandPrimary mb-8 flex items-center gap-3">
                                    <span class="w-2 h-8 bg-brandAccent rounded-full"></span> 3. Distribution Links
                                </h2>
                                <div id="link-container" class="space-y-4">
                                    <div class="flex flex-col md:flex-row items-center gap-4 p-5 bg-gray-50 rounded-[24px] border border-gray-100 animate-fade-in group">
                                        <div class="w-12 h-12 rounded-2xl bg-white flex items-center justify-center shadow-sm text-2xl border border-gray-50" id="platform-icon-0">🌐</div>
                                        <select name="link_platform[]" onchange="updateIcon(this, 0)" class="w-full md:w-40 bg-transparent font-bold text-sm outline-none">
                                            <option value="Direct">Direct Link</option><option value="Steam">Steam</option><option value="PlayStore">PlayStore</option><option value="AppStore">AppStore</option>
                                        </select>
                                        <input type="url" name="link_url[]" placeholder="URL Link" class="w-full flex-1 bg-white px-5 py-3 rounded-xl border border-gray-100 outline-none text-sm">
                                    </div>
                                </div>
                                <button type="button" onclick="addLinkRow()" class="mt-6 text-xs font-bold text-brandTeal hover:underline">+ Add Link</button>
                            </div>

                            <button type="submit" class="w-full py-5 bg-brandGold text-white rounded-[24px] font-bold text-lg shadow-2xl shadow-brandGold/30 hover:scale-[1.01] transition-all">
                                Publish Game Info
                            </button>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        // -- FILE STORAGE ARRAY --
        let screenshotFiles = []; // Menyimpan objek File asli

        // Preview Single (Header & Logo)
        function previewSingle(input, targetId) {
            const preview = document.getElementById(targetId);
            const placeholder = document.getElementById(targetId.replace('prev', 'placeholder'));
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

        function resetSinglePreview(inputId, imgId, placeholderId) {
            document.getElementById(inputId).value = "";
            document.getElementById(imgId).src = "";
            document.getElementById(imgId).classList.add('hidden');
            document.getElementById(placeholderId).classList.remove('hidden');
        }

        // Multiple Screenshots Logic
        const screenshotInput = document.getElementById('screenshot-input');
        const previewContainer = document.getElementById('screenshot-preview-container');

        screenshotInput.addEventListener('change', function() {
            const files = Array.from(this.files);
            
            files.forEach((file) => {
                // Tambahkan ke array global
                const fileId = 'file_' + Math.random().toString(36).substr(2, 9);
                screenshotFiles.push({ id: fileId, file: file });

                const reader = new FileReader();
                reader.onload = e => {
                    const div = document.createElement('div');
                    div.className = 'relative aspect-video rounded-2xl overflow-hidden bg-gray-100 cursor-move border-2 border-transparent hover:border-brandGold transition-all group animate-fade-in shadow-sm';
                    div.setAttribute('data-id', fileId); // ID untuk tracking saat reorder
                    div.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-brandPrimary/60 opacity-0 group-hover:opacity-100 transition flex flex-col items-center justify-center gap-2">
                            <button type="button" onclick="removeScreenshot('${fileId}', this)" class="bg-red-500 text-white p-2 rounded-xl hover:scale-110 transition shadow-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    `;
                    previewContainer.appendChild(div);
                }
                reader.readAsDataURL(file);
            });
            this.value = ""; // Clear input agar bisa pilih file yang sama lagi
        });

        function removeScreenshot(id, button) {
            screenshotFiles = screenshotFiles.filter(item => item.id !== id);
            const item = button.closest('.relative');
            item.classList.add('scale-0', 'opacity-0');
            setTimeout(() => item.remove(), 200);
        }

        // Initialize Sortable
        new Sortable(previewContainer, {
            animation: 150,
            ghostClass: 'sortable-ghost'
        });

        // PLATFORM LINKS LOGIC
        const platformIcons = { 'Direct': '🌐', 'Steam': '🎮', 'PlayStore': '🤖', 'AppStore': '🍎' };
        function updateIcon(select, index) {
            document.getElementById(`platform-icon-${index}`).innerText = platformIcons[select.value] || '🌐';
        }

        let linkIdx = 1;
        function addLinkRow() {
            const container = document.getElementById('link-container');
            container.insertAdjacentHTML('beforeend', `
                <div class="flex flex-col md:flex-row items-center gap-4 p-5 bg-gray-50 rounded-[24px] border border-gray-100 animate-fade-in group">
                    <div class="w-12 h-12 rounded-2xl bg-white flex items-center justify-center shadow-sm text-2xl border border-gray-50" id="platform-icon-${linkIdx}">🌐</div>
                    <select name="link_platform[]" onchange="updateIcon(this, ${linkIdx})" class="w-full md:w-40 bg-transparent font-bold text-sm outline-none">
                        <option value="Direct">Direct Link</option><option value="Steam">Steam</option><option value="PlayStore">PlayStore</option><option value="AppStore">AppStore</option>
                    </select>
                    <input type="url" name="link_url[]" placeholder="URL Link" class="w-full flex-1 bg-white px-5 py-3 rounded-xl border border-gray-100 outline-none text-sm">
                    <button type="button" onclick="this.parentElement.remove()" class="text-red-400 hover:text-red-600 p-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>`);
            linkIdx++;
        }

        // -- FINAL SUBMISSION LOGIC --
        document.getElementById('gameForm').onsubmit = function(e) {
            e.preventDefault(); // Stop default submission

            const formData = new FormData(this);

            // 1. Ambil urutan visual dari Sortable DOM
            const sortedIds = Array.from(previewContainer.querySelectorAll('[data-id]')).map(el => el.getAttribute('data-id'));

            // 2. Gunakan DataTransfer untuk menyusun ulang file sesuai urutan visual
            const dataTransfer = new DataTransfer();
            sortedIds.forEach(id => {
                const fileObj = screenshotFiles.find(item => item.id === id);
                if (fileObj) {
                    dataTransfer.items.add(fileObj.file);
                }
            });

            // 3. Masukkan file yang sudah diurutkan ke dalam FormData
            // Hapus entry screenshot lama jika ada, lalu ganti dengan yang terurut
            formData.delete('screenshots[]'); 
            const filesToUpload = dataTransfer.files;
            for (let i = 0; i < filesToUpload.length; i++) {
                formData.append('screenshots[]', filesToUpload[i]);
            }

            // 4. Submit menggunakan Fetch atau biarkan form submit manual
            // Untuk kemudahan, kita akan buat input file dummy di form untuk menampung dataTransfer
            const dummyInput = document.createElement('input');
            dummyInput.type = 'file';
            dummyInput.name = 'screenshots[]';
            dummyInput.multiple = true;
            dummyInput.files = dataTransfer.files;
            dummyInput.style.display = 'none';
            
            this.appendChild(dummyInput);
            this.submit();
        };
    </script>
</body>
</html>