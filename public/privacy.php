<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy | DangDang Studio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200..800&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .text-outline {
            -webkit-text-stroke: 1px rgba(255, 255, 255, 0.3);
            color: transparent;
        }

        .legal-content section {
            margin-bottom: 4rem;
        }

        .legal-content h2 {
            font-size: 1.875rem;
            font-weight: 900;
            text-transform: uppercase;
            font-style: italic;
            color: #333A73;
            letter-spacing: -0.05em;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        /* Aksen warna berbeda untuk Privacy: Coral */
        .legal-content h2 span {
            color: #FF6136;
        }

        .legal-content p {
            color: #64748B;
            line-height: 1.8;
            font-size: 0.95rem;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-[#F8FAFC]">

    <?php include '_navbar.php'; ?>

    <header class="pt-48 pb-24 px-6 relative overflow-hidden rounded-b-[4rem] md:rounded-b-[6rem]" style="background-color: #333A73;">
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-[#FF6136]/10 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-0 left-0 w-[300px] h-[300px] bg-[#019E9A]/5 rounded-full blur-[100px]"></div>
        
        <div class="max-w-7xl mx-auto relative z-10 text-center">
            <div class="inline-flex items-center gap-2 bg-white/5 border border-white/10 px-4 py-2 rounded-full mb-8">
                <span class="w-2 h-2 bg-[#FF6136] rounded-full animate-pulse"></span>
                <span class="text-[10px] font-black uppercase tracking-[0.4em] text-white/80">Data Security</span>
            </div>
            <h1 class="text-6xl md:text-8xl font-black tracking-tighter text-white italic uppercase leading-[0.85] mb-8">
                Privacy <br> <span class="text-outline">&</span> <span class="text-[#FF6136]">Safety.</span>
            </h1>
            <p class="text-white/40 max-w-xl mx-auto text-sm font-medium italic uppercase tracking-widest">
                Bagaimana kami menjaga dan menghargai data digital Anda.
            </p>
        </div>
    </header>

    <main class="max-w-4xl mx-auto py-24 px-6">
        
        <div class="mb-20 p-8 md:p-12 bg-white rounded-[3.5rem] shadow-sm border border-slate-100 relative overflow-hidden">
            <div class="absolute -right-10 -bottom-10 opacity-[0.03]">
                <svg class="w-64 h-64 text-[#333A73]" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z"/>
                </svg>
            </div>
            <p class="relative z-10 text-lg md:text-xl font-bold text-[#333A73] italic leading-relaxed">
                Kepercayaan adalah fondasi kolaborasi. Di <span class="text-[#FF6136]">DangDang Studio</span>, kami berkomitmen untuk melindungi setiap informasi yang Anda percayakan kepada sistem kami.
            </p>
        </div>

        <div class="legal-content">
            
            <section>
                <h2><span>01.</span> Data Collection</h2>
                <p>
                    Kami mengumpulkan informasi yang Anda berikan secara langsung saat mengisi formulir kontak atau pendaftaran proyek, termasuk nama, alamat email, dan detail perusahaan. Kami juga mengumpulkan data analitik standar untuk meningkatkan pengalaman pengguna di situs kami.
                </p>
            </section>

            <section>
                <h2><span>02.</span> Use of Information</h2>
                <p>
                    Data yang kami kumpulkan digunakan secara eksklusif untuk kebutuhan komunikasi proyek, pembaruan layanan, dan personalisasi konten kreatif yang kami tawarkan. Kami tidak akan pernah menjual atau menyewakan data Anda kepada pihak ketiga.
                </p>
            </section>

            <section>
                <h2><span>03.</span> Cookies & Tracking</h2>
                <p>
                    Website kami menggunakan cookies untuk mengingat preferensi Anda dan memantau traffic website melalui Google Analytics. Anda dapat menonaktifkan cookies melalui pengaturan browser Anda, namun beberapa fungsi situs mungkin tidak berjalan optimal.
                </p>
            </section>

            <section>
                <h2><span>04.</span> Security Systems</h2>
                <p>
                    Kami menerapkan protokol keamanan SSL terenkripsi untuk memastikan setiap transmisi data antara browser Anda dan server kami tetap aman dari akses yang tidak sah.
                </p>
            </section>

            <div class="mt-24 p-10 bg-[#333A73] rounded-[3.5rem] flex flex-col md:flex-row items-center justify-between gap-8 shadow-2xl shadow-[#333A73]/20">
                <div class="text-center md:text-left">
                    <h3 class="text-2xl font-black text-white italic uppercase tracking-tighter leading-none">Punya Kekhawatiran?</h3>
                    <p class="text-white/50 text-[10px] uppercase tracking-widest mt-2 font-bold">Tanyakan detail keamanan data Anda pada kami.</p>
                </div>
                <a href="mailto:privacy@dangdang.id" class="px-10 py-4 bg-[#FF6136] hover:bg-[#019E9A] text-white text-[10px] font-black uppercase tracking-[0.3em] rounded-full transition-all duration-300">
                    Contact Privacy Team
                </a>
            </div>

        </div>
    </main>

    <?php include '_footer.php'; ?>

</body>

</html>