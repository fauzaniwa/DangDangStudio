<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Contact — Dang Creative Media</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'studio-orange': '#F9A21F',
                        'studio-blue': '#0055FF',
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
            scroll-behavior: smooth;
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

        .form-input {
            width: 100%;
            border: 1px solid #e5e7eb;
            padding: 0.75rem 1rem;
            border-radius: 0.375rem;
            outline: none;
            transition: border-color 0.3s;
        }

        .form-input:focus {
            border-color: #F9A21F;
        }

        .contact-link {
            position: relative;
            display: inline-block;
            font-weight: 700;
        }

        .contact-link::after {
            content: '';
            position: absolute;
            width: 100%;
            transform: scaleX(1);
            height: 2px;
            bottom: 0;
            left: 0;
            background-color: currentColor;
            transform-origin: bottom right;
            transition: transform 0.4s ease-out;
        }

        .contact-link:hover::after {
            transform: scaleX(0);
            transform-origin: bottom left;
        }
    </style>
</head>

<body class="overflow-x-hidden">

    <footer class="bg-studio-teal text-white py-24 px-8 md:px-24 lg:px-64 border-t border-white/10">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-20">
                <div class="space-y-8">
                    <img src="assets/img/logo_white.png" alt="Logo" class="h-10">
                    <div class="flex gap-4"><img src="assets/img/icon-youtube.png" class="w-6 h-6"><img src="assets/img/icon-ig.png" class="w-6 h-6"><img src="assets/img/icon-tiktok.png" class="w-6 h-6"></div>
                </div>
                <div>
                    <h4 class="font-bold mb-8 uppercase tracking-widest text-sm">Merch</h4>
                    <ul class="space-y-3 text-sm font-medium">
                        <li>Apparel</li>
                        <li>Aksesoris</li>
                        <li>Merchandise Figurin</li>
                        <li>Artbook</li>
                        <li>Collector's Edition</li>
                        <li>Stiker</li>
                        <li>Original Soundtrack</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-8 uppercase tracking-widest text-sm">Our Games</h4>
                    <ul class="space-y-3 text-sm font-medium">
                        <li>Uncovering The Truth</li>
                        <li>Midnight at The Museum</li>
                        <li>Journey to The Nanoland</li>
                        <li>Kwak Kwek</li>
                        <li>Crystal Quest</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-8 uppercase tracking-widest text-sm">Contact Us</h4>
                    <div class="space-y-6 text-sm font-medium">
                        <p>📍 Jl. Gegerkalong Girang, Bandung - Jawa Barat.</p>
                        <p>✉️ support@dangdangstudio.com</p>
                    </div>
                </div>
            </div>
            <div class="pt-8 border-t border-white/20 text-center text-[10px] font-bold tracking-[0.4em] uppercase">©2026 - DangDang</div>
        </div>
    </footer>

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