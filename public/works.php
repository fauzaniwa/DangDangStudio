<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Works — Dang Creative Media</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'studio-orange': '#F9A21F'
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
            background-color: #000;
            color: #fff;
        }

        .full-page-section {
            height: 100vh;
            width: 100%;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .bg-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1;
        }

        .bg-image {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: 0;
            transition: transform 0.8s ease;
        }

        .full-page-section:hover .bg-image {
            transform: scale(1.05);
        }

        .content-box {
            position: relative;
            z-index: 2;
            text-align: center;
            max-width: 800px;
            padding: 0 2rem;
        }

        #cursor {
            width: 40px;
            height: 40px;
            border: 1px solid rgba(255, 255, 255, 0.4);
            border-radius: 50%;
            position: fixed;
            pointer-events: none;
            z-index: 9999;
            transition: transform 0.2s ease-out;
            transform: translate(-50%, -50%);
        }
    </style>
</head>

<body>
    <div id="cursor" class="hidden md:block"></div>

    <main class="snap-y snap-mandatory h-screen overflow-y-scroll">

        <section class="full-page-section snap-start">
            <img src="assets/img/thb_midnight.png" class="bg-image" alt="Game 1">
            <div class="bg-overlay"></div>
            <div class="content-box">
                <h2 data-aos="fade-down" class="text-xs font-bold tracking-[0.5em] text-studio-orange uppercase mb-4">Adventure / RPG</h2>
                <h1 data-aos="fade-up" class="text-5xl md:text-8xl font-black uppercase tracking-tighter leading-none mb-6">Midnight at<br>The Museum</h1>
                <p data-aos="fade-up" data-aos-delay="200" class="text-sm md:text-base opacity-70 font-light tracking-wide mb-10 max-w-xl mx-auto">
                    Explore the mystery behind the ancient artifacts that come to life when the clock strikes twelve.
                </p>
                <div data-aos="zoom-in" data-aos-delay="400">
                    <button class="group flex items-center justify-center gap-4 mx-auto">
                        <div class="px-4 py-2 md:px-8 :py-4 rounded-full border border-white flex items-center justify-center group-hover:bg-white group-hover:text-black transition-all">
                            <span class="text-l md:text-xl">▶ Play Game</span>
                        </div>
                    </button>
                </div>
            </div>
        </section>

        <section class="full-page-section snap-start">
            <img src="assets/img/thb_jtn.png" class="bg-image" alt="Game 1">
            <div class="bg-overlay"></div>
            <div class="content-box">
                <h2 data-aos="fade-down" class="text-xs font-bold tracking-[0.5em] text-studio-orange uppercase mb-4">Adventure / RPG</h2>
                <h1 data-aos="fade-up" class="text-5xl md:text-8xl font-black uppercase tracking-tighter leading-none mb-6">Journey To<br>The Nanaland</h1>
                <p data-aos="fade-up" data-aos-delay="200" class="text-sm md:text-base opacity-70 font-light tracking-wide mb-10 max-w-xl mx-auto">
                    Explore the mystery behind the ancient artifacts that come to life when the clock strikes twelve.
                </p>
                <div data-aos="zoom-in" data-aos-delay="400">
                    <button class="group flex items-center justify-center gap-4 mx-auto">
                        <div class="px-4 py-2 md:px-8 :py-4 rounded-full border border-white flex items-center justify-center group-hover:bg-white group-hover:text-black transition-all">
                            <span class="text-l md:text-xl">▶ Play Game</span>
                        </div>
                    </button>
                </div>
            </div>
        </section>



    </main>

    <script src="https://unpkg.com/aos@2.3.1/dist/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000
        });
        const cursor = document.getElementById('cursor');
        document.addEventListener('mousemove', e => {
            cursor.style.left = e.clientX + 'px';
            cursor.style.top = e.clientY + 'px';
        });
        // Hover effect for interactive elements
        document.querySelectorAll('button').forEach(el => {
            el.addEventListener('mouseenter', () => cursor.style.transform = 'translate(-50%, -50%) scale(2)');
            el.addEventListener('mouseleave', () => cursor.style.transform = 'translate(-50%, -50%) scale(1)');
        });
    </script>
</body>

</html>