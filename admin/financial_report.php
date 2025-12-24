<?php
require_once './process/config.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// --- LOGIKA AGREGASI DATA (DASHBOARD CARDS) ---
$query_summary = "SELECT 
    SUM(CASE WHEN type = 'in' THEN amount ELSE 0 END) as total_in,
    SUM(CASE WHEN type = 'out' THEN amount ELSE 0 END) as total_out
    FROM financial_transactions";
$summary_res = mysqli_fetch_assoc(mysqli_query($conn, $query_summary));

$total_in = $summary_res['total_in'] ?? 0;
$total_out = $summary_res['total_out'] ?? 0;
$net_profit = $total_in - $total_out;
$margin = ($total_in > 0) ? ($net_profit / $total_in) * 100 : 0;

// --- LOGIKA AMBIL DATA TABEL ---
$filter_month = isset($_GET['month']) ? mysqli_real_escape_string($conn, $_GET['month']) : '';
$sql_list = "SELECT * FROM financial_transactions ORDER BY transaction_date DESC, id DESC";
$result_list = mysqli_query($conn, $sql_list);

// --- LOGIKA DATA CHART (12 BULAN TERAKHIR) ---
$months = [];
$income_data = [];
$expense_data = [];
$profit_data = [];

for ($i = 11; $i >= 0; $i--) {
    $month_label = date('M Y', strtotime("-$i months"));
    $months[] = $month_label;

    $start_date = date('Y-m-01', strtotime("-$i months"));
    $end_date = date('Y-m-t', strtotime("-$i months"));

    $q_chart = "SELECT 
        SUM(CASE WHEN type = 'in' THEN amount ELSE 0 END) as inc,
        SUM(CASE WHEN type = 'out' THEN amount ELSE 0 END) as exp
        FROM financial_transactions 
        WHERE transaction_date BETWEEN '$start_date' AND '$end_date'";

    $res_chart = mysqli_fetch_assoc(mysqli_query($conn, $q_chart));

    $inc = $res_chart['inc'] ?? 0;
    $exp = $res_chart['exp'] ?? 0;

    $income_data[] = $inc;
    $expense_data[] = $exp;
    $profit_data[] = $inc - $exp;
}
?>



