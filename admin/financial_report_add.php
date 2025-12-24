<?php
require_once './process/config.php';
session_start();

// Cek Session
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Ambil data kategori awal
$cat_query = mysqli_query($conn, "SELECT * FROM transaction_categories ORDER BY category_name ASC");
$categories = [];
while ($row = mysqli_fetch_assoc($cat_query)) {
    $categories[] = $row;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bulk Add Transaction - DangDang Studio</title>
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

        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #E2E8F0;
            border-radius: 10px;
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

            <main class="flex-1 flex flex-col min-w-0 overflow-y-auto custom-scrollbar">
                <div class="p-6 md:p-10 max-w-4xl mx-auto w-full">

                    <div class="flex items-center justify-between mb-10">
                        <div>
                            <h1 class="text-3xl font-extrabold text-brandPrimary tracking-tight">Tambah Transaksi</h1>
                            <p class="text-sm text-gray-400 font-medium mt-1">Input data pengeluaran & pemasukan secara massal.</p>
                        </div>
                        <a href="financial_report.php" class="px-6 py-3 bg-white border border-gray-200 rounded-2xl text-[11px] font-extrabold text-gray-400 hover:text-brandPrimary hover:border-brandPrimary transition-all shadow-sm">KEMBALI</a>

                    </div>
                    <div class="flex items-center justify-between mb-10">
                        <div>
                            <p class="text-sm text-gray-400 font-medium mt-1">Tambah Category Baru</p>
                            <button type="button" onclick="openCategoryModal()" class="px-6 py-3 bg-white border border-gray-200 rounded-2xl text-[11px] font-extrabold text-gray-400 hover:text-brandPrimary hover:border-brandPrimary transition-all shadow-sm">+ TAMBAH KATEGORI BARU</button>
                        </div>


                    </div>

                    <form action="process/process_add_transaction_bulk.php" method="POST" enctype="multipart/form-data">
                        <div id="transaction-wrapper" class="space-y-8">

                            <div id="card-0" class="tx-card bg-white rounded-[40px] border border-gray-100 shadow-xl shadow-slate-200/40 p-8 animate-slide-up relative">
                                <div class="absolute -left-1 top-10 w-2 h-20 bg-brandPrimary rounded-full"></div>
                                <div class="flex justify-between items-center mb-6">
                                    <span class="px-5 py-1.5 bg-slate-50 rounded-full text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Utama #1</span>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                                    <div class="space-y-6">
                                        <div>
                                            <label class="block text-[11px] font-bold text-gray-400 uppercase mb-3 ml-1">Jenis Arus Kas</label>
                                            <div class="flex gap-2 p-1.5 bg-gray-50 rounded-[24px] border border-gray-100">
                                                <label class="flex-1 cursor-pointer">
                                                    <input type="radio" name="tx[0][type]" value="in" class="hidden peer" checked onchange="updateCategoryOptions(0, 'in')">
                                                    <div class="text-center py-3 rounded-[18px] text-[10px] font-extrabold transition peer-checked:bg-white peer-checked:text-brandTeal peer-checked:shadow-md text-gray-400">PEMASUKAN</div>
                                                </label>
                                                <label class="flex-1 cursor-pointer">
                                                    <input type="radio" name="tx[0][type]" value="out" class="hidden peer" onchange="updateCategoryOptions(0, 'out')">
                                                    <div class="text-center py-3 rounded-[18px] text-[10px] font-extrabold transition peer-checked:bg-white peer-checked:text-brandAccent peer-checked:shadow-md text-gray-400">PENGELUARAN</div>
                                                </label>
                                            </div>
                                        </div>

                                        <div>
                                            <div class="flex justify-between items-center mb-3 ml-1">
                                                <label class="block text-[11px] font-bold text-gray-400 uppercase">Kategori</label>

                                            </div>
                                            <select name="tx[0][category]" id="cat-select-0" class="w-full px-6 py-4 rounded-[22px] bg-gray-50 border-none text-sm font-bold outline-none cursor-pointer appearance-none"></select>
                                        </div>

                                        <div>
                                            <label class="block text-[11px] font-bold text-gray-400 uppercase mb-3 ml-1">Nominal (Rp)</label>
                                            <input type="number" name="tx[0][amount]" required placeholder="0" class="w-full px-6 py-4 rounded-[22px] bg-gray-50 border-none focus:ring-2 ring-brandPrimary outline-none text-xl font-bold text-brandPrimary transition-all">
                                        </div>
                                    </div>

                                    <div class="space-y-6">
                                        <div>
                                            <label class="block text-[11px] font-bold text-gray-400 uppercase mb-3 ml-1">Keterangan & Tanggal</label>
                                            <input type="date" name="tx[0][date]" value="<?= date('Y-m-d') ?>" class="w-full px-6 py-4 rounded-[22px] bg-gray-50 border-none text-sm font-bold mb-3 outline-none">
                                            <textarea name="tx[0][desc]" rows="3" placeholder="Catatan transaksi..." required class="w-full px-6 py-4 rounded-[22px] bg-gray-50 border-none text-sm outline-none resize-none"></textarea>
                                        </div>

                                        <div>
                                            <label class="block text-[11px] font-bold text-gray-400 uppercase mb-3 ml-1">Bukti Bukti</label>
                                            <div class="flex items-center gap-5">
                                                <div id="pv-0" class="w-24 h-24 rounded-[28px] bg-gray-50 border-2 border-dashed border-gray-200 flex items-center justify-center overflow-hidden shrink-0 transition-all text-gray-300 italic text-[8px]">PREVIEW</div>
                                                <div class="flex-1">
                                                    <input type="file" name="file_0" onchange="handlePreview(this, 0)" accept="image/*,application/pdf" class="block w-full text-[10px] text-gray-400 file:mr-3 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-brandPrimary/10 file:text-brandPrimary file:font-bold cursor-pointer">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-12 space-y-4">
                            <button type="button" onclick="addCard()" class="group w-full py-5 rounded-[32px] border-2 border-dashed border-gray-200 text-gray-400 font-bold hover:border-brandPrimary hover:text-brandPrimary transition-all flex items-center justify-center gap-3">
                                <span class="w-8 h-8 rounded-full bg-gray-100 group-hover:bg-brandPrimary/10 flex items-center justify-center transition-colors">+</span>
                                Tambah Baris Transaksi Baru
                            </button>
                            <button type="submit" class="w-full py-6 bg-brandPrimary text-white rounded-[32px] font-extrabold text-lg shadow-2xl shadow-brandPrimary/30 hover:scale-[1.01] transition-all tracking-wider">
                                SIMPAN SEMUA DATA
                            </button>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>

    <div id="categoryModal" class="fixed inset-0 z-[99] hidden flex items-center justify-center bg-black/50 backdrop-blur-sm animate-in">
        <div class="bg-white w-full max-w-md rounded-[40px] p-10 shadow-2xl relative animate-slide-up">
            <button type="button" onclick="closeCategoryModal()" class="absolute top-8 right-8 text-gray-400 hover:text-rose-500 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <h3 class="text-2xl font-extrabold text-brandPrimary mb-2">Kategori Baru</h3>
            <p class="text-xs text-gray-400 font-medium mb-8">Tambahkan kategori arus kas ke dalam sistem.</p>
            <form id="categoryForm" class="space-y-6">
                <div>
                    <label class="block text-[11px] font-bold text-gray-400 uppercase mb-3 ml-1">Nama Kategori</label>
                    <input type="text" id="new_cat_name" required placeholder="Contoh: Biaya Server" class="w-full px-6 py-4 rounded-[22px] bg-gray-50 border-none outline-none text-sm font-bold focus:ring-2 ring-brandPrimary">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-gray-400 uppercase mb-3 ml-1">Tipe Kategori</label>
                    <div class="flex gap-2 p-1.5 bg-gray-50 rounded-[24px] border border-gray-100">
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="new_cat_type" value="in" class="hidden peer" checked>
                            <div class="text-center py-3 rounded-[18px] text-[10px] font-extrabold transition peer-checked:bg-white peer-checked:text-brandTeal peer-checked:shadow-md text-gray-400 uppercase">PEMASUKAN</div>
                        </label>
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="new_cat_type" value="out" class="hidden peer">
                            <div class="text-center py-3 rounded-[18px] text-[10px] font-extrabold transition peer-checked:bg-white peer-checked:text-brandAccent peer-checked:shadow-md text-gray-400 uppercase">PENGELUARAN</div>
                        </label>
                    </div>
                </div>
                <button type="submit" id="btnSaveCategory" class="w-full py-5 bg-brandPrimary text-white rounded-[28px] font-extrabold text-sm shadow-xl shadow-brandPrimary/20 hover:scale-[1.02] transition-all">SIMPAN KATEGORI</button>
            </form>
        </div>
    </div>

    <script>
        let categoriesData = <?= json_encode($categories) ?>;
        let cardCount = 1;

        function updateCategoryOptions(index, type) {
            const select = document.getElementById(`cat-select-${index}`);
            if (!select) return;
            select.innerHTML = '';
            categoriesData.filter(c => c.type === type).forEach(cat => {
                const opt = document.createElement('option');
                opt.value = cat.category_name;
                opt.textContent = cat.category_name;
                select.appendChild(opt);
            });
        }

        function handlePreview(input, id) {
            const container = document.getElementById(`pv-${id}`);
            const file = input.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = e => container.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
                reader.readAsDataURL(file);
            } else if (file) {
                container.innerHTML = `<div class="bg-red-50 w-full h-full flex items-center justify-center text-red-500 font-bold text-[10px]">PDF</div>`;
            }
        }

        function addCard() {
            const wrapper = document.getElementById('transaction-wrapper');
            const id = cardCount;
            const cardHTML = `
                <div id="card-${id}" class="tx-card bg-white rounded-[40px] border border-gray-100 shadow-xl shadow-slate-200/40 p-8 animate-slide-up relative">
                    <button type="button" onclick="removeCard(${id})" class="absolute top-8 right-8 w-10 h-10 flex items-center justify-center rounded-2xl bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white transition-all"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg></button>
                    <div class="absolute -left-1 top-10 w-2 h-20 bg-gray-200 rounded-full"></div>
                    <div class="flex justify-between items-center mb-6"><span class="px-5 py-1.5 bg-slate-50 rounded-full text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Entry #${id + 1}</span></div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div class="space-y-6">
                            <div>
                                <label class="block text-[11px] font-bold text-gray-400 uppercase mb-3 ml-1">Jenis Arus Kas</label>
                                <div class="flex gap-2 p-1.5 bg-gray-50 rounded-[24px] border border-gray-100">
                                    <label class="flex-1 cursor-pointer"><input type="radio" name="tx[${id}][type]" value="in" class="hidden peer" checked onchange="updateCategoryOptions(${id}, 'in')"><div class="text-center py-3 rounded-[18px] text-[10px] font-extrabold transition peer-checked:bg-white peer-checked:text-brandTeal text-gray-400 uppercase">PEMASUKAN</div></label>
                                    <label class="flex-1 cursor-pointer"><input type="radio" name="tx[${id}][type]" value="out" class="hidden peer" onchange="updateCategoryOptions(${id}, 'out')"><div class="text-center py-3 rounded-[18px] text-[10px] font-extrabold transition peer-checked:bg-white peer-checked:text-brandAccent text-gray-400 uppercase">PENGELUARAN</div></label>
                                </div>
                            </div>
                            <div><label class="block text-[11px] font-bold text-gray-400 uppercase mb-3 ml-1">Kategori</label><select name="tx[${id}][category]" id="cat-select-${id}" class="w-full px-6 py-4 rounded-[22px] bg-gray-50 border-none text-sm font-bold outline-none cursor-pointer appearance-none"></select></div>
                            <div><label class="block text-[11px] font-bold text-gray-400 uppercase mb-3 ml-1">Nominal (Rp)</label><input type="number" name="tx[${id}][amount]" required class="w-full px-6 py-4 rounded-[22px] bg-gray-50 border-none text-xl font-bold text-brandPrimary outline-none"></div>
                        </div>
                        <div class="space-y-6">
                            <div><input type="date" name="tx[${id}][date]" value="<?= date('Y-m-d') ?>" class="w-full px-6 py-4 rounded-[22px] bg-gray-50 border-none text-sm font-bold mb-3 outline-none"><textarea name="tx[${id}][desc]" rows="3" required class="w-full px-6 py-4 rounded-[22px] bg-gray-50 border-none text-sm outline-none resize-none" placeholder="Catatan..."></textarea></div>
                            <div class="flex items-center gap-5"><div id="pv-${id}" class="w-24 h-24 rounded-[28px] bg-gray-50 border-2 border-dashed border-gray-200 flex items-center justify-center shrink-0 text-gray-300 text-[8px] italic overflow-hidden">PREVIEW</div><div class="flex-1"><input type="file" name="file_${id}" onchange="handlePreview(this, ${id})" class="block w-full text-[10px] text-gray-400"></div></div>
                        </div>
                    </div>
                </div>`;
            wrapper.insertAdjacentHTML('beforeend', cardHTML);
            updateCategoryOptions(id, 'in');
            cardCount++;
        }

        function removeCard(id) {
            const card = document.getElementById(`card-${id}`);
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            setTimeout(() => card.remove(), 300);
        }

        function openCategoryModal() {
            document.getElementById('categoryModal').classList.remove('hidden');
        }

        function closeCategoryModal() {
            document.getElementById('categoryModal').classList.add('hidden');
            document.getElementById('categoryForm').reset();
        }

        document.getElementById('categoryForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = document.getElementById('btnSaveCategory');
            const name = document.getElementById('new_cat_name').value;
            const type = document.querySelector('input[name="new_cat_type"]:checked').value;
            btn.innerText = "MENYIMPAN...";
            btn.disabled = true;

            fetch('process/process_add_category.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `name=${encodeURIComponent(name)}&type=${type}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        categoriesData.push({
                            category_name: name,
                            type: type
                        });
                        const allCards = document.querySelectorAll('[id^="cat-select-"]');
                        allCards.forEach((select, index) => {
                            const radio = document.querySelector(`input[name="tx[${index}][type]"]:checked`);
                            if (radio) updateCategoryOptions(index, radio.value);
                        });
                        closeCategoryModal();
                        alert("Kategori berhasil ditambahkan!");
                    } else {
                        alert("Gagal: " + data.message);
                    }
                })
                .finally(() => {
                    btn.innerText = "SIMPAN KATEGORI";
                    btn.disabled = false;
                });
        });

        // Init
        updateCategoryOptions(0, 'in');
    </script>
</body>

</html>