<?php
require_once 'process/config.php';
session_start();

// Proteksi Admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// 1. Logika Filter Tampilan (Active/Archived)
$view_mode = isset($_GET['view']) && $_GET['view'] === 'archived' ? 1 : 0;

// 2. Ambil Statistik Ringkas
$total_pj = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM projects WHERE is_archived = 0"))['total'];
$done_pj = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM projects WHERE progress = 100 AND is_archived = 0"))['total'];
$archived_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM projects WHERE is_archived = 1"))['total'];
// 2. Ambil Statistik Ringkas
// Menghitung jumlah proyek yang status is_archived = 0
$active_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM projects WHERE is_archived = 0"))['total'];

// Menghitung jumlah proyek yang status is_archived = 1
$archived_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM projects WHERE is_archived = 1"))['total'];

// Variabel lama $total_pj dan $done_pj bisa tetap ada jika masih dipakai di tempat lain, 
// tapi untuk header kita gunakan dua variabel di atas.
// 3. Ambil data dari database berdasarkan view_mode
$query = "SELECT * FROM projects WHERE is_archived = $view_mode ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Manager - DangDang Studio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="assets/tailwind-config.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
</head>

<body class="bg-[#F8FAFC] text-slate-800">

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
                                <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-100 text-emerald-700 px-6 py-4 rounded-[24px]">
                                    <div class="w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center text-white shadow-lg shadow-emerald-200">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <div class="text-sm font-bold">Action Successful! Data has been updated.</div>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
                        <div>
                            <h1 class="text-4xl font-black text-brandPrimary tracking-tight">
                                <?php echo $view_mode ? 'Archived Projects' : 'Project Management'; ?>
                            </h1>
                            <p class="text-sm text-gray-500 font-medium mt-1">
                                <?php echo $view_mode ? 'Melihat koleksi proyek yang telah diarsipkan.' : 'Pantau siklus pengembangan game DangDang Studio secara real-time.'; ?>
                            </p>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="hidden sm:block text-right border-r border-gray-200 pr-4">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Active / Archived</p>
                                <p class="text-lg font-black text-brandPrimary">
                                    <?php echo $active_count; ?>
                                    <span class="text-gray-300">/</span>
                                    <span class="text-brandGold"><?php echo $archived_count; ?></span>
                                </p>
                            </div>
                            <a href="project_add.php" class="flex items-center gap-2 px-6 py-4 bg-brandPrimary text-white rounded-2xl font-bold shadow-xl shadow-brandPrimary/20 hover:scale-[1.02] active:scale-95 transition-all text-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Add New Project
                            </a>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 mb-8 bg-gray-200/50 p-1.5 rounded-[22px] w-fit">
                        <a href="project_manager.php" class="px-6 py-2.5 rounded-[18px] text-xs font-bold transition-all <?php echo !$view_mode ? 'bg-white text-brandPrimary shadow-sm' : 'text-gray-400 hover:text-gray-600'; ?>">
                            Active (<?php echo $total_pj; ?>)
                        </a>
                        <a href="project_manager.php?view=archived" class="px-6 py-2.5 rounded-[18px] text-xs font-bold transition-all <?php echo $view_mode ? 'bg-white text-brandPrimary shadow-sm' : 'text-gray-400 hover:text-gray-600'; ?>">
                            Archived (<?php echo $archived_count; ?>)
                        </a>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 pb-10">
                        <?php if (mysqli_num_rows($result) > 0): ?>
                            <?php while ($pj = mysqli_fetch_assoc($result)):
                                $colorClass = ($pj['progress'] == 100) ? 'bg-emerald-500' : 'bg-brandGold';
                                $iconPath = "../uploads/projects/" . ($pj['project_icon'] ?: 'default_icon.png');
                            ?>
                                <div class="bg-white rounded-[32px] p-6 border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 group <?php echo $view_mode ? 'opacity-80' : ''; ?>">
                                    <div class="flex gap-6">
                                        <div class="w-28 h-28 rounded-[24px] overflow-hidden flex-shrink-0 bg-gray-50 border border-gray-100 shadow-inner">
                                            <img src="<?php echo $iconPath; ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500 <?php echo $view_mode ? 'grayscale' : ''; ?>" onerror="this.src='https://ui-avatars.com/api/?name=<?php echo urlencode($pj['project_name']); ?>&background=random'">
                                        </div>

                                        <div class="flex-1">
                                            <div class="flex items-center justify-between mb-1">
                                                <span class="px-3 py-1 bg-gray-100 rounded-full text-[9px] font-black text-gray-400 uppercase tracking-widest"><?php echo htmlspecialchars($pj['genre']); ?></span>
                                                <div class="flex gap-2">
                                                    <a href="project_edit.php?id=<?php echo $pj['id']; ?>" class="p-2 text-gray-300 hover:text-brandPrimary hover:bg-gray-50 rounded-xl transition">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                        </svg>
                                                    </a>
                                                    <button onclick="confirmDelete('<?php echo $pj['id']; ?>', '<?php echo addslashes($pj['project_name']); ?>')" class="p-2 text-gray-300 hover:text-red-500 hover:bg-red-50 rounded-xl transition">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                            <h3 class="text-xl font-extrabold text-brandPrimary mb-4 leading-tight"><?php echo htmlspecialchars($pj['project_name']); ?></h3>

                                            <div class="space-y-2 mb-4">
                                                <div class="flex justify-between text-[10px] font-bold uppercase tracking-tight">
                                                    <span class="text-gray-400"><?php echo htmlspecialchars($pj['status']); ?></span>
                                                    <span class="text-brandPrimary"><?php echo $pj['progress']; ?>%</span>
                                                </div>
                                                <div class="w-full h-2 bg-gray-50 rounded-full overflow-hidden">
                                                    <div class="h-full <?php echo $colorClass; ?> rounded-full transition-all duration-1000 shadow-sm" style="width: <?php echo $pj['progress']; ?>%"></div>
                                                </div>
                                            </div>

                                            <div class="flex flex-wrap gap-2">
                                                <?php
                                                $platforms = json_decode($pj['platforms'] ?? '[]');
                                                if (!empty($platforms)):
                                                    foreach ($platforms as $plt): ?>
                                                        <span class="px-2.5 py-1 bg-brandPrimary/5 border border-brandPrimary/10 rounded-lg text-[9px] font-bold text-brandPrimary/70 flex items-center gap-1">
                                                            <span class="w-1 h-1 bg-brandGold rounded-full"></span>
                                                            <?php echo $plt; ?>
                                                        </span>
                                                <?php endforeach;
                                                endif; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-6 pt-5 border-t border-gray-50 flex items-center justify-between">
                                        <span class="text-[9px] font-bold text-gray-300 uppercase italic">Created at: <?php echo date('d M Y', strtotime($pj['created_at'])); ?></span>

                                        <?php if ($view_mode): ?>
                                            <a href="process/process_archive_project.php?id=<?php echo $pj['id']; ?>&action=restore" class="text-[10px] font-black text-emerald-500 hover:text-emerald-700 uppercase tracking-widest transition-colors flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                                </svg>
                                                Restore Project
                                            </a>
                                        <?php else: ?>
                                            <button onclick="confirmArchive('<?php echo $pj['id']; ?>', '<?php echo addslashes($pj['project_name']); ?>')" class="text-[10px] font-black text-red-300 hover:text-red-500 uppercase tracking-widest transition-colors">
                                                Archive Project
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <div class="lg:col-span-2 py-20 bg-white rounded-[32px] border border-dashed border-gray-200 flex flex-col items-center justify-center text-center">
                                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4 text-gray-300">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                    </svg>
                                </div>
                                <h3 class="font-bold text-gray-400 tracking-tight">
                                    <?php echo $view_mode ? 'Belum ada proyek yang diarsipkan.' : 'No active projects found.'; ?>
                                </h3>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <?php include_once '_footer.php'; ?>
            </main>
        </div>
    </div>

    <div id="modal-delete" class="fixed inset-0 z-[100] hidden overflow-y-auto">
        <div class="fixed inset-0 bg-brandPrimary/40 backdrop-blur-sm" onclick="closeModalDelete()"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative bg-white p-8 rounded-[40px] shadow-2xl w-full max-w-sm animate-fade-in">
                <div class="text-center">
                    <div id="modal-icon-container" class="w-20 h-20 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-6 text-red-500">
                        <svg id="icon-delete" class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </div>
                    <h3 id="modal-title" class="text-xl font-black text-brandPrimary mb-2">Konfirmasi</h3>
                    <p class="text-sm text-gray-400 mb-8">Proyek <span id="delete-article-title" class="font-bold text-brandGold"></span> <span id="modal-description"></span></p>
                    <div class="flex gap-3">
                        <button onclick="closeModalDelete()" class="flex-1 py-4 text-sm font-bold text-gray-400 hover:text-gray-600">Batal</button>
                        <a id="btn-confirm-action" href="#" class="flex-1 py-4 bg-brandPrimary text-white rounded-2xl font-bold text-sm shadow-lg shadow-brandPrimary/20">Ya, Lanjutkan</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmArchive(id, title) {
            document.getElementById('delete-article-title').innerText = title;
            document.getElementById('btn-confirm-delete').href = 'process/process_archive_project.php?id=' + id;
            document.getElementById('modal-delete').classList.remove('hidden');
        }

        function closeModalDelete() {
            document.getElementById('modal-delete').classList.add('hidden');
        }
    </script>

    <script>
        // Fungsi untuk Arsip (Sesuai kode lama Anda)
        function confirmArchive(id, title) {
            const modal = document.getElementById('modal-delete');
            document.getElementById('modal-title').innerText = "Move to Archive?";
            document.getElementById('modal-description').innerText = "akan dipindahkan ke folder arsip.";
            document.getElementById('delete-article-title').innerText = title;
            document.getElementById('btn-confirm-action').href = 'process/process_archive_project.php?id=' + id;
            document.getElementById('btn-confirm-action').className = "flex-1 py-4 bg-brandPrimary text-white rounded-2xl font-bold text-sm shadow-lg";
            modal.classList.remove('hidden');
        }

        // Fungsi Baru untuk Delete Permanen
        function confirmDelete(id, title) {
            const modal = document.getElementById('modal-delete');
            document.getElementById('modal-title').innerText = "Delete Permanently?";
            document.getElementById('modal-description').innerText = "akan dihapus selamanya dan tidak bisa dikembalikan.";
            document.getElementById('delete-article-title').innerText = title;
            document.getElementById('btn-confirm-action').href = 'process/process_delete_project.php?id=' + id;
            // Ubah warna tombol jadi merah untuk peringatan bahaya
            document.getElementById('btn-confirm-action').className = "flex-1 py-4 bg-red-600 text-white rounded-2xl font-bold text-sm shadow-lg shadow-red-200";
            modal.classList.remove('hidden');
        }

        function closeModalDelete() {
            document.getElementById('modal-delete').classList.add('hidden');
        }
    </script>
</body>

</html>