<?php
require_once './process/config.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// 1. Ambil Statistik Konten
$stats_query = "SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN status = 'Scheduled' THEN 1 ELSE 0 END) as upcoming,
    SUM(CASE WHEN status = 'Published' THEN 1 ELSE 0 END) as live,
    SUM(CASE WHEN status = 'Draft' THEN 1 ELSE 0 END) as draft
    FROM social_media";
$stats_result = mysqli_query($conn, $stats_query);
$stats = mysqli_fetch_assoc($stats_result);

// 2. Ambil Daftar Rencana Konten
$query = "SELECT * FROM social_media ORDER BY publish_date DESC, publish_time DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Media Plan - DangDang Studio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="assets/tailwind-config.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
    <style>
        .animate-fade-in { animation: fadeIn 0.3s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="bg-[#FBFBFB] text-slate-800">

    <div class="flex flex-col h-screen overflow-hidden">
        <?php include_once '_header.php'; ?>
        <div class="flex flex-1 overflow-hidden">
            <?php include_once '_aside.php'; ?>
            <main class="flex-1 flex flex-col min-w-0 overflow-y-auto">
                <?php include_once '_topbar.php'; ?>

                <div class="p-6 md:p-10 flex-1">
                    
                    <?php if (isset($_GET['status'])): ?>
                        <div class="mb-6 animate-fade-in">
                            <?php if ($_GET['status'] == 'success'): ?>
                                <div class="p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center gap-3">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                    <span class="text-sm font-bold">Rencana konten berhasil diperbarui!</span>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
                        <div>
                            <h1 class="text-3xl font-extrabold text-brandPrimary tracking-tight">Social Media Plan</h1>
                            <p class="text-sm text-gray-500 font-medium">Jadwalkan dan pantau konten promosi studio di berbagai platform.</p>
                        </div>
                        <a href="social_media_add.php" class="flex items-center gap-2 px-6 py-3 bg-brandPrimary text-white rounded-2xl font-bold shadow-lg shadow-brandPrimary/20 hover:scale-105 transition-all text-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Create Plan
                        </a>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10">
                        <div class="bg-white p-6 rounded-[28px] border border-gray-100 shadow-sm">
                            <p class="text-[10px] font-bold text-gray-400 uppercase mb-1 tracking-widest">Total Campaign</p>
                            <p class="text-2xl font-extrabold text-brandPrimary"><?php echo $stats['total'] ?? 0; ?></p>
                        </div>
                        <div class="bg-white p-6 rounded-[28px] border border-gray-100 shadow-sm border-l-4 border-l-brandGold">
                            <p class="text-[10px] font-bold text-gray-400 uppercase mb-1 tracking-widest">Upcoming</p>
                            <p class="text-2xl font-extrabold text-brandGold"><?php echo $stats['upcoming'] ?? 0; ?></p>
                        </div>
                        <div class="bg-white p-6 rounded-[28px] border border-gray-100 shadow-sm border-l-4 border-l-brandTeal">
                            <p class="text-[10px] font-bold text-gray-400 uppercase mb-1 tracking-widest">Live</p>
                            <p class="text-2xl font-extrabold text-brandTeal"><?php echo $stats['live'] ?? 0; ?></p>
                        </div>
                        <div class="bg-white p-6 rounded-[28px] border border-gray-100 shadow-sm border-l-4 border-l-brandAccent">
                            <p class="text-[10px] font-bold text-gray-400 uppercase mb-1 tracking-widest">Draft</p>
                            <p class="text-2xl font-extrabold text-brandAccent"><?php echo $stats['draft'] ?? 0; ?></p>
                        </div>
                    </div>

                    <div class="space-y-4 pb-10">
                        <?php if (mysqli_num_rows($result) > 0): ?>
                            <?php while ($plan = mysqli_fetch_assoc($result)): 
                                // Ubah string platform kembali menjadi array
                                $plan_platforms = explode(', ', $plan['platforms']);
                                // Format Tanggal
                                $formatted_date = date('d M Y', strtotime($plan['publish_date']));
                                $formatted_time = date('H:i', strtotime($plan['publish_time']));
                                // Placeholder Thumbnail jika kosong
                                $thumb_url = !empty($plan['thumbnail']) ? "../uploads/social/" . $plan['thumbnail'] : "assets/img/placeholder-social.jpg";
                            ?>
                                <div class="group bg-white rounded-[32px] p-4 flex flex-col md:flex-row items-center gap-6 border border-gray-100 hover:border-brandGold/30 hover:shadow-xl hover:shadow-brandPrimary/5 transition-all duration-500 animate-fade-in">
                                    <div class="w-full md:w-40 h-24 rounded-[20px] overflow-hidden bg-gray-100 flex-shrink-0 relative">
                                        <img src="<?php echo $thumb_url; ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                                    </div>

                                    <div class="flex-1 space-y-3">
                                        <div class="flex flex-wrap gap-1.5">
                                            <?php foreach($plan_platforms as $p): ?>
                                                <?php if(!empty($p)): ?>
                                                    <span class="text-[9px] font-bold px-2.5 py-1 rounded-lg bg-gray-50 text-gray-400 border border-gray-100 uppercase"><?php echo trim($p); ?></span>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </div>
                                        <h3 class="text-lg font-bold text-brandPrimary group-hover:text-brandGold transition-colors"><?php echo htmlspecialchars($plan['title']); ?></h3>
                                        <div class="flex items-center gap-4 text-[11px] text-gray-400 font-bold uppercase tracking-tight">
                                            <span class="flex items-center gap-1.5">
                                                <svg class="w-4 h-4 text-brandGold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v12a2 2 0 002 2z"></path></svg>
                                                <?php echo $formatted_date; ?>
                                            </span>
                                            <span class="flex items-center gap-1.5">
                                                <svg class="w-4 h-4 text-brandGold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                <?php echo $formatted_time; ?> WIB
                                            </span>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-6 w-full md:w-auto pr-4">
                                        <div class="text-right">
                                            <?php if($plan['status'] == 'Scheduled'): ?>
                                                <span class="flex items-center gap-1.5 text-brandGold text-[10px] font-bold uppercase tracking-widest">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-brandGold animate-pulse"></span> Scheduled
                                                </span>
                                            <?php elseif($plan['status'] == 'Published'): ?>
                                                <span class="flex items-center gap-1.5 text-brandTeal text-[10px] font-bold uppercase tracking-widest">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-brandTeal"></span> Live / Published
                                                </span>
                                            <?php else: ?>
                                                <span class="flex items-center gap-1.5 text-gray-300 text-[10px] font-bold uppercase tracking-widest">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-200"></span> Draft
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="flex gap-2">
                                            <a href="social_media_edit.php?id=<?php echo $plan['id']; ?>" class="w-11 h-11 rounded-xl bg-gray-50 flex items-center justify-center text-gray-400 hover:text-brandPrimary hover:bg-brandPrimary/5 transition-all shadow-sm">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </a>
                                            <button onclick="confirmDeletePlan('<?php echo $plan['id']; ?>', '<?php echo addslashes($plan['title']); ?>')" class="w-11 h-11 rounded-xl bg-gray-50 flex items-center justify-center text-gray-400 hover:text-brandAccent hover:bg-brandAccent/5 transition-all shadow-sm">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <div class="bg-white rounded-[32px] p-20 text-center border border-dashed border-gray-200">
                                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6 text-gray-300">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                </div>
                                <h3 class="text-xl font-bold text-brandPrimary mb-2">Belum ada rencana konten</h3>
                                <p class="text-sm text-gray-400 mb-8">Mulai buat jadwal promosi perdana Anda hari ini.</p>
                                <a href="social_media_add.php" class="inline-flex items-center gap-2 px-8 py-4 bg-brandPrimary text-white rounded-2xl font-bold">Create First Plan</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <?php include_once '_footer.php'; ?>
            </main>
        </div>
    </div>

    <div id="modal-delete" class="fixed inset-0 z-[100] hidden overflow-y-auto">
        <div class="fixed inset-0 bg-brandPrimary/40 backdrop-blur-sm transition-opacity" onclick="closeModalDelete()"></div>
        <div class="flex min-h-full items-center justify-center p-4 text-center">
            <div class="relative transform overflow-hidden rounded-[32px] bg-white p-8 text-left shadow-2xl transition-all w-full max-w-md animate-fade-in">
                <div class="flex flex-col items-center">
                    <div class="w-20 h-20 bg-red-50 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-10 h-10 text-brandAccent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-brandPrimary mb-2 text-center">Hapus Rencana Konten?</h3>
                    <p class="text-sm text-gray-500 text-center mb-8 px-4">
                        Tindakan ini akan menghapus jadwal konten <span id="delete-title" class="font-bold text-brandPrimary"></span> secara permanen.
                    </p>
                    <div class="flex gap-3 w-full">
                        <button onclick="closeModalDelete()" class="flex-1 py-4 bg-gray-50 text-gray-400 rounded-2xl font-bold hover:bg-gray-100 transition">Batal</button>
                        <a id="btn-confirm-delete" href="#" class="flex-1 py-4 bg-brandAccent text-white rounded-2xl font-bold shadow-lg shadow-brandAccent/20 hover:scale-[1.02] transition-all text-center">Ya, Hapus</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmDeletePlan(id, title) {
            document.getElementById('delete-title').innerText = title;
            document.getElementById('btn-confirm-delete').href = 'process/process_delete_social.php?id=' + id;
            document.getElementById('modal-delete').classList.remove('hidden');
        }

        function closeModalDelete() {
            document.getElementById('modal-delete').classList.add('hidden');
        }
    </script>
</body>
</html>