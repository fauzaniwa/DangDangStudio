<?php
// team.php - Lokasi: /public/team.php
require_once '../admin/process/config.php';

$query = "SELECT t.*, ts.color_class 
          FROM team t 
          LEFT JOIN teams ts ON t.division = ts.team_name 
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
    // FALLBACK DATA
    $all_divisions = ['Creative', 'Technology'];
    $grouped_team['Creative'] = [
        ['name' => 'Aang Dang', 'level' => 'Founder', 'member_image' => 'aang.jpg', 'division' => 'Creative', 'color_class' => 'bg-brandCoral']
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
        .filter-btn.active { background-color: #333A73; color: white; border-color: #333A73; }
        .team-item { transition: all 0.6s cubic-bezier(0.23, 1, 0.32, 1); }
        /* Sembunyikan scrollbar untuk filter mobile */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="antialiased">

    <nav class="fixed w-full z-50 px-4 md:px-6 py-4">
        <div class="max-w-7xl mx-auto nav-glass px-5 md:px-8 py-4 rounded-full flex justify-between items-center shadow-sm">
            <a href="index.php" class="text-lg md:text-2xl font-extrabold tracking-tighter flex items-center gap-2">
                <span class="w-3 h-3 bg-brandCoral rounded-full"></span>
                DANGDANG<span class="text-brandTeal italic">STUDIO</span>
            </a>
            
            <div class="hidden lg:flex gap-8 text-[10px] font-black uppercase tracking-[0.2em] text-brandDark/60">
                <a href="index.php" class="hover:text-brandTeal transition">Home</a>
                <a href="team.php" class="text-brandCoral">Team</a>
                <a href="index.php#contact" class="hover:text-brandTeal transition">Contact</a>
            </div>

            <div class="flex items-center gap-3">
                <a href="../admin/login.php" class="bg-brandDark text-white px-4 md:px-6 py-2 md:py-2.5 rounded-full text-[9px] md:text-[10px] font-bold uppercase tracking-widest hover:bg-brandTeal transition">Portal</a>
                <button class="lg:hidden w-10 h-10 flex items-center justify-center bg-slate-100 rounded-full" onclick="document.getElementById('mobile-nav').classList.toggle('hidden')">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                </button>
            </div>
        </div>

        <div id="mobile-nav" class="hidden lg:hidden mt-4 bg-white rounded-3xl p-6 shadow-2xl border border-slate-100 flex flex-col gap-4 text-center font-bold uppercase text-[10px] tracking-widest">
            <a href="index.php" class="py-3 border-b border-slate-50">Home</a>
            <a href="team.php" class="py-3 border-b border-slate-50 text-brandCoral">Team</a>
            <a href="index.php#contact" class="py-3">Contact</a>
        </div>
    </nav>

    <section class="pt-32 md:pt-44 pb-12 md:pb-20 px-6">
        <div class="max-w-7xl mx-auto flex flex-col items-center text-center">
            <div class="inline-flex items-center gap-3 bg-white px-4 py-2 rounded-full shadow-sm mb-6 border border-slate-100">
                <span class="text-[9px] md:text-[10px] font-black uppercase tracking-widest text-brandTeal italic">Our Human Capital</span>
            </div>
            <h1 class="text-4xl sm:text-6xl md:text-[100px] font-black tracking-tighter leading-[0.9] text-brandDark uppercase italic">
                THE BRAIN <br> OF <span class="text-brandCoral">IMAGINATION.</span>
            </h1>
        </div>
    </section>

    <section class="pb-10 px-6">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-nowrap md:flex-wrap overflow-x-auto no-scrollbar justify-start md:justify-center gap-3 pb-4">
                <button onclick="filterTeam('all')" class="filter-btn active whitespace-nowrap px-6 md:px-8 py-3 md:py-4 rounded-full border border-slate-200 bg-white text-[9px] md:text-[10px] font-black uppercase tracking-widest transition-all">
                    All Departments
                </button>
                <?php foreach ($all_divisions as $div): ?>
                <button onclick="filterTeam('<?= $div ?>')" class="filter-btn whitespace-nowrap px-6 md:px-8 py-3 md:py-4 rounded-full border border-slate-200 bg-white text-[9px] md:text-[10px] font-black uppercase tracking-widest hover:border-brandTeal transition-all">
                    <?= $div ?>
                </button>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="pb-24 md:pb-40 px-6">
        <div class="max-w-7xl mx-auto">
            <div id="team-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-x-8 gap-y-12 md:gap-y-16">
                <?php foreach ($grouped_team as $division => $members): ?>
                    <?php foreach ($members as $m): ?>
                    <div class="team-item group" data-division="<?= $division ?>">
                        <div class="relative aspect-[3/4] rounded-[2.5rem] md:rounded-5xl overflow-hidden mb-6 md:mb-8 bg-slate-100 shadow-sm transition-transform duration-500 group-hover:-translate-y-3">
                            <img src="<?= (strpos($m['member_image'], 'http') !== false) ? $m['member_image'] : '../uploads/team/'.$m['member_image'] ?>" 
                                 class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition duration-700" 
                                 alt="<?= $m['name'] ?>"
                                 onerror="this.src='https://via.placeholder.com/600x800?text=Member'">
                            
                            <div class="absolute top-4 right-4 md:top-6 md:right-6">
                                <span class="px-3 py-1.5 md:px-4 md:py-2 rounded-full text-[7px] md:text-[8px] font-black uppercase tracking-widest text-white <?= $m['color_class'] ?? 'bg-brandDark' ?> shadow-lg">
                                    <?= $division ?>
                                </span>
                            </div>

                            <div class="absolute inset-0 bg-gradient-to-t from-brandDark/90 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex flex-col justify-end p-6 md:p-10">
                                <p class="text-white/60 text-[9px] font-bold uppercase tracking-widest mb-1"><?= $m['email'] ?? 'hello@dang.com' ?></p>
                                <div class="h-1 w-10 bg-brandCoral rounded-full"></div>
                            </div>
                        </div>
                        
                        <div class="px-2">
                            <h3 class="text-xl md:text-2xl font-black uppercase italic text-brandDark leading-none mb-1 md:mb-2"><?= $m['name'] ?></h3>
                            <div class="flex items-center gap-2 md:gap-3">
                                <span class="h-[1px] w-6 md:w-8 bg-brandTeal"></span>
                                <p class="text-[9px] md:text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]"><?= $m['level'] ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="py-16 md:py-24 px-6 bg-brandDark rounded-t-[3.5rem] md:rounded-t-[5rem]">
        <div class="max-w-7xl mx-auto">
            <div class="mb-12 text-center lg:text-left">
                <h2 class="text-3xl md:text-5xl font-black text-white italic uppercase tracking-tighter">STUDIO CULTURE.</h2>
            </div>
            <div class="grid lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 bg-white/5 border border-white/10 p-8 md:p-12 rounded-4xl backdrop-blur-sm">
                    <p class="text-brandGold font-black uppercase tracking-widest text-[10px] mb-4">#01 Flexibility</p>
                    <h4 class="text-2xl md:text-3xl font-bold text-white mb-4">Work from anywhere, create for everywhere.</h4>
                    <p class="text-white/50 text-sm md:text-base leading-relaxed">Kami percaya produktivitas tidak dibatasi oleh dinding kantor. Tim kami tersebar dari berbagai tempat.</p>
                </div>
                <div class="bg-brandCoral p-8 md:p-12 rounded-4xl flex flex-col justify-between text-white min-h-[200px]">
                    <h4 class="text-4xl md:text-5xl font-black italic">No <br class="hidden md:block">Ego.</h4>
                    <p class="font-medium opacity-80 text-sm">Ide terbaik selalu menang.</p>
                </div>
            </div>
        </div>
    </section>

    <footer class="py-12 px-6 bg-white border-t border-slate-100 text-center md:text-left">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-8">
            <div class="text-lg font-black tracking-tighter italic text-brandDark">DANGDANG<span class="text-brandTeal">STUDIO</span></div>
            <p class="text-[8px] font-black uppercase tracking-widest text-slate-400">© 2025 Creative Department.</p>
            <div class="flex gap-6 text-[9px] font-black uppercase tracking-widest italic text-brandDark">
                <a href="#" class="hover:text-brandCoral transition">Instagram</a>
                <a href="#" class="hover:text-brandCoral transition">LinkedIn</a>
            </div>
        </div>
    </footer>

    <script>
        function filterTeam(division) {
            const btns = document.querySelectorAll('.filter-btn');
            btns.forEach(btn => {
                btn.classList.remove('active');
                if(btn.innerText.trim().toLowerCase() === division.toLowerCase() || (division === 'all' && btn.innerText.includes('All'))) {
                    btn.classList.add('active');
                }
            });

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