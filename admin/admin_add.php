<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Admin - DangDang Studio</title>
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
                        <a href="admin_manager.php" class="inline-flex items-center gap-2 text-sm font-bold text-gray-400 hover:text-brandPrimary transition mb-8">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            Back to Manager
                        </a>

                        <form action="process_add_admin.php" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-8 pb-20">
                            
                            <div class="lg:col-span-1">
                                <div class="bg-white p-8 rounded-[32px] border border-gray-100 shadow-sm text-center">
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-6">Profile Picture</label>
                                    
                                    <div class="relative w-32 h-32 mx-auto group">
                                        <div class="w-full h-full rounded-full bg-gray-50 border-2 border-dashed border-gray-200 flex items-center justify-center overflow-hidden transition-all group-hover:border-brandGold shadow-inner">
                                            <input type="file" name="avatar" accept="image/*" onchange="previewAvatar(this)" class="absolute inset-0 opacity-0 cursor-pointer z-20">
                                            <img id="avatar-prev" class="absolute inset-0 w-full h-full object-cover hidden z-10">
                                            <svg id="avatar-placeholder" class="w-10 h-10 text-gray-200" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                                        </div>
                                        <div class="absolute bottom-0 right-0 bg-brandPrimary text-white p-2 rounded-full shadow-lg border-4 border-white">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path></svg>
                                        </div>
                                    </div>
                                    <p class="mt-4 text-[10px] text-gray-400 font-medium">PNG or JPG. Max 2MB.</p>
                                </div>
                            </div>

                            <div class="lg:col-span-2 space-y-6">
                                <div class="bg-white rounded-[32px] p-8 md:p-10 shadow-sm border border-gray-100 space-y-6">
                                    <h2 class="text-xl font-bold text-brandPrimary mb-4 flex items-center gap-3">
                                        <span class="w-2 h-8 bg-brandGold rounded-full"></span> Account Credentials
                                    </h2>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Full Name</label>
                                            <input type="text" name="name" required placeholder="John Doe" class="w-full px-6 py-4 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-brandGold outline-none transition font-bold">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Email Address</label>
                                            <input type="email" name="email" required placeholder="name@dangdang.studio" class="w-full px-6 py-4 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-brandGold outline-none transition font-bold">
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Role Permission</label>
                                            <select name="role" class="w-full px-6 py-4 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-brandGold outline-none transition font-bold text-sm appearance-none">
                                                <option value="Super Admin">Super Admin (Full Access)</option>
                                                <option value="Content Editor">Content Editor</option>
                                                <option value="Marketing">Marketing & Social</option>
                                            </select>
                                        </div>
                                        <div class="relative">
                                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Password</label>
                                            <input type="password" id="pass-input" name="password" required placeholder="••••••••" class="w-full px-6 py-4 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-brandGold outline-none transition font-bold">
                                            <button type="button" onclick="togglePassword()" class="absolute right-6 top-[42px] text-gray-300 hover:text-brandPrimary transition">
                                                <svg id="eye-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="pt-6">
                                        <button type="submit" class="w-full py-5 bg-brandPrimary text-white rounded-[24px] font-bold text-lg shadow-2xl shadow-brandPrimary/20 hover:scale-[1.02] active:scale-95 transition-all">
                                            Create Admin Account
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
        function previewAvatar(input) {
            const preview = document.getElementById('avatar-prev');
            const placeholder = document.getElementById('avatar-placeholder');
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

        function togglePassword() {
            const input = document.getElementById('pass-input');
            const icon = document.getElementById('eye-icon');
            if (input.type === "password") {
                input.type = "text";
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path>';
            } else {
                input.type = "password";
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>';
            }
        }
    </script>
</body>
</html>