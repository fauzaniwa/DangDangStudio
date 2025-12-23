<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dang Creative Media</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/dist/aos.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            scroll-behavior: smooth;
        }

        #cursor {
            width: 40px;
            height: 40px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 50%;
            position: fixed;
            pointer-events: none;
            z-index: 9999;
            transition: transform 0.2s ease-out;
            transform: translate(-50%, -50%);
        }

        /* FIX MARQUEE ANTI PATAH */
        @keyframes marquee {
            from {
                transform: translateX(0);
            }

            to {
                transform: translateX(-100%);
            }

            /* Pakai -100% dari kontainer konten */
        }

        .marquee-container {
            display: flex;
            overflow: hidden;
            user-select: none;
            width: 100%;
        }

        .marquee-content {
            flex-shrink: 0;
            display: flex;
            justify-content: space-around;
            min-width: 100%;
            /* Pastikan lebar grup 1 dan 2 identik */
            gap: 6rem;
            padding: 0 3rem;
            animation: marquee 25s linear infinite;
        }

        /* BENTO LAYOUT (SHARP CORNERS) */
        @media (min-width: 1024px) {
            .bento-grid-custom {
                display: grid !important;
                grid-template-columns: repeat(3, 1fr);
                grid-template-rows: repeat(4, 240px);
                gap: 1.5rem;
                grid-template-areas:
                    "whoosh whoosh ovo"
                    "whoosh whoosh peruri"
                    "burgreens digital digital"
                    "visious digital digital";
            }
        }

        @media (min-width: 768px) and (max-width: 1023px) {
            .bento-grid-custom {
                display: grid !important;
                grid-template-columns: repeat(2, 1fr);
                grid-template-rows: repeat(5, 220px);
                gap: 1.25rem;
                grid-template-areas: "whoosh whoosh" "whoosh whoosh" "ovo peruri" "burgreens visious" "digital digital";
            }
        }

        @media (max-width: 767px) {
            .bento-grid-custom {
                display: flex !important;
                flex-direction: column;
                gap: 1rem;
            }

            .bento-grid-custom>div {
                height: 320px;
            }
        }

        .area-whoosh {
            grid-area: whoosh;
        }

        .area-ovo {
            grid-area: ovo;
        }

        .area-peruri {
            grid-area: peruri;
        }

        .area-burgreens {
            grid-area: burgreens;
        }

        .area-visious {
            grid-area: visious;
        }

        .area-digital {
            grid-area: digital;
        }

        /* BENTO HOVER REVEAL EFFECT */
        .bento-item {
            position: relative;
            overflow: hidden;
            border-radius: 0 !important;
            width: 100%;
            height: 100%;
        }

        .bento-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            transition: transform 0.7s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .bento-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.6);
            opacity: 0;
            transition: opacity 0.4s ease;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 2rem;
        }

        .bento-item:hover .bento-overlay {
            opacity: 1;
        }

        .bento-item:hover .bento-img {
            transform: scale(1.08);
        }

        .bento-title {
            align-self: flex-start;
            font-weight: 700;
            color: white;
            font-size: 1.5rem;
            line-height: 1.2;
        }

        .bento-category {
            align-self: flex-start;
            font-weight: 400;
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.15em;
        }
    </style>
</head>

