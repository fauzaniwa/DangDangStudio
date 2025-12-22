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
        /* Base: Background Putih, Teks Hitam */
        body {
            font-family: 'Poppins', sans-serif;
            scroll-behavior: smooth;
            background-color: #fff;
            color: #000;
        }

        /* Kursor di-invert: border hitam tipis */
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

        /* BENTO LAYOUT Tetap Sama */
        @media (min-width: 1024px) {
            .bento-grid-custom {
                display: grid !important;
                grid-template-columns: repeat(3, 1fr);
                grid-template-rows: repeat(4, 240px);
                gap: 1.5rem;
                grid-template-areas: "whoosh whoosh ovo" "whoosh whoosh peruri" "burgreens digital digital" "visious digital digital";
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
    </style>
</head>

<body class="overflow-x-hidden">
    <div id="cursor" class="hidden md:block"></div>

    <section class="min-h-screen w-full flex flex-col justify-center px-8 md:px-24 lg:px-64 relative overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img src="assets/img/thb_midnight.png" alt="Landing" class="w-full h-full object-cover opacity-100">
            <div class="absolute inset-0 bg-black/40"></div>
        </div>
        <div class="max-w-4xl relative z-10">
            <h2 data-aos="fade-up" class="text-xs font-bold tracking-[0.4em] text-gray-400 uppercase mb-4">DANG CREATIVE MEDIA</h2>
            <h1 data-aos="fade-up" class="text-6xl md:text-8xl font-extrabold tracking-tighter leading-[0.9] mb-12 uppercase text-white">ABOUT<br>US</h1>
            <div data-aos="fade-up" data-aos-delay="300" class="pt-12 border-t border-white/20">
                <p class="text-lg font-semibold text-white leading-snug italic">Discover captivating worlds and embark on epic adventures with Aang.</p>
            </div>
        </div>
    </section>

    <section class="bg-white flex items-center px-8 md:px-24 lg:px-64 py-32">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-16 md:gap-24 items-start">

            <div data-aos="fade-right">
                <p class="text-2xl md:text-4xl font-light leading-tight text-black">
                    We're a vision-focused creative company. We kicked things off in 2023, and over the past years, we’ve learned how experiences last and developed methods to achieve that.
                </p>
            </div>

            <div class="space-y-12">
                <div data-aos="fade-left" data-aos-delay="200">
                    <p class="text-2xl md:text-4xl font-light leading-tight text-black">
                        We are always looking for the right method to build a strong foundation that is right for you. Shaping and refining it to help you achieve a brand that lasts.
                    </p>
                </div>

                <div data-aos="fade-up" data-aos-delay="400">
                    <a href="#" class="inline-block text-sm font-bold uppercase tracking-widest border-b border-black pb-2 hover:text-gray-400 transition text-black">
                        Read Our Credentials
                    </a>
                </div>
            </div>

        </div>
    </section>

    <section class="bg-white px-8 md:px-24 lg:px-64 py-32 border-t border-black/5">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-20">
            <div class="flex flex-col justify-between h-full">
                <h3 data-aos="fade-right" class="text-6xl font-bold tracking-tighter uppercase text-black">Services</h3>
                <p data-aos="fade-up" class="text-sm text-gray-400 max-w-xs leading-relaxed">
                    We work in diverse mediums. Often collaborating with our colleagues from various disciplines to produce noteworthy outcomes.
                </p>
            </div>
            <div class="space-y-16">
                <div data-aos="fade-up">
                    <h4 class="text-gray-400 uppercase text-xs font-bold tracking-widest mb-6">Interactive Strategy:</h4>
                    <ul class="text-2xl md:text-3xl space-y-3 font-medium text-black">
                        <li class="hover:translate-x-3 transition duration-300">Games Strategy</li>
                        <li class="hover:translate-x-3 transition duration-300">Gamification Flow</li>
                        <li class="hover:translate-x-3 transition duration-300">Interactive DNA</li>
                        <li class="hover:translate-x-3 transition duration-300">Brand Messaging</li>
                    </ul>
                </div>
                <div data-aos="fade-up">
                    <h4 class="text-gray-400 uppercase text-xs font-bold tracking-widest mb-6">Production:</h4>
                    <ul class="text-2xl md:text-3xl space-y-3 font-medium text-black">
                        <li class="hover:translate-x-3 transition duration-300">2D/3D Animation</li>
                        <li class="hover:translate-x-3 transition duration-300">Motion Graphics</li>
                        <li class="hover:translate-x-3 transition duration-300">Cinematic Storytelling</li>
                        <li class="hover:translate-x-3 transition duration-300">Web Development</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section class="py-32 px-8 md:px-24 lg:px-64 bg-white text-black border-t border-gray-100">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-8">
                <div data-aos="fade-right">
                    <h2 class="text-xs font-bold tracking-[0.5em] text-gray-400 uppercase mb-3">Expertise</h2>
                    <h3 class="text-5xl font-black uppercase tracking-tighter">Our Team</h3>
                </div>
                <div class="flex flex-wrap gap-4" data-aos="fade-left">
                    <button onclick="filterTeam('core')" class="team-tab active-tab px-6 py-2 border border-black text-[10px] font-bold uppercase tracking-widest transition-all">Core Team</button>
                    <button onclick="filterTeam('game')" class="team-tab px-6 py-2 border border-gray-200 text-gray-400 text-[10px] font-bold uppercase tracking-widest transition-all hover:border-black hover:text-black">Game Division</button>
                    <button onclick="filterTeam('animation')" class="team-tab px-6 py-2 border border-gray-200 text-gray-400 text-[10px] font-bold uppercase tracking-widest transition-all hover:border-black hover:text-black">Animation Division</button>
                </div>
            </div>
            <div id="team-grid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-10"></div>
        </div>
    </section>

    <style>
        .active-tab {
            background-color: black;
            color: white !important;
            border-color: black !important;
        }

        .member-card {
            position: relative;
            width: 100%;
            aspect-ratio: 4/5;
            overflow: hidden;
            background-color: #f3f3f3;
            border-radius: 0;
        }

        /* Container untuk kontrol zoom */
        .img-container {
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        .member-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: grayscale(100%);
            transition: filter 0.5s ease, transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .member-card:hover .member-img {
            filter: grayscale(0%);
        }

        .member-info {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            padding: 2rem;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.9), transparent);
            opacity: 0;
            transform: translateY(10px);
            transition: all 0.4s ease;
            z-index: 10;
        }

        .member-card:hover .member-info {
            opacity: 1;
            transform: translateY(0);
        }
    </style>

    <script>
        const teamData = {
            core: [{
                    name: "Aang",
                    role: "Founder / Lead",
                    img: "assets/img/team_1.png",
                    scale: "1.15",
                    top: "50%"
                },
                {
                    name: "Member 2",
                    role: "Producer",
                    img: "assets/img/team_2.png",
                    scale: "1.0",
                    top: "-20%"
                },
                {
                    name: "Member 3",
                    role: "Creative Director",
                    img: "assets/img/team_3.png",
                    scale: "1.3",
                    top: "80%"
                },
                {
                    name: "Member 4",
                    role: "Art Director",
                    img: "assets/img/team_4.png",
                    scale: "1.5",
                    top: "20%"
                },
                {
                    name: "Member 5",
                    role: "Operations",
                    img: "assets/img/team_5.png",
                    scale: "1.0",
                    top: "0%"
                }
            ],
            game: [{
                    name: "Game Dev 1",
                    role: "Unity Dev",
                    img: "assets/img/team_g1.png",
                    scale: "1.7",
                    top: "5%"
                },
                {
                    name: "Game Dev 2",
                    role: "Game Designer",
                    img: "assets/img/team_g2.png",
                    scale: "1.0",
                    top: "0%"
                }
            ],
            animation: [{
                    name: "Anim 1",
                    role: "2D Animator",
                    img: "assets/img/team_a1.png",
                    scale: "1.2",
                    top: "10%"
                },
                {
                    name: "Anim 2",
                    role: "3D Modeler",
                    img: "assets/img/team_a2.png",
                    scale: "1.0",
                    top: "0%"
                }
            ]
        };

        function filterTeam(category) {
            const grid = document.getElementById('team-grid');
            const buttons = document.querySelectorAll('.team-tab');
            buttons.forEach(btn => {
                btn.classList.remove('active-tab');
                if (btn.getAttribute('onclick').includes(category)) btn.classList.add('active-tab');
            });

            grid.style.opacity = 0;
            setTimeout(() => {
                grid.innerHTML = '';
                (teamData[category] || []).forEach(m => {
                    grid.innerHTML += `
                <div class="member-card shadow-sm" data-aos="fade-up">
                    <div class="img-container">
                        <img src="${m.img}" 
                             class="member-img" 
                             style="transform: scale(${m.scale || '1'}); object-position: center ${m.top || '0%'};" 
                             alt="${m.name}"
                             onerror="this.src='https://via.placeholder.com/400x500?text=Photo'">
                    </div>
                    <div class="member-info">
                        <h4 class="text-white font-bold uppercase text-base tracking-tighter">${m.name}</h4>
                        <p class="text-white/60 text-[10px] uppercase tracking-[0.2em] mt-2">${m.role}</p>
                    </div>
                </div>`;
                });
                grid.style.opacity = 1;
            }, 300);
        }
        window.onload = () => filterTeam('core');
    </script>

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
        document.querySelectorAll('a, .bento-item, ul li').forEach(el => {
            el.addEventListener('mouseenter', () => {
                cursor.style.transform = 'translate(-50%, -50%) scale(2)';
                cursor.style.backgroundColor = 'rgba(0, 0, 0, 0.05)';
            });
            el.addEventListener('mouseleave', () => {
                cursor.style.transform = 'translate(-50%, -50%) scale(1)';
                cursor.style.backgroundColor = 'transparent';
            });
        });
    </script>
</body>

</html>