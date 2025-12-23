<?php
// homepage.php - Lokasi: /public/homepage.php
require_once '../admin/process/config.php';

/** * Data Fetching berdasarkan struktur SQL:
 * - Mengambil games untuk section portfolio
 * - Mengambil articles untuk section insight
 * - Mengambil testimonials untuk social proof
 */
$q_games = mysqli_query($conn, "SELECT * FROM games ORDER BY created_at DESC LIMIT 3");
$q_articles = mysqli_query($conn, "SELECT * FROM articles WHERE status = 'Published' ORDER BY created_at DESC LIMIT 3");
$q_testimonials = mysqli_query($conn, "SELECT * FROM testimonials WHERE is_featured = 1 ORDER BY created_at DESC LIMIT 3");
?>

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DangDang Studio | The 2025 Gaming Experience</title>
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
                    borderRadius: {
                        '4xl': '2rem',
                        '5xl': '3.5rem',
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #fcfcfd; color: #333A73; }
        .modern-card { 
            background: white; 
            border: 1px solid rgba(51, 58, 115, 0.05); 
            border-radius: 3rem;
            transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .modern-card:hover { 
            transform: translateY(-12px) scale(1.02); 
            box-shadow: 0 50px 100px -20px rgba(51, 58, 115, 0.15); 
        }
        .nav-glass { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(25px); border: 1px solid rgba(255, 255, 255, 0.4); }
        .text-gradient { background: linear-gradient(135deg, #019E9A, #333A73); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    </style>
</head>
<body class="antialiased">

    <nav class="fixed w-full z-50 px-6 py-6">
        <div class="max-w-7xl mx-auto nav-glass px-10 py-5 rounded-full flex justify-between items-center shadow-sm">
            <div class="text-2xl font-extrabold tracking-tighter flex items-center gap-2">
                <span class="w-3 h-3 bg-brandCoral rounded-full"></span>
                DANGDANG<span class="text-brandTeal italic">STUDIO</span>
            </div>
            <div class="hidden lg:flex gap-10 text-[10px] font-black uppercase tracking-[0.3em] text-brandDark/50">
                <a href="#about" class="hover:text-brandCoral transition">Philosophy</a>
                <a href="#games" class="hover:text-brandCoral transition">Works</a>
                <a href="#testimonials" class="hover:text-brandCoral transition">Reviews</a>
                <a href="#articles" class="hover:text-brandCoral transition">Insight</a>
                <a href="#contact" class="hover:text-brandCoral transition">Contact</a>
            </div>
            <a href="../admin/login.php" class="bg-brandDark text-white px-8 py-3 rounded-full text-[10px] font-black uppercase tracking-widest hover:bg-brandTeal transition shadow-lg">Portal Admin</a>
        </div>
    </nav>

    <header class="pt-52 pb-24 px-6 text-center relative overflow-hidden">
        <div class="max-w-4xl mx-auto space-y-10 relative z-10">
            <div class="inline-block px-6 py-2 rounded-full bg-brandTeal/5 border border-brandTeal/10 text-brandTeal text-[10px] font-black uppercase tracking-[0.4em]">
                Elevating Independent Games
            </div>
            <h1 class="text-7xl md:text-[110px] font-extrabold leading-[0.85] tracking-tighter text-brandDark uppercase italic">
                Art of <br> <span class="text-gradient">Gaming.</span>
            </h1>
            <p class="text-xl text-slate-400 font-medium max-w-2xl mx-auto leading-relaxed">
                Dari baris kode hingga mahakarya visual. Kami membangun pengalaman interaktif masa depan.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center pt-5">
                <a href="#contact" class="bg-brandCoral text-white px-12 py-5 rounded-full font-black uppercase text-xs tracking-widest shadow-2xl shadow-brandCoral/30 hover:scale-105 transition transform">Start Collaboration</a>
                <a href="#games" class="bg-white text-brandDark border border-slate-100 px-12 py-5 rounded-full font-black uppercase text-xs tracking-widest hover:bg-slate-50 transition">Explore Games</a>
            </div>
        </div>
    </header>

    <section id="about" class="py-24 px-6">
        <div class="max-w-7xl mx-auto">
            <div class="grid lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 modern-card p-16 flex flex-col justify-end min-h-[450px] bg-white">
                    <span class="text-brandCoral font-black uppercase text-[10px] tracking-[0.3em] mb-4">Who We Are</span>
                    <h2 class="text-5xl font-black mb-6 uppercase italic leading-none text-brandDark">The Intersection <br> of <span class="text-brandTeal">Code</span> & <span class="text-brandGold italic">Art.</span></h2>
                    <p class="text-slate-500 font-medium max-w-xl">DangDang Studio adalah kolektif kreatif yang berdedikasi untuk menciptakan game yang meninggalkan jejak emosional bagi pemainnya.</p>
                </div>
                <div class="bg-brandDark p-16 rounded-5xl text-white flex flex-col justify-between">
                    <div class="w-14 h-14 bg-brandGold rounded-3xl rotate-12 shadow-xl shadow-brandGold/20"></div>
                    <div>
                        <p class="text-5xl font-black italic mb-2 tracking-tighter">Est. 2024</p>
                        <p class="text-[10px] uppercase font-bold tracking-[0.3em] opacity-50 italic">Crafting Excellence</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="games" class="py-24 px-6 bg-slate-50/50">
        <div class="max-w-7xl mx-auto">
            <div class="flex justify-between items-end mb-16 px-4">
                <h2 class="text-5xl font-black uppercase italic tracking-tighter">Featured <br><span class="text-brandTeal">Works.</span></h2>
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-300">Our Digital Library</p>
            </div>
            <div class="grid md:grid-cols-3 gap-10">
                <?php while($g = mysqli_fetch_assoc($q_games)): ?>
                <div class="modern-card p-4 pb-12">
                    <div class="aspect-[4/5] rounded-[2.5rem] overflow-hidden mb-8 shadow-inner relative group">
                        <img src="../admin/uploads/games/<?= $g['header_image'] ?>" onerror="this.src='https://images.unsplash.com/photo-1542751371-adc38448a05e?q=80&w=600'" class="w-full h-full object-cover grayscale group-hover:grayscale-0 group-hover:scale-110 transition duration-1000">
                        <div class="absolute inset-0 bg-gradient-to-t from-brandDark/80 to-transparent opacity-0 group-hover:opacity-100 transition duration-500 flex items-end p-8">
                            <span class="text-white text-[10px] font-black uppercase tracking-widest italic border border-white/20 px-4 py-2 rounded-full">Play Trailer</span>
                        </div>
                    </div>
                    <div class="px-6">
                        <span class="text-brandCoral font-black text-[9px] uppercase tracking-widest"><?= $g['category'] ?></span>
                        <h3 class="text-2xl font-black uppercase italic text-brandDark mt-1"><?= $g['title'] ?></h3>
                        <p class="text-slate-400 text-[11px] mt-3 line-clamp-2"><?= $g['short_desc'] ?></p>
                        <div class="mt-8 flex items-center gap-3 group cursor-pointer">
                            <span class="text-[10px] font-black uppercase text-brandDark group-hover:text-brandTeal transition">View Project</span>
                            <div class="h-[1px] flex-1 bg-slate-100 group-hover:bg-brandTeal transition-all"></div>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <section id="testimonials" class="py-32 px-6">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-24">
                <h2 class="text-5xl font-black uppercase italic tracking-tighter mb-4 text-brandDark">Voices of <br><span class="text-brandCoral">Trust.</span></h2>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <?php while($tes = mysqli_fetch_assoc($q_testimonials)): ?>
                <div class="modern-card p-12 flex flex-col justify-between min-h-[400px] bg-white shadow-sm border-none">
                    <div class="space-y-8">
                        <div class="flex gap-1">
                            <?php for($i=1;$i<=$tes['stars'];$i++): ?>
                                <svg class="w-5 h-5 text-brandGold" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <?php endfor; ?>
                        </div>
                        <p class="text-xl font-bold italic text-brandDark/80 leading-relaxed">"<?= $tes['content'] ?>"</p>
                    </div>
                    
                    <div class="flex items-center gap-5 pt-10 border-t border-slate-50">
                        <div class="w-14 h-14 rounded-full bg-brandTeal text-white flex items-center justify-center font-black text-xl shadow-lg shadow-brandTeal/20">
                            <?= substr($tes['name'], 0, 1) ?>
                        </div>
                        <div>
                            <h4 class="font-black uppercase text-sm italic tracking-tight"><?= $tes['name'] ?></h4>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest"><?= $tes['role'] ?></p>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <section id="articles" class="py-32 px-6 bg-brandDark rounded-t-[5rem] text-white">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col md:flex-row justify-between items-end mb-20 gap-8">
                <div>
                    <h2 class="text-5xl font-black uppercase italic tracking-tighter">Latest <br><span class="text-brandTeal">Insight.</span></h2>
                </div>
                <div class="text-right">
                    <p class="text-[11px] font-black uppercase tracking-[0.3em] text-white/40 mb-2">Dev Logs & News</p>
                    <div class="h-1 w-24 bg-brandCoral ml-auto"></div>
                </div>
            </div>

            <div class="grid md:grid-cols-3 gap-12">
                <?php while($art = mysqli_fetch_assoc($q_articles)): ?>
                <div class="group cursor-pointer">
                    <div class="aspect-video rounded-4xl overflow-hidden shadow-2xl mb-8 relative">
                        <img src="../admin/uploads/articles/<?= $art['cover_image'] ?>" onerror="this.src='https://images.unsplash.com/photo-1550745165-9bc0b252726f?q=80&w=800'" class="w-full h-full object-cover opacity-50 group-hover:opacity-100 group-hover:scale-105 transition duration-700">
                        <div class="absolute top-6 left-6">
                            <span class="bg-brandTeal text-white px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest shadow-xl">
                                <?= $art['category'] ?>
                            </span>
                        </div>
                    </div>
                    <div class="space-y-4 px-2">
                        <span class="text-brandGold font-bold text-[10px] uppercase tracking-[0.2em]"><?= date('F d, Y', strtotime($art['created_at'])) ?></span>
                        <h4 class="text-2xl font-black uppercase italic group-hover:text-brandTeal transition leading-tight"><?= $art['title'] ?></h4>
                        <p class="text-sm text-white/50 leading-relaxed line-clamp-3 font-medium">
                            <?= strip_tags($art['content']) ?>
                        </p>
                        <div class="pt-4">
                            <a href="article-detail.php?slug=<?= $art['slug'] ?>" class="text-[10px] font-black uppercase tracking-widest text-brandCoral flex items-center gap-2 group-hover:gap-4 transition-all">
                                Read Details 
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                            </a>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <section id="contact" class="py-40 px-6">
        <div class="max-w-5xl mx-auto bg-white p-20 rounded-[4rem] text-center shadow-2xl relative overflow-hidden border border-slate-100">
            <div class="absolute -top-10 -right-10 w-40 h-40 bg-brandGold/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-brandTeal/10 rounded-full blur-3xl"></div>
            
            <span class="text-brandCoral font-black uppercase text-xs tracking-[0.4em] mb-10 block">Available for New Projects</span>
            <h2 class="text-6xl md:text-8xl font-black tracking-tighter text-brandDark italic uppercase leading-[0.9] mb-12">Start your <br>Journey <span class="text-brandTeal">Today.</span></h2>
            
            <div class="flex flex-col md:flex-row gap-6 justify-center items-center">
                <a href="mailto:hello@dangdang.com" class="bg-brandDark text-white px-12 py-6 rounded-full font-black uppercase text-xs tracking-widest hover:bg-brandCoral transition shadow-xl shadow-brandDark/10">Get in Touch</a>
                <a href="#" class="text-2xl font-black italic text-brandDark hover:text-brandTeal transition">@dangdangstudio</a>
            </div>
        </div>
    </section>

    <footer class="py-16 px-6 border-t border-slate-100">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-10">
            <div class="text-2xl font-black tracking-tighter italic text-brandDark">DANGDANG<span class="text-brandTeal">STUDIO</span></div>
            <div class="flex gap-10 text-[9px] font-black uppercase tracking-[0.3em] text-slate-400">
                <a href="#" class="hover:text-brandTeal transition">Instagram</a>
                <a href="#" class="hover:text-brandTeal transition">Twitter</a>
                <a href="#" class="hover:text-brandTeal transition">LinkedIn</a>
            </div>
            <p class="text-[9px] font-black uppercase tracking-widest text-slate-300">© 2025 DangDang Studio. Designed for the Future.</p>
        </div>
    </footer>

</body>
</html>