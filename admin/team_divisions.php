<?php
require_once './process/config.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login");
    exit();
}

// Ambil data divisi dari tabel divisions
$query = "SELECT * FROM divisions ORDER BY division_name ASC";
$result = mysqli_query($conn, $query);
$divisions = [];
while ($row = mysqli_fetch_assoc($result)) {
    $divisions[] = $row;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Division Headers - DangDang Studio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="assets/tailwind-config.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
    <style>
        .animate-slide-up { animation: slideUp 0.4s ease-out forwards; }
        @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .modal-active { overflow: hidden; }
    </style>
</head>

<body class="bg-[#FBFBFB] text-slate-800 font-['Plus_Jakarta_Sans']">

    <div class="flex flex-col h-screen overflow-hidden">
        <?php include_once '_header.php'; ?>
        <div class="flex flex-1 overflow-hidden">
            <?php include_once '_aside.php'; ?>
            <main class="flex-1 flex flex-col min-w-0 overflow-y-auto">
                <?php include_once '_topbar.php'; ?>

                <div class="p-6 md:p-10">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12">
                        <div class="space-y-1">
                            <h1 class="text-3xl font-extrabold text-brandPrimary tracking-tight">Division Headers</h1>
                            <p class="text-sm text-gray-400 font-medium">Manajemen visual header per divisi studio.</p>
                        </div>
                        <button onclick="openModal('add')" class="flex items-center gap-3 px-8 py-4 bg-brandPrimary text-white rounded-[24px] font-bold shadow-xl shadow-brandPrimary/20 hover:scale-[1.03] transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                            Add New Division
                        </button>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                        <?php if (empty($divisions)): ?>
                            <div class="sm:col-span-3 text-center py-20 bg-white rounded-[40px] border border-dashed border-gray-200">
                                <p class="text-gray-400 font-medium">Belum ada data divisi.</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($divisions as $div) : 
                                $header_path = "../uploads/headers/" . $div['header_image'];
                                $final_header = (!empty($div['header_image']) && file_exists($header_path)) ? $header_path : '../uploads/headers/default_team.png';
                            ?>
                                <div class="animate-slide-up group bg-white rounded-[32px] overflow-hidden border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-500">
                                    <div class="relative h-44 overflow-hidden bg-slate-200">
                                        <img src="<?= $final_header ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                                        <div class="absolute bottom-5 left-6">
                                            <h2 class="text-xl font-black italic uppercase text-white tracking-tighter"><?= htmlspecialchars($div['division_name']) ?></h2>
                                        </div>
                                    </div>
                                    
                                    <div class="p-5 flex items-center justify-between">
                                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest italic">Header Active</span>
                                        <div class="flex gap-2">
                                            <button onclick='openModal("edit", <?= json_encode($div) ?>)' class="w-10 h-10 rounded-xl bg-slate-50 text-brandPrimary flex items-center justify-center hover:bg-brandPrimary hover:text-white transition-all shadow-sm">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                            </button>
                                            <button onclick="openDeleteModal('<?= $div['id'] ?>', '<?= addslashes($div['division_name']) ?>')" class="w-10 h-10 rounded-xl bg-red-50 text-red-400 flex items-center justify-center hover:bg-red-500 hover:text-white transition-all shadow-sm">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <?php include_once '_footer.php'; ?>
            </main>
        </div>
    </div>

    <div id="formModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
        <div class="fixed inset-0 bg-brandPrimary/60 backdrop-blur-md transition-opacity" onclick="closeModal()"></div>
        <div class="relative flex min-h-screen items-center justify-center p-4">
            <div class="relative w-full max-w-lg bg-white rounded-[40px] p-10 shadow-2xl animate-slide-up">
                <button onclick="closeModal()" class="absolute top-8 right-8 text-gray-400 hover:text-brandPrimary transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
                
                <h2 id="modalTitle" class="text-2xl font-black italic uppercase text-brandPrimary mb-8 tracking-tighter">Add Division</h2>
                
                <form id="divForm" action="./process/upsert_division.php" method="POST" enctype="multipart/form-data" class="space-y-6">
                    <input type="hidden" name="id" id="divId">
                    
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 ml-2">Division Name</label>
                        <input type="text" name="division_name" id="divName" required placeholder="e.g. Animation"
                               class="w-full px-6 py-4 rounded-[20px] bg-gray-50 border border-transparent focus:border-brandPrimary focus:bg-white outline-none font-bold transition-all text-brandPrimary">
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 ml-2">Header Preview</label>
                        <div class="relative group w-full h-44 rounded-[24px] overflow-hidden bg-slate-100 border-2 border-dashed border-slate-200 hover:border-brandPrimary transition-all">
                            <img id="imagePreview" src="../uploads/headers/default_team.png" class="w-full h-full object-cover">
                            <div class="absolute inset-0 flex flex-col items-center justify-center bg-black/10 group-hover:bg-black/40 transition-all cursor-pointer">
                                <svg class="w-8 h-8 text-white mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <p class="text-[9px] font-black text-white uppercase tracking-widest">Change Image</p>
                            </div>
                            <input type="file" name="header_image" id="divImage" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer z-20">
                        </div>
                    </div>

                    <button type="submit" class="w-full py-5 bg-brandPrimary text-white rounded-[24px] font-[800] italic uppercase tracking-widest shadow-xl shadow-brandPrimary/30 hover:scale-[1.02] active:scale-95 transition-all">
                        Save Division
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div id="deleteModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
        <div class="fixed inset-0 bg-red-900/40 backdrop-blur-md transition-opacity" onclick="closeDeleteModal()"></div>
        <div class="relative flex min-h-screen items-center justify-center p-4 text-center">
            <div class="relative w-full max-w-sm bg-white rounded-[40px] p-10 shadow-2xl animate-slide-up">
                <div class="w-20 h-20 bg-red-50 rounded-3xl flex items-center justify-center mx-auto mb-6 text-red-500">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </div>
                <h3 class="text-2xl font-black italic uppercase text-brandPrimary mb-2">Delete?</h3>
                <p class="text-sm text-gray-400 mb-8">Hapus divisi <span id="delName" class="text-red-500 font-bold"></span>?</p>
                <div class="flex gap-4">
                    <button onclick="closeDeleteModal()" class="flex-1 py-4 rounded-2xl bg-gray-50 text-gray-400 font-bold hover:bg-gray-100 transition-all">Cancel</button>
                    <a id="delConfirmBtn" href="#" class="flex-1 py-4 rounded-2xl bg-red-500 text-white font-bold shadow-lg shadow-red-200 hover:bg-red-600 transition-all">Delete</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        const defaultImg = "../uploads/headers/default_team.png";
        const previewEl = document.getElementById('imagePreview');
        const inputEl = document.getElementById('divImage');

        function openModal(mode, data = null) {
            const modal = document.getElementById('formModal');
            document.body.classList.add('modal-active');
            modal.classList.remove('hidden');

            if (mode === 'edit') {
                document.getElementById('modalTitle').innerText = "Edit Division";
                document.getElementById('divId').value = data.id;
                document.getElementById('divName').value = data.division_name;
                previewEl.src = (data.header_image) ? "../uploads/headers/" + data.header_image : defaultImg;
            } else {
                document.getElementById('modalTitle').innerText = "Add Division";
                document.getElementById('divForm').reset();
                document.getElementById('divId').value = "";
                previewEl.src = defaultImg;
            }
        }

        inputEl.onchange = function() {
            const [file] = this.files;
            if (file) previewEl.src = URL.createObjectURL(file);
        };

        function closeModal() {
            document.getElementById('formModal').classList.add('hidden');
            document.body.classList.remove('modal-active');
        }

        function openDeleteModal(id, name) {
            document.getElementById('delName').innerText = name;
            document.getElementById('delConfirmBtn').href = `./process/delete_division.php?id=${id}`;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }
    </script>
</body>
</html>