<?php
// team.php - Lokasi: /public/team.php
require_once '../admin/process/config.php';

/** * 1. Ambil Divisi yang memiliki anggota */
$div_query = "SELECT DISTINCT d.id, d.division_name, d.header_image 
              FROM divisions d
              INNER JOIN team t ON TRIM(d.division_name) = TRIM(t.division) 
              WHERE t.status IN ('Full-time', 'Intern', 'Freelance')
              ORDER BY (d.division_name = 'Founder') DESC, d.division_name ASC";

$div_result = mysqli_query($conn, $div_query);
$divisions_data = [];
if ($div_result && mysqli_num_rows($div_result) > 0) {
    while ($row = mysqli_fetch_assoc($div_result)) {
        $divisions_data[] = $row;
    }
}

/** * 2. Ambil data Team Lengkap */
$team_query = "SELECT `id`, `name`, `member_image`, `division`, `level`, `status`, `email`, `phone`, `instagram` 
                FROM `team` 
                WHERE `status` IN ('Full-time', 'Intern', 'Freelance')
                ORDER BY name ASC";
$team_result = mysqli_query($conn, $team_query);
$team_members = [];
if ($team_result && mysqli_num_rows($team_result) > 0) {
    while ($row = mysqli_fetch_assoc($team_result)) {
        $team_members[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Team | DangDang Studio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,700;0,800;1,800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; color: #333A73; }
        
        .team-card-shape {
            clip-path: polygon(0 0, 100% 0, 100% 85%, 20% 100%, 0 100%);
            transition: all 0.6s cubic-bezier(0.23, 1, 0.32, 1);
        }
        .group:hover .team-card-shape { clip-path: polygon(0 0, 100% 0, 100% 100%, 0 100%, 0 100%); }

        .filter-btn { border: 1px solid #e2e8f0; background: white; color: #333A73; white-space: nowrap; }
        .filter-btn.active { 
            background-color: #333A73; color: white; 
            transform: translateY(-5px); 
            box-shadow: 0 20px 25px -5px rgba(51, 58, 115, 0.2); 
            border-color: #333A73;
        }
        
        .no-scrollbar::-webkit-scrollbar { display: none; }
        #header-bg { transition: background-image 0.8s ease-in-out, opacity 0.5s; }
        
        .social-link {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
        }
        .social-link:hover {
            background: #FF6136;
            border-color: #FF6136;
            transform: rotate(12deg) scale(1.1);
        }
    </style>
</head>

<body class="antialiased">
    <?php include_once '_navbar.php'; ?>

    <header class="relative pt-44 pb-32 px-6 overflow-hidden min-h-[60vh] flex items-center justify-center text-center bg-[#333A73]">
        <div id="header-bg" class="absolute inset-0 bg-cover bg-center opacity-30 transition-all duration-1000" 
             style="background-image: url('../uploads/headers/default_team.png');"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-transparent via-[#333A73]/20 to-[#f8fafc]"></div>
        <div class="max-w-7xl mx-auto relative z-10">
            <h1 id="header-title" class="text-5xl md:text-9xl font-[1000] tracking-tighter leading-[0.85] italic uppercase mb-4 text-white">
                THE BRAINS <br> OF <span class="text-[#FF6136]">IMAGINATION.</span>
            </h1>
        </div>
    </header>

    <nav class="relative -mt-12 z-30 pb-16 px-4">
        <div class="max-w-6xl mx-auto flex overflow-x-auto no-scrollbar md:justify-center">
            <div class="flex gap-2 p-2 bg-white/80 backdrop-blur-md shadow-2xl rounded-[2.5rem] border border-slate-100 min-w-max md:min-w-0">
                <button onclick="filterTeam('all', '../uploads/headers/default_team.png')" 
                    class="filter-btn active px-6 md:px-8 py-3 md:py-4 rounded-[2rem] text-[9px] md:text-[10px] font-black uppercase tracking-widest transition-all">
                    All Members
                </button>
                <?php foreach ($divisions_data as $div): ?>
                    <button onclick="filterTeam('<?= $div['division_name'] ?>', '../uploads/headers/<?= $div['header_image'] ?>')" 
                        class="filter-btn px-6 md:px-8 py-3 md:py-4 rounded-[2rem] text-[9px] md:text-[10px] font-black uppercase tracking-widest transition-all">
                        <?= $div['division_name'] ?>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>
    </nav>

    <section class="max-w-7xl mx-auto px-6 pb-40">
        <div id="team-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-12">
            <?php foreach ($team_members as $m): 
                $img_path = '../uploads/team/' . $m['member_image'];
                $fallback_img = "https://ui-avatars.com/api/?name=" . urlencode($m['name']) . "&background=333A73&color=fff&size=512";
                
                // Kondisi awal: Sembunyikan Founder jika tampilan "All"
                $initial_display = (trim($m['division']) === 'Founder') ? 'none' : 'block';
            ?>
                <div class="team-item group" data-division="<?= $m['division'] ?>" style="display: <?= $initial_display ?>;">
                    <div class="team-card-shape relative aspect-[4/5] bg-slate-200 overflow-hidden mb-8 shadow-sm">
                        <img src="<?= $img_path ?>"
                             class="w-full h-full object-cover grayscale group-hover:grayscale-0 group-hover:scale-105 transition-all duration-700"
                             alt="<?= $m['name'] ?>"
                             onerror="this.src='<?= $fallback_img ?>'">
                        
                        <div class="absolute inset-0 bg-gradient-to-t from-[#333A73] via-[#333A73]/40 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-500 flex flex-col justify-between p-6">
                            
                            <div class="flex justify-end gap-2 translate-y-[-20px] group-hover:translate-y-0 transition-transform duration-500 delay-100">
                                <?php if(!empty($m['instagram'])): ?>
                                    <a href="https://instagram.com/<?= $m['instagram'] ?>" target="_blank" 
                                       class="social-link w-11 h-11 rounded-full flex items-center justify-center text-white" title="Instagram">
                                       <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                                    </a>
                                <?php endif; ?>
                                <?php if(!empty($m['email'])): ?>
                                    <a href="mailto:<?= $m['email'] ?>" 
                                       class="social-link w-11 h-11 rounded-full flex items-center justify-center text-white" title="Email">
                                       <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    </a>
                                <?php endif; ?>
                            </div>

                            <div class="translate-y-[20px] group-hover:translate-y-0 transition-transform duration-500">
                                <p class="text-[#019E9A] text-[9px] font-black uppercase tracking-widest mb-1 italic"><?= $m['division'] ?> / <?= $m['status'] ?></p>
                                <h4 class="text-white text-3xl font-black italic uppercase leading-none mb-3"><?= $m['level'] ?></h4>
                                <?php if(!empty($m['phone'])): ?>
                                    <p class="text-white/50 text-[9px] tracking-[0.2em]"><?= $m['phone'] ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="px-2">
                        <h3 class="text-2xl font-[1000] italic uppercase leading-none text-[#333A73] group-hover:text-[#FF6136] transition-colors duration-300"><?= $m['name'] ?></h3>
                        <div class="flex items-center gap-3 mt-4">
                            <span class="h-[2px] w-6 bg-[#019E9A]"></span>
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Master of <?= $m['division'] ?></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <?php include_once '_footer.php'; ?>

    <script>
        function filterTeam(division, imageSrc) {
            // Update Active State Buttons
            const btns = document.querySelectorAll('.filter-btn');
            btns.forEach(btn => btn.classList.remove('active'));
            if(window.event) window.event.currentTarget.classList.add('active');

            // Header BG Transition
            const bg = document.getElementById('header-bg');
            bg.style.opacity = '0';
            setTimeout(() => {
                const finalImg = (imageSrc && !imageSrc.includes('undefined')) ? imageSrc : '../uploads/headers/default_team.png';
                bg.style.backgroundImage = `url('${finalImg}')`;
                bg.style.opacity = '0.3';
            }, 400);

            // Perbaikan Logika Filter (Exclude Founder pada "All")
            const items = document.querySelectorAll('.team-item');
            items.forEach(item => {
                const itemDiv = item.getAttribute('data-division').trim();
                item.style.opacity = '0';
                item.style.transform = 'translateY(15px)';
                
                setTimeout(() => {
                    let shouldShow = false;

                    if (division === 'all') {
                        // Sembunyikan Founder jika kategori yang dipilih adalah "All"
                        shouldShow = (itemDiv !== 'Founder');
                    } else {
                        // Tampilkan hanya yang sesuai dengan divisi yang diklik
                        shouldShow = (itemDiv === division.trim());
                    }

                    if (shouldShow) {
                        item.style.display = 'block';
                        setTimeout(() => {
                            item.style.opacity = '1';
                            item.style.transform = 'translateY(0)';
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