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
    <title>Insights & Stories | DangDang Studio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200..800&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .text-outline {
            -webkit-text-stroke: 1px rgba(255, 255, 255, 0.3);
            color: transparent;
        }

        [x-cloak] {
            display: none !important;
        }

        /* Animasi halus untuk kartu */
        .fade-up {
            animation: fadeUp 0.5s ease-out forwards;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body class="bg-[#F8FAFC]" x-data="articleLoader('<?= $category ?>', '<?= $search ?>')" x-init="init()">

    <?php include '_navbar.php'; ?>

    <header class="pt-48 pb-24 px-6 relative overflow-hidden rounded-b-[4rem] md:rounded-b-[6rem]" style="background-color: #333A73;">
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-[#019E9A]/10 rounded-full blur-[120px]"></div>
        <div class="max-w-7xl mx-auto relative z-10 text-center">
            <div class="inline-flex items-center gap-2 bg-white/5 border border-white/10 px-4 py-2 rounded-full mb-8">
                <span class="w-2 h-2 bg-[#FF6136] rounded-full animate-pulse"></span>
                <span class="text-[10px] font-black uppercase tracking-[0.4em] text-white/80">Studio Journal</span>
            </div>
            <h1 class="text-6xl md:text-8xl font-black tracking-tighter text-white italic uppercase leading-[0.85] mb-8">
                Insights <br> <span class="text-outline">&</span> <span class="text-[#019E9A]">Stories.</span>
            </h1>
            <p class="text-white/40 max-w-xl mx-auto text-sm font-medium italic uppercase tracking-widest">
                Eksplorasi proses kreatif dan pemikiran terbaru kami.
            </p>
        </div>
    </header>

    <main class="max-w-7xl mx-auto py-24 px-6">

        <div class="mb-20 flex flex-col lg:flex-row justify-between items-center gap-8">
            <div class="flex items-center gap-2 bg-slate-100 p-1.5 rounded-full overflow-x-auto max-w-full no-scrollbar shadow-inner">
                <button @click="setCategory('')"
                    :class="activeCat == '' ? 'bg-white shadow-sm text-[#333A73]' : 'text-slate-400'"
                    class="px-8 py-3 rounded-full text-[10px] font-black uppercase tracking-widest transition-all">
                    All Feed
                </button>
                <?php
                $q_cats = mysqli_query($conn, "SELECT DISTINCT category FROM articles WHERE status = 'published'");
                while ($c = mysqli_fetch_assoc($q_cats)): ?>
                    <button @click="setCategory('<?= $c['category'] ?>')"
                        :class="activeCat == '<?= $c['category'] ?>' ? 'bg-white shadow-sm text-[#019E9A]' : 'text-slate-400'"
                        class="px-8 py-3 rounded-full text-[10px] font-black uppercase tracking-widest transition-all whitespace-nowrap">
                        <?= $c['category'] ?>
                    </button>
                <?php endwhile; ?>
            </div>

            <div class="relative w-full lg:w-[400px]">
                <input type="text" x-model.debounce.500ms="searchQuery" placeholder="Search by title..."
                    class="w-full bg-white border-none rounded-full px-8 py-4 text-xs font-bold shadow-2xl shadow-slate-200/50 focus:ring-2 ring-[#019E9A] outline-none pr-14">
                <div class="absolute right-5 top-1/2 -translate-y-1/2 text-slate-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="3" stroke-linecap="round" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12 lg:gap-16">
            <template x-for="art in articles" :key="art.id">
                <article class="group fade-up bg-white rounded-[3.5rem] p-5 shadow-sm hover:shadow-2xl transition-all duration-500">
                    <a :href="'article-detail?slug=' + art.slug" class="block">
                        <div class="relative aspect-[16/11] rounded-[2.8rem] overflow-hidden mb-8 bg-slate-100">
                            <img :src="'../uploads/articles/' + art.cover_image"
                                class="w-full h-full object-cover transition-all duration-1000 group-hover:scale-105">
                            <div class="absolute top-5 left-5">
                                <span class="bg-white/90 backdrop-blur-md text-[#333A73] text-[8px] font-black uppercase tracking-[0.2em] px-4 py-2 rounded-full shadow-sm" x-text="art.category"></span>
                            </div>
                        </div>
                    </a>

                    <div class="space-y-5 px-3 pb-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full overflow-hidden bg-[#019E9A]/10 border border-slate-50 flex items-center justify-center flex-shrink-0">
                                    <template x-if="art.profile_picture">
                                        <img :src="'../admin/uploads/profiles/' + art.profile_picture"
                                            class="w-full h-full object-cover">
                                    </template>
                                    <template x-if="!art.profile_picture">
                                        <span class="text-[10px] font-black text-[#019E9A]" x-text="art.fullname.charAt(0)"></span>
                                    </template>
                                </div>

                                <div class="flex flex-col">
                                    <span class="text-[9px] font-black text-[#333A73] uppercase tracking-widest leading-none" x-text="art.fullname"></span>
                                    <span class="text-[8px] font-bold text-slate-300 uppercase italic mt-1" x-text="art.date_formatted"></span>
                                </div>
                            </div>

                            <button @click="openShare(art)" class="w-9 h-9 rounded-full bg-slate-50 text-slate-400 hover:bg-[#FF6136] hover:text-white transition-all flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" stroke-width="2.5" />
                                </svg>
                            </button>
                        </div>

                        <a :href="'article-detail?slug=' + art.slug" class="block">
                            <h3 class="text-xl font-black leading-tight tracking-tight text-[#333A73] group-hover:text-[#019E9A] transition-colors line-clamp-2" x-text="art.title"></h3>
                            <p class="text-slate-500 text-xs mt-3 line-clamp-2 leading-relaxed font-medium" x-text="art.excerpt"></p>
                        </a>
                    </div>
                </article>
            </template>
        </div>

        <div class="mt-24 text-center" x-show="hasMore">
            <button @click="loadArticles()"
                :disabled="loading"
                class="px-14 py-5 rounded-full bg-[#333A73] text-white text-[10px] font-black uppercase tracking-[0.3em] hover:bg-[#FF6136] transition-all shadow-2xl shadow-[#333A73]/20 disabled:opacity-50">
                <span x-text="loading ? 'Loading...' : 'Load More Content'"></span>
            </button>
        </div>

        <div x-show="!loading && articles.length === 0" class="py-40 text-center bg-white rounded-[4rem] border-2 border-dashed border-slate-100" x-cloak>
            <h3 class="text-lg font-black uppercase text-[#333A73] tracking-widest">No matching stories</h3>
        </div>
    </main>

    <div x-show="showModal" x-cloak class="fixed inset-0 z-[100] flex items-end md:items-center justify-center p-0 md:p-6">
        <div x-show="showModal" x-transition.opacity @click="showModal = false" class="absolute inset-0 bg-[#333A73]/60 backdrop-blur-sm"></div>

        <div x-show="showModal"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="translate-y-full md:translate-y-0 md:scale-95 md:opacity-0"
            x-transition:enter-end="translate-y-0 md:scale-100 md:opacity-100"
            class="relative bg-white w-full md:max-w-sm rounded-t-[3.5rem] md:rounded-[3.5rem] p-10 shadow-2xl overflow-hidden">

            <div class="text-center mb-8">
                <div class="w-12 h-1 bg-slate-100 rounded-full mx-auto mb-6 md:hidden"></div>
                <h4 class="text-xl font-black text-[#333A73] uppercase italic">Spread Insight</h4>
                <p class="text-slate-400 text-[9px] font-bold uppercase tracking-widest mt-2">Bagikan ke media sosial favoritmu</p>
            </div>

            <div class="grid grid-cols-4 gap-4">
                <button @click="share('wa')" class="flex flex-col items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-green-50 text-green-500 flex items-center justify-center hover:bg-green-500 hover:text-white transition-all shadow-sm">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                        </svg>
                    </div>
                </button>

                <button @click="share('fb')" class="flex flex-col items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                        </svg>
                    </div>
                </button>

                <button @click="share('tw')" class="flex flex-col items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-slate-50 text-black flex items-center justify-center hover:bg-black hover:text-white transition-all shadow-sm">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.134l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                        </svg>
                    </div>
                </button>

                <button @click="share('ig')" class="flex flex-col items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center hover:bg-purple-600 hover:text-white transition-all shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" stroke-width="2" stroke-linecap="round" />
                        </svg>
                    </div>
                </button>
            </div>

            <button @click="copyLink()" class="w-full mt-6 flex items-center justify-center gap-3 bg-slate-50 py-4 rounded-2xl group hover:bg-[#333A73] transition-all">
                <svg class="w-4 h-4 text-slate-400 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" stroke-width="2" />
                </svg>
                <span class="text-[10px] font-black uppercase tracking-widest text-[#333A73] group-hover:text-white" x-text="copied ? 'Link Copied!' : 'Copy Direct Link'"></span>
            </button>

            <button @click="showModal = false" class="w-full mt-8 text-[9px] font-black uppercase tracking-[0.3em] text-slate-300 hover:text-red-400">Cancel</button>
        </div>
    </div>

    <?php include '_footer.php'; ?>

    <script>
        function articleLoader(initialCat, initialSearch) {
            // Daftar Caption Variatif
            const randomCaptions = [
                "Wow, kamu harus lihat artikel ini! 🔥",
                "Gokil! Insight di artikel ini bener-bener ngebuka pikiran. 💡",
                "Nemuin bacaan keren di DangDang Journal, cek deh! 🚀",
                "Lagi cari inspirasi? Artikel ini jawabannya. ✨",
                "Baru aja baca ini dan langsung kepikiran buat share ke kamu. 📌",
                "Ini sih gila banget informasinya, wajib baca! 💎"
            ];

            return {
                articles: [],
                offset: 0,
                loading: false,
                hasMore: true,
                activeCat: initialCat,
                searchQuery: initialSearch,

                // Modal State
                showModal: false,
                activeArt: null,
                copied: false,

                async loadArticles(reset = false) {
                    if (this.loading) return;
                    this.loading = true;

                    if (reset) {
                        this.articles = [];
                        this.offset = 0;
                        this.hasMore = true;
                    }

                    try {
                        const res = await fetch(`api-articles.php?offset=${this.offset}&category=${encodeURIComponent(this.activeCat)}&search=${encodeURIComponent(this.searchQuery)}`);
                        const data = await res.json();

                        if (data.length < 10) this.hasMore = false;

                        this.articles.push(...data);
                        this.offset += 10;
                    } catch (e) {
                        console.error("Load failed", e);
                    } finally {
                        this.loading = false;
                    }
                },

                setCategory(cat) {
                    this.activeCat = cat;
                    this.loadArticles(true);
                    const url = new URL(window.location);
                    cat ? url.searchParams.set('category', cat) : url.searchParams.delete('category');
                    window.history.pushState({}, '', url);
                },

                openShare(art) {
                    this.activeArt = art;
                    this.showModal = true;
                    this.copied = false;
                },

                share(platform) {
                    const art = this.activeArt;
                    const url = `${window.location.origin}/article-detail?slug=${art.slug}`;

                    // Mengambil caption acak
                    const randomIntro = randomCaptions[Math.floor(Math.random() * randomCaptions.length)];

                    // Format Pesan: Intro + Judul + URL
                    const fullText = `${randomIntro}\n\n"${art.title}"\n\nBaca selengkapnya di DangDang Studio: `;

                    const encodedMsg = encodeURIComponent(fullText);
                    const encodedUrl = encodeURIComponent(url);

                    const links = {
                        wa: `https://api.whatsapp.com/send?text=${encodedMsg}${encodedUrl}`,
                        tw: `https://twitter.com/intent/tweet?text=${encodedMsg}&url=${encodedUrl}`,
                        fb: `https://www.facebook.com/sharer/sharer.php?u=${encodedUrl}`,
                        ig: `https://www.instagram.com/direct/inbox/`
                    };
                    window.open(links[platform], '_blank');
                },

                copyLink() {
                    const art = this.activeArt;
                    const url = `${window.location.origin}/article-detail?slug=${art.slug}`;

                    // Mengambil caption acak untuk copy link juga agar seru
                    const randomIntro = randomCaptions[Math.floor(Math.random() * randomCaptions.length)];
                    const textToCopy = `${randomIntro}\n\n"${art.title}"\n\nLink: ${url}`;

                    navigator.clipboard.writeText(textToCopy).then(() => {
                        this.copied = true;
                        // Beri feedback visual sebentar sebelum tutup modal
                        setTimeout(() => {
                            this.showModal = false;
                            this.copied = false;
                        }, 1200);
                    });
                },

                init() {
                    this.loadArticles();
                    this.$watch('searchQuery', () => this.loadArticles(true));
                }
            }
        }
    </script>
</body>

</html>