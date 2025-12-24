<?php
require_once 'process/config.php';
session_start();

// 1. Proteksi Sesi
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// 2. Ambil ID dan Validasi
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    header("Location: financial_report.php?status=error");
    exit();
}

// 3. Ambil Data dengan Prepared Statement
// Sesuaikan dengan struktur tabel Anda: id, type, amount, description, category, transaction_date, attachment
$stmt = $conn->prepare("SELECT * FROM financial_transactions WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

// Jika data tidak ditemukan
if (!$data) {
    header("Location: financial_report.php?status=not_found");
    exit();
}

// 4. Ambil Kategori untuk Dropdown
$cat_query = mysqli_query($conn, "SELECT * FROM transaction_categories ORDER BY category_name ASC");
$categories = [];
while ($cat_row = mysqli_fetch_assoc($cat_query)) {
    $categories[] = $cat_row;
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
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .animate-slide-up { animation: slideUp 0.4s ease-out; }
        @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .tx-card { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
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
                            <p class="text-sm text-gray-400 font-medium mt-1">Mengubah data transaksi #<?= $data['id'] ?></p>
                        </div>
                        <a href="financial_report.php" class="px-6 py-3 bg-white border border-gray-200 rounded-2xl text-[11px] font-extrabold text-gray-400 hover:text-brandPrimary hover:border-brandPrimary transition-all shadow-sm">KEMBALI</a>
                    </div>

                    <form action="process/process_edit_transaction.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?= $data['id'] ?>">
                        
                        <div class="tx-card bg-white rounded-[40px] border border-gray-100 shadow-xl shadow-slate-200/40 p-8 animate-slide-up relative">
                            <div class="absolute -left-1 top-10 w-2 h-20 bg-brandPrimary rounded-full"></div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                                <div class="space-y-6">
                                    <div>
                                        <label class="block text-[11px] font-bold text-gray-400 uppercase mb-3 ml-1">Jenis Arus Kas</label>
                                        <div class="flex gap-2 p-1.5 bg-gray-50 rounded-[24px] border border-gray-100">
                                            <label class="flex-1 cursor-pointer">
                                                <input type="radio" name="type" value="in" class="hidden peer" <?= $data['type'] == 'in' ? 'checked' : '' ?> onchange="updateCategoryOptions('in')">
                                                <div class="text-center py-3 rounded-[18px] text-[10px] font-extrabold transition peer-checked:bg-white peer-checked:text-brandTeal peer-checked:shadow-md text-gray-400 uppercase">PEMASUKAN</div>
                                            </label>
                                            <label class="flex-1 cursor-pointer">
                                                <input type="radio" name="type" value="out" class="hidden peer" <?= $data['type'] == 'out' ? 'checked' : '' ?> onchange="updateCategoryOptions('out')">
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
                                        <input type="number" name="amount" value="<?= $data['amount'] ?>" required class="w-full px-6 py-4 rounded-[22px] bg-gray-50 border-none focus:ring-2 ring-brandPrimary outline-none text-xl font-bold text-brandPrimary transition-all">
                                    </div>
                                </div>

                                <div class="space-y-6">
                                    <div>
                                        <label class="block text-[11px] font-bold text-gray-400 uppercase mb-3 ml-1">Keterangan & Tanggal</label>
                                        <input type="date" name="date" value="<?= $data['transaction_date'] ?>" class="w-full px-6 py-4 rounded-[22px] bg-gray-50 border-none text-sm font-bold mb-3 outline-none">
                                        <textarea name="desc" rows="3" required class="w-full px-6 py-4 rounded-[22px] bg-gray-50 border-none text-sm outline-none resize-none" placeholder="Catatan transaksi..."><?= htmlspecialchars((string)($data['description'] ?? '')) ?></textarea>
                                    </div>

                                    <div>
                                        <label class="block text-[11px] font-bold text-gray-400 uppercase mb-3 ml-1">Bukti Transaksi</label>
                                        <div class="flex items-center gap-5">
                                            <div id="pv-edit" class="w-24 h-24 rounded-[28px] bg-gray-50 border-2 border-dashed border-gray-200 flex items-center justify-center overflow-hidden shrink-0 transition-all text-gray-300 italic text-[8px]">
                                                <?php if (!empty($data['attachment'])): ?>
                                                    <img src="uploads/finance/<?= $data['attachment'] ?>" class="w-full h-full object-cover">
                                                <?php else: ?>
                                                    PREVIEW
                                                <?php endif; ?>
                                            </div>
                                            <div class="flex-1">
                                                <input type="file" name="attachment" onchange="handlePreview(this)" accept="image/*,application/pdf" class="block w-full text-[10px] text-gray-400 file:mr-3 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-brandPrimary/10 file:text-brandPrimary file:font-bold cursor-pointer">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-12">
                            <button type="submit" class="w-full py-6 bg-brandPrimary text-white rounded-[32px] font-extrabold text-lg shadow-2xl shadow-brandPrimary/30 hover:scale-[1.01] transition-all tracking-wider uppercase">
                                PERBARUI DATA TRANSAKSI
                            </button>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>

    <script>
        const categoriesData = <?= json_encode($categories) ?>;
        const savedCategory = "<?= $data['category'] ?>";

        function updateCategoryOptions(type) {
            const select = document.getElementById('cat-select');
            if(!select) return;
            select.innerHTML = '';
            categoriesData.filter(c => c.type === type).forEach(cat => {
                const opt = document.createElement('option');
                opt.value = cat.category_name;
                opt.textContent = cat.category_name;
                if(cat.category_name === savedCategory) opt.selected = true;
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
            } else if (file) {
                container.innerHTML = `<div class="bg-gray-100 w-full h-full flex items-center justify-center text-[10px] font-bold">FILE</div>`;
            }
        }

        // Jalankan saat pertama kali load
        updateCategoryOptions("<?= $data['type'] ?>");
    </script>
</body>
</html>