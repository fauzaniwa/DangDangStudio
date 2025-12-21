<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Maker - DangDang Studio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="assets/tailwind-config.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
    <script src="assets/script.js"></script>
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
                            <p class="text-sm text-gray-500">Kelola penagihan klien dan pantau status pembayaran secara real-time.</p>
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
                            <p class="text-[10px] font-bold text-gray-400 uppercase">Total Draft</p>
                            <p class="text-xl font-bold text-gray-700">12</p>
                        </div>
                        <div class="bg-white p-4 rounded-2xl border border-gray-100">
                            <p class="text-[10px] font-bold text-gray-400 uppercase">Pending</p>
                            <p class="text-xl font-bold text-brandGold">Rp 45.000.000</p>
                        </div>
                        <div class="bg-white p-4 rounded-2xl border border-gray-100">
                            <p class="text-[10px] font-bold text-gray-400 uppercase">Paid This Month</p>
                            <p class="text-xl font-bold text-brandTeal">Rp 128.000.000</p>
                        </div>
                        <div class="bg-white p-4 rounded-2xl border border-gray-100 border-l-4 border-brandAccent">
                            <p class="text-[10px] font-bold text-gray-400 uppercase">Overdue</p>
                            <p class="text-xl font-bold text-brandAccent">3 Invoices</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-[32px] shadow-sm border border-gray-100 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead>
                                    <tr class="bg-gray-50/50">
                                        <th class="px-6 py-4 text-[10px] font-bold uppercase text-gray-400">Invoice ID</th>
                                        <th class="px-6 py-4 text-[10px] font-bold uppercase text-gray-400">Client</th>
                                        <th class="px-6 py-4 text-[10px] font-bold uppercase text-gray-400">Due Date</th>
                                        <th class="px-6 py-4 text-[10px] font-bold uppercase text-gray-400">Amount</th>
                                        <th class="px-6 py-4 text-[10px] font-bold uppercase text-gray-400">Status</th>
                                        <th class="px-6 py-4 text-[10px] font-bold uppercase text-gray-400 text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    <?php
                                    $invoices = [
                                        ['id' => '#INV-2025-001', 'client' => 'PT. Maju Bersama', 'due' => '25 Dec 2025', 'total' => 'Rp 15.000.000', 'status' => 'Paid'],
                                        ['id' => '#INV-2025-002', 'client' => 'StartUp Digital', 'due' => '30 Dec 2025', 'total' => 'Rp 8.500.000', 'status' => 'Pending'],
                                        ['id' => '#INV-2025-003', 'client' => 'Kedai Kopi Senja', 'due' => '15 Dec 2025', 'total' => 'Rp 4.200.000', 'status' => 'Overdue'],
                                    ];

                                    foreach ($invoices as $inv) : ?>
                                        <tr class="hover:bg-gray-50/50 transition">
                                            <td class="px-6 py-5 text-sm font-bold text-brandPrimary"><?php echo $inv['id']; ?></td>
                                            <td class="px-6 py-5">
                                                <p class="text-sm font-semibold text-gray-700"><?php echo $inv['client']; ?></p>
                                            </td>
                                            <td class="px-6 py-5 text-xs text-gray-500"><?php echo $inv['due']; ?></td>
                                            <td class="px-6 py-5 text-sm font-bold text-brandPrimary"><?php echo $inv['total']; ?></td>
                                            <td class="px-6 py-5">
                                                <?php
                                                $statusColor = [
                                                    'Paid' => 'bg-green-50 text-green-600 border-green-100',
                                                    'Pending' => 'bg-orange-50 text-orange-600 border-orange-100',
                                                    'Overdue' => 'bg-red-50 text-red-600 border-red-100'
                                                ];
                                                ?>
                                                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase border <?php echo $statusColor[$inv['status']]; ?>">
                                                    <?php echo $inv['status']; ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-5">
                                                <div class="flex justify-center gap-2">
                                                    <button onclick="toggleStatusModal('<?php echo $inv['id']; ?>')" title="Update Status" class="p-2 text-gray-400 hover:text-brandGold hover:bg-brandGold/5 rounded-lg transition">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                    </button>

                                                    <button title="Download PDF" class="p-2 text-gray-400 hover:text-brandTeal hover:bg-brandTeal/5 rounded-lg transition">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
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
        <div class="bg-white w-full max-w-md rounded-[32px] shadow-2xl overflow-hidden transform transition-all">
            <div class="p-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
                <h3 class="font-bold text-brandPrimary">Update Payment Status</h3>
                <button onclick="toggleStatusModal()" class="text-gray-400 hover:text-brandAccent">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form action="process_update_status.php" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                <input type="hidden" name="invoice_id" id="modal_invoice_id">

                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Payment Status</label>
                    <select name="status" id="statusSelect" onchange="checkStatus(this.value)" class="w-full px-4 py-3 rounded-xl border border-gray-200 outline-none focus:border-brandPrimary transition font-bold text-sm">
                        <option value="Pending">Pending</option>
                        <option value="Paid">Paid (Lunas)</option>
                        <option value="Overdue">Overdue</option>
                    </select>
                </div>

                <div id="proofSection" class="hidden animate-fade-in">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Upload Proof of Payment</label>
                    <div class="relative group">
                        <input type="file" name="payment_proof" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                        <div class="p-8 border-2 border-dashed border-gray-200 rounded-2xl flex flex-col items-center justify-center group-hover:border-brandTeal transition">
                            <svg class="w-8 h-8 text-gray-300 group-hover:text-brandTeal mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <p class="text-[10px] font-bold text-gray-400 uppercase group-hover:text-brandTeal">Click to upload Receipt</p>
                        </div>
                    </div>
                    <p class="text-[10px] text-gray-400 mt-2 italic">*Format: JPG, PNG (Max 2MB)</p>
                </div>

                <button type="submit" class="w-full py-4 bg-brandPrimary text-white rounded-2xl font-bold shadow-xl shadow-brandPrimary/20 hover:scale-[1.02] transition-all mt-4">
                    Update Status
                </button>
            </form>
        </div>
    </div>

    <script>
        function toggleStatusModal(id = '') {
            const modal = document.getElementById('statusModal');
            document.getElementById('modal_invoice_id').value = id;
            modal.classList.toggle('hidden');
        }

        function checkStatus(val) {
            const proofSection = document.getElementById('proofSection');
            if (val === 'Paid') {
                proofSection.classList.remove('hidden');
            } else {
                proofSection.classList.add('hidden');
            }
        }
    </script>
</body>

</html>