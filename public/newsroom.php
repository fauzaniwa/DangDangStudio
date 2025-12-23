<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Newsroom — Dang Creative Media</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'studio-orange': '#F9A21F',
                        'studio-teal': '#00A19D'
                    }
                }
            }
        }
    </script>
    <link href="https://unpkg.com/aos@2.3.1/dist/dist/aos.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #fff;
            color: #000;
        }

        #cursor {
            width: 40px;
            height: 40px;
            border: 1px solid rgba(0, 0, 0, 0.2);
            border-radius: 50%;
            position: fixed;
            pointer-events: none;
            z-index: 9999;
            transition: transform 0.2s ease-out;
            transform: translate(-50%, -50%);
        }

        /* Thumbnail Hover Effect */
        .news-card .img-wrapper {
            overflow: hidden;
            position: relative;
            aspect-ratio: 16/9;
            margin-bottom: 1.5rem;
        }

        .news-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .news-card:hover img {
            transform: scale(1.05);
        }
    </style>
</head>

<body class="overflow-x-hidden">
    <div id="cursor" class="hidden md:block"></div>

    <section class="min-h-screen w-full flex flex-col justify-center px-8 md:px-24 lg:px-64 relative overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img src="assets/img/thb_pacujalur.png" alt="Landing" class="w-full h-full object-cover opacity-100">
            <div class="absolute inset-0 bg-black/40"></div>
        </div>
        <div class="max-w-4xl relative z-10">
            <h2 data-aos="fade-up" class="text-xs font-bold tracking-[0.4em] text-gray-400 uppercase mb-4">OUR ARTICLE</h2>
            <h1 data-aos="fade-up" class="text-6xl md:text-8xl font-extrabold tracking-tighter leading-[0.9] mb-12 uppercase text-white">NEWS<br>ROOM</h1>
            <div data-aos="fade-up" data-aos-delay="300" class="pt-12 border-t border-white/20">
                <p class="text-lg font-semibold text-white leading-snug italic">Discover captivating worlds and embark on epic adventures with Aang.</p>
            </div>
        </div>
    </section>

    <section class="bg-white text-black px-8 md:px-24 lg:px-64 py-32">

        <div class="max-w-7xl mx-auto">
            <h1 class="text-5xl md:text-3xl font-bold tracking-tighter uppercase mb-2 leading-none">
                HIGHLIGHT POST
            </h1>
            <div class="w-full aspect-[16/8] overflow-hidden mb-6" data-aos="zoom-out">
                <img src="assets/img/thb_us.png"
                    class="w-full h-full object-cover transition-transform duration-700 hover:scale-105"
                    alt="Featured Post"
                    onerror="this.src='https://via.placeholder.com/1200x600'">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-start pb-8">
                <div data-aos="fade-right">
                    <h1 class="text-5xl md:text-7xl font-bold tracking-tighter uppercase mb-8 leading-none">
                        Making Our Mark
                    </h1>
                    <div class="flex items-center gap-4">
                        <span class="text-xs font-black uppercase tracking-widest text-black">STUDIO LIFE</span>
                        <span class="text-xs text-gray-500 uppercase">Dec 16, 2025 by Kireina Masri</span>
                    </div>
                </div>

                <div class="space-y-6" data-aos="fade-left">
                    <p class="text-gray-400 text-sm leading-relaxed max-w-md">
                        Chronicling a 20-year creative legacy and the days leading up to putting it out there for the rest of the world to see.
                    </p>
                    <a href="#" class="inline-block text-studio-orange font-bold uppercase tracking-widest text-xs border-b border-studio-orange pb-1 hover:text-white hover:border-white transition">
                        Read More
                    </a>
                </div>
            </div>

            <div id="news-grid" class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-20">

                <div class="news-card group" data-aos="fade-up">
                    <div class="img-wrapper">
                        <img src="assets/img/thb_us.png" alt="News 1" onerror="this.src='https://via.placeholder.com/800x450'">
                    </div>
                    <div class="flex items-center gap-4 mb-3">
                        <span class="text-xs font-black uppercase tracking-widest text-black">STUDIO LIFE</span>
                        <span class="text-xs text-gray-500 uppercase">Dec 16, 2025</span>
                    </div>
                    <h3 class="text-3xl font-bold tracking-tight mb-3 group-hover:text-studio-orange transition-colors">Making Our Mark</h3>
                    <p class="text-gray-500 italic text-sm">by Kireina Masri</p>
                </div>

                <div class="news-card group" data-aos="fade-up" data-aos-delay="200">
                    <div class="img-wrapper">
                        <img src="assets/img/thb_us.png" alt="News 2" onerror="this.src='https://via.placeholder.com/800x450'">
                    </div>
                    <div class="flex items-center gap-4 mb-3">
                        <span class="text-xs font-black uppercase tracking-widest text-black">GAME DEV</span>
                        <span class="text-xs text-gray-500 uppercase">Dec 10, 2025</span>
                    </div>
                    <h3 class="text-3xl font-bold tracking-tight mb-3 group-hover:text-studio-orange transition-colors">Behind The Scenes: Midnight at The Museum</h3>
                    <p class="text-gray-500 italic text-sm">by Aang</p>
                </div>

                <div class="news-card group" data-aos="fade-up">
                    <div class="img-wrapper">
                        <img src="assets/img/thb_us.png" alt="News 3" onerror="this.src='https://via.placeholder.com/800x450'">
                    </div>
                    <div class="flex items-center gap-4 mb-3">
                        <span class="text-xs font-black uppercase tracking-widest text-black">EVENTS</span>
                        <span class="text-xs text-gray-500 uppercase">Nov 28, 2025</span>
                    </div>
                    <h3 class="text-3xl font-bold tracking-tight mb-3 group-hover:text-studio-orange transition-colors">Journey to Nanoland Exhibition</h3>
                    <p class="text-gray-500 italic text-sm">by Member 5</p>
                </div>

                <div class="news-card group" data-aos="fade-up" data-aos-delay="200">
                    <div class="img-wrapper">
                        <img src="assets/img/thb_us.png" alt="News 4" onerror="this.src='https://via.placeholder.com/800x450'">
                    </div>
                    <div class="flex items-center gap-4 mb-3">
                        <span class="text-xs font-black uppercase tracking-widest text-black">MERCHANDISE</span>
                        <span class="text-xs text-gray-500 uppercase">Nov 15, 2025</span>
                    </div>
                    <h3 class="text-3xl font-bold tracking-tight mb-3 group-hover:text-studio-orange transition-colors">Limited Artbook Release</h3>
                    <p class="text-gray-500 italic text-sm">by Art Director</p>
                </div>

            </div>
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
    </script>
</body>

</html>