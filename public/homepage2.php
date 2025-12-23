<?php
// homepage.php - Lokasi: /public/homepage.php
require_once '../admin/process/config.php';

// Data Fetching dengan Fallback jika query gagal/kosong
function fetchSafe($conn, $query) {
    $res = mysqli_query($conn, $query);
    return ($res && mysqli_num_rows($res) > 0) ? $res : false;
}

$q_games = fetchSafe($conn, "SELECT * FROM games ORDER BY created_at DESC LIMIT 3");
$q_articles = fetchSafe($conn, "SELECT * FROM articles ORDER BY created_at DESC LIMIT 3");
$q_partners = fetchSafe($conn, "SELECT * FROM partners ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DangDang Studio | The 2025 Experience</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brandDark: '#333A73',
                        brandCoral: '#FF6136',
                        brandTeal: '#019E9A',
                        brandGold: '#FEA302',
                    },
                    borderRadius: { '4xl': '2rem', '5xl': '3.5rem', }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; color: #333A73; overflow-x: hidden; }
        .bento-card { background: white; border: 1px solid rgba(51, 58, 115, 0.05); transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
        .bento-card:hover { transform: translateY(-10px); box-shadow: 0 40px 80px -20px rgba(51, 58, 115, 0.1); border-color: #019E9A; }
        .nav-glass { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.3); }
        
        /* Marquee Animation */
        @keyframes marquee {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        .marquee-wrapper { display: flex; width: max-content; animation: marquee 30s linear infinite; }
        .marquee-wrapper:hover { animation-play-state: paused; }
    </style>
</head>
<body class="antialiased">

    <nav class="fixed w-full z-50 px-4 md:px-6 py-4 md:py-6">
        <div class="max-w-7xl mx-auto nav-glass px-6 md:px-8 py-4 md:py-5 rounded-full flex justify-between items-center shadow-sm">
            <div class="text-xl md:text-2xl font-extrabold tracking-tighter flex items-center gap-2">
                <span class="w-3 h-3 md:w-4 md:h-4 bg-brandCoral rounded-full"></span>
                DANGDANG<span class="text-brandTeal italic">STUDIO</span>
            </div>
            <div class="hidden lg:flex gap-10 text-[11px] font-black uppercase tracking-[0.2em] text-brandDark/60">
                <a href="#about" class="hover:text-brandTeal transition">About</a>
                <a href="#games" class="hover:text-brandTeal transition">Games</a>
                <a href="#articles" class="hover:text-brandTeal transition">Insight</a>
                <a href="#contact" class="hover:text-brandTeal transition">Contact</a>
            </div>
            <a href="../admin/login.php" class="bg-brandDark text-white px-5 md:px-6 py-2 md:py-2.5 rounded-full text-[9px] md:text-[10px] font-bold uppercase tracking-widest hover:bg-brandTeal transition shadow-lg">Portal</a>
        </div>
    </nav>

    <section class="pt-32 md:pt-44 pb-20 px-6">
        <div class="max-w-7xl mx-auto grid lg:grid-cols-2 gap-12 md:gap-16 items-center">
            <div class="space-y-6 md:space-y-8 text-center lg:text-left">
                <div class="inline-flex items-center gap-3 bg-white px-5 py-2 rounded-full shadow-sm border border-slate-100">
                    <span class="flex h-2 w-2 rounded-full bg-brandTeal animate-pulse"></span>
                    <span class="text-[9px] md:text-[10px] font-black uppercase tracking-widest text-slate-400 italic">2025 Visionary Studio</span>
                </div>
                <h1 class="text-5xl md:text-7xl lg:text-[100px] font-extrabold leading-[0.9] md:leading-[0.85] tracking-tighter text-brandDark uppercase italic">
                    Beyond <br> <span class="text-brandCoral">Imagination.</span>
                </h1>
                <p class="text-base md:text-lg text-slate-500 max-w-md mx-auto lg:mx-0 font-medium leading-relaxed">
                    Kami mendefinisikan ulang cara dunia bermain melalui desain yang elegan dan teknologi masa depan.
                </p>
                <div class="flex flex-wrap justify-center lg:justify-start gap-4">
                    <a href="#contact" class="bg-brandCoral text-white px-8 md:px-10 py-4 md:py-5 rounded-full font-black uppercase text-[10px] md:text-xs tracking-widest shadow-2xl shadow-brandCoral/30 hover:scale-105 transition transform">Start Project</a>
                    <a href="#games" class="bg-white text-brandDark border border-slate-100 px-8 md:px-10 py-4 md:py-5 rounded-full font-black uppercase text-[10px] md:text-xs tracking-widest hover:bg-slate-50 transition">Portfolio</a>
                </div>
            </div>
            <div class="relative mt-10 lg:mt-0">
                <div class="aspect-square bg-brandTeal rounded-4xl md:rounded-5xl overflow-hidden rotate-2 md:rotate-3 shadow-2xl">
                    <img src="https://studiodangdang.com/public/assets/img/thb_pacujalur.png" class="w-full h-full object-cover grayscale hover:grayscale-0 transition duration-1000" alt="Creative Visual">
                </div>
                <div class="absolute -bottom-6 -left-6 md:-bottom-10 md:-left-10 bg-brandGold p-6 md:p-10 rounded-3xl md:rounded-4xl shadow-2xl">
                    <p class="text-3xl md:text-4xl font-black italic leading-none">100%</p>
                    <p class="text-[8px] md:text-[10px] font-bold uppercase tracking-widest opacity-80 mt-1 md:mt-2">Creative Freedom</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-12 md:py-20 bg-white border-y border-slate-50 overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 mb-8 md:mb-12 text-center lg:text-left">
            <h2 class="text-[10px] font-black uppercase tracking-[0.4em] text-slate-300 italic">Trusted Collaborations</h2>
        </div>
        <div class="relative flex overflow-x-hidden">
            <div class="marquee-wrapper gap-12 md:gap-24 items-center px-6 md:px-12">
                <?php if($q_partners): while($p = mysqli_fetch_assoc($q_partners)): ?>
                    <img src="../admin/uploads/partners/<?= $p['partner_logo'] ?>" class="h-10 md:h-14 grayscale opacity-30 hover:opacity-100 hover:grayscale-0 transition cursor-pointer" alt="<?= $p['partner_name'] ?>">
                <?php endwhile; else: ?>
                    <img src="https://upload.wikimedia.org/wikipedia/commons/b/bb/Gojek_logo_2019.svg" class="h-8 md:h-10 grayscale opacity-30" alt="Client">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/a/a6/Logo_NIKE.svg" class="h-8 md:h-10 grayscale opacity-30" alt="Client">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/2/20/Adidas_Logo.svg" class="h-8 md:h-10 grayscale opacity-30" alt="Client">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/5/53/Google_2015_logo.svg" class="h-8 md:h-10 grayscale opacity-30" alt="Client">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/b/bb/Gojek_logo_2019.svg" class="h-8 md:h-10 grayscale opacity-30" alt="Client">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/a/a6/Logo_NIKE.svg" class="h-8 md:h-10 grayscale opacity-30" alt="Client">
                <?php endif; ?>
            </div>
            <div class="marquee-wrapper gap-12 md:gap-24 items-center px-6 md:px-12" aria-hidden="true">
                <img src="https://upload.wikimedia.org/wikipedia/commons/b/bb/Gojek_logo_2019.svg" class="h-8 md:h-10 grayscale opacity-30" alt="Client">
                <img src="https://upload.wikimedia.org/wikipedia/commons/a/a6/Logo_NIKE.svg" class="h-8 md:h-10 grayscale opacity-30" alt="Client">
                <img src="https://upload.wikimedia.org/wikipedia/commons/2/20/Adidas_Logo.svg" class="h-8 md:h-10 grayscale opacity-30" alt="Client">
                <img src="https://upload.wikimedia.org/wikipedia/commons/5/53/Google_2015_logo.svg" class="h-8 md:h-10 grayscale opacity-30" alt="Client">
                <img src="https://upload.wikimedia.org/wikipedia/commons/b/bb/Gojek_logo_2019.svg" class="h-8 md:h-10 grayscale opacity-30" alt="Client">
                <img src="https://upload.wikimedia.org/wikipedia/commons/a/a6/Logo_NIKE.svg" class="h-8 md:h-10 grayscale opacity-30" alt="Client">
            </div>
        </div>
    </section>

    <section id="about" class="py-24 md:py-32 px-6 bg-slate-50">
        <div class="max-w-7xl mx-auto">
            <div class="grid lg:grid-cols-3 gap-6 md:gap-8">
                <div class="lg:col-span-2 bento-card p-10 md:p-16 rounded-4xl md:rounded-5xl flex flex-col justify-end min-h-[350px] md:min-h-[400px]">
                    <h2 class="text-3xl md:text-4xl font-black mb-4 md:mb-6 uppercase italic leading-none">Kami adalah simfoni antara <span class="text-brandTeal underline">seni</span> & <span class="text-brandCoral italic">kode</span>.</h2>
                    <p class="text-slate-500 font-medium text-sm md:text-base">DangDang Studio bukan sekadar developer. Kami adalah arsitek pengalaman digital yang berbasis di Indonesia, melayani pasar global dengan standar kualitas 2025.</p>
                </div>
                <div class="bg-brandDark p-10 md:p-16 rounded-4xl md:rounded-5xl text-white flex flex-col justify-between">
                    <div class="w-10 h-10 md:w-12 md:h-12 bg-brandGold rounded-2xl mb-10"></div>
                    <div>
                        <p class="text-4xl md:text-5xl font-black italic mb-2">50+</p>
                        <p class="text-[10px] md:text-xs uppercase font-bold tracking-[0.2em] opacity-60 italic">World-class Clients</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="games" class="py-24 md:py-32 px-6">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-16 md:mb-20 gap-4 md:gap-6">
                <h2 class="text-5xl md:text-6xl font-black italic uppercase tracking-tighter">Selected <br><span class="text-brandTeal">Games.</span></h2>
                <div class="h-[1px] flex-1 bg-slate-200 mx-0 md:mx-10 w-full md:w-auto"></div>
                <p class="text-[10px] md:text-[11px] font-black uppercase tracking-widest text-slate-400">Library 2024-2025</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8 md:gap-10">
                <?php if($q_games): while($g = mysqli_fetch_assoc($q_games)): ?>
                <div class="group cursor-pointer">
                    <div class="aspect-[4/5] rounded-4xl md:rounded-5xl overflow-hidden mb-6 md:mb-8 bento-card shadow-lg">
                        <img src="../uploads/game/<?= $g['header_image'] ?>" onerror="this.src='https://images.unsplash.com/photo-1542751371-adc38448a05e?q=80&w=600'" class="w-full h-full object-cover group-hover:scale-110 transition duration-700" alt="<?= $g['title'] ?>">
                    </div>
                    <h3 class="text-xl font-black uppercase italic mb-1 md:mb-2"><?= $g['title'] ?></h3>
                    <p class="text-[10px] md:text-xs font-bold text-brandTeal uppercase tracking-widest"><?= $g['category'] ?></p>
                </div>
                <?php endwhile; endif; ?>
            </div>
        </div>
    </section>

    <section id="articles" class="py-24 md:py-32 px-6 bg-brandDark text-white rounded-t-[3rem] md:rounded-t-[5rem]">
        <div class="max-w-7xl mx-auto">
            <h2 class="text-3xl md:text-4xl font-black mb-12 md:mb-16 uppercase italic text-center md:text-left">Latest Insight.</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8 md:gap-10">
                <?php if($q_articles): while($art = mysqli_fetch_assoc($q_articles)): ?>
                <div class="space-y-4 md:space-y-6 group">
                    <div class="aspect-video bg-white/5 rounded-3xl md:rounded-4xl overflow-hidden border border-white/10 shadow-inner">
                         <img src="../uploads/articles/<?= $art['cover_image'] ?>" class="w-full h-full object-cover opacity-60 group-hover:opacity-100 group-hover:scale-105 transition duration-500" alt="<?= $art['title'] ?>">
                    </div>
                    <div class="px-2">
                        <p class="text-[9px] md:text-[10px] font-black text-brandGold uppercase tracking-widest mb-2"><?= date('M d, Y', strtotime($art['created_at'])) ?></p>
                        <h4 class="text-lg md:text-xl font-bold leading-tight group-hover:text-brandCoral transition"><?= $art['title'] ?></h4>
                    </div>
                </div>
                <?php endwhile; endif; ?>
            </div>
        </div>
    </section>

    <section id="contact" class="py-24 md:py-40 px-6">
        <div class="max-w-5xl mx-auto bg-white p-10 md:p-20 rounded-4xl md:rounded-5xl text-center shadow-2xl relative overflow-hidden border border-slate-50">
            <div class="absolute -top-20 -right-20 w-48 md:w-64 h-48 md:h-64 bg-brandTeal/5 rounded-full"></div>
            <span class="text-brandCoral font-black uppercase text-[10px] md:text-xs tracking-[0.4em] mb-6 md:mb-8 block italic">Ready to play?</span>
            <h2 class="text-5xl md:text-8xl font-black tracking-tighter text-brandDark italic uppercase leading-none mb-10 md:mb-12">Let's <br>talk.</h2>
            <div class="flex flex-col md:flex-row gap-4 md:gap-6 justify-center items-center">
                <a href="mailto:hello@dangdang.com" class="text-xl md:text-3xl font-black italic hover:text-brandTeal transition decoration-brandCoral underline underline-offset-8 decoration-2 md:decoration-4">hello@dangdang.com</a>
                <span class="hidden md:block w-3 h-3 bg-slate-200 rounded-full"></span>
                <a href="#" class="text-xl md:text-3xl font-black italic hover:text-brandCoral transition">@dangdangstudio</a>
            </div>
        </div>
    </section>

    <footer class="py-12 md:py-16 px-6 border-t border-slate-100 bg-white">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-8 md:gap-10">
            <div class="text-lg md:text-xl font-black tracking-tighter italic">DANGDANG<span class="text-brandTeal">STUDIO</span></div>
            <div class="flex gap-6 md:gap-10 text-[8px] md:text-[9px] font-black uppercase tracking-widest text-slate-400">
                <a href="#" class="hover:text-brandDark transition">Terms</a>
                <a href="#" class="hover:text-brandDark transition">Privacy</a>
                <a href="#" class="hover:text-brandDark transition">Cookies</a>
            </div>
            <p class="text-[8px] md:text-[9px] font-black uppercase tracking-widest text-slate-400">© 2025 Creative Department.</p>
        </div>
    </footer>

</body>
</html>