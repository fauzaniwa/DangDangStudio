<?php
require_once 'process/config.php';
session_start();

// Proteksi akses
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Query untuk mengambil data klien dan menghitung jumlah invoice (project) mereka
$query = "SELECT c.*, COUNT(i.id) as total_projects 
          FROM clients c 
          LEFT JOIN invoices i ON c.id = i.client_id 
          GROUP BY c.id 
          ORDER BY c.created_at DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client List - DangDang Studio</title>
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
                        <div>
                            <h1 class="text-2xl font-bold text-brandPrimary">Client Database</h1>
                            <p class="text-sm text-gray-500">Kelola informasi kontak dan kemitraan klien DangDang Studio.</p>
                        </div>
                        <a href="client_add.php" class="flex items-center justify-center gap-2 px-6 py-3 bg-brandTeal text-white rounded-2xl font-bold shadow-lg shadow-brandTeal/20 hover:scale-105 transition-all text-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                            Add New Client
                        </a>
                    </div>

                    <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
                        <div class="mb-6 p-4 bg-green-50 border border-green-100 text-green-600 rounded-2xl text-sm font-bold flex items-center gap-3 animate-bounce">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <?php echo htmlspecialchars($_GET['msg']); ?>
                        </div>
                    <?php endif; ?>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php if (mysqli_num_rows($result) > 0): ?>
                            <?php while ($client = mysqli_fetch_assoc($result)) :
                                // Generate Logo Text (Inisial 2 huruf dari nama perusahaan)
                                $words = explode(" ", preg_replace('/[^A-Za-z0-9 ]/', '', $client['company_name']));
                                $logo_text = (count($words) > 1)
                                    ? strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1))
                                    : strtoupper(substr($words[0], 0, 2));
                            ?>
                                <div class="bg-white rounded-[32px] p-6 shadow-sm border border-gray-100 hover:border-brandTeal/30 transition-all group">
                                    <div class="flex items-center justify-between mb-6">
                                        <div class="w-14 h-14 rounded-2xl bg-gray-50 flex items-center justify-center text-xl font-bold text-brandPrimary border border-gray-100 group-hover:bg-brandPrimary group-hover:text-white transition-all">
                                            <?php echo $logo_text; ?>
                                        </div>
                                        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase <?php echo $client['total_projects'] > 0 ? 'bg-green-50 text-green-500' : 'bg-gray-50 text-gray-400'; ?>">
                                            <?php echo $client['total_projects'] > 0 ? 'Active' : 'New'; ?>
                                        </span>
                                    </div>

                                    <div class="mb-6">
                                        <h3 class="text-lg font-bold text-brandPrimary leading-tight mb-1"><?php echo $client['company_name']; ?></h3>
                                        <p class="text-xs text-brandTeal font-bold uppercase tracking-wider mb-4"><?php echo $client['category']; ?></p>

                                        <div class="space-y-3">
                                            <div class="flex items-center gap-3 text-gray-500">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                                <span class="text-xs font-medium"><?php echo $client['pic_name']; ?></span>
                                            </div>
                                            <div class="flex items-center gap-3 text-gray-500">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                                </svg>
                                                <span class="text-xs font-medium break-all"><?php echo $client['email']; ?></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between pt-5 border-t border-gray-50">
                                        <div>
                                            <p class="text-[10px] font-bold text-gray-400 uppercase leading-none mb-1">Total Projects</p>
                                            <p class="text-sm font-bold text-brandPrimary"><?php echo $client['total_projects']; ?> Projects</p>
                                            <p class="text-[10px] text-gray-400 italic">
                                                Last updated: <?php echo date('d M Y, H:i', strtotime($client['updated_at'])); ?>
                                            </p>
                                        </div>

                                        <div class="flex gap-2 text-sm">
                                            <a href="client_edit.php?id=<?php echo $client['id']; ?>" class="p-2 bg-gray-50 text-gray-400 hover:text-brandPrimary hover:bg-brandPrimary/10 rounded-xl transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </a>
                                            <button onclick="confirmDelete(<?php echo $client['id']; ?>, '<?php echo addslashes($client['company_name']); ?>')" class="p-2 bg-gray-50 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <div class="col-span-full py-20 text-center bg-white rounded-[32px] border border-dashed border-gray-200">
                                <p class="text-gray-400 font-medium">Belum ada klien terdaftar.</p>
                                <a href="client_add.php" class="text-brandTeal font-bold text-sm mt-2 inline-block italic">Klik di sini untuk menambah klien pertama.</a>
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
                    <h3 class="text-xl font-bold text-brandPrimary mb-2">Delete Client?</h3>
                    <p class="text-sm text-gray-500 text-center mb-8">
                        Are you sure you want to delete <span id="delete-client-name" class="font-bold text-brandPrimary"></span>? This action cannot be undone.
                    </p>
                    <div class="flex gap-3 w-full">
                        <button onclick="closeModalDelete()" class="flex-1 py-4 bg-gray-50 text-gray-400 rounded-2xl font-bold hover:bg-gray-100 transition">Cancel</button>
                        <a id="btn-confirm-delete" href="#" class="flex-1 py-4 bg-brandAccent text-white rounded-2xl font-bold shadow-lg shadow-brandAccent/20 hover:scale-[1.02] transition-all text-center">Delete Now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/script.js"></script>
    <script>
        // function confirmDelete(id, name) {
        //     // Menggunakan template literal agar lebih rapi
        //     if (confirm(`Apakah Anda yakin ingin menghapus "${name}"?\nData yang sudah dihapus tidak dapat dikembalikan.`)) {
        //         // Arahkan ke file proses delete di dalam folder process/
        //         window.location.href = 'process/client_delete.php?id=' + id;
        //     }
        // }

        function confirmDelete(id, name) {
            const modal = document.getElementById('modal-delete');
            const nameDisplay = document.getElementById('delete-client-name');
            const confirmBtn = document.getElementById('btn-confirm-delete');

            // Set konten dinamis
            nameDisplay.innerText = name;
            confirmBtn.href = `process/client_delete.php?id=${id}`;

            // Tampilkan modal
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Kunci scroll
        }

        function closeModalDelete() {
            const modal = document.getElementById('modal-delete');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto'; // Kembalikan scroll
        }
    </script>
</body>

</html>