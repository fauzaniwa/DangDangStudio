<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - DangDang Studio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="assets/tailwind-config.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
                        
                        <div class="mb-10">
                            <h1 class="text-3xl font-extrabold text-brandPrimary tracking-tight">Account Settings</h1>
                            <p class="text-sm text-gray-500 font-medium">Perbarui informasi personal dan keamanan akun Anda.</p>
                        </div>

                        <form action="process_update_profile.php" method="POST" enctype="multipart/form-data" class="space-y-8 pb-20">
                            
                            <div class="bg-white rounded-[40px] border border-gray-100 shadow-sm overflow-hidden">
                                <div class="h-32 bg-gradient-to-r from-brandPrimary to-brandAccent"></div>
                                <div class="px-8 pb-10">
                                    <div class="relative flex justify-between items-end -mt-12 mb-8">
                                        <div class="relative group">
                                            <div class="w-32 h-32 rounded-[32px] border-8 border-white bg-gray-200 overflow-hidden shadow-lg">
                                                <img id="avatar-preview" src="https://i.pravatar.cc/300?u=admin" class="w-full h-full object-cover">
                                            </div>
                                            <label class="absolute bottom-2 right-2 w-10 h-10 bg-brandGold text-white rounded-xl flex items-center justify-center cursor-pointer shadow-lg hover:scale-110 transition-transform">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                                <input type="file" name="avatar" class="hidden" onchange="previewAvatar(this)">
                                            </label>
                                        </div>
                                        <div class="pb-2">
                                            <span class="px-4 py-2 bg-brandPrimary/5 text-brandPrimary rounded-full text-[10px] font-black uppercase tracking-widest">Super Administrator</span>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div class="space-y-1">
                                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.15em] ml-1">Full Name</label>
                                            <input type="text" name="fullname" value="Aditya Pratama" class="w-full px-6 py-4 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-brandGold outline-none transition font-bold text-brandPrimary">
                                        </div>
                                        <div class="space-y-1">
                                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.15em] ml-1">Username</label>
                                            <input type="text" name="username" value="aditya_dangdang" class="w-full px-6 py-4 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-brandGold outline-none transition font-bold text-brandPrimary">
                                        </div>
                                        <div class="space-y-1 md:col-span-2">
                                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.15em] ml-1">Email Address</label>
                                            <input type="email" name="email" value="aditya@dangdang.studio" class="w-full px-6 py-4 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-brandGold outline-none transition font-bold text-brandPrimary">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white rounded-[40px] p-8 md:p-10 border border-gray-100 shadow-sm">
                                <h2 class="text-xl font-bold text-brandPrimary mb-6 flex items-center gap-3">
                                    <span class="w-1.5 h-6 bg-brandAccent rounded-full"></span> Change Password
                                </h2>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-1">
                                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.15em] ml-1">New Password</label>
                                        <input type="password" name="new_password" placeholder="••••••••" class="w-full px-6 py-4 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-brandAccent outline-none transition font-bold text-brandPrimary">
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.15em] ml-1">Confirm New Password</label>
                                        <input type="password" name="confirm_password" placeholder="••••••••" class="w-full px-6 py-4 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-brandAccent outline-none transition font-bold text-brandPrimary">
                                    </div>
                                </div>
                                <p class="mt-4 text-[10px] text-gray-400 italic leading-relaxed">Kosongkan kolom password jika Anda tidak berniat mengubahnya.</p>
                            </div>

                            <div class="flex items-center justify-end gap-4">
                                <button type="button" onclick="history.back()" class="px-8 py-4 text-sm font-bold text-gray-400 hover:text-brandPrimary transition">Batal</button>
                                <button type="submit" class="px-10 py-4 bg-brandPrimary text-white rounded-2xl font-bold shadow-xl shadow-brandPrimary/20 hover:scale-105 active:scale-95 transition-all">
                                    Simpan Perubahan
                                </button>
                            </div>

                        </form>
                    </div>
                </div>

                <?php include_once '_footer.php'; ?>
            </main>
        </div>
    </div>

    <script>
        function previewAvatar(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('avatar-preview').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
    <script src="assets/script.js"></script>
</body>
</html>