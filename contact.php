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
                        'studio-teal': '#00A19D', // Warna tombol send dari referensi lo
                        'studio-blue': '#323B75'
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

        /* Input styling agar clean sesuai referensi */
        .form-input {
            width: 100%;
            border: 1px solid #e5e7eb;
            padding: 0.75rem 1rem;
            border-radius: 0.375rem;
            outline: none;
            transition: border-color 0.3s;
            color: #000;
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
    <div id="cursor" class="hidden md:block"></div>
    <section class="min-h-screen w-full flex flex-col justify-center px-8 md:px-24 lg:px-64 py-20 relative overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img src="assets/img/landing_page.png" alt="Landing Background" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-white/30"></div>
        </div>
        <div class="max-w-4xl relative z-10">
            <h2 data-aos="fade-up" class="text-xs font-bold tracking-[0.4em] text-gray-500 uppercase mb-4">GET IN TOUCH</h2>
            <h1 data-aos="fade-up" class="text-6xl md:text-8xl font-extrabold tracking-tighter leading-[0.9] mb-12 uppercase text-black">LET'S<br>COLLABORATE</h1>
        </div>
    </section>

    <section class="py-24 flex items-center justify-center bg-white border-y border-gray-100">
        <div class="px-8 text-center" data-aos="zoom-in">
            <h2 class="text-5xl md:text-7xl font-black tracking-tighter text-studio-blue uppercase italic">
                Ready To Work With Us?
            </h2>
            <div class="h-1 w-20 bg-studio-blue mx-auto mt-8"></div>
        </div>
    </section>

    <section class="bg-studio-orange text-white px-8 md:px-24 lg:px-64 py-32 border-t border-white/10">
        <div class="max-w-7xl mx-auto">

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-20 mb-40">
                <div>
                    <h3 data-aos="fade-right" class="text-5xl md:text-6xl font-bold tracking-tighter uppercase mb-8">Contact</h3>
                </div>

                <div class="space-y-16">
                    <div data-aos="zoom-in" class="bg-white p-10 rounded-xl text-black">
                        <form action="#" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-widest mb-2 text-gray-500">Name</label>
                                    <input type="text" placeholder="Your Name" class="form-input">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-widest mb-2 text-gray-500">E-mail</label>
                                    <input type="email" placeholder="Your Email" class="form-input">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-widest mb-2 text-gray-500">Title</label>
                                <input type="text" placeholder="Subject Title" class="form-input">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-widest mb-2 text-gray-500">Message</label>
                                <textarea placeholder="Your message here..." rows="4" class="form-input resize-none"></textarea>
                            </div>
                            <div class="flex justify-end pt-4">
                                <button type="submit" class="bg-studio-teal hover:bg-opacity-90 text-white px-8 py-3 rounded-full font-bold transition-all flex items-center gap-2 group">
                                    Send <span class="group-hover:translate-x-1 transition-transform">→</span>
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 text-white">
                        <div data-aos="fade-up">
                            <p class="text-white text-xs font-bold tracking-widest uppercase mb-2">New project ↘</p>
                            <a href="mailto:info@dangcreative.co" class="contact-link text-xl">dangdangstudios<br>@gmail.com</a>
                        </div>
                        <div data-aos="fade-up" data-aos-delay="100">
                            <p class="text-white text-xs font-bold tracking-widest uppercase mb-2">Project Manager ↘</p>
                            <a href="tel:+62817539995" class="contact-link text-xl">IWa: +62 817 53 999 5</a>
                        </div>
                    </div>
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