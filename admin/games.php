<?php
// Menggunakan file konfigurasi sesuai instruksi Anda
require_once './process/config.php';

/** * Catatan: Saya berasumsi di dalam config.php variabel koneksinya bernama $conn. 
 * Jika di config.php Anda menggunakan nama lain (misal: $db atau $koneksi), 
 * silakan ganti semua variabel $conn di bawah ini.
 */

// Ambil data game dari database
$query = "SELECT id, title, category, short_desc, header_image, created_at FROM games ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Portfolio - DangDang Studio</title>
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

                    <?php if (isset($_GET['status'])): ?>
                        <div id="feedback-alert" class="mb-8 animate-fade-in">
                            <?php if ($_GET['status'] == 'success'): ?>
                                <div class="flex items-center gap-4 p-5 bg-emerald-50 border border-emerald-100 rounded-[24px]">
                                    <div class="w-12 h-12 bg-emerald-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-emerald-200">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-emerald-800">Berhasil!</h4>
                                        <p class="text-xs text-emerald-600/80 font-medium">Data game telah berhasil diperbarui di katalog portfolio.</p>
                                    </div>
                                    <button onclick="this.parentElement.parentElement.remove()" class="ml-auto text-emerald-400 hover:text-emerald-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            <?php elseif ($_GET['status'] == 'deleted'): ?>
                                <div class="flex items-center gap-4 p-5 bg-gray-100 border border-gray-200 rounded-[24px]">
                                    <div class="w-12 h-12 bg-slate-600 rounded-2xl flex items-center justify-center text-white">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-slate-800">Game Dihapus</h4>
                                        <p class="text-xs text-slate-500 font-medium">Data game telah dihapus secara permanen.</p>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10">
                        <div>
                            <h1 class="text-2xl font-bold text-brandPrimary">Game Portfolio</h1>
                            <p class="text-sm text-gray-500 font-medium">Mengelola katalog game DangDang Studio.</p>
                        </div>
                        <a href="game_add.php" class="flex items-center justify-center gap-2 px-6 py-3 bg-brandGold text-white rounded-2xl font-bold shadow-lg shadow-brandGold/20 hover:scale-105 transition-all text-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0h-3m-9-4.5a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0z"></path>
                            </svg>
                            Add New Game
                        </a>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                        <?php if ($result && mysqli_num_rows($result) > 0) : ?>
                            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                                <div class="bg-white rounded-[32px] overflow-hidden shadow-sm border border-gray-100 group hover:shadow-xl transition-all duration-500">
                                    <div class="relative h-56 overflow-hidden bg-gray-200">
                                        <?php
                                        $imgPath = "../uploads/game/" . $row['header_image'];
                                        // Cek jika file ada, jika tidak pakai placeholder
                                        if (!empty($row['header_image']) && file_exists($imgPath)) {
                                            $showImg = "../uploads/game/" . $row['header_image'];
                                        } else {
                                            $showImg = "https://via.placeholder.com/800x450?text=No+Image";
                                        }
                                        ?>
                                        <img src="<?php echo $showImg; ?>" alt="Header" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">

                                        <div class="absolute inset-0 bg-gradient-to-t from-brandPrimary/80 to-transparent opacity-60"></div>

                                        <div class="absolute top-4 left-4">
                                            <span class="px-3 py-1 rounded-full text-[9px] font-bold uppercase tracking-wider bg-white/20 backdrop-blur-md text-white border border-white/30">
                                                <?php echo date('Y', strtotime($row['created_at'])); ?>
                                            </span>
                                        </div>

                                        <div class="absolute bottom-4 left-6">
                                            <span class="text-[10px] font-bold text-brandTeal bg-white px-3 py-1 rounded-lg uppercase tracking-widest shadow-sm">
                                                <?php echo htmlspecialchars($row['category']); ?>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="p-6">
                                        <h3 class="text-lg font-bold text-brandPrimary mb-1 group-hover:text-brandGold transition-colors">
                                            <?php echo htmlspecialchars($row['title']); ?>
                                        </h3>
                                        <p class="text-xs text-gray-400 font-medium mb-4 line-clamp-2">
                                            <?php echo htmlspecialchars($row['short_desc']); ?>
                                        </p>

                                        <div class="flex items-center justify-between pt-4 border-t border-gray-50">
                                            <a href="game_edit.php?id=<?php echo $row['id']; ?>" class="text-xs font-bold text-brandPrimary hover:text-brandGold transition">Edit Details</a>
                                            <button type="button"
                                                onclick="openDeleteModal('<?php echo $row['id']; ?>', '<?php echo htmlspecialchars($row['title']); ?>')"
                                                class="p-2 bg-gray-50 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <div class="col-span-full py-20 text-center">
                                <p class="text-gray-400 font-medium">Belum ada data game di database.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <?php include_once '_footer.php'; ?>
            </main>
        </div>
    </div>

    <div id="deleteModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
        <div class="fixed inset-0 bg-brandPrimary/40 backdrop-blur-sm transition-opacity"></div>

        <div class="relative flex min-h-screen items-center justify-center p-4 text-center">
            <div class="relative w-full max-w-md transform overflow-hidden rounded-[32px] bg-white p-8 text-left align-middle shadow-2xl transition-all animate-fade-in">

                <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-3xl bg-red-50 mb-6">
                    <svg class="h-10 w-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>

                <div class="text-center">
                    <h3 class="text-xl font-bold text-brandPrimary mb-2">Delete Game?</h3>
                    <p class="text-sm text-gray-500 mb-8">
                        Are you sure you want to delete <span id="gameTitleDisplay" class="font-bold text-brandPrimary"></span>?
                        This action cannot be undone and all assets will be permanently removed.
                    </p>
                </div>

                <div class="flex gap-4">
                    <button type="button" onclick="closeDeleteModal()" class="flex-1 px-6 py-4 rounded-2xl bg-gray-100 text-gray-500 font-bold hover:bg-gray-200 transition-all">
                        Cancel
                    </button>
                    <a id="confirmDeleteBtn" href="#" class="flex-1 px-6 py-4 rounded-2xl bg-red-500 text-white font-bold shadow-lg shadow-red-200 hover:bg-red-600 transition-all text-center">
                        Delete Now
                    </a>
                </div>
            </div>
        </div>
    </div>
    <script>
    function openDeleteModal(gameId, gameTitle) {
        const modal = document.getElementById('deleteModal');
        const displayTitle = document.getElementById('gameTitleDisplay');
        const confirmBtn = document.getElementById('confirmDeleteBtn');

        // Set Nama Game di teks modal
        displayTitle.innerText = gameTitle;
        
        // Set Link Hapus dengan ID yang benar
        confirmBtn.href = `./process/delete_game.php?id=${gameId}`;

        // Tampilkan Modal
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden'; // Prevent scrolling
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto'; // Re-enable scrolling
    }

    // Close modal jika user klik di luar kotak putih (backdrop)
    window.onclick = function(event) {
        const modal = document.getElementById('deleteModal');
        if (event.target == modal.querySelector('.bg-brandPrimary\\/40')) {
            closeDeleteModal();
        }
    }
</script>
</body>

</html>