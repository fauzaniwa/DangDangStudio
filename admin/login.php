<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - DangDang Studio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="assets/tailwind-config.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
</head>

<body class="bg-brandPrimary min-h-screen flex items-center justify-center p-6 py-10 relative overflow-y-auto">


    <div class="fixed top-0 left-0 w-full h-full opacity-10 pointer-events-none z-0">
        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-brandAccent rounded-full blur-[120px]"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-brandGold rounded-full blur-[120px]"></div>


    </div>


    <div class="w-full max-w-[440px] relative z-10">
        <div class="text-center mb-10">
            <div class="inline-flex w-16 h-16 bg-gradient-to-tr from-brandAccent to-brandGold rounded-2xl items-center justify-center mb-4 shadow-xl shadow-brandAccent/30 transform hover:scale-110 transition-transform">
                <span class="text-2xl font-black text-white">DS</span>
            </div>
            <h1 class="text-2xl font-bold text-white tracking-tight">Personnel Login</h1>
            <p class="text-blue-100/50 text-sm mt-2 font-medium">Sistem Manajemen Internal DangDang Studio</p>
        </div>

        <?php if (isset($_GET['registration']) && $_GET['registration'] == 'success'): ?>
            <div class="mb-6 p-4 bg-brandTeal/20 border border-brandTeal/50 rounded-2xl text-brandTeal text-sm font-bold flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Registrasi Berhasil! Silakan Login.
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['logout']) && $_GET['logout'] == 'success'): ?>
            <div class="mb-6 p-4 bg-blue-500/20 border border-blue-500/50 rounded-2xl text-blue-200 text-sm font-bold flex items-center gap-3 animate-fade-in">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Sesi telah berakhir. Sampai jumpa kembali!
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="mb-6 p-4 bg-red-500/20 border border-red-500/50 rounded-2xl text-red-200 text-sm font-bold flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <?php
                if ($_GET['error'] == 'wrong_credentials') echo "Email atau Password salah!";
                elseif ($_GET['error'] == 'need_login') echo "Anda harus login terlebih dahulu!";
                ?>
            </div>
        <?php endif; ?>

        <div class="bg-white/5 backdrop-blur-2xl border border-white/10 rounded-[40px] p-8 md:p-10 shadow-2xl">
            <form action="./process/process_login.php" method="POST" class="space-y-6">
                <div>
                    <label class="block text-[10px] font-bold text-blue-200/60 uppercase tracking-widest mb-2 ml-1">Official Email</label>
                    <input type="email" name="email" required placeholder="name@dangdang.studio"
                        class="w-full px-6 py-4 rounded-2xl border border-white/10 bg-white/5 focus:bg-white focus:border-brandGold outline-none transition font-bold text-white focus:text-brandPrimary">
                </div>

                <div>
                    <div class="flex justify-between items-center mb-2 ml-1">
                        <label class="block text-[10px] font-bold text-blue-200/60 uppercase tracking-widest">Password</label>
                        <a href="#" class="text-[10px] font-bold text-brandGold uppercase hover:text-brandAccent transition">Forgot?</a>
                    </div>
                    <input type="password" name="password" required placeholder="••••••••"
                        class="w-full px-6 py-4 rounded-2xl border border-white/10 bg-white/5 focus:bg-white focus:border-brandGold outline-none transition font-bold text-white focus:text-brandPrimary">
                </div>

                <button type="submit" class="w-full py-5 bg-brandAccent text-white rounded-[24px] font-bold text-lg shadow-xl shadow-brandAccent/20 hover:bg-brandGold hover:scale-[1.02] active:scale-95 transition-all mt-4">
                    Sign In to Dashboard
                </button>
            </form>
        </div>

        <p class="text-center mt-8 text-sm text-blue-100/40 font-medium">
            Belum terdaftar? <a href="register.php" class="text-brandGold font-bold hover:text-brandAccent transition">Minta Akses Admin</a>
        </p>
    </div>


</body>

</html>