<div class="hidden md:flex items-center justify-between bg-white border-b px-8 py-4">
    <div>
        <h2 class="text-lg font-bold text-brandPrimary">Halo, DangDang Team! 👋</h2>
        <p class="text-[11px] text-gray-400 font-medium">Sistem manajemen studio sedang berjalan optimal.</p>
    </div>
    <div class="flex items-center gap-6">
        <div class="text-right border-r pr-4">
            <p class="text-xs text-gray-400 font-bold uppercase">Today</p>
            <p class="text-sm font-semibold text-gray-700"><?php echo date('d F Y'); ?></p>
        </div>

        <div class="relative inline-block text-left" id="profileDropdown">
            <button id="profileBtn" class="flex items-center gap-3 hover:bg-gray-50 p-2 rounded-2xl transition-all duration-300 group">
                <div class="w-10 h-10 rounded-full bg-brandGold flex items-center justify-center text-white font-bold shadow-md group-hover:scale-110 transition-transform">
                    D
                </div>
                <div class="text-left">
                    <p class="text-sm font-bold text-brandPrimary leading-none">Super Admin</p>
                    <p class="text-[10px] text-brandTeal font-bold uppercase tracking-tighter mt-1">Verified Account</p>
                </div>
                <svg class="w-4 h-4 text-gray-400 group-hover:text-brandPrimary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <div id="dropdownMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-2xl shadow-xl border border-gray-100 py-2 z-[100] transform origin-top-right transition-all">
                <div class="px-4 py-2 border-b border-gray-50 mb-1">
                    <p class="text-[10px] font-bold text-gray-400 uppercase">Manage Account</p>
                </div>
                <a href="edit_profile.php" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 hover:text-brandPrimary transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Edit Profile
                </a>
                <a href="logout.php" class="flex items-center gap-3 px-4 py-2 text-sm text-brandAccent hover:bg-red-50 transition font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Logout
                </a>
            </div>
        </div>
    </div>
</div>