<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Financial Report - DangDang Studio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="assets/tailwind-config.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Custom Scrollbar untuk Modal agar tetap cantik */
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #e2e2e2;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #d1d1d1;
        }
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
                        <div id="alert-feedback" class="mb-8 animate-slide-up">
                            <?php if ($_GET['status'] == 'success'): ?>
                                <div class="flex items-center gap-4 p-5 bg-emerald-50 border border-emerald-100 rounded-[24px]">
                                    <div class="w-12 h-12 bg-emerald-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-emerald-200">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-emerald-900">Berhasil!</h4>
                                        <p class="text-sm text-emerald-700/80">Finance baru telah ditambahkan.</p>
                                    </div>
                                    <button onclick="this.parentElement.parentElement.remove()" class="ml-auto text-emerald-400 hover:text-emerald-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            <?php elseif ($_GET['status'] == 'deleted'): ?>
                                <div class="flex items-center gap-4 p-5 bg-amber-50 border border-amber-100 rounded-[24px]">
                                    <div class="w-12 h-12 bg-amber-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-amber-200">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-amber-900">Data Dihapus</h4>
                                        <p class="text-sm text-amber-700/80">Finance telah dihapus secara permanen dari sistem.</p>
                                    </div>
                                    <button onclick="this.parentElement.parentElement.remove()" class="ml-auto text-amber-400 hover:text-amber-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            <?php elseif ($_GET['status'] == 'updated'): ?>
                                <div class="flex items-center gap-4 p-5 bg-emerald-50 border border-emerald-100 rounded-[24px]">
                                    <div class="w-12 h-12 bg-emerald-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-emerald-200">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-emerald-900">Berhasil!</h4>
                                        <p class="text-sm text-emerald-700/80">Data Finance telah diubah.</p>
                                    </div>
                                    <button onclick="this.parentElement.parentElement.remove()" class="ml-auto text-emerald-400 hover:text-emerald-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                        <div>
                            <h1 class="text-2xl font-bold text-brandPrimary">Financial Report</h1>
                            <p class="text-sm text-gray-400 font-medium">Pantau arus kas masuk dan keluar studio Anda.</p>
                        </div>
                        <div class="flex gap-3">
                            <button class="px-5 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-bold text-gray-600 hover:bg-gray-50 transition">Export PDF</button>


                            <a href="financial_report_add.php" class="px-5 py-2.5 bg-brandPrimary text-white rounded-xl text-sm font-bold shadow-lg shadow-brandPrimary/20 hover:scale-105 transition-all inline-block">
                                + Add Transaction
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                        <div class="bg-white p-6 rounded-[32px] shadow-sm border border-gray-100 relative overflow-hidden group">
                            <div class="absolute -right-4 -top-4 w-24 h-24 bg-brandTeal/5 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-1">Total Income</p>
                            <h3 class="text-2xl font-bold text-brandTeal">Rp <?php echo number_format($total_in, 0, ',', '.'); ?></h3>
                            <p class="text-[10px] text-green-500 font-bold mt-2">↑ Up to date</p>
                        </div>
                        <div class="bg-white p-6 rounded-[32px] shadow-sm border border-gray-100 relative overflow-hidden group">
                            <div class="absolute -right-4 -top-4 w-24 h-24 bg-brandAccent/5 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-1">Total Expenses</p>
                            <h3 class="text-2xl font-bold text-brandAccent">Rp <?php echo number_format($total_out, 0, ',', '.'); ?></h3>
                            <p class="text-[10px] text-gray-400 font-bold mt-2">Operational & Payroll</p>
                        </div>
                        <div class="bg-white p-6 rounded-[32px] shadow-sm border border-brandPrimary/10 bg-brandPrimary/[0.02] relative overflow-hidden group">
                            <div class="absolute -right-4 -top-4 w-24 h-24 bg-brandPrimary/5 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-1">Net Profit</p>
                            <h3 class="text-2xl font-bold text-brandPrimary">Rp <?php echo number_format($net_profit, 0, ',', '.'); ?></h3>
                            <p class="text-[10px] text-brandTeal font-bold mt-2">Safe Margin (<?php echo round($margin, 1); ?>%)</p>
                        </div>
                    </div>

                    <div class="bg-white p-8 rounded-[32px] shadow-sm border border-gray-100 mb-10">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="font-bold text-brandPrimary text-lg">Financial Overview</h3>
                                <p class="text-xs text-gray-400 font-medium">Performa keuangan 12 bulan terakhir</p>
                            </div>
                            <div class="flex gap-4">
                                <div class="flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-full bg-brandTeal"></span>
                                    <span class="text-[10px] font-bold text-gray-400 uppercase">Income</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-full bg-brandAccent"></span>
                                    <span class="text-[10px] font-bold text-gray-400 uppercase">Outcome</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-full bg-brandPrimary"></span>
                                    <span class="text-[10px] font-bold text-gray-400 uppercase">Net Profit</span>
                                </div>
                            </div>
                        </div>
                        <div class="h-[300px] w-full">
                            <canvas id="financialChart"></canvas>
                        </div>
                    </div>

                    <div class="bg-white rounded-[32px] shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-6 border-b border-gray-50 flex justify-between items-center">
                            <h3 class="font-bold text-brandPrimary">Recent Transactions</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead>
                                    <tr class="bg-gray-50/50">
                                        <th class="px-8 py-4 text-[10px] font-bold uppercase text-gray-400">Description</th>
                                        <th class="px-8 py-4 text-[10px] font-bold uppercase text-gray-400">Category</th>
                                        <th class="px-8 py-4 text-[10px] font-bold uppercase text-gray-400">Date</th>
                                        <th class="px-8 py-4 text-[10px] font-bold uppercase text-gray-400">Amount</th>
                                        <th class="px-8 py-4 text-[10px] font-bold uppercase text-gray-400 text-center">Proof & Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    <?php while ($tx = mysqli_fetch_assoc($result_list)) : ?>
                                        <tr class="hover:bg-gray-50/50 transition cursor-default">
                                            <td class="px-8 py-5">
                                                <p class="text-sm font-bold text-brandPrimary"><?php echo htmlspecialchars($tx['description']); ?></p>
                                            </td>
                                            <td class="px-8 py-5">
                                                <span class="text-[10px] font-bold px-2 py-1 rounded-md bg-gray-50 text-gray-500 border border-gray-100">
                                                    <?php echo htmlspecialchars($tx['category']); ?>
                                                </span>
                                            </td>
                                            <td class="px-8 py-5 text-xs text-gray-400">
                                                <?php echo date('d M Y', strtotime($tx['transaction_date'])); ?>
                                            </td>
                                            <td class="px-8 py-5">
                                                <span class="text-sm font-bold <?php echo $tx['type'] == 'in' ? 'text-brandTeal' : 'text-brandAccent'; ?>">
                                                    <?php echo ($tx['type'] == 'in' ? '+ ' : '- ') . 'Rp ' . number_format($tx['amount'], 0, ',', '.'); ?>
                                                </span>
                                            </td>
                                            <td class="px-8 py-5">
                                                <div class="flex justify-center items-center gap-3">
                                                    <?php if (!empty($tx['attachment'])): ?>
                                                        <a href="uploads/finance/<?php echo $tx['attachment']; ?>" target="_blank" title="View Proof" class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center text-gray-400 hover:bg-brandPrimary hover:text-white transition-all shadow-sm">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                            </svg>
                                                        </a>
                                                    <?php else: ?>
                                                        <span class="text-[9px] text-gray-300 italic font-bold">No Proof</span>
                                                    <?php endif; ?>

                                                    <a href="financial_report_edit.php?id=<?php echo $tx['id']; ?>" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-500 hover:bg-blue-500 hover:text-white flex items-center justify-center transition-all shadow-sm" title="Edit">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                                        </svg>
                                                    </a>

                                                    <button onclick="openDeleteModal(<?php echo $tx['id']; ?>, '<?php echo htmlspecialchars($tx['description']); ?>')"
                                                        class="w-8 h-8 rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white flex items-center justify-center transition-all shadow-sm" title="Delete">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <?php include_once '_footer.php'; ?>
            </main>
        </div>
    </div>

    <div id="transactionModal" class="fixed inset-0 bg-brandPrimary/60 backdrop-blur-sm z-[150] hidden flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-lg rounded-[32px] shadow-2xl overflow-hidden flex flex-col max-h-[90vh] transform transition-all">

            <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-gray-50/50 flex-shrink-0">
                <div>
                    <h3 class="text-xl font-bold text-brandPrimary">Add New Transaction</h3>
                    <p class="text-xs text-gray-400 font-medium">Catat arus kas studio dengan bukti resi.</p>
                </div>
                <button onclick="toggleModal()" class="p-2 hover:bg-white rounded-xl transition text-gray-400 hover:text-brandAccent">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto p-8 custom-scrollbar">
                <form action="process/process_add_transaction.php" method="POST" enctype="multipart/form-data" class="space-y-5">
                    <div class="flex gap-4 p-1 bg-gray-100 rounded-2xl">
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="type" value="in" class="hidden peer" checked>
                            <div class="text-center py-2 rounded-xl text-xs font-bold transition peer-checked:bg-white peer-checked:text-brandTeal peer-checked:shadow-sm text-gray-400">Income</div>
                        </label>
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="type" value="out" class="hidden peer">
                            <div class="text-center py-2 rounded-xl text-xs font-bold transition peer-checked:bg-white peer-checked:text-brandAccent peer-checked:shadow-sm text-gray-400">Expense</div>
                        </label>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Nominal (Rp)</label>
                        <input type="number" name="amount" required class="w-full px-5 py-3 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-brandPrimary outline-none transition font-bold text-lg text-brandPrimary">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Category</label>
                        <select name="category" required class="w-full px-5 py-3 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white outline-none transition text-sm font-bold">
                            <optgroup label="Income (Pendapatan)">
                                <option value="Project Payment">Project Payment</option>
                                <option value="Maintenance Fee">Maintenance Fee</option>
                                <option value="Asset Sale">Digital Asset Sale</option>
                            </optgroup>
                            <optgroup label="Expenses (Pengeluaran)">
                                <option value="Salary">Payroll / Gaji</option>
                                <option value="Operational">Operational</option>
                                <option value="Software/Sub">Software Subscription</option>
                                <option value="Hardware">Hardware & Equipment</option>
                                <option value="Marketing">Marketing / Ads</option>
                            </optgroup>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Description</label>
                        <input type="text" name="description" required placeholder="Contoh: Pembelian Aset UI" class="w-full px-5 py-3 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white outline-none transition">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Transaction Date</label>
                        <input type="date" name="date" value="<?php echo date('Y-m-d'); ?>" class="w-full px-5 py-3 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white outline-none transition text-sm">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Attachment (Image/PDF)</label>
                        <div class="space-y-3">
                            <input type="file" name="attachment" id="fileInput" accept="image/*,application/pdf" onchange="handleFilePreview(this, 'previewContainer', 'imgPreview', 'pdfPreview', 'pdfName')"
                                class="w-full text-xs text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-bold file:bg-brandPrimary file:text-white hover:file:bg-brandPrimary/80 cursor-pointer">

                            <div id="previewContainer" class="hidden relative w-full rounded-2xl border border-gray-100 bg-gray-50 overflow-hidden flex items-center justify-center min-h-[150px]">
                                <img id="imgPreview" class="hidden w-full h-auto object-contain max-h-[300px]" src="">
                                <div id="pdfPreview" class="hidden flex flex-col items-center gap-2 py-6 text-brandAccent">
                                    <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 2a2 2 0 00-2 2v8a2 2 0 002 2h6a2 2 0 002-2V6.414A2 2 0 0016.414 5L14 2.586A2 2 0 0012.586 2H9z"></path>
                                        <path d="M3 8a2 2 0 012-2v10h8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"></path>
                                    </svg>
                                    <span id="pdfName" class="text-[10px] font-bold uppercase truncate max-w-[200px]">document.pdf</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 sticky bottom-0 bg-white pb-2">
                        <button type="submit" class="w-full py-4 bg-brandPrimary text-white rounded-2xl font-bold shadow-xl shadow-brandPrimary/20 hover:scale-[1.02] active:scale-95 transition-all">
                            Simpan Transaksi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="editModal" class="fixed inset-0 bg-brandPrimary/60 backdrop-blur-sm z-[150] hidden flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-lg rounded-[32px] shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
            <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-gray-50/50 flex-shrink-0">
                <div>
                    <h3 class="text-xl font-bold text-brandPrimary">Edit Transaction</h3>
                    <p class="text-xs text-gray-400 font-medium">Perbarui data transaksi keuangan.</p>
                </div>
                <button onclick="toggleEditModal()" class="p-2 text-gray-400 hover:text-brandAccent">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="flex-1 overflow-y-auto p-8 custom-scrollbar">
                <form action="process/process_edit_transaction.php" method="POST" enctype="multipart/form-data" class="space-y-5">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="flex gap-4 p-1 bg-gray-100 rounded-2xl">
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="type" id="edit_type_in" value="in" class="hidden peer">
                            <div class="text-center py-2 rounded-xl text-xs font-bold transition peer-checked:bg-white peer-checked:text-brandTeal text-gray-400">Income</div>
                        </label>
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="type" id="edit_type_out" value="out" class="hidden peer">
                            <div class="text-center py-2 rounded-xl text-xs font-bold transition peer-checked:bg-white peer-checked:text-brandAccent text-gray-400">Expense</div>
                        </label>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Nominal (Rp)</label>
                        <input type="number" name="amount" id="edit_amount" required class="w-full px-5 py-3 rounded-2xl border border-gray-100 bg-gray-50 font-bold text-brandPrimary outline-none">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Category</label>
                        <select name="category" id="edit_category" class="w-full px-5 py-3 rounded-2xl border border-gray-100 outline-none text-sm font-bold">
                            <optgroup label="Income (Pendapatan)">
                                <option value="Project Payment">Project Payment</option>
                                <option value="Maintenance Fee">Maintenance Fee</option>
                                <option value="Asset Sale">Digital Asset Sale</option>
                            </optgroup>
                            <optgroup label="Expenses (Pengeluaran)">
                                <option value="Salary">Payroll / Gaji</option>
                                <option value="Operational">Operational</option>
                                <option value="Software/Sub">Software Subscription</option>
                                <option value="Hardware">Hardware & Equipment</option>
                                <option value="Marketing">Marketing / Ads</option>
                            </optgroup>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Description</label>
                        <input type="text" name="description" id="edit_description" required class="w-full px-5 py-3 rounded-2xl border border-gray-100 outline-none">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Date</label>
                        <input type="date" name="date" id="edit_date" class="w-full px-5 py-3 rounded-2xl border border-gray-100 outline-none">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Attachment (Ganti jika perlu)</label>
                        <input type="file" name="attachment" onchange="handleFilePreview(this, 'previewContainerEdit', 'imgPreviewEdit', 'pdfPreviewEdit', 'pdfNameEdit')" class="text-xs text-gray-400">
                        <div id="previewContainerEdit" class="hidden mt-3 rounded-xl border border-gray-100 p-2 min-h-[100px] flex items-center justify-center">
                            <img id="imgPreviewEdit" class="hidden max-h-[150px] w-full object-contain">
                            <div id="pdfPreviewEdit" class="hidden flex flex-col items-center py-2 text-brandAccent">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 2a2 2 0 00-2 2v8a2 2 0 002 2h6a2 2 0 002-2V6.414A2 2 0 0016.414 5L14 2.586A2 2 0 0012.586 2H9z"></path>
                                    <path d="M3 8a2 2 0 012-2v10h8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"></path>
                                </svg>
                                <span id="pdfNameEdit" class="text-[9px] font-bold uppercase truncate max-w-[150px]"></span>
                            </div>
                        </div>
                    </div>
                    <div class="pt-4 sticky bottom-0 bg-white">
                        <button type="submit" class="w-full py-4 bg-brandPrimary text-white rounded-2xl font-bold shadow-xl">Update Transaksi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="deleteModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[200] hidden flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-sm rounded-[32px] p-8 text-center shadow-2xl">
            <div class="w-20 h-20 bg-red-50 text-red-500 rounded-3xl flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-slate-900 mb-2">Hapus Transaksi?</h3>
            <p class="text-sm text-slate-500 mb-8">Anda akan menghapus <span id="deleteItemName" class="font-bold text-slate-700"></span>. Tindakan ini tidak dapat dibatalkan.</p>
            <div class="flex gap-3">
                <button onclick="toggleDeleteModal()" class="flex-1 py-4 bg-slate-100 text-slate-600 rounded-2xl font-bold hover:bg-slate-200 transition">Batal</button>
                <a id="confirmDeleteBtn" href="#" class="flex-1 py-4 bg-red-500 text-white rounded-2xl font-bold shadow-lg shadow-red-200 hover:bg-red-600 transition">Ya, Hapus</a>
            </div>
        </div>
    </div>

    <script>
        function toggleModal() {
            const modal = document.getElementById('transactionModal');
            modal.classList.toggle('hidden');
        }

        function toggleEditModal() {
            const modal = document.getElementById('editModal');
            modal.classList.toggle('hidden');
        }

        function toggleDeleteModal() {
            document.getElementById('deleteModal').classList.toggle('hidden');
        }

        function openDeleteModal(id, name) {
            document.getElementById('deleteItemName').innerText = name;
            document.getElementById('confirmDeleteBtn').href = 'process/process_delete_transaction.php?id=' + id;
            toggleDeleteModal();
        }

        function handleFilePreview(input, containerId, imgId, pdfId, nameId) {
            const container = document.getElementById(containerId);
            const imgPrev = document.getElementById(imgId);
            const pdfPrev = document.getElementById(pdfId);
            const pdfName = document.getElementById(nameId);

            if (input.files && input.files[0]) {
                const file = input.files[0];
                container.classList.remove('hidden');

                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = e => {
                        imgPrev.src = e.target.result;
                        imgPrev.classList.remove('hidden');
                        pdfPrev.classList.add('hidden');
                    }
                    reader.readAsDataURL(file);
                } else if (file.type === 'application/pdf') {
                    pdfName.innerText = file.name;
                    pdfPrev.classList.remove('hidden');
                    imgPrev.classList.add('hidden');
                }
            } else {
                container.classList.add('hidden');
            }
        }

        function openEditModal(data) {
            document.getElementById('edit_id').value = data.id;
            document.getElementById('edit_amount').value = data.amount;
            document.getElementById('edit_description').value = data.description;
            document.getElementById('edit_category').value = data.category;
            document.getElementById('edit_date').value = data.transaction_date;

            if (data.type === 'in') document.getElementById('edit_type_in').checked = true;
            else document.getElementById('edit_type_out').checked = true;

            toggleEditModal();
        }
    </script>

    <script>
        const ctx = document.getElementById('financialChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($months); ?>,
                datasets: [{
                        label: 'Income',
                        data: <?php echo json_encode($income_data); ?>,
                        borderColor: '#2DD4BF', // brandTeal
                        backgroundColor: 'rgba(45, 212, 191, 0.1)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 3,
                        pointRadius: 4
                    },
                    {
                        label: 'Outcome',
                        data: <?php echo json_encode($expense_data); ?>,
                        borderColor: '#FB7185', // brandAccent
                        backgroundColor: 'rgba(251, 113, 133, 0.1)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 3,
                        pointRadius: 4
                    },
                    {
                        label: 'Net Profit',
                        data: <?php echo json_encode($profit_data); ?>,
                        borderColor: '#4F46E5', // brandPrimary
                        backgroundColor: 'rgba(79, 70, 229, 0.05)',
                        fill: false,
                        tension: 0.4,
                        borderWidth: 4,
                        borderDash: [5, 5], // Garis putus-putus untuk profit
                        pointRadius: 5
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
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            display: false
                        },
                        ticks: {
                            callback: (value) => 'Rp ' + value.toLocaleString('id-ID')
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    </script>

</body>

</html>