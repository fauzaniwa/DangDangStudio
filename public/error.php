<?php
// Path disesuaikan karena config ada di public/process/config.php
$config_path = __DIR__ . '../admin/process/config.php';
if (file_exists($config_path)) {
    require_once $config_path;
}
session_start();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 Not Found | DangDang Studio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200..800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; }
        
        .text-outline { 
            -webkit-text-stroke: 1.5px rgba(255, 255, 255, 0.2); 
            color: transparent; 
        }

        .error-fade-up {
            animation: errorFadeUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes errorFadeUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .bg-vault {
            background-color: #333A73;
            background-image: radial-gradient(circle at 20% 30%, rgba(1, 158, 154, 0.15) 0%, transparent 50%),
                              radial-gradient(circle at 80% 70%, rgba(255, 97, 54, 0.1) 0%, transparent 50%);
        }
    </style>
</head>

<body class="overflow-x-hidden">
    <?php include_once '_navbar.php'; ?>

    <header class="min-h-screen flex items-center pt-32 pb-20 md:pt-48 md:pb-32 px-6 relative overflow-hidden bg-vault shadow-2xl">
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 40px 40px;"></div>

        <div class="max-w-7xl mx-auto relative z-10 text-center w-full">
            <div class="error-fade-up inline-flex items-center gap-3 bg-white/5 border border-white/10 px-6 py-2 rounded-full mb-8">
                <span class="w-2 h-2 bg-[#FF6136] rounded-full animate-ping"></span>
                <span class="text-[10px] font-black uppercase tracking-[0.4em] text-white/70">Vault Exception: 404</span>
            </div>

            <h1 class="error-fade-up text-[100px] md:text-[180px] font-black tracking-tighter text-white italic uppercase leading-[0.8] mb-8 select-none">
                404 <br> <span class="text-outline">Empty.</span>
            </h1>

            <div class="error-fade-up max-w-2xl mx-auto space-y-10" style="animation-delay: 200ms">
                <p class="text-white/50 text-lg font-medium italic">
                    Data yang Anda cari tidak tersimpan dalam database <span class="text-[#019E9A] font-bold italic">DangDang Studio.</span>
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
                    <a href="hommepage.php" class="px-12 py-5 bg-[#019E9A] text-white text-[10px] font-black uppercase tracking-[0.4em] rounded-full hover:scale-105 transition-all shadow-xl shadow-[#019E9A]/20">
                        Back to Homepage
                    </a>
                    <button onclick="history.back()" class="px-10 py-5 border border-white/10 text-white/50 text-[10px] font-black uppercase tracking-[0.4em] rounded-full hover:bg-white/5 transition-all">
                        Go Back
                    </button>
                </div>
            </div>
        </div>
    </header>

    <?php include_once '_footer.php'; ?>

</body>
</html>