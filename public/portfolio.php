<?php
require_once '../admin/process/config.php';
$category = isset($_GET['category']) ? $_GET['category'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Archive | DangDang Studio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200..800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; scroll-behavior: smooth; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .text-outline { -webkit-text-stroke: 1.5px rgba(255, 255, 255, 0.2); color: transparent; }
        [x-cloak] { display: none !important; }

        .portfolio-fade-up {
            animation: portfolioFadeUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        @keyframes portfolioFadeUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>

<body class="bg-[#F8FAFC]" 
      x-data="portfolioLoader('<?= $category ?>', '<?= $search ?>')" 
      x-init="init()"
      @mousemove="mouseX = $event.clientX; mouseY = $event.clientY">

    <?php include '_navbar.php'; ?>

    <header class="pt-48 pb-32 px-6 relative overflow-hidden bg-[#333A73] rounded-b-[4rem] shadow-2xl">
        <div class="absolute inset-0 opacity-[0.05] pointer-events-none"
             style="background-image: radial-gradient(#fff 1.5px, transparent 1.5px); background-size: 40px 40px;"></div>
             
        <div class="max-w-7xl mx-auto relative z-10 text-center">
            <div class="inline-flex items-center gap-3 bg-white/5 border border-white/10 px-6 py-2 rounded-full mb-8">
                <span class="w-2 h-2 bg-[#019E9A] rounded-full animate-ping"></span>
                <span class="text-[10px] font-black uppercase tracking-[0.4em] text-white/70">Project Archive System</span>
            </div>
            <h1 class="text-7xl md:text-9xl font-black tracking-tighter text-white italic uppercase leading-[0.85] mb-8">
                The <span class="text-outline">Vault</span> <br> <span class="text-[#019E9A]">Records.</span>
            </h1>
        </div>
    </header>

    <main class="max-w-[1600px] mx-auto py-24 px-6 relative">
        
        <div class="mb-32 flex flex-col lg:row justify-between items-center gap-8">
            <div class="flex items-center gap-2 bg-slate-100 p-1.5 rounded-full overflow-x-auto max-w-full no-scrollbar">
                <button @click="setCategory('')"
                    :class="activeCat == '' ? 'bg-white shadow-sm text-[#333A73]' : 'text-slate-400'"
                    class="px-8 py-3 rounded-full text-[10px] font-black uppercase tracking-widest transition-all">
                    All Access
                </button>
                <?php
                $q_cats = mysqli_query($conn, "SELECT DISTINCT category FROM games");
                while ($c = mysqli_fetch_assoc($q_cats)): ?>
                    <button @click="setCategory('<?= $c['category'] ?>')"
                        :class="activeCat == '<?= $c['category'] ?>' ? 'bg-white shadow-sm text-[#019E9A]' : 'text-slate-400'"
                        class="px-8 py-3 rounded-full text-[10px] font-black uppercase tracking-widest transition-all whitespace-nowrap">
                        <?= $c['category'] ?>
                    </button>
                <?php endwhile; ?>
            </div>

            <div class="relative w-full lg:w-[400px]">
                <input type="text" x-model.debounce.500ms="searchQuery" placeholder="Search project database..."
                    class="w-full bg-white border-none rounded-full px-8 py-4 text-xs font-bold shadow-xl shadow-slate-200/50 focus:ring-2 ring-[#019E9A] outline-none transition-all">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 lg:gap-20">
            <template x-for="(game, index) in games" :key="game.id">
                <a :href="'work-detail?slug=' + game.slug"
                    class="relative block group transition-transform duration-700 ease-out portfolio-fade-up"
                    :class="index % 3 == 0 ? 'mt-0' : (index % 3 == 1 ? 'md:mt-24' : 'md:mt-12')"
                    x-data="{ isHovered: false }"
                    @mouseenter="isHovered = true"
                    @mouseleave="isHovered = false"
                    :style="`animation-delay: ${index * 100}ms; transform: translateY(${(mouseY - window.innerHeight/2) * (0.005 * (index % 3 + 1))}px)`">

                    <div class="absolute -top-4 left-8 z-30 bg-[#333A73] text-white px-4 py-1 text-[8px] font-black italic tracking-[0.3em] rounded-full shadow-lg"
                         x-text="'ARCHIVE_REF/00' + (index + 1)">
                    </div>

                    <div class="relative aspect-[16/10] bg-[#333A73] rounded-[3rem] overflow-hidden group-hover:shadow-[0_40px_80px_-20px_rgba(1,158,154,0.3)] transition-all duration-700 border border-slate-100/50">
                        
                        <img :src="'../uploads/game/' + game.header_image"
                            class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-[1.5s] group-hover:scale-110 opacity-80 group-hover:opacity-100">

                        <template x-if="game.video_id && isHovered">
                            <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-700 pointer-events-none">
                                <iframe :src="`https://www.youtube.com/embed/${game.video_id}?autoplay=1&mute=1&controls=0&loop=1&playlist=${game.video_id}&rel=0`"
                                    class="w-full h-full scale-[1.8]" frameborder="0"></iframe>
                            </div>
                        </template>

                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-500 bg-[#333A73]/20">
                            <div class="w-16 h-16 bg-white/20 backdrop-blur-xl rounded-full flex items-center justify-center border border-white/30">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                    <path d="M17 8l4 4m0 0l-4 4m4-4H3" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="mt-10 px-4 relative">
                        <template x-if="game.game_logo">
                            <div class="absolute -top-20 right-4 w-20 h-20 bg-white p-2 shadow-2xl rounded-full z-20 border-4 border-white transform group-hover:-translate-y-3 group-hover:rotate-12 transition-all duration-500">
                                <img :src="'../uploads/game/' + game.game_logo" class="w-full h-full object-contain rounded-full">
                            </div>
                        </template>

                        <div class="flex items-center gap-3 mb-4">
                            <span class="text-[#019E9A] font-black text-[9px] uppercase tracking-[0.4em]" x-text="game.category"></span>
                            <div class="h-[1px] w-8 bg-slate-200 group-hover:w-16 transition-all duration-500"></div>
                            <span class="text-slate-300 font-bold text-[9px]" x-text="game.date_formatted"></span>
                        </div>

                        <h3 class="text-4xl font-[1000] text-[#333A73] uppercase italic leading-none tracking-tighter group-hover:text-[#FF6136] transition-colors duration-300" 
                            x-text="game.title"></h3>

                        <p class="mt-4 text-slate-400 text-[12px] leading-relaxed font-medium uppercase tracking-tight line-clamp-2 max-w-[90%] italic" 
                           x-text="game.short_desc"></p>
                    </div>
                </a>
            </template>
        </div>

        <div x-show="!loading && games.length === 0" class="py-40 text-center" x-cloak>
            <h3 class="text-2xl font-black uppercase text-[#333A73]/10 tracking-[1em]">Record Not Found</h3>
        </div>

        <div class="mt-32 text-center" x-show="hasMore">
            <button @click="loadGames()" :disabled="loading"
                class="group relative inline-flex items-center gap-6 px-14 py-6 rounded-full bg-[#333A73] text-white text-[10px] font-black uppercase tracking-[0.4em] hover:bg-[#019E9A] transition-all disabled:opacity-50 shadow-2xl overflow-hidden">
                <span class="relative z-10" x-text="loading ? 'Synchronizing...' : 'Expand Archive'"></span>
                <svg x-show="!loading" class="w-4 h-4 relative z-10 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                    <path d="M17 8l4 4m0 0l-4 4m4-4H3" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>
        </div>
    </main>

    <?php include '_footer.php'; ?>

    <script>
        function portfolioLoader(initialCat, initialSearch) {
            return {
                games: [],
                offset: 0,
                loading: false,
                hasMore: true,
                activeCat: initialCat,
                searchQuery: initialSearch,
                mouseX: 0,
                mouseY: 0,

                async loadGames(reset = false) {
                    if (this.loading) return;
                    this.loading = true;

                    if (reset) {
                        this.games = [];
                        this.offset = 0;
                        this.hasMore = true;
                    }

                    try {
                        const res = await fetch(`api-games.php?offset=${this.offset}&category=${encodeURIComponent(this.activeCat)}&search=${encodeURIComponent(this.searchQuery)}`);
                        const data = await res.json();

                        // Asumsi api-games.php mengembalikan array objek
                        // Tambahkan pembersihan video_id jika URL youtube utuh dikirim dari API
                        const processedData = data.map(game => {
                            if(game.trailer_url) {
                                const match = game.trailer_url.match(/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i);
                                game.video_id = match ? match[1] : null;
                            }
                            return game;
                        });

                        if (processedData.length < 10) this.hasMore = false;
                        this.games.push(...processedData);
                        this.offset += 10;
                    } catch (e) {
                        console.error("Archive Access Denied", e);
                    } finally {
                        this.loading = false;
                    }
                },

                setCategory(cat) {
                    this.activeCat = cat;
                    this.loadGames(true);
                    const url = new URL(window.location);
                    cat ? url.searchParams.set('category', cat) : url.searchParams.delete('category');
                    window.history.pushState({}, '', url);
                },

                init() {
                    this.loadGames();
                    this.$watch('searchQuery', () => this.loadGames(true));
                }
            }
        }
    </script>
</body>
</html>