<?php
require_once '../admin/process/config.php';

$slug = isset($_GET['slug']) ? mysqli_real_escape_string($conn, $_GET['slug']) : '';

// 1. Query Artikel Utama
$query = "SELECT articles.*, admins.fullname, admins.profile_picture 
          FROM articles 
          LEFT JOIN admins ON articles.admin_id = admins.id 
          WHERE articles.slug = '$slug' AND articles.status = 'published'";
$result = mysqli_query($conn, $query);
$art = mysqli_fetch_assoc($result);

if (!$art) {
    header("Location: articles.php");
    exit;
}

// 2. Query Gallery
$article_id = $art['id'];
$gallery_result = mysqli_query($conn, "SELECT * FROM article_gallery WHERE article_id = '$article_id' ORDER BY uploaded_at DESC");

// 3. Query Rekomendasi Artikel (Acak & Published)
$rec_result = mysqli_query($conn, "SELECT * FROM articles 
                                  WHERE status = 'published' 
                                  AND id != '$article_id' 
                                  ORDER BY RAND() 
                                  LIMIT 3");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $art['title'] ?> | DangDang Journal</title>
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
        .article-content {
            white-space: pre-line;
        }

        .article-content p {
            margin-bottom: 1.8rem;
            font-size: 1.1rem;
            line-height: 1.8;
            color: #475569;
        }

        .article-content h2,
        .article-content h3 {
            font-weight: 800;
            color: #333A73;
            margin-top: 2.5rem;
            margin-bottom: 1rem;
            text-transform: uppercase;
            font-style: italic;
        }

        [x-cloak] {
            display: none !important;
        }

        /* --- PRINT OPTIMIZATION --- */
        @media print {
            @page {
                margin: 2cm;
            }

            .no-print {
                display: none !important;
            }

            body {
                background: white !important;
                color: black !important;
            }

            /* Reset layout grid agar tidak berantakan di kertas */
            .max-w-7xl,
            .max-w-4xl,
            .max-w-6xl {
                max-width: 100% !important;
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            header {
                padding-top: 0 !important;
                margin-bottom: 2rem !important;
            }

            h1 {
                font-size: 2.5rem !important;
                line-height: 1.2 !important;
                color: black !important;
            }

            /* Pastikan konten artikel memenuhi lebar kertas */
            .grid {
                display: block !important;
            }

            main {
                width: 100% !important;
                margin: 0 !important;
            }

            .article-content p {
                font-size: 12pt !important;
                color: black !important;
                line-height: 1.6 !important;
            }

            /* Sembunyikan gallery saat print agar hemat tinta, atau tampilkan secara rapi */
            .gallery-print {
                display: grid !important;
                grid-template-columns: repeat(2, 1fr) !important;
                gap: 10px !important;
                margin-top: 2rem !important;
            }

            img {
                max-width: 100% !important;
                border-radius: 1rem !important;
            }
        }
    </style>
</head>

<body x-data="detailHandler()">

    <?php include '_navbar.php'; ?>

    <div class="fixed top-0 left-0 w-full h-1 z-[110] no-print">
        <div class="h-full bg-[#019E9A] transition-all duration-150" :style="'width: ' + scrollPercent + '%'"></div>
    </div>


    <header class="pt-48 pb-16 px-6">
        <div class="max-w-6xl mx-auto px-6 mb-20">
            <div class="rounded-[3rem] md:rounded-[5rem] overflow-hidden aspect-[16/9] shadow-2xl">
                <img src="../uploads/articles/<?= $art['cover_image'] ?>" class="w-full h-full object-cover">
            </div>
        </div>
        <div class="max-w-4xl mx-auto text-center">
            <span class="inline-block px-5 py-2 rounded-full bg-slate-100 text-[#333A73] text-[9px] font-black uppercase tracking-[0.3em] mb-8"><?= $art['category'] ?></span>
            <h1 class="text-4xl md:text-6xl font-[900] text-[#333A73] leading-tight tracking-tighter italic uppercase mb-10"><?= $art['title'] ?></h1>

            <div class="flex items-center justify-center gap-6 py-8 border-y border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full overflow-hidden bg-slate-100 border-2 border-white shadow-sm no-print">
                        <?php if ($art['profile_picture']): ?>
                            <img src="../admin/uploads/profiles/<?= $art['profile_picture'] ?>" class="w-full h-full object-cover">
                        <?php else: ?><div class="w-full h-full flex items-center justify-center font-black text-[#019E9A] bg-[#019E9A]/10"><?= substr($art['fullname'], 0, 1) ?></div><?php endif; ?>
                    </div>
                    <div class="text-left">
                        <p class="text-[8px] text-slate-400 font-bold uppercase tracking-widest leading-none mb-1">Author</p>
                        <p class="text-[10px] font-black text-[#333A73] uppercase italic"><?= $art['fullname'] ?></p>
                    </div>
                </div>
                <div class="w-px h-8 bg-slate-100"></div>
                <div class="text-left">
                    <p class="text-[8px] text-slate-400 font-bold uppercase tracking-widest leading-none mb-1">Date</p>
                    <p class="text-[10px] font-black text-[#333A73] uppercase italic"><?= date('M d, Y', strtotime($art['created_at'])) ?></p>
                </div>
            </div>
        </div>
    </header>



    <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-12 gap-12 relative">

        <aside class="lg:col-span-1 no-print">
            <div class="sticky top-32 flex lg:flex-col gap-3 justify-center">
                <button @click="window.print()" class="w-12 h-12 rounded-2xl bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:text-[#333A73] hover:shadow-lg transition-all" title="Print Article">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" stroke-width="2" />
                    </svg>
                </button>
                <div class="h-px w-8 lg:w-full bg-slate-100 my-2 self-center"></div>
                <button @click="openShare()" class="w-12 h-12 rounded-2xl bg-[#333A73] text-white flex items-center justify-center hover:bg-[#FF6136] shadow-lg transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" stroke-width="2" />
                    </svg>
                </button>
            </div>
        </aside>

        <main class="lg:col-span-8 lg:col-start-3">
            <div class="article-content prose-slate max-w-none mb-32">
                <?= htmlspecialchars(string: $art['content']) ?>
            </div>

            <?php if (mysqli_num_rows($gallery_result) > 0): ?>
                <div class="mb-32" x-data="{ activeImg: null }">
                    <h3 class="text-2xl font-black text-[#333A73] italic mb-10 uppercase tracking-tight">Visual Journal</h3>

                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 no-print">
                        <?php
                        mysqli_data_seek($gallery_result, 0); // Reset pointer loop
                        while ($img = mysqli_fetch_assoc($gallery_result)):
                        ?>
                            <div class="aspect-square rounded-[2rem] overflow-hidden cursor-pointer group"
                                @click="activeImg = '<?= $img['image_url'] ?>'">
                                <img src="../uploads/articles/gallery/<?= $img['image_url'] ?>"
                                    class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            </div>
                        <?php endwhile; ?>
                    </div>

                    <div class="hidden print:grid grid-cols-2 gap-4">
                        <?php
                        mysqli_data_seek($gallery_result, 0); // Reset pointer loop lagi
                        while ($img = mysqli_fetch_assoc($gallery_result)):
                        ?>
                            <img src="../uploads/articles/gallery/<?= $img['image_url'] ?>" class="rounded-xl w-full">
                        <?php endwhile; ?>
                    </div>

                    <template x-if="activeImg">
                        <div x-show="activeImg"
                            x-cloak
                            class="no-print fixed inset-0 z-[200] bg-[#333A73]/95 backdrop-blur-md flex items-center justify-center p-6"
                            @click="activeImg = null"
                            @keydown.escape.window="activeImg = null">
                            <div class="relative max-w-5xl max-h-full">
                                <img :src="'../uploads/articles/gallery/' + activeImg"
                                    class="max-w-full max-h-[90vh] rounded-2xl shadow-2xl object-contain">
                                <button class="absolute -top-12 right-0 text-white font-bold uppercase tracking-widest text-xs">Click anywhere to close</button>
                            </div>
                        </div>
                    </template>
                </div>
            <?php endif; ?>
        </main>
    </div>

    <?php if (mysqli_num_rows($rec_result) > 0): ?>
        <section class="bg-slate-50 py-24 px-6 no-print">
            <div class="max-w-7xl mx-auto">
                <div class="flex items-center justify-between mb-12">
                    <h3 class="text-2xl font-[900] text-[#333A73] italic uppercase tracking-tighter">Random Discovery</h3>
                    <a href="articles.php" class="text-[10px] font-black uppercase tracking-widest text-[#019E9A] border-b-2 border-[#019E9A]/20 pb-1">Explore Journal</a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                    <?php while ($rec = mysqli_fetch_assoc($rec_result)): ?>
                        <a href="article-detail?slug=<?= $rec['slug'] ?>" class="group block bg-white p-4 rounded-[2.5rem] shadow-sm hover:shadow-xl transition-all">
                            <div class="aspect-[16/10] rounded-[2rem] overflow-hidden mb-6">
                                <img src="../uploads/articles/<?= $rec['cover_image'] ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                            </div>
                            <div class="px-2">
                                <span class="text-[8px] font-black text-[#019E9A] uppercase tracking-widest mb-2 block"><?= $rec['category'] ?></span>
                                <h4 class="text-lg font-black text-[#333A73] group-hover:text-[#FF6136] transition-colors line-clamp-2 uppercase italic leading-tight"><?= $rec['title'] ?></h4>
                            </div>
                        </a>
                    <?php endwhile; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <div x-show="showModal" x-cloak class="fixed inset-0 z-[300] flex items-end md:items-center justify-center no-print">
        <div x-show="showModal" x-transition.opacity @click="showModal = false" class="absolute inset-0 bg-[#333A73]/60 backdrop-blur-sm"></div>
        <div x-show="showModal" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="translate-y-full md:translate-y-10 md:opacity-0" x-transition:enter-end="translate-y-0 md:opacity-100"
            class="relative bg-white w-full md:max-w-sm rounded-t-[3rem] md:rounded-[3rem] p-10 shadow-2xl text-center">
            <div class="w-12 h-1 bg-slate-100 rounded-full mx-auto mb-6 md:hidden"></div>
            <h4 class="text-xl font-black text-[#333A73] uppercase italic mb-8">Share the magic</h4>
            <div class="grid grid-cols-4 gap-4 mb-8">
                <button @click="share('wa')" class="flex flex-col items-center gap-2 group">
                    <div class="w-12 h-12 rounded-2xl bg-green-50 text-green-500 flex items-center justify-center group-hover:bg-green-500 group-hover:text-white transition-all">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                        </svg>
                    </div>
                </button>
                <button @click="share('tw')" class="flex flex-col items-center gap-2 group">
                    <div class="w-12 h-12 rounded-2xl bg-slate-50 text-black flex items-center justify-center group-hover:bg-black group-hover:text-white transition-all">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.134l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                        </svg>
                    </div>
                </button>
                <button @click="share('fb')" class="flex flex-col items-center gap-2 group">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-all">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                        </svg>
                    </div>
                </button>
                <button @click="share('ig')" class="flex flex-col items-center gap-2 group">
                    <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center group-hover:bg-purple-600 group-hover:text-white transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" stroke-width="2" stroke-linecap="round" />
                        </svg>
                    </div>
                </button>
            </div>
            <button @click="copyLink()" class="w-full bg-slate-50 py-4 rounded-2xl group hover:bg-[#333A73] transition-all flex items-center justify-center gap-3">
                <span class="text-[10px] font-black uppercase tracking-widest text-[#333A73] group-hover:text-white" x-text="copied ? 'Copied!' : 'Copy Link with Caption'"></span>
            </button>
        </div>
    </div>

    <?php include '_footer.php'; ?>

    <script>
        function detailHandler() {
            const randomCaptions = ["Wow, kamu harus lihat artikel ini! 🔥", "Gokil! Insight ini bener-bener ngebuka pikiran. 💡", "Nemuin bacaan keren di DangDang Journal, cek deh! 🚀", "Lagi cari inspirasi? Artikel ini jawabannya. ✨", "Baru aja baca ini dan langsung kepikiran buat share ke kamu. 📌", "Ini sih gila banget informasinya, wajib baca! 💎"];

            return {
                showModal: false,
                copied: false,
                scrollPercent: 0,
                artData: {
                    title: '<?= addslashes($art['title']) ?>',
                    slug: '<?= $art['slug'] ?>'
                },

                openShare() {
                    this.showModal = true;
                    this.copied = false;
                },

                share(platform) {
                    const url = `${window.location.origin}/article-detail?slug=${this.artData.slug}`;
                    const intro = randomCaptions[Math.floor(Math.random() * randomCaptions.length)];
                    const text = `${intro}\n\n"${this.artData.title}"\n\nBaca di DangDang Studio: `;
                    const links = {
                        wa: `https://api.whatsapp.com/send?text=${encodeURIComponent(text)}${encodeURIComponent(url)}`,
                        tw: `https://twitter.com/intent/tweet?text=${encodeURIComponent(text)}&url=${encodeURIComponent(url)}`,
                        fb: `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`,
                        ig: `https://www.instagram.com/direct/inbox/`
                    };
                    window.open(links[platform], '_blank');
                },

                copyLink() {
                    const url = `${window.location.origin}/article-detail?slug=${this.artData.slug}`;
                    const intro = randomCaptions[Math.floor(Math.random() * randomCaptions.length)];
                    navigator.clipboard.writeText(`${intro}\n\n"${this.artData.title}"\n\nLink: ${url}`);
                    this.copied = true;
                    setTimeout(() => {
                        this.showModal = false;
                        this.copied = false;
                    }, 1200);
                },

                updateScroll() {
                    const h = document.documentElement,
                        b = document.body,
                        st = 'scrollTop',
                        sh = 'scrollHeight';
                    this.scrollPercent = (h[st] || b[st]) / ((h[sh] || b[sh]) - h.clientHeight) * 100;
                },

                init() {
                    window.addEventListener('scroll', () => this.updateScroll());
                }
            }
        }
    </script>
</body>

</html>