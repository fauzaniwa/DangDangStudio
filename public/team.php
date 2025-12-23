<?php
// team.php - Lokasi: /public/team.php
require_once '../admin/process/config.php';

/**
 * MENGAMBIL DATA TIM DENGAN JOIN
 * Kita mengambil color_class dari tabel 'teams' untuk styling kartu per divisi
 */
$query = "SELECT t.*, ts.color_class 
          FROM team t 
          LEFT JOIN teams ts ON t.division = ts.team_name 
          WHERE t.status = 'Active' 
          ORDER BY t.division ASC, t.name ASC";
$result = mysqli_query($conn, $query);

$grouped_team = [];
$all_divisions = [];

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $grouped_team[$row['division']][] = $row;
        if (!in_array($row['division'], $all_divisions)) {
            $all_divisions[] = $row['division'];
        }
    }
} else {
    // FALLBACK DATA (Jika DB Kosong)
    $all_divisions = ['Creative', 'Technology'];
    $grouped_team['Creative'] = [
        ['name' => 'Aang Dang', 'level' => 'Founder', 'member_image' => 'aang.jpg', 'division' => 'Creative', 'color_class' => 'bg-brandCoral'],
        ['name' => 'Putri Utami', 'level' => 'Illustrator', 'member_image' => 'putri.jpg', 'division' => 'Creative', 'color_class' => 'bg-brandCoral']
    ];
    $grouped_team['Technology'] = [
        ['name' => 'Sarah Wijaya', 'level' => 'Lead Dev', 'member_image' => 'sarah.jpg', 'division' => 'Technology', 'color_class' => 'bg-brandTeal']
    ];
}
?>

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Team | DangDang Studio</title>
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
                    borderRadius: { '4xl': '2rem', '5xl': '3.5rem' }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; color: #333A73; overflow-x: hidden; }
        .nav-glass { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.3); }
        .filter-btn.active { background-color: #333A73; color: white; border-color: #333A73; transform: scale(1.05); }
        .team-item { transition: all 0.6s cubic-bezier(0.23, 1, 0.32, 1); }
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


    <section class="pt-44 pb-20 px-6">
        <div class="max-w-7xl mx-auto flex flex-col items-center text-center">
            <div class="inline-flex items-center gap-3 bg-white px-5 py-2 rounded-full shadow-sm mb-8 border border-slate-100">
                <span class="text-[10px] font-black uppercase tracking-widest text-brandTeal italic">Our Human Capital</span>
            </div>
            <h1 class="text-6xl md:text-[100px] font-black tracking-tighter leading-[0.85] text-brandDark uppercase italic">
                THE BRAIN <br> OF <span class="text-brandCoral">IMAGINATION.</span>
            </h1>
        </div>
    </section>

    <section class="pb-16 px-6">
        <div class="max-w-7xl mx-auto flex flex-wrap justify-center gap-3">
            <button onclick="filterTeam('all')" class="filter-btn active px-8 py-4 rounded-full border border-slate-200 bg-white text-[10px] font-black uppercase tracking-widest transition-all">
                All Departments
            </button>
            <?php foreach ($all_divisions as $div): ?>
            <button onclick="filterTeam('<?= $div ?>')" class="filter-btn px-8 py-4 rounded-full border border-slate-200 bg-white text-[10px] font-black uppercase tracking-widest hover:border-brandTeal transition-all">
                <?= $div ?>
            </button>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="pb-40 px-6">
        <div class="max-w-7xl mx-auto">
            <div id="team-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-x-8 gap-y-16">
                <?php foreach ($grouped_team as $division => $members): ?>
                    <?php foreach ($members as $m): ?>
                    <div class="team-item group" data-division="<?= $division ?>">
                        <div class="relative aspect-[3/4] rounded-5xl overflow-hidden mb-8 bg-slate-100 shadow-sm transition-transform duration-500 group-hover:-translate-y-4">
                            <img src="<?= (strpos($m['member_image'], 'http') !== false) ? $m['member_image'] : '../admin/uploads/team/'.$m['member_image'] ?>" 
                                 class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition duration-700" 
                                 alt="<?= $m['name'] ?>"
                                 onerror="this.src='https://via.placeholder.com/600x800?text=Member'">
                            
                            <div class="absolute top-6 right-6">
                                <span class="px-4 py-2 rounded-full text-[8px] font-black uppercase tracking-widest text-white <?= $m['color_class'] ?? 'bg-brandDark' ?> shadow-lg">
                                    <?= $division ?>
                                </span>
                            </div>

                            <div class="absolute inset-0 bg-gradient-to-t from-brandDark/90 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex flex-col justify-end p-10">
                                <p class="text-white/60 text-[10px] font-bold uppercase tracking-widest mb-1"><?= $m['email'] ?? 'hello@dang.com' ?></p>
                                <div class="h-1 w-12 bg-brandCoral rounded-full"></div>
                            </div>
                        </div>
                        
                        <div class="px-2">
                            <h3 class="text-2xl font-black uppercase italic text-brandDark leading-none mb-2"><?= $m['name'] ?></h3>
                            <div class="flex items-center gap-3">
                                <span class="h-[1px] w-8 bg-brandTeal"></span>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]"><?= $m['level'] ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

 <section class="py-24 px-6 bg-brandDark rounded-t-[5rem]">
        <div class="max-w-7xl mx-auto">
            <div class="mb-16 text-center lg:text-left">
                <h2 class="text-4xl md:text-5xl font-black text-white italic uppercase tracking-tighter">STUDIO CULTURE.</h2>
            </div>
            <div class="grid lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 bg-white/5 border border-white/10 p-12 rounded-4xl backdrop-blur-sm">
                    <p class="text-brandGold font-black uppercase tracking-widest text-xs mb-6">#01 Flexibility</p>
                    <h4 class="text-3xl font-bold text-white mb-6">Work from anywhere, <br>create for everywhere.</h4>
                    <p class="text-white/50 leading-relaxed">Kami percaya produktivitas tidak dibatasi oleh dinding kantor. Tim kami tersebar, memberikan perspektif yang beragam pada setiap piksel yang kami buat.</p>
                </div>
                <div class="bg-brandCoral p-12 rounded-4xl flex flex-col justify-between text-white">
                    <h4 class="text-5xl font-black italic">No <br>Ego.</h4>
                    <p class="font-medium opacity-80">Ide terbaik selalu menang, tidak peduli dari siapa ide itu berasal.</p>
                </div>
            </div>
        </div>
    </section>

    <footer class="py-16 px-6 bg-white border-t border-slate-100">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-10">
            <div class="text-xl font-black tracking-tighter italic text-brandDark">DANGDANG<span class="text-brandTeal">STUDIO</span></div>
            <p class="text-[9px] font-black uppercase tracking-widest text-slate-400">© 2025 Creative Department. Bandung, Indonesia.</p>
            <div class="flex gap-8 text-[9px] font-black uppercase tracking-widest italic text-brandDark">
                <a href="#" class="hover:text-brandCoral transition">Instagram</a>
                <a href="#" class="hover:text-brandCoral transition">LinkedIn</a>
            </div>
        </div>
    </footer>

    <script>
        function filterTeam(division) {
            // Update Buttons State
            const btns = document.querySelectorAll('.filter-btn');
            btns.forEach(btn => {
                btn.classList.remove('active');
                if(btn.innerText.trim().toLowerCase() === division.toLowerCase() || (division === 'all' && btn.innerText.includes('All'))) {
                    btn.classList.add('active');
                }
            });

            // Filter Grid Items
            const items = document.querySelectorAll('.team-item');
            items.forEach(item => {
                item.style.opacity = '0';
                item.style.transform = 'scale(0.95)';
                
                setTimeout(() => {
                    if (division === 'all' || item.getAttribute('data-division') === division) {
                        item.style.display = 'block';
                        setTimeout(() => {
                            item.style.opacity = '1';
                            item.style.transform = 'scale(1)';
                        }, 50);
                    } else {
                        item.style.display = 'none';
                    }
                }, 300);
            });
        }
    </script>
</body>
</html>