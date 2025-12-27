<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms of Service | DangDang Studio</title>
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

        /* Styling konten legal agar rapi */
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
            /* items-center; */
            gap: 1rem;
        }

        .legal-content h2 span {
            color: #019E9A;
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
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-[#019E9A]/10 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-0 left-0 w-[300px] h-[300px] bg-[#FF6136]/5 rounded-full blur-[100px]"></div>
        
        <div class="max-w-7xl mx-auto relative z-10 text-center">
            <div class="inline-flex items-center gap-2 bg-white/5 border border-white/10 px-4 py-2 rounded-full mb-8">
                <span class="w-2 h-2 bg-[#019E9A] rounded-full animate-pulse"></span>
                <span class="text-[10px] font-black uppercase tracking-[0.4em] text-white/80">Legal Framework</span>
            </div>
            <h1 class="text-6xl md:text-8xl font-black tracking-tighter text-white italic uppercase leading-[0.85] mb-8">
                Terms <br> <span class="text-outline">&</span> <span class="text-[#019E9A]">Rules.</span>
            </h1>
            <p class="text-white/40 max-w-xl mx-auto text-sm font-medium italic uppercase tracking-widest">
                Kesepakatan bersama untuk kolaborasi kreatif yang transparan.
            </p>
        </div>
    </header>

    <main class="max-w-4xl mx-auto py-24 px-6">
        
        <div class="mb-20 p-8 md:p-12 bg-white rounded-[3.5rem] shadow-sm border border-slate-100 relative overflow-hidden">
            <div class="absolute top-0 right-0 p-8 opacity-[0.05]">
                <svg class="w-32 h-32 text-[#333A73]" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/>
                </svg>
            </div>
            <p class="relative z-10 text-lg md:text-xl font-bold text-[#333A73] italic leading-relaxed">
                Dengan mengakses layanan <span class="text-[#019E9A]">PT. Dang Creative Media</span>, Anda menyetujui pedoman operasional yang dirancang untuk melindungi integritas kreatif dan profesionalisme kerja kami.
            </p>
        </div>

        <div class="legal-content">
            
            <section>
                <h2><span>01.</span> Scope of Services</h2>
                <p>
                    DangDang Studio menyediakan solusi kreatif digital meliputi Game Development, Animasi, dan Gamifikasi. Lingkup kerja spesifik akan diatur dalam *Creative Brief* atau kontrak terpisah yang telah disetujui kedua belah pihak.
                </p>
            </section>

            <section>
                <h2><span>02.</span> Intellectual Property</h2>
                <p>
                    Semua hak kekayaan intelektual atas konsep, desain, dan kode yang dibuat selama proses pengembangan tetap menjadi milik DangDang Studio hingga pelunasan kewajiban pembayaran diselesaikan sepenuhnya oleh klien.
                </p>
            </section>

            <section>
                <h2><span>03.</span> Revision Policy</h2>
                <p>
                    Kami berkomitmen pada kualitas. Setiap proyek mencakup batas revisi sesuai kesepakatan awal. Perubahan di luar cakupan kerja (*scope creep*) akan dikenakan biaya tambahan sesuai dengan kompleksitas permintaan.
                </p>
            </section>

            <section>
                <h2><span>04.</span> Project Termination</h2>
                <p>
                    Pembatalan proyek secara sepihak akan dikenakan biaya pembatalan berdasarkan persentase pekerjaan yang telah selesai. Seluruh uang muka (Down Payment) bersifat *non-refundable*.
                </p>
            </section>

            <div class="mt-24 p-10 bg-[#333A73] rounded-[3.5rem] flex flex-col md:flex-row items-center justify-between gap-8 shadow-2xl shadow-[#333A73]/20">
                <div class="text-center md:text-left">
                    <h3 class="text-2xl font-black text-white italic uppercase tracking-tighter leading-none">Butuh Klarifikasi?</h3>
                    <p class="text-white/50 text-[10px] uppercase tracking-widest mt-2 font-bold">Tim legal kami siap membantu Anda.</p>
                </div>
                <a href="mailto:legal@dangdang.id" class="px-10 py-4 bg-[#019E9A] hover:bg-[#FF6136] text-white text-[10px] font-black uppercase tracking-[0.3em] rounded-full transition-all duration-300">
                    Send Inquiry
                </a>
            </div>

        </div>
    </main>

    <?php include '_footer.php'; ?>

</body>

</html>