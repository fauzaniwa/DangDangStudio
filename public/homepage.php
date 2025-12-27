<?php
// homepage.php - Lokasi: /public/homepage.php
require_once '../admin/process/config.php';

// Data Fetching dengan Fallback jika query gagal/kosong
function fetchSafe($conn, $query)
{
    $res = mysqli_query($conn, $query);
    return ($res && mysqli_num_rows($res) > 0) ? $res : false;
}

$q_games = fetchSafe($conn, "SELECT * FROM games ORDER BY created_at DESC LIMIT 3");
$q_articles = mysqli_query($conn, "SELECT * FROM articles WHERE status = 'published' ORDER BY created_at DESC LIMIT 3");
$q_partners = fetchSafe($conn, "SELECT * FROM partners ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DangDang Studio | Animation & Game Experience</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,700;0,800;1,800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="assets/js/tailwind.config.js"></script>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body class="antialiased bg-mesh">

    <?php include_once '_navbar.php'; ?>

    <section id="header" class="relative pt-32 pb-20 md:pt-48 md:pb-32 px-6 overflow-hidden bg-white">
        <div class="absolute inset-0 z-0 opacity-[0.03]" style="background-image: radial-gradient(#333A73 1px, transparent 1px); background-size: 30px 30px;"></div>
        <div class="absolute -top-24 -right-24 w-64 h-64 bg-[#019E9A]/10 rounded-full blur-3xl"></div>

        <div class="max-w-7xl mx-auto relative z-10">
            <div class="grid lg:grid-cols-12 gap-12 items-center">
                
                <div class="lg:col-span-7 order-2 lg:order-1 text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 bg-slate-50 px-4 py-2 rounded-full border border-slate-100 mb-6 md:mb-8">
                        <span class="flex h-2 w-2 rounded-full bg-[#FF6136] animate-ping"></span>
                        <span class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-500">Digital Production Studio</span>
                    </div>

                    <h1 class="text-[42px] md:text-7xl lg:text-[85px] font-[1000] text-[#333A73] leading-[1] md:leading-[0.95] tracking-tight uppercase italic mb-6">
                        Build <span class="text-[#019E9A]">Interactive</span> <br class="hidden md:block"> Digital Products.
                    </h1>

                    <p class="text-base md:text-xl text-slate-500 max-w-xl mx-auto lg:mx-0 font-medium leading-relaxed mb-10 px-2 md:px-0">
                        Kami memproduksi <span class="text-[#333A73] font-bold">Custom Game</span>, <span class="text-[#333A73] font-bold">Gamifikasi</span>, dan <span class="text-[#333A73] font-bold">Aset Animasi</span> yang dirancang khusus untuk memperkuat interaksi brand Anda.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 px-4 md:px-0">
                        <a href="#contact" class="flex items-center justify-center bg-[#333A73] text-white px-8 py-5 rounded-2xl font-black uppercase text-[11px] tracking-widest hover:bg-[#019E9A] transition-all shadow-xl shadow-slate-200 active:scale-95">
                            Konsultasi Proyek
                        </a>
                        <a href="#about-services" class="flex items-center justify-center bg-white text-[#333A73] border-2 border-slate-100 px-8 py-5 rounded-2xl font-black uppercase text-[11px] tracking-widest hover:border-[#333A73] transition-all active:scale-95">
                            Lihat Portofolio
                        </a>
                    </div>
                </div>

                <div class="lg:col-span-5 order-1 lg:order-2">
                    <div class="relative px-4 md:px-0">
                        <div class="relative aspect-square md:aspect-[4/5] bg-slate-100 rounded-[3rem] md:rounded-[4rem] overflow-hidden shadow-2xl group border-4 border-white">
                            <img src="https://images.unsplash.com/photo-1550745165-9bc0b252726f?q=80&w=1000" 
                                 class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-1000" 
                                 alt="DangDang Preview">
                            
                            <div class="absolute inset-0 bg-gradient-to-t from-[#333A73]/60 via-transparent to-transparent"></div>
                            <div class="absolute bottom-6 left-6 right-6">
                                <div class="bg-white/10 backdrop-blur-md border border-white/20 p-4 rounded-2xl flex items-center justify-between">
                                    <span class="text-white text-[10px] font-black uppercase tracking-widest italic">Project Alpha-2025</span>
                                    <div class="flex gap-1">
                                        <span class="w-1 h-1 bg-white rounded-full"></span>
                                        <span class="w-1 h-1 bg-white rounded-full"></span>
                                        <span class="w-1 h-1 bg-white/40 rounded-full"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="absolute -top-4 -right-2 md:-right-8 bg-[#FF6136] text-white p-5 md:p-7 rounded-[2rem] shadow-xl rotate-12 flex flex-col items-center">
                            <span class="text-2xl md:text-3xl font-black italic leading-none">50+</span>
                            <span class="text-[8px] font-bold uppercase tracking-tighter opacity-80">Projects</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <section class="py-24 bg-white border-y border-slate-100 overflow-hidden no-print">
        <div class="max-w-7xl mx-auto px-6 mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <span class="w-8 h-1 bg-[#333A73] rounded-full"></span>
                    <span class="text-[10px] font-black uppercase tracking-[0.4em] text-slate-400">Network ecosystem</span>
                </div>
                <h3 class="text-4xl md:text-5xl font-[900] text-[#333A73] italic uppercase tracking-tighter leading-none">
                    Supported by <span class="text-[#019E9A]">Trusted Entities.</span>
                </h3>
            </div>
            <div class="hidden md:block">
                <p class="text-[10px] font-bold text-slate-300 uppercase tracking-[0.2em] text-right">
                    Collaborating across various<br>industries & sectors.
                </p>
            </div>
        </div>

        <div class="relative flex overflow-x-hidden group">
            <div class="absolute inset-y-0 left-0 w-24 md:w-48 bg-gradient-to-r from-white to-transparent z-10 pointer-events-none"></div>
            <div class="absolute inset-y-0 right-0 w-24 md:w-48 bg-gradient-to-l from-white to-transparent z-10 pointer-events-none"></div>

            <div class="flex animate-marquee-fast md:animate-marquee whitespace-nowrap items-center gap-16 md:gap-24 py-8 px-12">
                <?php if ($q_partners && mysqli_num_rows($q_partners) > 0): 
                    // Loop pertama
                    while ($p = mysqli_fetch_assoc($q_partners)): ?>
                        <div class="flex-shrink-0">
                            <img src="../uploads/partners/<?= $p['partner_logo'] ?>" 
                                 class="h-10 md:h-14 w-auto grayscale opacity-40 hover:opacity-100 hover:grayscale-0 transition-all duration-500 cursor-pointer transform hover:scale-110" 
                                 alt="<?= $p['partner_name'] ?>">
                        </div>
                    <?php endwhile; 

                    // Loop kedua (untuk kelancaran animasi seamless)
                    mysqli_data_seek($q_partners, 0);
                    while ($p = mysqli_fetch_assoc($q_partners)): ?>
                        <div class="flex-shrink-0">
                            <img src="../uploads/partners/<?= $p['partner_logo'] ?>" 
                                 class="h-10 md:h-14 w-auto grayscale opacity-40 hover:opacity-100 hover:grayscale-0 transition-all duration-500 cursor-pointer transform hover:scale-110" 
                                 alt="<?= $p['partner_name'] ?>">
                        </div>
                    <?php endwhile; 
                else: ?>
                    <span class="text-3xl font-black text-slate-100 uppercase italic tracking-[0.3em]">
                        DangDang Studio Partners • Collaborative Network • Excellence • Visionary • 2025 • 
                    </span>
                    <span class="text-3xl font-black text-slate-100 uppercase italic tracking-[0.3em]">
                        DangDang Studio Partners • Collaborative Network • Excellence • Visionary • 2025 • 
                    </span>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section id="about-services" class="py-32 px-6 no-print bg-[#FCFDFF] overflow-hidden">
        <div class="max-w-7xl mx-auto">
            <div class="grid lg:grid-cols-12 gap-12 items-start">
                
                <div class="lg:col-span-5 lg:sticky lg:top-32">
                    <div class="space-y-8">
                        <div>
                            <span class="inline-block px-4 py-1.5 rounded-full bg-[#019E9A]/10 text-[#019E9A] text-[10px] font-black uppercase tracking-[0.3em] mb-6">
                                Mission Brief 01
                            </span>
                            <h2 class="text-6xl md:text-7xl font-[1000] text-[#333A73] leading-[0.9] italic uppercase tracking-tighter mb-8">
                                DangDang<br><span class="text-[#FF6136]">Studio.</span>
                            </h2>
                            <div class="w-20 h-2 bg-[#FF6136] rounded-full mb-8"></div>
                        </div>

                        <div class="space-y-6">
                            <p class="text-xl text-[#333A73] font-black italic uppercase leading-tight">
                                Kami adalah simfoni antara <span class="text-[#019E9A]">seni visual</span> & <span class="text-[#FF6136]">logika kode</span>.
                            </p>
                            <p class="text-slate-500 font-medium text-lg leading-relaxed">
                                Berbasis di Indonesia, kami melayani pasar global dengan menciptakan pengalaman interaktif yang berfokus pada emosi pemain dan detail animasi yang presisi. Kami adalah partner strategis untuk kebutuhan gamifikasi dan produksi konten digital berkualitas tinggi.
                            </p>
                        </div>

                        <div class="pt-8 border-t border-slate-100 flex gap-10">
                            <div>
                                <p class="text-2xl font-black text-[#333A73]">50+</p>
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Global Ops</p>
                            </div>
                            <div>
                                <p class="text-2xl font-black text-[#333A73]">100%</p>
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Custom Built</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-7 space-y-8">
                    
                    <div class="group bg-white rounded-[3.5rem] overflow-hidden border border-slate-100 shadow-sm hover:shadow-2xl transition-all duration-500">
                        <div class="grid md:grid-cols-2">
                            <div class="p-10 flex flex-col justify-between">
                                <div>
                                    <h3 class="text-2xl font-[900] text-[#333A73] uppercase italic leading-none mb-2">Custom Game</h3>
                                    <p class="text-[10px] font-black text-[#019E9A] uppercase tracking-widest mb-6">Production Module</p>
                                    <p class="text-sm text-slate-500 leading-relaxed">Dari IP original hingga game simulasi untuk brand. Kami menghidupkan dunia virtual Anda dengan mekanik yang solid.</p>
                                </div>
                                <div class="mt-8">
                                    <a href="#" class="text-[10px] font-black uppercase tracking-widest text-[#333A73] flex items-center gap-2 group-hover:text-[#FF6136]">
                                        Explore Scope <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 8l4 4m0 0l-4 4m4-4H3" stroke-width="3"/></svg>
                                    </a>
                                </div>
                            </div>
                            <div class="h-64 md:h-auto overflow-hidden relative">
                                <img src="https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&q=80&w=800" alt="Game Dev" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000">
                                <div class="absolute inset-0 bg-gradient-to-r from-white md:from-white/80 to-transparent"></div>
                            </div>
                        </div>
                    </div>

                    <div class="group bg-[#333A73] rounded-[3.5rem] overflow-hidden shadow-xl hover:shadow-[#333A73]/20 transition-all duration-500">
                        <div class="grid md:grid-cols-2">
                            <div class="h-64 md:h-auto overflow-hidden relative order-2 md:order-1">
                                <img src="https://images.unsplash.com/photo-1550745165-9bc0b252726f?auto=format&fit=crop&q=80&w=800" alt="Gamification" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000">
                                <div class="absolute inset-0 bg-gradient-to-l from-[#333A73] md:from-[#333A73]/80 to-transparent"></div>
                            </div>
                            <div class="p-10 flex flex-col justify-between order-1 md:order-2 text-white">
                                <div>
                                    <h3 class="text-2xl font-[900] uppercase italic leading-none mb-2 text-[#FFC107]">Gamification</h3>
                                    <p class="text-[10px] font-black text-white/40 uppercase tracking-widest mb-6">Engagement Strategy</p>
                                    <p class="text-sm text-white/70 leading-relaxed">Ubah user journey yang membosankan menjadi sistem reward yang meningkatkan retensi dan loyalitas pelanggan secara drastis.</p>
                                </div>
                                <div class="mt-8 text-right">
                                    <a href="#" class="text-[10px] font-black uppercase tracking-widest text-white flex items-center justify-end gap-2 group-hover:text-[#019E9A]">
                                        Learn More <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 8l4 4m0 0l-4 4m4-4H3" stroke-width="3"/></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="group bg-white rounded-[3.5rem] overflow-hidden border border-slate-100 shadow-sm hover:shadow-2xl transition-all duration-500">
                        <div class="grid md:grid-cols-2">
                            <div class="p-10 flex flex-col justify-between">
                                <div>
                                    <h3 class="text-2xl font-[900] text-[#333A73] uppercase italic leading-none mb-2">Animation</h3>
                                    <p class="text-[10px] font-black text-[#FF6136] uppercase tracking-widest mb-6">Visual Fluidity</p>
                                    <p class="text-sm text-slate-500 leading-relaxed">Produksi sinematik, trailer, dan aset animasi 2D/3D dengan standar kualitas tinggi untuk kebutuhan promosi atau in-game asset.</p>
                                </div>
                                <div class="mt-8">
                                    <a href="#" class="text-[10px] font-black uppercase tracking-widest text-[#333A73] flex items-center gap-2 group-hover:text-[#019E9A]">
                                        View Reels <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 8l4 4m0 0l-4 4m4-4H3" stroke-width="3"/></svg>
                                    </a>
                                </div>
                            </div>
                            <div class="h-64 md:h-auto overflow-hidden relative">
                                <img src="https://images.unsplash.com/photo-1616469829581-73993eb86b02?auto=format&fit=crop&q=80&w=800" alt="Animation" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000">
                                <div class="absolute inset-0 bg-gradient-to-r from-white md:from-white/80 to-transparent"></div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-[#019E9A] p-12 rounded-[3.5rem] text-white flex flex-col md:flex-row items-center justify-between gap-8 group">
                        <div class="text-center md:text-left">
                            <h4 class="text-3xl font-black uppercase italic leading-none mb-2">Ready to Scale?</h4>
                            <p class="text-sm text-white/80 font-medium">Konsultasikan ide custom game atau animasi Anda sekarang.</p>
                        </div>
                        <a href="#contact" class="px-10 py-5 bg-white text-[#019E9A] rounded-2xl font-black uppercase text-[10px] tracking-widest hover:bg-[#333A73] hover:text-white transition-all shadow-xl group-hover:scale-105">
                            Contact Us
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </section>

   <section id="games" class="relative py-32 bg-white overflow-hidden" x-data="{ mouseX: 0, mouseY: 0 }" @mousemove="mouseX = $event.clientX; mouseY = $event.clientY">

    <div class="absolute top-0 left-0 w-full h-full opacity-[0.03] pointer-events-none"
        style="background-image: radial-gradient(#333A73 1.5px, transparent 1.5px); background-size: 60px 60px;"></div>

    <div class="max-w-[1600px] mx-auto px-6 relative z-10">

        <div class="flex justify-between items-end mb-24">
            <div class="relative">
                <div class="flex items-center gap-4 mb-2">
                    <span class="w-12 h-[2px] bg-[#019E9A]"></span>
                    <span class="text-[#FF6136] font-black uppercase tracking-[0.4em] text-[10px]">Project Data</span>
                </div>
                <h2 class="font-heading text-6xl md:text-8xl font-[1000] italic uppercase tracking-tighter text-[#333A73] leading-none">
                    Selected <span class="text-[#019E9A] outline-text">Files.</span>
                </h2>
            </div>
            <div class="hidden lg:block text-right font-mono text-[10px] text-[#333A73]/40 tracking-widest uppercase">
                System_Status: Operational<br>
                Database_Index: 2025.ARCHIVE
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 lg:gap-20">
            <?php
            if ($q_games):
                $i = 0;
                while ($g = mysqli_fetch_assoc($q_games)):
                    $i++;
                    if ($i > 3) break;

                    $mt = ($i == 1) ? 'mt-0' : (($i == 2) ? 'mt-20' : 'mt-10');

                    $videoId = "";
                    if (!empty($g['trailer_url'])) {
                        preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $g['trailer_url'], $match);
                        $videoId = $match[1] ?? "";
                    }
            ?>
                    <a href="work-detail?slug=<?= $g['slug'] ?>"
                        class="relative <?= $mt ?> block group transition-transform duration-700 ease-out"
                        x-data="{ isHovered: false }"
                        @mouseenter="isHovered = true"
                        @mouseleave="isHovered = false"
                        :style="`transform: translateY(${(mouseY - window.innerHeight/2) * <?= 0.03 * $i ?>}px)`">

                        <div class="absolute -top-4 left-8 z-30 bg-[#333A73] text-white px-4 py-1 text-[8px] font-black italic tracking-[0.3em] rounded-full shadow-lg">
                            DATA_REF/00<?= $i ?>
                        </div>

                        <div class="relative aspect-[16/10] bg-slate-100 rounded-[3rem] overflow-hidden group-hover:shadow-[0_40px_80px_-20px_rgba(1,158,154,0.3)] transition-all duration-700 border border-slate-100/50">

                            <img src="../uploads/game/<?= $g['header_image'] ?>"
                                class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-[1.5s] group-hover:scale-105">

                            <?php if ($videoId): ?>
                                <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-700 pointer-events-none">
                                    <template x-if="isHovered">
                                        <iframe src="https://www.youtube.com/embed/<?= $videoId ?>?autoplay=1&mute=1&controls=0&loop=1&playlist=<?= $videoId ?>"
                                            class="w-full h-full scale-[1.8]" frameborder="0"></iframe>
                                    </template>
                                </div>
                            <?php endif; ?>

                            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-500">
                                <div class="w-16 h-16 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M17 8l4 4m0 0l-4 4m4-4H3" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="mt-10 px-4 relative">
                            <?php if (!empty($g['game_logo'])): ?>
                                <div class="absolute -top-20 right-4 w-20 h-20 bg-white p-2 shadow-2xl rounded-full z-20 border-4 border-white transform group-hover:-translate-y-3 group-hover:rotate-12 transition-all duration-500">
                                    <img src="../uploads/game/<?= $g['game_logo'] ?>" class="w-full h-full object-contain rounded-full" alt="Icon">
                                </div>
                            <?php endif; ?>

                            <div class="flex items-center gap-3 mb-4">
                                <span class="text-[#019E9A] font-black text-[9px] uppercase tracking-[0.4em]"><?= $g['category'] ?></span>
                                <div class="h-[1px] w-8 bg-slate-200 group-hover:w-16 transition-all duration-500"></div>
                            </div>

                            <h3 class="text-4xl font-[1000] text-[#333A73] uppercase italic leading-none tracking-tighter group-hover:text-[#FF6136] transition-colors duration-300">
                                <?= $g['title'] ?>
                            </h3>

                            <p class="mt-4 text-slate-400 text-[12px] leading-relaxed font-medium uppercase tracking-tight line-clamp-2 max-w-[85%]">
                                <?= $g['short_desc'] ?>
                            </p>
                        </div>
                    </a>
            <?php endwhile;
            endif; ?>
        </div>

        <div class="mt-20 pt-12 border-t border-slate-100 flex flex-col md:flex-row justify-between items-center gap-8">
            
            <div class="max-w-md text-center md:text-left">
                <h4 class="text-[#333A73] font-[1000] uppercase italic tracking-tighter text-xl">
                    Hungry for more?
                </h4>
                <p class="text-slate-400 text-xs font-medium uppercase tracking-wide mt-1">
                    Lihat koleksi lengkap proyek game dan animasi kami di galeri portfolio.
                </p>
            </div>

            <a href="portfolio.php" class="group relative flex items-center gap-8 bg-[#333A73] hover:bg-[#019E9A] px-10 py-5 rounded-2xl transition-all duration-500 shadow-xl shadow-slate-200 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/5 to-white/0 -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
                
                <span class="relative z-10 text-white text-xs font-black uppercase tracking-[0.2em]">
                    Buka Seluruh Portfolio
                </span>
                
                <div class="relative z-10 flex items-center justify-center w-8 h-8 rounded-full bg-white/10 group-hover:bg-white group-hover:text-[#019E9A] transition-all duration-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M17 8l4 4m0 0l-4 4m4-4H3" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
            </a>

        </div>
    </div>
</section>

<style>
    .outline-text {
        color: transparent;
        -webkit-text-stroke: 1.5px #333A73;
    }

    @keyframes spin-slow {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    .animate-spin-slow {
        animation: spin-slow 12s linear infinite;
    }
</style>

    <section id="articles" class="py-32 px-6 bg-brandDark text-white rounded-[3rem] md:rounded-[6xl] mx-4 relative overflow-hidden">
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-brandTeal/10 rounded-full blur-[120px]"></div>

        <div class="max-w-7xl mx-auto relative z-10">
            <div class="flex flex-col md:flex-row justify-between items-center md:items-end mb-20 gap-8">
                <div class="space-y-4 text-center md:text-left">
                    <div class="inline-flex items-center gap-2 bg-white/5 border border-white/10 px-4 py-1.5 rounded-full">
                        <span class="w-1.5 h-1.5 bg-brandCoral rounded-full animate-pulse"></span>
                        <span class="text-[9px] font-black uppercase tracking-[0.3em] text-white/70">Studio Journal</span>
                    </div>
                    <h2 class="font-heading text-5xl md:text-7xl font-extrabold uppercase italic leading-none tracking-tighter">
                        Latest <span class="text-brandCoral">Insight.</span>
                    </h2>
                </div>

                <a href="articles.php" class="group relative inline-flex items-center gap-4 bg-white px-8 py-4 rounded-full overflow-hidden transition-all duration-300 hover:pr-12">
                    <span class="relative z-10 text-brandDark text-[10px] font-black uppercase tracking-widest transition-colors group-hover:text-white">View All Stories</span>
                    <div class="absolute inset-0 bg-brandCoral translate-y-[101%] group-hover:translate-y-0 transition-transform duration-300"></div>
                    <svg class="relative z-10 w-4 h-4 text-brandDark transition-colors group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M17 8l4 4m0 0l-4 4m4-4H3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 lg:gap-12">
                <?php
                if ($q_articles && mysqli_num_rows($q_articles) > 0):
                    while ($art = mysqli_fetch_assoc($q_articles)):
                ?>
                        <a href="article-detail.php?slug=<?= $art['slug'] ?>" class="group block">
                            <div class="relative aspect-[16/10] rounded-[2.5rem] overflow-hidden border border-white/5 mb-8 shadow-2xl shadow-black/20 bg-white/5">
                                <img src="../uploads/articles/<?= $art['cover_image'] ?>"
                                    class="w-full h-full object-cover transition-all duration-1000 scale-110 group-hover:scale-100 brightness-75 group-hover:brightness-100"
                                    alt="<?= htmlspecialchars($art['title']) ?>">

                                <div class="absolute inset-0 bg-gradient-to-t from-brandDark via-transparent to-transparent opacity-60 group-hover:opacity-20 transition-opacity duration-500"></div>

                                <div class="absolute top-6 left-6">
                                    <span class="bg-white/10 backdrop-blur-md border border-white/20 text-[8px] font-black uppercase tracking-widest px-4 py-2 rounded-full">
                                        <?= date('M d, Y', strtotime($art['created_at'])) ?>
                                    </span>
                                </div>
                            </div>

                            <div class="space-y-4 px-2">
                                <div class="flex items-center gap-3">
                                    <span class="h-[1px] w-8 bg-brandCoral"></span>
                                    <p class="text-[10px] font-black text-brandGold uppercase tracking-[0.2em]">
                                        <?= htmlspecialchars($art['category'] ?? 'General') ?>
                                    </p>
                                </div>

                                <h4 class="text-2xl md:text-3xl font-bold leading-[1.1] tracking-tight group-hover:text-brandTeal transition-colors duration-300">
                                    <?= htmlspecialchars($art['title']) ?>
                                </h4>

                                <p class="text-white/40 text-sm line-clamp-2 font-medium leading-relaxed group-hover:text-white/60 transition-colors">
                                    <?= strip_tags($art['content']) ?>
                                </p>

                                <div class="pt-4 flex items-center gap-2 text-brandTeal opacity-0 group-hover:opacity-100 group-hover:translate-x-2 transition-all duration-500">
                                    <span class="text-[9px] font-black uppercase tracking-widest">Read Article</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M17 8l4 4m0 0l-4 4m4-4H3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </div>
                        </a>
                    <?php
                    endwhile;
                else:
                    ?>
                    <div class="col-span-full py-24 text-center border-2 border-dashed border-white/5 rounded-[3rem]">
                        <div class="w-16 h-16 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-8 h-8 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l4 4v10a2 2 0 01-2 2z" stroke-width="2" />
                            </svg>
                        </div>
                        <p class="text-white/20 italic tracking-widest uppercase text-xs font-black">No insights published yet.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section id="contact" class="py-24 md:py-40 px-4 md:px-6 relative overflow-hidden">
        <div class="absolute inset-0 hidden md:flex items-center justify-center opacity-[0.03] select-none pointer-events-none">
            <h2 class="font-heading text-[20vw] font-black uppercase tracking-tighter italic leading-none">
                Connect
            </h2>
        </div>

        <div class="max-w-7xl mx-auto relative z-10">
            <div class="grid lg:grid-cols-2 gap-10 md:gap-16 items-center">

                <div class="space-y-6 md:space-y-10 text-center lg:text-left">
                    <div class="inline-flex flex-col sm:flex-row items-center gap-4">
                        <div class="flex -space-x-3">
                            <div class="w-8 h-8 md:w-10 md:h-10 rounded-full border-2 border-white bg-brandCoral"></div>
                            <div class="w-8 h-8 md:w-10 md:h-10 rounded-full border-2 border-white bg-brandTeal"></div>
                            <div class="w-8 h-8 md:w-10 md:h-10 rounded-full border-2 border-white bg-brandGold"></div>
                        </div>
                        <p class="text-[9px] md:text-[10px] font-black uppercase tracking-[0.3em] md:tracking-[0.4em] text-brandDark/40 italic">Ready to Level Up?</p>
                    </div>

                    <h2 class="font-heading text-5xl md:text-7xl lg:text-8xl font-extrabold tracking-tighter text-brandDark uppercase italic leading-[0.9]">
                        Got a <br class="hidden sm:block"> <span class="text-brandTeal">Vision?</span> <br> <span class="text-outline">Share it.</span>
                    </h2>

                    <p class="text-slate-500 font-medium text-base md:text-lg max-w-md mx-auto lg:mx-0">
                        Jangan biarkan ide hebat mengendap. Diskusikan proyek animasi atau game Anda bersama tim kreatif kami.
                    </p>
                </div>

                <div class="grid gap-4 md:gap-6">
                    <a href="mailto:hello@dangdang.com"
                        class="group relative bg-white border border-slate-100 p-0.5 md:p-1 bg-gradient-to-r hover:from-brandCoral hover:to-brandGold transition-all duration-500 rounded-[2rem] md:rounded-[3rem] shadow-xl shadow-brandDark/5">
                        <div class="bg-white rounded-[1.9rem] md:rounded-[2.9rem] p-6 md:p-10 flex items-center justify-between">
                            <div class="flex items-center gap-4 md:gap-6">
                                <div class="w-12 h-12 md:w-16 md:h-16 rounded-2xl bg-brandCoral/10 text-brandCoral flex items-center justify-center group-hover:scale-110 transition-transform duration-500">
                                    <svg class="w-6 h-6 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-[8px] md:text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-0.5 md:mb-1">Send an Email</h4>
                                    <p class="text-lg md:text-2xl font-black text-brandDark break-all">hello@dangdang.com</p>
                                </div>
                            </div>
                            <div class="hidden sm:flex w-10 h-10 md:w-12 md:h-12 rounded-full border border-slate-100 items-center justify-center group-hover:bg-brandDark group-hover:text-white transition-all">
                                <svg class="w-4 h-4 md:w-5 md:h-5 -rotate-45" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M14 5l7 7m0 0l-7 7m7-7H3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                        </div>
                    </a>

                    <a href="#"
                        class="group relative bg-white border border-slate-100 p-0.5 md:p-1 bg-gradient-to-r hover:from-brandTeal hover:to-brandDark transition-all duration-500 rounded-[2rem] md:rounded-[3rem] shadow-xl shadow-brandDark/5">
                        <div class="bg-white rounded-[1.9rem] md:rounded-[2.9rem] p-6 md:p-10 flex items-center justify-between">
                            <div class="flex items-center gap-4 md:gap-6">
                                <div class="w-12 h-12 md:w-16 md:h-16 rounded-2xl bg-brandTeal/10 text-brandTeal flex items-center justify-center group-hover:scale-110 transition-transform duration-500">
                                    <svg class="w-6 h-6 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M13 10V3L4 14h7v7l9-11h-7z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-[8px] md:text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-0.5 md:mb-1">Direct Message</h4>
                                    <p class="text-lg md:text-2xl font-black text-brandDark">@dangdangstudio</p>
                                </div>
                            </div>
                            <div class="hidden sm:flex w-10 h-10 md:w-12 md:h-12 rounded-full border border-slate-100 items-center justify-center group-hover:bg-brandDark group-hover:text-white transition-all">
                                <svg class="w-4 h-4 md:w-5 md:h-5 -rotate-45" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M14 5l7 7m0 0l-7 7m7-7H3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <?php include_once '_footer.php'; ?>

</body>

</html>