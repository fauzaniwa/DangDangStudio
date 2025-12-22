<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Staff - DangDang Studio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="assets/tailwind-config.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
    <style>
        .animate-fade-in { animation: fadeIn 0.4s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
    <script src="assets/script.js"></script>
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
                        <a href="team.php" class="inline-flex items-center gap-2 text-sm font-bold text-gray-400 hover:text-brandPrimary transition mb-8">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            Kembali ke Roster Tim
                        </a>

                        <form action="./process/process_add_team.php" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-8 pb-20">
                            
                            <div class="lg:col-span-1 space-y-4">
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-2">Foto Profil (3:4)</label>
                                <div class="relative aspect-[3/4] bg-white rounded-[32px] border-2 border-dashed border-gray-200 p-2 group hover:border-brandGold transition-all duration-300 overflow-hidden shadow-sm">
                                    <input type="file" name="member_image" accept="image/*" onchange="previewPortrait(this)" class="absolute inset-0 opacity-0 cursor-pointer z-20">
                                    
                                    <div class="w-full h-full rounded-[24px] overflow-hidden bg-gray-50 flex items-center justify-center relative">
                                        <img id="portrait-prev" class="w-full h-full object-cover hidden z-10">
                                        <div id="portrait-placeholder" class="text-center p-4 z-0">
                                            <div class="w-12 h-12 bg-white rounded-2xl shadow-sm flex items-center justify-center mx-auto mb-3">
                                                <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </div>
                                            <p class="text-[10px] font-bold text-gray-400 uppercase">Upload Potrait</p>
                                        </div>
                                    </div>

                                    <div id="change-photo-btn" class="absolute bottom-6 inset-x-6 z-30 hidden group-hover:block animate-fade-in">
                                        <div class="bg-brandPrimary/90 backdrop-blur text-white py-2 rounded-xl text-center text-[10px] font-bold uppercase tracking-wider shadow-lg">Ganti Foto</div>
                                    </div>
                                </div>
                                <p class="text-[9px] text-gray-400 text-center italic px-4">* Gunakan foto dengan orientasi berdiri untuk hasil terbaik.</p>
                            </div>

                            <div class="lg:col-span-2 space-y-6">
                                <div class="bg-white rounded-[32px] p-8 shadow-sm border border-gray-100 space-y-6">
                                    <h2 class="text-xl font-bold text-brandPrimary mb-4 flex items-center gap-3">
                                        <span class="w-2 h-8 bg-brandGold rounded-full"></span> Detail Anggota Baru
                                    </h2>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div class="md:col-span-2">
                                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Nama Lengkap</label>
                                            <input type="text" name="name" required placeholder="Masukkan nama lengkap..." class="w-full px-6 py-4 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-brandGold outline-none transition font-semibold text-brandPrimary">
                                        </div>

                                        <div>
                                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Divisi Kerja</label>
                                            <select name="division" class="w-full px-6 py-4 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-brandGold outline-none transition font-bold text-sm">
                                                <option>Management</option>
                                                <option>Technical Art</option>
                                                <option>Game Programming</option>
                                                <option>3D Modeling</option>
                                                <option>UI/UX Design</option>
                                                <option>Audio & Music</option>
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Level / Jabatan</label>
                                            <input type="text" name="level" placeholder="Contoh: Senior Staff" class="w-full px-6 py-4 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-brandGold outline-none transition font-bold text-sm">
                                        </div>

                                        <div>
                                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Status Pekerjaan</label>
                                            <div class="flex flex-wrap gap-2">
                                                <label class="flex-1">
                                                    <input type="radio" name="status" value="Full-time" checked class="hidden peer">
                                                    <div class="text-center py-3 rounded-xl border border-gray-100 bg-gray-50 peer-checked:bg-brandPrimary peer-checked:text-white peer-checked:border-brandPrimary transition cursor-pointer font-bold text-[10px] uppercase">Full-time</div>
                                                </label>
                                                <label class="flex-1">
                                                    <input type="radio" name="status" value="Intern" class="hidden peer">
                                                    <div class="text-center py-3 rounded-xl border border-gray-100 bg-gray-50 peer-checked:bg-brandPrimary peer-checked:text-white peer-checked:border-brandPrimary transition cursor-pointer font-bold text-[10px] uppercase">Intern</div>
                                                </label>
                                                <label class="flex-1">
                                                    <input type="radio" name="status" value="Freelance" class="hidden peer">
                                                    <div class="text-center py-3 rounded-xl border border-gray-100 bg-gray-50 peer-checked:bg-brandPrimary peer-checked:text-white peer-checked:border-brandPrimary transition cursor-pointer font-bold text-[10px] uppercase">Freelance</div>
                                                </label>
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Email Studio</label>
                                            <input type="email" name="email" placeholder="nama@dangdang.com" class="w-full px-6 py-4 rounded-2xl border border-gray-100 bg-gray-50 outline-none text-sm font-medium">
                                        </div>

                                        <div class="md:col-span-2">
                                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Nomor WhatsApp (Aktif)</label>
                                            <div class="relative">
                                                <span class="absolute left-6 top-1/2 -translate-y-1/2 text-sm font-bold text-gray-400">+62</span>
                                                <input type="tel" name="phone" placeholder="81234567..." class="w-full pl-16 pr-6 py-4 rounded-2xl border border-gray-100 bg-gray-50 outline-none text-sm font-medium">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="pt-6">
                                        <button type="submit" class="w-full py-5 bg-brandPrimary text-white rounded-[24px] font-bold text-lg shadow-2xl shadow-brandPrimary/20 hover:scale-[1.02] active:scale-95 transition-all">
                                            Simpan Anggota Baru
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
        function previewPortrait(input) {
            const preview = document.getElementById('portrait-prev');
            const placeholder = document.getElementById('portrait-placeholder');
            const btn = document.getElementById('change-photo-btn');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                    btn.classList.remove('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>