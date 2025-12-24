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
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
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

<body class="bg-[#F8FAFC]" x-data="portfolioLoader('<?= $category ?>', '<?= $search ?>')" x-init="init()">

    <?php include '_navbar.php'; ?>

    <header class="pt-48 pb-32 px-6 relative overflow-hidden bg-[#333A73] rounded-b-[4rem]">
        <div class="max-w-7xl mx-auto relative z-10 text-center">
            <div class="inline-flex items-center gap-3 bg-white/5 border border-white/10 px-6 py-2 rounded-full mb-8">
                <span class="w-2 h-2 bg-brandTeal rounded-full animate-ping"></span>
                <span class="text-[10px] font-black uppercase tracking-[0.4em] text-white/70">Project Archive System</span>
            </div>
            <h1 class="text-7xl md:text-9xl font-black tracking-tighter text-white italic uppercase leading-[0.85] mb-8">
                The <span class="text-outline">Vault</span> <br> <span class="text-[#019E9A]">Records.</span>
            </h1>
        </div>
    </header>

    <main class="max-w-7xl mx-auto py-24 px-6">
        
        <div class="mb-20 flex flex-col lg:flex-row justify-between items-center gap-8">
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
                    class="w-full bg-white border-none rounded-full px-8 py-4 text-xs font-bold shadow-xl shadow-slate-200/50 focus:ring-2 ring-[#019E9A] outline-none">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12 lg:gap-16">
            <template x-for="(game, index) in games" :key="game.id">
                <article class="portfolio-fade-up group" :style="`animation-delay: ${index * 100}ms`">
                    <div class="relative aspect-[16/10] rounded-[2rem] overflow-hidden mb-8 bg-[#333A73] shadow-lg group-hover:shadow-2xl group-hover:shadow-[#019E9A]/20 transition-all duration-500">
                        <img :src="'../uploads/game/' + game.header_image" 
                             class="w-full h-full object-cover transition-all duration-700 grayscale group-hover:grayscale-0 group-hover:scale-105 opacity-80 group-hover:opacity-100">
                        
                        <div class="absolute inset-0 flex flex-col justify-end p-8 bg-gradient-to-t from-[#333A73] via-transparent opacity-0 group-hover:opacity-100 transition-opacity">
                            <a :href="'work-detail?slug=' + game.slug" class="inline-flex items-center gap-3 text-white">
                                <span class="font-black uppercase tracking-widest text-[10px]">Initialize Analysis</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 8l4 4m0 0l-4 4m4-4H3" stroke-width="2" /></svg>
                            </a>
                        </div>
                    </div>

                    <div class="space-y-4 px-2">
                        <div class="flex items-center justify-between text-[9px] font-black uppercase tracking-widest">
                            <span class="text-[#019E9A]" x-text="game.category"></span>
                            <span class="text-slate-300" x-text="game.date_formatted"></span>
                        </div>
                        <h3 class="text-2xl font-black italic uppercase text-[#333A73] group-hover:text-[#019E9A] transition-colors" x-text="game.title"></h3>
                        <p class="text-slate-400 text-xs line-clamp-2 leading-relaxed italic" x-text="game.short_desc"></p>
                    </div>
                </article>
            </template>
        </div>

        <div x-show="!loading && games.length === 0" class="py-40 text-center" x-cloak>
            <h3 class="text-lg font-black uppercase text-[#333A73]/20 tracking-widest">Database Record Empty</h3>
        </div>

        <div class="mt-24 text-center" x-show="hasMore">
            <button @click="loadGames()" :disabled="loading"
                class="px-12 py-5 rounded-full bg-[#333A73] text-white text-[10px] font-black uppercase tracking-[0.4em] hover:bg-[#FF6136] transition-all disabled:opacity-50">
                <span x-text="loading ? 'Synchronizing...' : 'Expand Archive'"></span>
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

                        if (data.length < 10) this.hasMore = false;
                        this.games.push(...data);
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