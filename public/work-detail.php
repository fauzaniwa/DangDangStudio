<?php
require_once '../admin/process/config.php';

$slug = isset($_GET['slug']) ? mysqli_real_escape_string($conn, $_GET['slug']) : '';

// 1. Query Data Game
$query = "SELECT * FROM games WHERE slug = '$slug'";
$result = mysqli_query($conn, $query);
$game = mysqli_fetch_assoc($result);

if (!$game) {
    header("Location: portfolio.php");
    exit;
}

// 2. Decode JSON Data
$screenshots = !empty($game['screenshots']) ? json_decode($game['screenshots'], true) : [];
$dist_links = !empty($game['distribution_links']) ? json_decode($game['distribution_links'], true) : [];

// 3. Query Rekomendasi
$rec_result = mysqli_query($conn, "SELECT * FROM games WHERE id != '{$game['id']}' ORDER BY RAND() LIMIT 3");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $game['title'] ?> | DangDang Studio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200..800&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            scroll-behavior: smooth;
            color: #1e293b;
            background-color: #FCFDFF;
        }

        .article-content p {
            margin-bottom: 1.8rem;
            font-size: 1.1rem;
            line-height: 1.8;
            color: #475569;
        }

        .article-content h2 {
            font-weight: 900;
            color: #333A73;
            margin-top: 3rem;
            margin-bottom: 1.5rem;
            font-size: 2rem;
            text-transform: uppercase;
            font-style: italic;
            letter-spacing: -0.025em;
        }

        [x-cloak] { display: none !important; }

        .btn-download {
            background: #333A73;
            color: white;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .btn-download:hover {
            background: #019E9A;
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(1, 158, 154, 0.25);
        }

        /* Hero Image Layout */
        .hero-full {
            height: 70vh; /* Mengambil 70% tinggi layar */
            width: 100%;
        }
    </style>
</head>

<body x-data="detailHandler()">

    <?php include '_navbar.php'; ?>

    <div class="fixed top-0 left-0 w-full h-1.5 z-[200] no-print">
        <div class="h-full bg-[#019E9A] transition-all duration-150 shadow-[0_0_10px_#019E9A]" :style="'width: ' + scrollPercent + '%'"></div>
    </div>

    <div class="relative w-full hero-full overflow-hidden bg-[#333A73] no-print">
        <img src="../uploads/game/<?= $game['header_image'] ?>" 
             class="w-full h-full object-cover transform scale-105"
             style="object-position: center 25%;">
        <div class="absolute inset-0 bg-gradient-to-b from-black/30 via-transparent to-[#FCFDFF]"></div>
    </div>

    <header class="relative -mt-32 pb-16 px-6 z-10">
        <div class="max-w-4xl mx-auto text-center">
            <span class="inline-block px-6 py-2 rounded-full bg-white/90 backdrop-blur shadow-xl text-[#333A73] text-[10px] font-black uppercase tracking-[0.4em] mb-8 border border-slate-100">
                <?= $game['category'] ?>
            </span>
            <h1 class="text-5xl md:text-7xl font-[900] text-[#333A73] leading-tight tracking-tighter italic uppercase mb-12 drop-shadow-sm">
                <?= $game['title'] ?>
            </h1>

            <div class="flex flex-wrap items-center justify-center gap-6 md:gap-16 py-10 border-y border-slate-100/80 bg-white/50 backdrop-blur-sm rounded-3xl md:rounded-full px-8">
                <div class="text-center md:text-left">
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mb-1 text-center md:text-left">Release Date</p>
                    <p class="text-[11px] font-black text-[#333A73] uppercase italic"><?= date('F Y', strtotime($game['created_at'])) ?></p>
                </div>
                <div class="hidden md:block w-px h-10 bg-slate-200"></div>
                <div class="text-center md:text-left">
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mb-1 text-center md:text-left">Platform Status</p>
                    <p class="text-[11px] font-black text-[#019E9A] uppercase italic">Stable Release</p>
                </div>
                <div class="hidden md:block w-px h-10 bg-slate-200"></div>
                <div class="text-center md:text-left">
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mb-1 text-center md:text-left">Developer</p>
                    <p class="text-[11px] font-black text-[#333A73] uppercase italic">DangDang Studio</p>
                </div>
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-12 gap-12 relative mt-10">
        
        <aside class="lg:col-span-1 no-print order-2 lg:order-1">
            <div class="sticky top-32 flex lg:flex-col gap-4 justify-center items-center">
                <button @click="window.print()" class="w-14 h-14 rounded-2xl bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:text-[#333A73] hover:shadow-xl transition-all group" title="Download Datasheet">
                    <svg class="w-6 h-6 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" stroke-width="2" /></svg>
                </button>
                <div class="h-px w-8 lg:w-full bg-slate-200 my-2"></div>
                <button @click="openShare()" class="w-14 h-14 rounded-2xl bg-[#333A73] text-white flex items-center justify-center hover:bg-[#FF6136] shadow-lg hover:shadow-[#FF6136]/20 transition-all group">
                    <svg class="w-6 h-6 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" stroke-width="2" /></svg>
                </button>
            </div>
        </aside>

        <main class="lg:col-span-9 lg:col-start-3 order-1 lg:order-2">
            <article class="article-content max-w-none mb-24">
                <h2 class="flex items-center gap-4">
                    <span class="w-12 h-1 bg-[#019E9A] rounded-full inline-block"></span>
                    Description
                </h2>
                <?= $game['long_desc'] ?>
            </article>

            <?php if (!empty($dist_links)): ?>
            <div class="mb-32 p-12 md:p-20 bg-[#333A73] rounded-[4rem] text-center text-white relative overflow-hidden shadow-2xl">
                <div class="absolute top-0 right-0 w-64 h-64 bg-[#019E9A]/10 rounded-full blur-3xl -mr-32 -mt-32"></div>
                
                <h3 class="relative z-10 text-3xl italic uppercase font-black mb-4">Initialize Play</h3>
                <p class="relative z-10 text-xs text-slate-300 mb-12 font-bold uppercase tracking-[0.3em]">Deployment ready for following systems</p>
                
                <div class="relative z-10 flex flex-wrap justify-center gap-6">
                    <?php foreach ($dist_links as $link): ?>
                        <a href="<?= $link['url'] ?>" target="_blank" class="bg-white text-[#333A73] hover:bg-[#019E9A] hover:text-white px-12 py-6 rounded-3xl flex items-center gap-4 group transition-all duration-300 font-black italic uppercase text-xs tracking-widest shadow-xl">
                            Download for <?= $link['platform'] ?>
                            <svg class="w-5 h-5 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M14 5l7 7m0 0l-7 7m7-7H3" stroke-width="3"/></svg>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <?php if (!empty($screenshots)): ?>
            <div class="mb-32" x-data="{ activeImg: null }">
                <h2 class="flex items-center gap-4 mb-12 uppercase italic font-black text-[#333A73] text-2xl">
                    <span class="w-12 h-1 bg-[#019E9A] rounded-full inline-block"></span>
                    Visual Data
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 no-print">
                    <?php foreach ($screenshots as $img): ?>
                        <div class="aspect-video rounded-[3rem] overflow-hidden cursor-pointer group shadow-lg bg-slate-100 ring-1 ring-slate-100"
                            @click="activeImg = '<?= $img ?>'">
                            <img src="../uploads/game/<?= $img ?>"
                                class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110">
                        </div>
                    <?php endforeach; ?>
                </div>

                <template x-if="activeImg">
                    <div x-show="activeImg" x-cloak
                        class="fixed inset-0 z-[300] bg-[#333A73]/95 backdrop-blur-xl flex items-center justify-center p-6"
                        @click="activeImg = null">
                        <img :src="'../uploads/game/' + activeImg" class="max-w-full max-h-[85vh] rounded-[2rem] shadow-2xl object-contain border-4 border-white/10">
                        <button class="absolute top-10 right-10 text-white p-4 hover:rotate-90 transition-transform">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2"/></svg>
                        </button>
                    </div>
                </template>
            </div>
            <?php endif; ?>
        </main>
    </div>

    <?php if (mysqli_num_rows($rec_result) > 0): ?>
    <section class="bg-slate-50 py-32 px-6 no-print border-t border-slate-100">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-20 gap-6">
                <div>
                    <span class="text-[10px] font-black text-[#019E9A] uppercase tracking-[0.4em] mb-4 block">Recommended</span>
                    <h3 class="text-4xl md:text-5xl font-[900] text-[#333A73] italic uppercase tracking-tighter leading-none">Other Records.</h3>
                </div>
                <a href="portfolio.php" class="inline-flex items-center gap-3 text-[10px] font-black uppercase tracking-widest text-[#333A73] group">
                    View Full Archive
                    <span class="w-12 h-px bg-[#333A73] group-hover:w-20 transition-all"></span>
                </a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <?php while ($rec = mysqli_fetch_assoc($rec_result)): ?>
                    <a href="work-detail?slug=<?= $rec['slug'] ?>" class="group block bg-white p-6 rounded-[3.5rem] shadow-sm hover:shadow-2xl transition-all duration-500 hover:-translate-y-3">
                        <div class="aspect-[16/10] rounded-[2.5rem] overflow-hidden mb-8 bg-slate-100">
                            <img src="../uploads/game/<?= $rec['header_image'] ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000">
                        </div>
                        <div class="px-2 pb-4">
                            <span class="text-[8px] font-black text-[#019E9A] uppercase tracking-[0.2em] mb-3 block"><?= $rec['category'] ?></span>
                            <h4 class="text-xl font-black text-[#333A73] group-hover:text-[#FF6136] transition-colors line-clamp-2 uppercase italic leading-tight"><?= $rec['title'] ?></h4>
                        </div>
                    </a>
                <?php endwhile; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <div x-show="showModal" x-cloak class="fixed inset-0 z-[500] flex items-end md:items-center justify-center no-print p-4">
        <div x-show="showModal" x-transition.opacity @click="showModal = false" class="absolute inset-0 bg-[#333A73]/80 backdrop-blur-md"></div>
        <div x-show="showModal" 
             x-transition:enter="transition ease-out duration-300 transform" 
             x-transition:enter-start="translate-y-full md:translate-y-12 md:opacity-0" 
             x-transition:enter-end="translate-y-0 md:opacity-100"
             class="relative bg-white w-full md:max-w-md rounded-[3rem] p-12 shadow-2xl text-center">
            
            <h4 class="text-3xl font-black text-[#333A73] uppercase italic mb-2 tracking-tighter">Share System</h4>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-12">Initialize broadcast to your network</p>
            
            <div class="grid grid-cols-3 gap-6 mb-12">
                <button @click="share('wa')" class="flex flex-col items-center gap-3 group">
                    <div class="w-16 h-16 rounded-3xl bg-green-50 text-green-500 flex items-center justify-center group-hover:bg-green-500 group-hover:text-white transition-all shadow-sm group-hover:shadow-lg">
                        <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" /></svg>
                    </div>
                </button>
                <button @click="share('tw')" class="flex flex-col items-center gap-3 group">
                    <div class="w-16 h-16 rounded-3xl bg-slate-50 text-black flex items-center justify-center group-hover:bg-black group-hover:text-white transition-all shadow-sm group-hover:shadow-lg">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.134l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" /></svg>
                    </div>
                </button>
                <button @click="share('fb')" class="flex flex-col items-center gap-3 group">
                    <div class="w-16 h-16 rounded-3xl bg-blue-50 text-blue-600 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-all shadow-sm group-hover:shadow-lg">
                        <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" /></svg>
                    </div>
                </button>
            </div>

            <button @click="copyLink()" 
                    class="w-full py-6 rounded-3xl transition-all flex items-center justify-center gap-3 group border-2"
                    :class="copied ? 'bg-green-500 border-green-500 text-white' : 'bg-slate-50 border-transparent hover:border-[#333A73]'">
                <span class="text-[11px] font-black uppercase tracking-widest"
                      x-text="copied ? 'System Link Copied!' : 'Copy Project Link'"></span>
                <svg x-show="!copied" class="w-5 h-5 text-[#333A73]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <svg x-show="copied" class="w-5 h-5 text-white animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </button>
        </div>
    </div>

    <?php include '_footer.php'; ?>

    <script>
        function detailHandler() {
            const captions = [
                "Deploying new inspiration! Cek project dari DangDang Studio ini: ",
                "Project Archive: ",
                "Insight baru dari portfolio DangDang Studio: ",
                "Sistem visual yang luar biasa, lihat selengkapnya: "
            ];

            return {
                showModal: false,
                copied: false,
                scrollPercent: 0,
                gameTitle: '<?= addslashes($game['title']) ?>',

                openShare() { 
                    this.showModal = true; 
                    this.copied = false;
                },

                share(platform) {
                    const url = window.location.href;
                    const text = `${captions[Math.floor(Math.random() * captions.length)]} "${this.gameTitle}"\n\n`;
                    
                    const shareLinks = {
                        wa: `https://api.whatsapp.com/send?text=${encodeURIComponent(text + url)}`,
                        tw: `https://twitter.com/intent/tweet?text=${encodeURIComponent(text)}&url=${encodeURIComponent(url)}`,
                        fb: `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`
                    };
                    window.open(shareLinks[platform], '_blank');
                },

                copyLink() {
                    const fullText = `${captions[0]} "${this.gameTitle}" - ${window.location.href}`;
                    navigator.clipboard.writeText(fullText).then(() => {
                        this.copied = true;
                        setTimeout(() => { this.showModal = false; }, 2000);
                    });
                },

                updateScroll() {
                    const h = document.documentElement;
                    this.scrollPercent = (h.scrollTop || document.body.scrollTop) / (h.scrollHeight - h.clientHeight) * 100;
                },

                init() {
                    window.addEventListener('scroll', () => this.updateScroll());
                }
            }
        }
    </script>
</body>
</html>