<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Write New Article - DangDang Studio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="assets/tailwind-config.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
    <style>
        .animate-fade-in { animation: fadeIn 0.4s ease-out; }
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
                    <div class="max-w-4xl mx-auto">
                        <a href="articles.php" class="inline-flex items-center gap-2 text-sm font-bold text-gray-400 hover:text-brandPrimary transition mb-8">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            Back to Articles
                        </a>

                        <form action="process_add_article.php" method="POST" enctype="multipart/form-data" class="space-y-8 pb-20">
                            
                            <div class="group relative">
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4 ml-2">Article Cover (Wide 16:9)</label>
                                <div class="relative h-64 md:h-80 rounded-[32px] border-2 border-dashed border-gray-200 bg-white flex items-center justify-center overflow-hidden transition-all group-hover:border-brandGold/50 shadow-sm">
                                    <button type="button" id="btn-remove-cover" onclick="resetCover()" class="absolute top-4 right-4 z-40 bg-white/90 text-red-500 p-2 rounded-xl opacity-0 group-hover:opacity-100 transition shadow-sm hidden">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>

                                    <input type="file" id="cover-input" name="article_cover" accept="image/*" onchange="previewCover(this)" class="absolute inset-0 opacity-0 cursor-pointer z-30">
                                    <img id="cover-prev" class="absolute inset-0 w-full h-full object-cover hidden z-10">
                                    
                                    <div id="cover-placeholder" class="text-center z-20">
                                        <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Upload Main Cover</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white rounded-[32px] p-8 shadow-sm border border-gray-100 space-y-6">
                                <h2 class="text-xl font-bold text-brandPrimary mb-4 flex items-center gap-3">
                                    <span class="w-2 h-8 bg-brandGold rounded-full"></span> Detail Konten
                                </h2>

                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-3 ml-1">Article Title</label>
                                    <input type="text" name="title" required placeholder="Enter an engaging title..." class="w-full px-0 py-2 bg-transparent border-b-2 border-gray-100 focus:border-brandGold outline-none transition font-extrabold text-2xl md:text-3xl text-brandPrimary placeholder:text-gray-200">
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Category</label>
                                        <select name="category" class="w-full px-5 py-4 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-brandGold outline-none transition font-bold text-sm">
                                            <option>Announcement</option>
                                            <option>Development Log</option>
                                            <option>Art Showcase</option>
                                            <option>Event</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Publish Status</label>
                                        <select name="status" class="w-full px-5 py-4 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-brandGold outline-none transition font-bold text-sm">
                                            <option value="Draft">Save as Draft</option>
                                            <option value="Published">Publish Now</option>
                                        </select>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-3 ml-1">Article Story / Description</label>
                                    <textarea name="content" rows="8" placeholder="Start writing your story here..." class="w-full px-6 py-5 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-brandGold outline-none transition text-gray-600 leading-relaxed"></textarea>
                                </div>
                            </div>

                            <div class="bg-white rounded-[32px] p-8 shadow-sm border border-gray-100 space-y-6">
                                <div class="flex items-center justify-between mb-2">
                                    <div>
                                        <h2 class="text-lg font-bold text-brandPrimary">Image Assets Gallery</h2>
                                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Tambahkan aset visual untuk artikel berbasis gambar</p>
                                    </div>
                                    <label class="cursor-pointer px-6 py-3 bg-brandPrimary text-white rounded-xl text-xs font-bold hover:bg-brandGold transition-all shadow-lg shadow-brandPrimary/10">
                                        + Add Assets
                                        <input type="file" name="article_images[]" multiple accept="image/*" onchange="previewImages(this)" class="hidden">
                                    </label>
                                </div>

                                <div id="image-gallery-preview" class="grid grid-cols-2 md:grid-cols-4 gap-4 min-h-[150px] p-6 bg-gray-50 rounded-[28px] border-2 border-dashed border-gray-100">
                                    <div id="gallery-placeholder" class="col-span-full flex flex-col items-center justify-center text-gray-300 py-4">
                                        <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <p class="text-[10px] font-bold uppercase tracking-tighter">No assets selected</p>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-end gap-4 pb-10">
                                <button type="reset" class="px-8 py-4 font-bold text-gray-400 hover:text-brandAccent transition">Discard</button>
                                <button type="submit" class="px-12 py-4 bg-brandPrimary text-white rounded-[20px] font-bold shadow-2xl shadow-brandPrimary/20 hover:scale-[1.05] transition-all">
                                    Save & Publish Article
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
    <script>
        // Preview Cover Utama
        function previewCover(input) {
            const preview = document.getElementById('cover-prev');
            const placeholder = document.getElementById('cover-placeholder');
            const btnRemove = document.getElementById('btn-remove-cover');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                    btnRemove.classList.remove('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function resetCover() {
            const input = document.getElementById('cover-input');
            const preview = document.getElementById('cover-prev');
            const placeholder = document.getElementById('cover-placeholder');
            const btnRemove = document.getElementById('btn-remove-cover');
            input.value = "";
            preview.src = "";
            preview.classList.add('hidden');
            placeholder.classList.remove('hidden');
            btnRemove.classList.add('hidden');
        }

        // Preview Gallery Multiple Images
        function previewImages(input) {
            const container = document.getElementById('image-gallery-preview');
            const placeholder = document.getElementById('gallery-placeholder');
            
            if (input.files.length > 0 && placeholder) {
                placeholder.classList.add('hidden');
            }

            Array.from(input.files).forEach(file => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const wrapper = document.createElement('div');
                    wrapper.className = "relative group aspect-square rounded-[20px] overflow-hidden shadow-sm border border-white animate-fade-in";
                    wrapper.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-full object-cover transition group-hover:scale-110">
                        <div class="absolute inset-0 bg-brandAccent/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <button type="button" onclick="this.parentElement.parentElement.remove()" class="bg-white p-2 rounded-full text-brandAccent shadow-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    `;
                    container.appendChild(wrapper);
                }
                reader.readAsDataURL(file);
            });
        }
    </script>
</body>
</html>