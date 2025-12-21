<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Admin - DangDang Studio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="assets/tailwind-config.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="bg-brandPrimary min-h-screen flex items-center justify-center p-6 py-12 relative overflow-hidden">
    
    <div class="absolute top-0 left-0 w-full h-full opacity-10 pointer-events-none">
        <div class="absolute top-[20%] right-[-5%] w-[30%] h-[30%] bg-brandTeal rounded-full blur-[100px]"></div>
        <div class="absolute bottom-[10%] left-[-5%] w-[30%] h-[30%] bg-brandAccent rounded-full blur-[100px]"></div>
    </div>

    <div class="w-full max-w-[480px] relative z-10">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-extrabold text-white tracking-tight">Join the Team</h1>
            <p class="text-blue-100/50 text-sm mt-2 font-medium">Buat kredensial admin baru untuk DangDang Studio.</p>
        </div>

        <div class="bg-white/5 backdrop-blur-2xl border border-white/10 rounded-[48px] p-8 md:p-10 shadow-2xl">
            <form action="process_register.php" method="POST" class="space-y-5">
                <div>
                    <label class="block text-[10px] font-bold text-blue-200/60 uppercase tracking-widest mb-2 ml-1">Full Name</label>
                    <input type="text" name="fullname" required placeholder="John Doe" 
                           class="w-full px-6 py-4 rounded-2xl border border-white/10 bg-white/5 focus:bg-white focus:border-brandGold outline-none transition font-bold text-white focus:text-brandPrimary">
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-blue-200/60 uppercase tracking-widest mb-2 ml-1">Official Email</label>
                    <input type="email" name="email" required placeholder="name@dangdang.studio" 
                           class="w-full px-6 py-4 rounded-2xl border border-white/10 bg-white/5 focus:bg-white focus:border-brandGold outline-none transition font-bold text-white focus:text-brandPrimary">
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-blue-200/60 uppercase tracking-widest mb-2 ml-1">Access Code</label>
                    <input type="text" name="access_code" required placeholder="Studio Auth Code" 
                           class="w-full px-6 py-4 rounded-2xl border-brandGold/30 bg-brandGold/5 focus:bg-white focus:border-brandGold outline-none transition font-black text-brandGold uppercase placeholder:text-brandGold/30">
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full py-5 bg-brandTeal text-white rounded-[24px] font-bold text-lg shadow-xl shadow-brandTeal/20 hover:bg-brandGold hover:shadow-brandGold/20 hover:scale-[1.02] transition-all">
                        Register Personnel
                    </button>
                </div>
            </form>
        </div>

        <p class="text-center mt-8 text-sm text-blue-100/40 font-medium">
            Sudah memiliki akses? <a href="login.php" class="text-brandGold font-bold hover:text-brandAccent transition">Kembali ke Login</a>
        </p>
    </div>
</body>
</html>