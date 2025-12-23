<?php
require_once './process/config.php';
session_start();

if (!isset($_SESSION['is_logged_in'])) {
    header("Location: login.php");
    exit();
}

// Perbaikan SQL Mode untuk agregasi Chart
mysqli_query($conn, "SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");

/** * DATA MINING BERDASARKAN DANGDANG.SQL
 */

// 1. STATS UTAMA (HERO)
// Saldo Kas Saat Ini
$fin_summary = mysqli_fetch_assoc(mysqli_query($conn, "SELECT 
    SUM(CASE WHEN type = 'in' THEN amount ELSE 0 END) - 
    SUM(CASE WHEN type = 'out' THEN amount ELSE 0 END) as balance 
    FROM financial_transactions"));
$current_balance = $fin_summary['balance'] ?? 0;

// Invoice Belum Dibayar
$unpaid_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as jml, SUM(total_amount) as total FROM invoices WHERE status = 'unpaid'"));
$unpaid_count = $unpaid_data['jml'] ?? 0;
$unpaid_sum = $unpaid_data['total'] ?? 0;

// Project Manager (Mengambil dari tabel 'team' berdasarkan divisi)
$q_pm = mysqli_query($conn, "SELECT name FROM team WHERE division LIKE '%Project Manager%' OR division LIKE '%PM%' LIMIT 2");
$pm_names = [];
while ($p = mysqli_fetch_assoc($q_pm)) {
    $pm_names[] = $p['name'];
}

// 2. QUICK STATS ROW
$active_projs = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM projects WHERE is_archived = 0"))['t'] ?? 0;
$total_games = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM games"))['t'] ?? 0;
$total_team = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM team"))['t'] ?? 0;
$total_partners = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM partners"))['t'] ?? 0;

// 3. DATA UNTUK CHART (6 Bulan Terakhir)
$q_finance = mysqli_query($conn, "SELECT DATE_FORMAT(transaction_date, '%b') as bulan, SUM(CASE WHEN type = 'in' THEN amount ELSE 0 END) as masuk, SUM(CASE WHEN type = 'out' THEN amount ELSE 0 END) as keluar FROM financial_transactions GROUP BY MONTH(transaction_date) ORDER BY transaction_date ASC LIMIT 6");
$fin_labels = [];
$fin_in = [];
$fin_out = [];
while ($f = mysqli_fetch_assoc($q_finance)) {
    $fin_labels[] = $f['bulan'];
    $fin_in[] = $f['masuk'];
    $fin_out[] = $f['keluar'];
}

$q_industry = mysqli_query($conn, "SELECT category, COUNT(*) as jml FROM clients GROUP BY category");
$ind_labels = [];
$ind_counts = [];
while ($ind = mysqli_fetch_assoc($q_industry)) {
    $ind_labels[] = $ind['category'] ?? 'Other';
    $ind_counts[] = $ind['jml'];
}

// 4. LIST DATA (TIMELINE, ARTICLES, LOGS, TESTIMONI)
$q_deadlines = mysqli_query($conn, "SELECT pt.*, c.company_name FROM project_timelines pt LEFT JOIN clients c ON pt.client_id = c.id WHERE pt.status != 'Done' ORDER BY pt.deadline_date ASC LIMIT 3");
$q_articles = mysqli_query($conn, "SELECT * FROM articles ORDER BY created_at DESC LIMIT 3");
$q_social = mysqli_query($conn, "SELECT * FROM social_media WHERE status = 'Scheduled' ORDER BY publish_date ASC LIMIT 3");
$q_logs = mysqli_query($conn, "SELECT al.*, a.fullname FROM admin_logs al LEFT JOIN admins a ON al.admin_id = a.id ORDER BY al.created_at DESC LIMIT 4");
$q_testimonials = mysqli_query($conn, "SELECT * FROM testimonials ORDER BY created_at DESC LIMIT 2");

// SHORTCUT CONFIG
$shortcutMenus = [
    'Game Info' => ['icon' => 'M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z', 'link' => 'games.php'],
    'Team Info' => ['icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', 'link' => 'team.php'],
    'Partner List' => ['icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z', 'link' => 'partners.php'],
    'Article / Blog' => ['icon' => 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l4 4v10a2 2 0 01-2 2z', 'link' => 'articles.php'],
    'Social Media' => ['icon' => 'M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z', 'link' => 'social_media.php'],
    'Testimonial' => ['icon' => 'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z', 'link' => 'testimonials.php']
];
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DangDang Studio | Command Center</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="assets/tailwind-config.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
</head>

<body class="bg-gray-50 text-slate-800">

    <div class="flex flex-col h-screen overflow-hidden">
        <?php include_once '_header.php'; ?>

        <div class="flex flex-1 overflow-hidden">
            <?php include_once '_aside.php'; ?>

            <main class="flex-1 flex flex-col min-w-0 overflow-y-auto">
                <?php include_once '_topbar.php'; ?>

                <div class="p-6 md:p-10 flex-1">

                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                        <?php foreach ($shortcutMenus as $name => $data): ?>
                            <a href="<?= $data['link'] ?>" class="glass-card p-4 rounded-[25px] flex flex-col items-center justify-center gap-2 hover:bg-brandPrimary hover:text-white transition-all group shadow-sm">
                                <div class="p-2 rounded-xl bg-brandPrimary/5 group-hover:bg-white/20 transition-colors">
                                    <svg class="w-5 h-5 text-brandPrimary group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?= $data['icon'] ?>"></path>
                                    </svg>
                                </div>
                                <span class="text-[10px] font-bold text-center"><?= $name ?></span>
                            </a>
                        <?php endforeach; ?>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="glass-card p-8 rounded-[40px] border-b-4 border-brandTeal shadow-xl relative overflow-hidden group">
                            <div class="relative z-10">
                                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Total Treasury Balance</p>
                                <h2 class="text-3xl font-black text-brandPrimary tracking-tighter">
                                    <span class="text-sm font-bold text-slate-400 mr-1">IDR</span><?= number_format($current_balance, 0, ',', '.') ?>
                                </h2>
                                <p class="text-[11px] font-bold text-brandTeal mt-4 italic">Available for allocation</p>
                            </div>
                            <div class="absolute -right-6 -bottom-6 text-brandTeal/5"><svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg></div>
                        </div>

                        <div class="glass-card p-8 rounded-[40px] border-b-4 border-rose-500 shadow-xl relative overflow-hidden group">
                            <div class="relative z-10">
                                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Unpaid Invoices</p>
                                <h2 class="text-3xl font-black text-brandPrimary tracking-tighter">
                                    <?= $unpaid_count ?> <span class="text-sm font-bold text-slate-400 italic">Bills Pending</span>
                                </h2>
                                <p class="text-[11px] font-bold text-rose-500 mt-4 italic">Potential: Rp <?= number_format($unpaid_sum, 0, ',', '.') ?></p>
                            </div>
                            <div class="absolute -right-6 -bottom-6 text-rose-500/5"><svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg></div>
                        </div>

                        <div class="glass-card p-8 rounded-[40px] bg-indigo-900 border-b-4 border-indigo-400 shadow-xl relative overflow-hidden">
                            <div class="relative z-10">
                                <p class="text-[10px] font-black uppercase tracking-widest text-indigo-300/60 mb-4">Active Project Managers</p>
                                <div class="space-y-3">
                                    <?php if (!empty($pm_names)): foreach ($pm_names as $name): ?>
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-full bg-indigo-500/30 flex items-center justify-center text-[10px] font-bold text-white"><?= substr($name, 0, 1) ?></div>
                                                <span class="text-sm font-bold text-white italic"><?= $name ?></span>
                                            </div>
                                        <?php endforeach;
                                    else: ?>
                                        <span class="text-sm font-bold text-indigo-300 italic">No PM Assigned</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="absolute right-4 top-4 animate-pulse"><span class="h-2 w-2 rounded-full bg-indigo-400 block"></span></div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="glass-card p-6 rounded-[32px] border-b-4 border-brandTeal shadow-sm group hover:translate-y-[-5px] transition-all">
                            <p class="text-[10px] font-black uppercase text-slate-400 mb-1">Production</p>
                            <h2 class="text-3xl font-black text-brandPrimary"><?= $active_projs ?> <span class="text-xs text-slate-300">Active</span></h2>
                        </div>
                        <div class="glass-card p-6 rounded-[32px] border-b-4 border-brandGold shadow-sm group hover:translate-y-[-5px] transition-all">
                            <p class="text-[10px] font-black uppercase text-slate-400 mb-1">Creative</p>
                            <h2 class="text-3xl font-black text-brandPrimary"><?= $total_games ?> <span class="text-xs text-slate-300">Games</span></h2>
                        </div>
                        <div class="glass-card p-6 rounded-[32px] border-b-4 border-indigo-500 shadow-sm group hover:translate-y-[-5px] transition-all">
                            <p class="text-[10px] font-black uppercase text-slate-400 mb-1">Human Capital</p>
                            <h2 class="text-3xl font-black text-brandPrimary"><?= $total_team ?> <span class="text-xs text-slate-300">Staff</span></h2>
                        </div>
                        <div class="glass-card p-6 rounded-[32px] border-b-4 border-brandAccent shadow-sm group hover:translate-y-[-5px] transition-all">
                            <p class="text-[10px] font-black uppercase text-slate-400 mb-1">Network</p>
                            <h2 class="text-3xl font-black text-brandPrimary"><?= $total_partners ?> <span class="text-xs text-slate-300">Partners</span></h2>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
                        <div class="xl:col-span-2 bg-white rounded-[40px] p-8 shadow-sm border border-slate-100">
                            <h3 class="text-xl font-bold text-brandPrimary mb-8 italic uppercase tracking-tighter">Performance Analysis</h3>
                            <div class="h-[300px]"><canvas id="financialChart"></canvas></div>
                        </div>
                        <div class="bg-[#1e1e1e] rounded-[40px] p-8 text-white shadow-2xl relative overflow-hidden">
                            <h3 class="text-xl font-bold mb-6 italic uppercase tracking-tighter">Upcoming Deadlines</h3>
                            <div class="space-y-4">
                                <?php while ($d = mysqli_fetch_assoc($q_deadlines)): ?>
                                    <div class="p-4 rounded-3xl bg-white/5 border border-white/10 flex items-center gap-4">
                                        <div class="w-1.5 h-10 rounded-full bg-brandGold"></div>
                                        <div class="min-w-0">
                                            <p class="text-[10px] font-bold text-slate-500 uppercase"><?= $d['company_name'] ?? 'Internal' ?></p>
                                            <h4 class="text-sm font-bold truncate italic"><?= $d['project_name'] ?></h4>
                                            <p class="text-[10px] text-brandGold font-bold mt-1"><?= date('d M Y', strtotime($d['deadline_date'])) ?></p>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-8">
                        <div class="bg-white rounded-[40px] p-8 shadow-sm border border-slate-100">
                            <h3 class="text-lg font-bold text-brandPrimary mb-6 italic uppercase tracking-tighter">Studio News</h3>
                            <div class="space-y-5">
                                <?php while ($art = mysqli_fetch_assoc($q_articles)): ?>
                                    <div class="flex items-center gap-4">
                                        <div class="w-14 h-14 rounded-2xl bg-slate-100 overflow-hidden shrink-0"><img src="../uploads/articles/<?= $art['cover_image'] ?>" onerror="this.src='https://ui-avatars.com/api/?name=A'" class="w-full h-full object-cover"></div>
                                        <div class="min-w-0">
                                            <h4 class="text-xs font-bold text-brandPrimary truncate"><?= $art['title'] ?></h4>
                                            <p class="text-[10px] text-slate-400 italic"><?= date('d M Y', strtotime($art['created_at'])) ?></p>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        </div>

                        <div class="space-y-8">
                            <div class="bg-white rounded-[40px] p-8 shadow-sm border border-slate-100">
                                <h3 class="text-lg font-bold text-brandPrimary mb-4 italic uppercase tracking-tighter">Social Queue</h3>
                                <?php while ($soc = mysqli_fetch_assoc($q_social)): ?>
                                    <div class="flex items-center justify-between mb-3 p-3 bg-slate-50 rounded-2xl">
                                        <span class="text-[10px] font-bold text-brandPrimary uppercase"><?= $soc['title'] ?></span>
                                        <span class="text-[10px] font-black text-brandTeal"><?= date('d M', strtotime($soc['publish_date'])) ?></span>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                            <div class="bg-white rounded-[40px] p-8 shadow-sm border border-slate-100">
                                <h3 class="text-lg font-bold text-brandPrimary mb-4 italic text-center uppercase tracking-tighter">Market Reach</h3>
                                <div class="h-[150px]"><canvas id="industryChart"></canvas></div>
                            </div>
                        </div>

                        <div class="space-y-8">
                            <div class="bg-white rounded-[40px] p-8 shadow-sm border border-slate-100">
                                <h3 class="text-lg font-bold text-brandPrimary mb-4 italic uppercase tracking-tighter">Admin Activity</h3>
                                <?php while ($log = mysqli_fetch_assoc($q_logs)): ?>
                                    <p class="text-[10px] text-slate-600 mb-2 leading-tight border-l-2 border-brandTeal pl-2"><b><?= $log['fullname'] ?? 'Sys' ?>:</b> <?= $log['activity'] ?></p>
                                <?php endwhile; ?>
                            </div>
                            <div class="bg-brandTeal rounded-[40px] p-8 text-white shadow-xl relative overflow-hidden">
                                <h3 class="text-lg font-bold mb-4 italic uppercase tracking-tighter">Client Reviews</h3>
                                <?php while ($tes = mysqli_fetch_assoc($q_testimonials)): ?>
                                    <div class="mb-4 last:mb-0 p-3 bg-white/10 rounded-2xl border border-white/20">
                                        <div class="flex gap-1 mb-1">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <svg class="w-2.5 h-2.5 <?= ($i <= ($tes['stars'] ?? 5)) ? 'text-brandGold' : 'text-white/20' ?>" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                            <?php endfor; ?>
                                        </div>
                                        <p class="text-[10px] italic opacity-80 leading-relaxed">"<?= $tes['content'] ?>"</p>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        </div>
                    </div>

                </div>
                <?php include_once '_footer.php'; ?>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Financial Chart
        new Chart(document.getElementById('financialChart'), {
            type: 'bar',
            data: {
                labels: <?= json_encode($fin_labels) ?>,
                datasets: [{
                        label: 'In',
                        data: <?= json_encode($fin_in) ?>,
                        backgroundColor: '#14B8A6',
                        borderRadius: 8
                    },
                    {
                        label: 'Out',
                        data: <?= json_encode($fin_out) ?>,
                        backgroundColor: '#E2E8F0',
                        borderRadius: 8
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Market Chart
        new Chart(document.getElementById('industryChart'), {
            type: 'doughnut',
            data: {
                labels: <?= json_encode($ind_labels) ?>,
                datasets: [{
                    data: <?= json_encode($ind_counts) ?>,
                    backgroundColor: ['#1e1e1e', '#14B8A6', '#F59E0B', '#6366F1'],
                    borderWidth: 0
                }]
            },
            options: {
                cutout: '80%',
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>
</body>

</html>