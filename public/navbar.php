<!doctype html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        #full-menu {
            transition: transform 0.6s cubic-bezier(0.85, 0, 0.15, 1);
        }

        .mobile-link:hover {
            font-style: italic;
            padding-right: 10px;
            transition: all 0.3s ease;
        }
    </style>
</head>

<body class="bg-white">

    <nav class="fixed top-0 left-0 w-full z-50 bg-white/90 backdrop-blur-md border-b border-gray-50">
        <div class="max-w-7xl mx-auto px-6 h-20 flex justify-between items-center">
            <div class="z-50 flex items-center">
                <a href="#">
                    <img src="assets/img/logo.png" alt="Logo" class="h-6 w-auto object-contain">
                </a>
            </div>

            <div class="hidden md:flex items-center space-x-10">
                <a href="#" class="text-sm font-medium text-black hover:text-gray-400 transition">HOME</a>
                <a href="#" class="text-sm font-medium text-black hover:text-gray-400 transition">WORKS</a>
                <a href="#" class="text-sm font-medium text-black hover:text-gray-400 transition">ABOUT</a>
                <a href="#" class="text-sm font-medium text-black hover:text-gray-400 transition">NEWSROOM</a>
                <a href="#" class="text-sm font-medium text-black hover:text-gray-400 transition">CONTACT</a>
            </div>

            <button id="menu-btn" class="md:hidden z-50 group focus:outline-none cursor-pointer p-2">
                <div id="line1" class="w-8 h-0.5 bg-black mb-1.5 transition-all duration-300"></div>
                <div id="line2" class="w-8 h-0.5 bg-black transition-all duration-300"></div>
            </button>
        </div>
    </nav>

    <div id="full-menu" class="fixed inset-0 bg-white z-40 flex flex-col justify-between p-10 translate-y-[-100%] md:hidden shadow-2xl">
        <div class="h-20"></div>
        <div class="flex flex-col space-y-4 items-end">
            <a href="#" class="mobile-link text-5xl font-medium text-black">HOME</a>
            <a href="#" class="mobile-link text-5xl font-medium text-black">WORKS</a>
            <a href="#" class="mobile-link text-5xl font-medium text-black">ABOUT</a>
            <a href="#" class="mobile-link text-5xl font-medium text-black">NEWSROOM</a>
            <a href="#" class="mobile-link text-5xl font-medium text-black">CONTACT</a>
        </div>
        <div class="flex flex-col justify-between items-end pt-8 border-t border-gray-100">
            <div class="text-[10px] sm:text-xs text-gray-500 text-right mb-6 leading-relaxed uppercase tracking-widest">
                PT. Visindo Graphics ID<br>Jl. Rindang No.4, Cipedak Jagakarsa,<br>Jakarta Indonesia 12630.
            </div>
            <div class="flex space-x-6 text-sm font-semibold underline decoration-1 underline-offset-4">
                <a href="#" class="hover:text-gray-400">Instagram</a>
                <a href="#" class="hover:text-gray-400">Behance</a>
            </div>
        </div>
    </div>

    <main class="min-h-screen flex flex-col items-center justify-center p-6 text-center">
        <h1 class="text-4xl md:text-6xl font-bold mb-4 italic text-gray-800">Navbar Ready!</h1>
        <p class="text-gray-500 max-w-md">Jika logo tidak muncul, cek apakah file gambar di <b>/assets/img/logo.png</b> sudah benar jalurnya.</p>
    </main>

    <script>
        const menuBtn = document.getElementById('menu-btn');
        const fullMenu = document.getElementById('full-menu');
        const line1 = document.getElementById('line1');
        const line2 = document.getElementById('line2');
        let isOpen = false;

        menuBtn.addEventListener('click', () => {
            isOpen = !isOpen;
            if (isOpen) {
                fullMenu.style.transform = "translateY(0)";
                line1.classList.add('rotate-45', 'translate-y-2');
                line2.classList.add('-rotate-45');
                document.body.style.overflow = 'hidden';
            } else {
                fullMenu.style.transform = "translateY(-100%)";
                line1.classList.remove('rotate-45', 'translate-y-2');
                line2.classList.remove('-rotate-45');
                document.body.style.overflow = 'auto';
            }
        });

        window.addEventListener('resize', () => {
            if (window.innerWidth >= 768 && isOpen) {
                isOpen = false;
                fullMenu.style.transform = "translateY(-100%)";
                line1.classList.remove('rotate-45', 'translate-y-2');
                line2.classList.remove('-rotate-45');
                document.body.style.overflow = 'auto';
            }
        });
    </script>
</body>

</html>