<body class="bg-white text-black overflow-x-hidden">
    <div id="cursor" class="hidden md:block"></div>

    <section class="min-h-screen w-full flex flex-col justify-center px-8 md:px-24 lg:px-64 py-20 relative overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img src="assets/img/landing_page.png" alt="Landing Background" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-white/30"></div>
        </div>
        <div class="max-w-4xl relative z-10">
            <h2 data-aos="fade-up" class="text-xs font-bold tracking-[0.4em] text-gray-500 uppercase mb-4">DANG CREATIVE MEDIA</h2>
            <h1 data-aos="fade-up" class="text-6xl md:text-8xl font-extrabold tracking-tighter leading-[0.9] mb-12 uppercase text-black">UNLEASH YOUR<br>IMAGINATION</h1>
            <div data-aos="fade-up" data-aos-delay="300" class="grid grid-cols-1 md:grid-cols-2 gap-12 pt-12 border-t border-black/10">
                <p class="text-lg font-semibold text-black leading-snug italic">Discover captivating worlds and embark on epic adventures with Aang.</p>
                <p class="text-sm text-gray-600 leading-relaxed">Based in Bandung, we are a creative collective specializing in Games, Gamification, and Interactive Ads. We merge high-end Design with professional Animation to build digital experiences that truly play.</p>
            </div>
        </div>
    </section>

    <section class="py-24 bg-white overflow-hidden border-y border-gray-50">
        <div class="px-8 md:px-24 lg:px-64 mb-16 flex flex-col items-center text-center" data-aos="fade-up">
            <h2 class="text-xl md:text-2xl font-black tracking-[0.3em] text-black uppercase">Previous Collaborations</h2>
            <div class="h-1.5 w-24 bg-black mt-4"></div>
        </div>
        <div class="marquee-container">
            <div class="marquee-content">
                <img src="assets/img/client_gojek.png" class="h-14 md:h-20 grayscale opacity-40 hover:grayscale-0 hover:opacity-100 transition-all duration-500 cursor-pointer" alt="Gojek">
                <img src="assets/img/client_iqos.png" class="h-14 md:h-20 grayscale opacity-40 hover:grayscale-0 hover:opacity-100 transition-all duration-500 cursor-pointer" alt="IQOS">
                <img src="assets/img/client_oriflame.png" class="h-14 md:h-20 grayscale opacity-40 hover:grayscale-0 hover:opacity-100 transition-all duration-500 cursor-pointer" alt="Oriflame">
                <img src="assets/img/client_signature.png" class="h-14 md:h-20 grayscale opacity-40 hover:grayscale-0 hover:opacity-100 transition-all duration-500 cursor-pointer" alt="Signature">
                <img src="assets/img/client_kemenkebud.png" class="h-14 md:h-20 grayscale opacity-40 hover:grayscale-0 hover:opacity-100 transition-all duration-500 cursor-pointer" alt="Kemenkebud">
                <img src="assets/img/client_bi.png" class="h-14 md:h-20 grayscale opacity-40 hover:grayscale-0 hover:opacity-100 transition-all duration-500 cursor-pointer" alt="Bank Indonesia">
            </div>
            <div class="marquee-content" aria-hidden="true">
                <img src="assets/img/client_gojek.png" class="h-14 md:h-20 grayscale opacity-40 hover:grayscale-0 hover:opacity-100 transition-all duration-500 cursor-pointer" alt="Gojek">
                <img src="assets/img/client_iqos.png" class="h-14 md:h-20 grayscale opacity-40 hover:grayscale-0 hover:opacity-100 transition-all duration-500 cursor-pointer" alt="IQOS">
                <img src="assets/img/client_oriflame.png" class="h-14 md:h-20 grayscale opacity-40 hover:grayscale-0 hover:opacity-100 transition-all duration-500 cursor-pointer" alt="Oriflame">
                <img src="assets/img/client_signature.png" class="h-14 md:h-20 grayscale opacity-40 hover:grayscale-0 hover:opacity-100 transition-all duration-500 cursor-pointer" alt="Signature">
                <img src="assets/img/client_kemenkebud.png" class="h-14 md:h-20 grayscale opacity-40 hover:grayscale-0 hover:opacity-100 transition-all duration-500 cursor-pointer" alt="Kemenkebud">
                <img src="assets/img/client_bi.png" class="h-14 md:h-20 grayscale opacity-40 hover:grayscale-0 hover:opacity-100 transition-all duration-500 cursor-pointer" alt="Bank Indonesia">
            </div>
        </div>
    </section>

    <section class="pt-12 pb-32 px-8 md:px-12 lg:px-64 bg-white">
        <div class="mb-24 flex flex-col items-center text-center" data-aos="fade-up">
            <h2 class="text-xs font-bold tracking-[0.5em] text-gray-400 uppercase mb-3">Selected Works</h2>
            <h3 class="text-5xl md:text-6xl font-extrabold italic uppercase tracking-tighter text-black">2023 — 2025</h3>
            <div class="h-1.5 w-24 bg-black mt-6"></div>
        </div>
        <div class="max-w-7xl mx-auto">
            <div class="bento-grid-custom">
                <div data-aos="fade-up" class="area-whoosh bento-item group cursor-pointer shadow-sm">
                    <img src="assets/img/thb_pacujalur.png" class="bento-img" alt="Pacu Jalur">
                    <div class="bento-overlay">
                        <span class="bento-title">Pacu Jalur Game</span>
                        <span class="bento-category">Game Development</span>
                    </div>
                </div>
                <div data-aos="fade-up" data-aos-delay="100" class="area-ovo bento-item group cursor-pointer shadow-sm">
                    <img src="assets/img/thb_home.png" class="bento-img" alt="Home">
                    <div class="bento-overlay">
                        <span class="bento-title">Home Hacks</span>
                        <span class="bento-category">Game Development</span>
                    </div>
                </div>
                <div data-aos="fade-up" data-aos-delay="200" class="area-peruri bento-item group cursor-pointer shadow-sm">
                    <img src="assets/img/thb_edujak.png" class="bento-img" alt="Edujak">
                    <div class="bento-overlay">
                        <span class="bento-title">Edujak</span>
                        <span class="bento-category">Competition</span>
                    </div>
                </div>
                <div data-aos="fade-up" data-aos-delay="300" class="area-burgreens bento-item group cursor-pointer shadow-sm">
                    <img src="assets/img/thb_utt.png" class="bento-img" alt="UTT">
                    <div class="bento-overlay">
                        <span class="bento-title">Uncovering The Truth</span>
                        <span class="bento-category">Game Development</span>
                    </div>
                </div>
                <div data-aos="fade-up" data-aos-delay="400" class="area-visious bento-item group cursor-pointer shadow-sm">
                    <img src="assets/img/thb_wakwek.png" class="bento-img" alt="Wakwek">
                    <div class="bento-overlay">
                        <span class="bento-title">Wak Kwek</span>
                        <span class="bento-category">Game Development</span>
                    </div>
                </div>
                <div data-aos="fade-up" data-aos-delay="500" class="area-digital bento-item group cursor-pointer shadow-sm">
                    <img src="assets/img/thb_jtn.png" class="bento-img" alt="Journey">
                    <div class="bento-overlay">
                        <span class="bento-title">Journey To NanaLand</span>
                        <span class="bento-category">Competition</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-24 overflow-hidden" style="background-color: #F9A21F;">
        <div class="px-8 md:px-24 lg:px-64">
            <div class="mb-16 flex flex-col items-center text-center" data-aos="fade-up">
                <h2 class="text-xs font-bold tracking-[0.5em] text-white/80 uppercase mb-3">What They Say</h2>
                <h3 class="text-4xl md:text-5xl font-black uppercase tracking-tighter text-white">Client Stories</h3>
                <div class="h-1.5 w-24 bg-white mt-6"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div data-aos="fade-up" data-aos-delay="100" class="bg-white p-10 flex flex-col justify-between h-full shadow-xl">
                    <div>
                        <span class="text-6xl font-serif text-[#F9A21F] leading-none">“</span>
                        <p class="text-gray-800 text-lg leading-relaxed font-medium mt-4">Dang Creative Media berhasil mengubah visi kaku perusahaan kami menjadi pengalaman gamifikasi yang sangat interaktif.</p>
                    </div>
                    <div class="mt-10 pt-6 border-t border-gray-100">
                        <h4 class="font-bold text-black uppercase tracking-widest text-sm">Budi Santoso</h4>
                        <p class="text-gray-400 text-xs mt-1 uppercase">Marketing Director — Gojek</p>
                    </div>
                </div>
                <div data-aos="fade-up" data-aos-delay="100" class="bg-white p-10 flex flex-col justify-between h-full shadow-xl">
                    <div>
                        <span class="text-6xl font-serif text-[#F9A21F] leading-none">“</span>
                        <p class="text-gray-800 text-lg leading-relaxed font-medium mt-4">Dang Creative Media berhasil mengubah visi kaku perusahaan kami menjadi pengalaman gamifikasi yang sangat interaktif.</p>
                    </div>
                    <div class="mt-10 pt-6 border-t border-gray-100">
                        <h4 class="font-bold text-black uppercase tracking-widest text-sm">Budi Santoso</h4>
                        <p class="text-gray-400 text-xs mt-1 uppercase">Marketing Director — Gojek</p>
                    </div>
                </div>
                <div data-aos="fade-up" data-aos-delay="100" class="bg-white p-10 flex flex-col justify-between h-full shadow-xl">
                    <div>
                        <span class="text-6xl font-serif text-[#F9A21F] leading-none">“</span>
                        <p class="text-gray-800 text-lg leading-relaxed font-medium mt-4">Dang Creative Media berhasil mengubah visi kaku perusahaan kami menjadi pengalaman gamifikasi yang sangat interaktif.</p>
                    </div>
                    <div class="mt-10 pt-6 border-t border-gray-100">
                        <h4 class="font-bold text-black uppercase tracking-widest text-sm">Budi Santoso</h4>
                        <p class="text-gray-400 text-xs mt-1 uppercase">Marketing Director — Gojek</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-32 px-8 md:px-24 lg:px-64 bg-white border-t border-gray-50">
        <div class="flex justify-between items-end mb-16" data-aos="fade-up">
            <h3 class="text-5xl font-bold tracking-tighter uppercase">Newsroom</h3>
            <a href="#" class="font-bold text-xs tracking-widest border-b-2 border-black pb-2 hover:opacity-50 transition">VIEW ALL</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-16">
            <article data-aos="fade-up" class="group cursor-pointer">
                <div class="h-80 bg-gray-100 mb-8 rounded-none flex items-center justify-center overflow-hidden">
                    <img src="assets/img/thb_jtn.png" class="w-full h-full object-cover transition-all duration-700 group-hover:scale-105" alt="JTN">
                </div>
                <h4 class="text-2xl font-bold leading-tight group-hover:text-blue-600 transition-colors">Menjelajahi Mekanik Gameplay Baru di Update Winter 2025.</h4>
            </article>
            <article data-aos="fade-up" data-aos-delay="200" class="group cursor-pointer">
                <div class="h-80 bg-gray-100 mb-8 rounded-none flex items-center justify-center overflow-hidden">
                    <img src="assets/img/thb_us.png" class="w-full h-full object-cover transition-all duration-700 group-hover:scale-105" alt="US">
                </div>
                <h4 class="text-2xl font-bold leading-tight group-hover:text-blue-600 transition-colors">Bagaimana Tim Seni Kami Membangun Kota Masa Depan.</h4>
            </article>
        </div>
    </section>

    <script src="https://unpkg.com/aos@2.3.1/dist/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000,
            once: true
        });
        const cursor = document.getElementById('cursor');
        document.addEventListener('mousemove', e => {
            cursor.style.left = e.clientX + 'px';
            cursor.style.top = e.clientY + 'px';
        });
        document.querySelectorAll('.group, a, img').forEach(el => {
            el.addEventListener('mouseenter', () => cursor.style.transform = 'translate(-50%, -50%) scale(2)');
            el.addEventListener('mouseleave', () => cursor.style.transform = 'translate(-50%, -50%) scale(1)');
        });
    </script>
</body>

</html>