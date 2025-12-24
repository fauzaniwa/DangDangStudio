<?php
require_once 'process/config.php';
session_start();

// 1. Proteksi Admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// 2. Ambil dan Validasi ID dari URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// 3. Ambil data dengan Prepared Statement
$stmt = $conn->prepare("SELECT * FROM financial_transactions WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

// 4. Inisialisasi variabel cadangan (Mencegah Undefined Key)
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    // Jika ID tidak ada di DB, lempar kembali ke laporan
    header("Location: financial_report.php?status=notfound");
    exit();
}

// 5. Ekstraksi ke variabel tunggal (Ini kunci agar baris 87, 93, dll tidak error)
$t_id       = $row['id'] ?? 0;
$t_type     = $row['type'] ?? 'in';
$t_amount   = $row['amount'] ?? 0;
$t_desc     = $row['description'] ?? '';
$t_category = $row['category'] ?? '';
$t_date     = $row['transaction_date'] ?? date('Y-m-d');
$t_file     = $row['attachment'] ?? '';

// 6. Ambil data kategori untuk dropdown
$cat_query = mysqli_query($conn, "SELECT * FROM transaction_categories ORDER BY category_name ASC");
$categories = [];
while ($cat = mysqli_fetch_assoc($cat_query)) {
    $categories[] = $cat;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Transaksi - DangDang Studio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="assets/tailwind-config.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .animate-slide-up {
            animation: slideUp 0.4s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .tx-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>
</head>

<body class="bg-[#FBFBFB] text-slate-800">

    <div class="flex flex-col h-screen overflow-hidden">
        <?php include_once '_header.php'; ?>

        <div class="flex flex-1 overflow-hidden">
            <?php include_once '_aside.php'; ?>

            <main class="flex-1 flex flex-col min-w-0 overflow-y-auto">
                <div class="p-6 md:p-10 max-w-4xl mx-auto w-full">

                    <div class="flex items-center justify-between mb-10">
                        <div>
                            <h1 class="text-3xl font-extrabold text-brandPrimary tracking-tight">Edit Transaksi</h1>
                            <p class="text-sm text-gray-400 font-medium mt-1">Mengubah data ID #<?= $t_id ?></p>
                        </div>
                        <a href="financial_report.php" class="px-6 py-3 bg-white border border-gray-200 rounded-2xl text-[11px] font-extrabold text-gray-400 hover:text-brandPrimary hover:border-brandPrimary transition-all">KEMBALI</a>
                    </div>

                    <form action="process/process_edit_transaction.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?= $t_id ?>">

                        <div class="tx-card bg-white rounded-[40px] border border-gray-100 shadow-xl shadow-slate-200/40 p-8 animate-slide-up relative">
                            <div class="absolute -left-1 top-10 w-2 h-20 bg-brandPrimary rounded-full"></div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                                <div class="space-y-6">
                                    <div>
                                        <label class="block text-[11px] font-bold text-gray-400 uppercase mb-3 ml-1">Jenis Arus Kas</label>
                                        <div class="flex gap-2 p-1.5 bg-gray-50 rounded-[24px] border border-gray-100">
                                            <label class="flex-1 cursor-pointer">
                                                <input type="radio" name="type" value="in" class="hidden peer" <?= ($t_type == 'in') ? 'checked' : '' ?> onchange="updateCategoryOptions('in')">
                                                <div class="text-center py-3 rounded-[18px] text-[10px] font-extrabold transition peer-checked:bg-white peer-checked:text-brandTeal peer-checked:shadow-md text-gray-400 uppercase">PEMASUKAN</div>
                                            </label>
                                            <label class="flex-1 cursor-pointer">
                                                <input type="radio" name="type" value="out" class="hidden peer" <?= ($t_type == 'out') ? 'checked' : '' ?> onchange="updateCategoryOptions('out')">
                                                <div class="text-center py-3 rounded-[18px] text-[10px] font-extrabold transition peer-checked:bg-white peer-checked:text-brandAccent peer-checked:shadow-md text-gray-400 uppercase">PENGELUARAN</div>
                                            </label>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-[11px] font-bold text-gray-400 uppercase mb-3 ml-1">Kategori</label>
                                        <select name="category" id="cat-select" class="w-full px-6 py-4 rounded-[22px] bg-gray-50 border-none text-sm font-bold outline-none cursor-pointer appearance-none"></select>
                                    </div>

                                    <div>
                                        <label class="block text-[11px] font-bold text-gray-400 uppercase mb-3 ml-1">Nominal (Rp)</label>
                                        <input type="number" name="amount" value="<?= $t_amount ?>" required class="w-full px-6 py-4 rounded-[22px] bg-gray-50 border-none focus:ring-2 ring-brandPrimary outline-none text-xl font-bold text-brandPrimary">
                                    </div>
                                </div>

                                <div class="space-y-6">
                                    <div>
                                        <label class="block text-[11px] font-bold text-gray-400 uppercase mb-3 ml-1">Keterangan & Tanggal</label>
                                        <input type="date" name="date" value="<?= $t_date ?>" class="w-full px-6 py-4 rounded-[22px] bg-gray-50 border-none text-sm font-bold mb-3 outline-none">
                                        <textarea name="desc" rows="3" required class="w-full px-6 py-4 rounded-[22px] bg-gray-50 border-none text-sm outline-none resize-none" placeholder="Catatan..."><?= htmlspecialchars((string)$t_desc) ?></textarea>
                                    </div>

                                    <div>
                                        <label class="block text-[11px] font-bold text-gray-400 uppercase mb-3 ml-1">Bukti Transaksi</label>
                                        <div class="flex items-center gap-5">
                                            <div id="pv-edit" class="w-24 h-24 rounded-[28px] bg-gray-50 border-2 border-dashed border-gray-200 flex items-center justify-center overflow-hidden shrink-0 transition-all text-gray-300 italic text-[8px]">
                                                <?php
                                                if (!empty($t_file)):
                                                    $ext = pathinfo($t_file, PATHINFO_EXTENSION);
                                                    if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'webp'])): ?>
                                                        <img src="../uploads/finance/<?= $t_file ?>" class="w-full h-full object-cover">
                                                    <?php else: ?>
                                                        <div class="flex flex-col items-center gap-1">
                                                            <svg class="w-6 h-6 text-brandAccent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                            </svg>
                                                            <span class="font-bold uppercase"><?= $ext ?></span>
                                                        </div>
                                                    <?php endif;
                                                else: ?>
                                                    PREVIEW
                                                <?php endif; ?>
                                            </div>

                                            <div class="flex-1 space-y-3">
                                                <input type="file" name="attachment" id="file-input" onchange="handlePreview(this)" accept="image/*,application/pdf" class="block w-full text-[10px] text-gray-400 file:mr-3 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-brandPrimary/10 file:text-brandPrimary file:font-bold cursor-pointer">

                                                <?php if (!empty($t_file)): ?>
                                                    <a href="uploads/finance/<?= $t_file ?>" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-brandPrimary hover:text-white rounded-xl text-[10px] font-extrabold text-slate-500 transition-all uppercase tracking-wider">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke-width="2" />
                                                            <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" stroke-width="2" />
                                                        </svg>
                                                        Lihat Dokumen Saat Ini
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-12">
                            <button type="submit" class="w-full py-6 bg-brandPrimary text-white rounded-[32px] font-extrabold text-lg shadow-2xl shadow-brandPrimary/30 hover:scale-[1.01] transition-all tracking-wider uppercase">
                                SIMPAN PERUBAHAN
                            </button>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Kunci mengatasi SyntaxError: Bungkus dengan quotes dan pastikan tidak null
        const categoriesData = <?php echo json_encode($categories); ?>;
        const currentCat = "<?php echo (string)$t_category; ?>";

        function updateCategoryOptions(type) {
            const select = document.getElementById('cat-select');
            if (!select) return;
            select.innerHTML = '';
            categoriesData.filter(c => c.type === type).forEach(cat => {
                const opt = document.createElement('option');
                opt.value = cat.category_name;
                opt.textContent = cat.category_name;
                if (cat.category_name === currentCat) opt.selected = true;
                select.appendChild(opt);
            });
        }

        function handlePreview(input) {
            const container = document.getElementById('pv-edit');
            const file = input.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = e => container.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
                reader.readAsDataURL(file);
            }
        }

        // Jalankan saat load
        updateCategoryOptions("<?php echo (string)$t_type; ?>");

        function handlePreview(input) {
            const container = document.getElementById('pv-edit');
            const file = input.files[0];

            if (!file) return;

            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = e => {
                    container.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
                };
                reader.readAsDataURL(file);
            } else if (file.type === 'application/pdf') {
                container.innerHTML = `
            <div class="flex flex-col items-center gap-1 text-brandAccent">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <span class="font-bold uppercase text-[9px]">NEW PDF</span>
            </div>`;
            } else {
                container.innerHTML = `<span class="text-[8px]">FILE</span>`;
            }
        }
    </script>
</body>

</html>