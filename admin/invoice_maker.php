<?php
require_once 'process/config.php';
session_start();

// Pastikan Admin Login
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// 1. Ambil Statistik Invoice (Menambahkan logika Void)
$stats_query = "SELECT 
    COUNT(id) as total_inv,
    SUM(CASE WHEN status = 'unpaid' THEN total_amount ELSE 0 END) as total_pending,
    SUM(CASE WHEN status = 'paid' AND MONTH(invoice_date) = MONTH(CURRENT_DATE) THEN total_amount ELSE 0 END) as paid_month,
    COUNT(CASE WHEN status = 'void' THEN 1 END) as void_count
    FROM invoices";
$stats_result = mysqli_query($conn, $stats_query);
$stats = mysqli_fetch_assoc($stats_result);

// 2. Ambil Data List Invoice (JOIN dengan Clients)
$sql_list = "SELECT i.*, c.company_name 
             FROM invoices i 
             JOIN clients c ON i.client_id = c.id 
             ORDER BY i.created_at DESC";
$invoices_result = mysqli_query($conn, $sql_list);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Manager - DangDang Studio</title>
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
                            <h1 class="text-2xl font-bold text-brandPrimary">Invoice Manager</h1>
                            <p class="text-sm text-gray-500">Pantau status pembayaran dan kelola bukti transaksi klien.</p>
                        </div>
                        <a href="invoice_create.php" class="flex items-center justify-center gap-2 px-6 py-3 bg-brandPrimary text-white rounded-2xl font-bold shadow-lg shadow-brandPrimary/20 hover:scale-105 transition-all text-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Create New Invoice
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                        <div class="bg-white p-4 rounded-2xl border border-gray-100">
                            <p class="text-[10px] font-bold text-gray-400 uppercase">Total Invoice</p>
                            <p class="text-xl font-bold text-gray-700"><?php echo $stats['total_inv'] ?? 0; ?></p>
                        </div>
                        <div class="bg-white p-4 rounded-2xl border border-gray-100">
                            <p class="text-[10px] font-bold text-gray-400 uppercase">Pending Amount</p>
                            <p class="text-xl font-bold text-brandGold">Rp <?php echo number_format($stats['total_pending'] ?? 0, 0, ',', '.'); ?></p>
                        </div>
                        <div class="bg-white p-4 rounded-2xl border border-gray-100">
                            <p class="text-[10px] font-bold text-gray-400 uppercase">Paid (This Month)</p>
                            <p class="text-xl font-bold text-brandTeal">Rp <?php echo number_format($stats['paid_month'] ?? 0, 0, ',', '.'); ?></p>
                        </div>
                        <div class="bg-white p-4 rounded-2xl border border-gray-100">
                            <p class="text-[10px] font-bold text-gray-400 uppercase">Canceled (Void)</p>
                            <p class="text-xl font-bold text-red-400"><?php echo $stats['void_count'] ?? 0; ?></p>
                        </div>
                    </div>

                    <div class="bg-white rounded-[32px] shadow-sm border border-gray-100 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead>
                                    <tr class="bg-gray-50/50">
                                        <th class="px-6 py-4 text-[10px] font-bold uppercase text-gray-400">Invoice ID</th>
                                        <th class="px-6 py-4 text-[10px] font-bold uppercase text-gray-400">Client</th>
                                        <th class="px-6 py-4 text-[10px] font-bold uppercase text-gray-400 text-center">Attachment</th>
                                        <th class="px-6 py-4 text-[10px] font-bold uppercase text-gray-400">Amount</th>
                                        <th class="px-6 py-4 text-[10px] font-bold uppercase text-gray-400">Status</th>
                                        <th class="px-6 py-4 text-[10px] font-bold uppercase text-gray-400 text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    <?php while ($inv = mysqli_fetch_assoc($invoices_result)) :
                                        $status = $inv['status'];
                                        $style = [
                                            'paid'    => 'bg-green-50 text-green-600 border-green-100',
                                            'unpaid'  => 'bg-orange-50 text-orange-600 border-orange-100',
                                            'void'    => 'bg-gray-100 text-gray-400 border-gray-200'
                                        ];
                                    ?>
                                        <tr class="hover:bg-gray-50/50 transition">
                                            <td class="px-6 py-5 text-sm font-bold text-brandPrimary"><?php echo $inv['invoice_no']; ?></td>
                                            <td class="px-6 py-5">
                                                <p class="text-sm font-semibold text-gray-700"><?php echo htmlspecialchars($inv['company_name']); ?></p>
                                                <p class="text-[10px] text-gray-400"><?php echo date('d M Y', strtotime($inv['due_date'])); ?></p>
                                            </td>
                                            <td class="px-6 py-5 text-center">
                                                <?php if ($inv['payment_proof']): ?>
                                                    <a href="../uploads/proofs/<?php echo $inv['payment_proof']; ?>" target="_blank" class="inline-flex items-center gap-1 text-[10px] font-bold text-brandTeal bg-brandTeal/5 px-2 py-1 rounded-lg">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.414a4 4 0 00-5.656-5.656l-6.415 6.414a6 6 0 108.486 8.486L20.5 13"></path>
                                                        </svg>
                                                        VIEW PROOF
                                                    </a>
                                                <?php else: ?>
                                                    <span class="text-[10px] text-gray-300 font-medium italic">No Attachment</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-6 py-5 text-sm font-bold text-brandPrimary">
                                                Rp <?php echo number_format($inv['total_amount'], 0, ',', '.'); ?>
                                            </td>
                                            <td class="px-6 py-5">
                                                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase border <?php echo $style[$status]; ?>">
                                                    <?php echo $status; ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-5">
                                                <div class="flex justify-center gap-2">
                                                    <button onclick="toggleStatusModal('<?php echo $inv['id']; ?>', '<?php echo $status; ?>')"
                                                        class="p-2 text-gray-400 hover:text-brandGold transition">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                    </button>
                                                    <a href="invoice_view.php?id=<?php echo $inv['id']; ?>" target="_blank" class="p-2 text-gray-400 hover:text-brandTeal transition">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                        </svg>
                                                    </a>
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

    <div id="statusModal" class="fixed inset-0 bg-brandPrimary/60 backdrop-blur-sm z-[150] hidden flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-md rounded-[32px] shadow-2xl overflow-hidden">
            <div class="p-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
                <h3 class="font-bold text-brandPrimary text-lg">Update Payment Status</h3>
                <button onclick="toggleStatusModal()" class="text-gray-400 hover:text-brandAccent">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form action="process/update_invoice_status.php" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
                <input type="hidden" name="invoice_id" id="modal_invoice_id">

                <div class="space-y-2">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider">Select New Status</label>
                    <select name="status" id="statusSelect" onchange="checkStatus(this.value)"
                        class="w-full px-5 py-4 rounded-2xl border border-gray-100 bg-gray-50/50 focus:bg-white focus:border-brandPrimary outline-none transition font-bold text-gray-700">
                        <option value="unpaid">Unpaid / Pending</option>
                        <option value="paid">Paid (Lunas)</option>
                        <option value="void">Void (Dibatalkan)</option>
                    </select>
                </div>

                <div id="proofSection" class="hidden animate-in fade-in slide-in-from-top-4 duration-300">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Upload Proof of Payment <span class="text-red-500">*</span></label>
                    <div class="relative group mb-4">
                        <input type="file" name="payment_proof" id="fileInput" accept="image/*,application/pdf" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                        <div class="p-10 border-2 border-dashed border-gray-200 rounded-3xl flex flex-col items-center justify-center group-hover:border-brandTeal group-hover:bg-brandTeal/[0.02] transition-all">
                            <div class="w-12 h-12 bg-gray-50 rounded-2xl flex items-center justify-center mb-3 group-hover:bg-brandTeal/10 transition">
                                <svg class="w-6 h-6 text-gray-400 group-hover:text-brandTeal" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <p class="text-xs font-bold text-gray-500 group-hover:text-brandTeal uppercase">Choose Payment Receipt</p>
                            <p id="fileName" class="text-[10px] text-gray-400 mt-1 italic text-center px-4">JPG, PNG or PDF (Max 2MB)</p>
                        </div>
                    </div>

                    <div id="previewContainer" class="hidden border border-gray-100 rounded-2xl overflow-hidden bg-gray-50 p-2">
                        <div id="imagePreview" class="hidden">
                            <img src="" alt="Preview" class="w-full h-32 object-cover rounded-xl shadow-sm">
                        </div>
                        <div id="pdfPreview" class="hidden flex items-center gap-3 p-3">
                            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center text-red-600">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 2a2 2 0 00-2 2v8a2 2 0 002 2h6a2 2 0 002-2V6.414A2 2 0 0016.414 5L14 2.586A2 2 0 0012.586 2H9z"></path>
                                    <path d="M3 8a2 2 0 012-2v10h8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"></path>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p id="pdfName" class="text-xs font-bold text-gray-700 truncate"></p>
                                <p class="text-[10px] text-gray-400 uppercase">PDF Document</p>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full py-4 bg-brandPrimary text-white rounded-2xl font-bold shadow-xl shadow-brandPrimary/20 hover:scale-[1.02] active:scale-95 transition-all mt-4">
                    Confirm Changes
                </button>
            </form>
        </div>
    </div>


    <script>
    function toggleStatusModal(id = '', currentStatus = '') {
        const modal = document.getElementById('statusModal');
        const select = document.getElementById('statusSelect');
        
        if (id) {
            document.getElementById('modal_invoice_id').value = id;
            select.value = currentStatus;
            
            // Reset Preview saat modal dibuka
            resetPreview();
            checkStatus(currentStatus);
        }
        modal.classList.toggle('hidden');
    }

    function checkStatus(val) {
        const proofSection = document.getElementById('proofSection');
        const fileInput = document.getElementById('fileInput');
        
        if (val === 'paid') {
            proofSection.classList.remove('hidden');
            fileInput.required = true;
        } else {
            proofSection.classList.add('hidden');
            fileInput.required = false;
        }
    }

    function resetPreview() {
        document.getElementById('previewContainer').classList.add('hidden');
        document.getElementById('imagePreview').classList.add('hidden');
        document.getElementById('pdfPreview').classList.add('hidden');
        document.getElementById('fileName').textContent = "JPG, PNG or PDF (Max 2MB)";
    }

    // Mekanisme Preview File
    document.getElementById('fileInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const container = document.getElementById('previewContainer');
        const imgPrev = document.getElementById('imagePreview');
        const pdfPrev = document.getElementById('pdfPreview');
        const fileNameDisp = document.getElementById('fileName');

        if (file) {
            fileNameDisp.textContent = file.name;
            container.classList.remove('hidden');

            const reader = new FileReader();

            if (file.type.startsWith('image/')) {
                // Jika Gambar
                reader.onload = function(event) {
                    imgPrev.querySelector('img').src = event.target.result;
                    imgPrev.classList.remove('hidden');
                    pdfPrev.classList.add('hidden');
                }
                reader.readAsDataURL(file);
            } else if (file.type === 'application/pdf') {
                // Jika PDF
                document.getElementById('pdfName').textContent = file.name;
                pdfPrev.classList.remove('hidden');
                imgPrev.classList.add('hidden');
            }
        } else {
            resetPreview();
        }
    });
</script>
</body>

</html>