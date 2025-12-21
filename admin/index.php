<?php
// session_start();
// if(isset($_SESSION['admin_id'])) { header("Location: dashboard.php"); exit(); }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - DangDang Studio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="assets/tailwind-config.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="bg-brandPrimary min-h-screen flex items-center justify-center p-6 overflow-hidden relative">

    <div class="absolute -top-24 -left-24 w-96 h-96 bg-brandAccent/20 rounded-full blur-[120px] animate-pulse"></div>
    <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-brandGold/20 rounded-full blur-[120px] animate-pulse"></div>

    <div class="relative w-full max-w-xl text-center">
        <div class="mb-12">
            <div class="inline-flex w-28 h-28 bg-gradient-to-tr from-brandAccent to-brandGold rounded-[32px] items-center justify-center mb-8 shadow-2xl shadow-brandAccent/40 transform hover:rotate-6 transition-all duration-500">
                <span class="text-4xl font-black text-white tracking-tighter">DS</span>
            </div>
            <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-4 tracking-tight">
                DangDang <span class="text-brandGold italic font-black">Studio</span>
            </h1>
            <p class="text-blue-100/60 text-base md:text-lg max-w-md mx-auto leading-relaxed font-medium">
                Sistem Manajemen Internal untuk Pengembang Game dan Kreator Konten Digital.
            </p>
        </div>

        <div class="bg-white/5 backdrop-blur-2xl border border-white/10 rounded-[48px] p-8 md:p-12 shadow-3xl">
            <div class="space-y-4">
                <a href="login.php" class="flex items-center justify-center gap-4 w-full py-6 bg-brandAccent text-white rounded-[28px] font-bold text-xl hover:bg-brandGold hover:scale-[1.03] transition-all shadow-2xl shadow-brandAccent/20 group">
                    Masuk ke Sistem
                    <svg class="w-6 h-6 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </a>
                
                <div class="flex items-center justify-center gap-8 pt-6">
                    <div class="text-center">
                        <p class="text-[10px] font-bold text-blue-200/40 uppercase tracking-widest">Version</p>
                        <p class="text-xs font-bold text-white mt-1">v2.1.0 Stable</p>
                    </div>
                    <div class="h-8 w-px bg-white/10"></div>
                    <div class="text-center">
                        <p class="text-[10px] font-bold text-blue-200/40 uppercase tracking-widest">Status</p>
                        <p class="text-xs font-bold text-brandTeal mt-1 flex items-center justify-center gap-2">
                            <span class="w-2 h-2 bg-brandTeal rounded-full animate-ping"></span> Online
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-12 space-y-2">
            <p class="text-white/20 text-[10px] font-black uppercase tracking-[0.3em]">
                Protected by DangDang Security System
            </p>
            <p class="text-blue-200/30 text-[10px] font-medium italic">
                Akses terbatas hanya untuk personil terdaftar.
            </p>
        </div>
    </div>

</body>
</html>