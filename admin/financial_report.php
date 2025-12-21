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
                            <h1 class="text-2xl font-bold text-brandPrimary">Financial Report</h1>
                            <p class="text-sm text-gray-400 font-medium">Pantau arus kas masuk dan keluar studio Anda.</p>
                        </div>
                        <div class="flex gap-3">
                            <button class="px-5 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-bold text-gray-600 hover:bg-gray-50 transition">Export PDF</button>
                            <button onclick="toggleModal()" class="px-5 py-2.5 bg-brandPrimary text-white rounded-xl text-sm font-bold shadow-lg shadow-brandPrimary/20 hover:scale-105 transition-all">
                                + Add Transaction
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                        <div class="bg-white p-6 rounded-[32px] shadow-sm border border-gray-100 relative overflow-hidden group">
                            <div class="absolute -right-4 -top-4 w-24 h-24 bg-brandTeal/5 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-1">Total Income</p>
                            <h3 class="text-2xl font-bold text-brandTeal">Rp 128.450.000</h3>
                            <p class="text-[10px] text-green-500 font-bold mt-2">↑ 12% from last month</p>
                        </div>
                        <div class="bg-white p-6 rounded-[32px] shadow-sm border border-gray-100 relative overflow-hidden group">
                            <div class="absolute -right-4 -top-4 w-24 h-24 bg-brandAccent/5 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-1">Total Expenses</p>
                            <h3 class="text-2xl font-bold text-brandAccent">Rp 42.100.000</h3>
                            <p class="text-[10px] text-gray-400 font-bold mt-2">Operational & Payroll</p>
                        </div>
                        <div class="bg-white p-6 rounded-[32px] shadow-sm border border-brandPrimary/10 bg-brandPrimary/[0.02] relative overflow-hidden group">
                            <div class="absolute -right-4 -top-4 w-24 h-24 bg-brandPrimary/5 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-1">Net Profit</p>
                            <h3 class="text-2xl font-bold text-brandPrimary">Rp 86.350.000</h3>
                            <p class="text-[10px] text-brandTeal font-bold mt-2">Safe Margin (67%)</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-[32px] shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-6 border-b border-gray-50 flex justify-between items-center">
                            <h3 class="font-bold text-brandPrimary">Recent Transactions</h3>
                            <select class="text-xs font-bold text-gray-400 bg-gray-50 px-3 py-1 rounded-lg outline-none cursor-pointer hover:text-brandPrimary transition">
                                <option>All Months</option>
                                <option>December 2025</option>
                                <option>November 2025</option>
                            </select>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead>
                                    <tr class="bg-gray-50/50">
                                        <th class="px-8 py-4 text-[10px] font-bold uppercase text-gray-400">Description</th>
                                        <th class="px-8 py-4 text-[10px] font-bold uppercase text-gray-400">Category</th>
                                        <th class="px-8 py-4 text-[10px] font-bold uppercase text-gray-400">Date</th>
                                        <th class="px-8 py-4 text-[10px] font-bold uppercase text-gray-400">Amount</th>
                                        <th class="px-8 py-4 text-[10px] font-bold uppercase text-gray-400 text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    <?php
                                    $transactions = [
                                        ['desc' => 'DP - Website PT. Maju Bersama', 'cat' => 'Income', 'date' => '20 Dec 2025', 'amount' => '+ Rp 15.000.000', 'type' => 'in'],
                                        ['desc' => 'Sewa Cloud Server (AWS)', 'cat' => 'Operational', 'date' => '18 Dec 2025', 'amount' => '- Rp 2.450.000', 'type' => 'out'],
                                        ['desc' => 'Final Payment - Branding KS', 'cat' => 'Income', 'date' => '15 Dec 2025', 'amount' => '+ Rp 8.000.000', 'type' => 'in'],
                                        ['desc' => 'Pembelian Aset Envato', 'cat' => 'Aset', 'date' => '12 Dec 2025', 'amount' => '- Rp 1.200.000', 'type' => 'out'],
                                    ];

                                    foreach ($transactions as $tx) : ?>
                                        <tr class="hover:bg-gray-50/50 transition cursor-default">
                                            <td class="px-8 py-5">
                                                <p class="text-sm font-bold text-brandPrimary"><?php echo $tx['desc']; ?></p>
                                            </td>
                                            <td class="px-8 py-5 text-xs font-semibold text-gray-500"><?php echo $tx['cat']; ?></td>
                                            <td class="px-8 py-5 text-xs text-gray-400"><?php echo $tx['date']; ?></td>
                                            <td class="px-8 py-5">
                                                <span class="text-sm font-bold <?php echo $tx['type'] == 'in' ? 'text-brandTeal' : 'text-brandAccent'; ?>">
                                                    <?php echo $tx['amount']; ?>
                                                </span>
                                            </td>
                                            <td class="px-8 py-5">
                                                <div class="flex justify-center">
                                                    <span class="px-3 py-1 rounded-full text-[9px] font-bold uppercase tracking-widest bg-green-50 text-green-500 border border-green-100">Success</span>
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

    <div id="transactionModal" class="fixed inset-0 bg-brandPrimary/60 backdrop-blur-sm z-[150] hidden flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-lg rounded-[32px] shadow-2xl overflow-hidden transform transition-all">
            <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
                <div>
                    <h3 class="text-xl font-bold text-brandPrimary">Add New Transaction</h3>
                    <p class="text-xs text-gray-400 font-medium">Catat arus kas masuk atau keluar studio.</p>
                </div>
                <button onclick="toggleModal()" class="p-2 hover:bg-white rounded-xl transition text-gray-400 hover:text-brandAccent">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form action="process_transaction.php" method="POST" class="p-8 space-y-5">
                <div class="flex gap-4 p-1 bg-gray-100 rounded-2xl">
                    <label class="flex-1 cursor-pointer">
                        <input type="radio" name="type" value="in" class="hidden peer" checked>
                        <div class="text-center py-2 rounded-xl text-xs font-bold transition peer-checked:bg-white peer-checked:text-brandTeal peer-checked:shadow-sm text-gray-400">
                            Income (Masuk)
                        </div>
                    </label>
                    <label class="flex-1 cursor-pointer">
                        <input type="radio" name="type" value="out" class="hidden peer">
                        <div class="text-center py-2 rounded-xl text-xs font-bold transition peer-checked:bg-white peer-checked:text-brandAccent peer-checked:shadow-sm text-gray-400">
                            Expense (Keluar)
                        </div>
                    </label>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Nominal (Rp)</label>
                    <input type="number" name="amount" required placeholder="Contoh: 5000000"
                        class="w-full px-5 py-3 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-brandPrimary outline-none transition font-bold text-lg text-brandPrimary">
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Description</label>
                    <input type="text" name="description" required placeholder="Pembayaran Project / Sewa Server"
                        class="w-full px-5 py-3 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-brandPrimary outline-none transition">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Category</label>
                        <select name="category" class="w-full px-5 py-3 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white outline-none transition appearance-none text-sm">
                            <option value="Project">Project Payment</option>
                            <option value="Operational">Operational</option>
                            <option value="Marketing">Marketing</option>
                            <option value="Salary">Payroll</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Date</label>
                        <input type="date" name="date" value="<?php echo date('Y-m-d'); ?>"
                            class="w-full px-5 py-3 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white outline-none transition text-sm">
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full py-4 bg-brandPrimary text-white rounded-2xl font-bold shadow-xl shadow-brandPrimary/20 hover:scale-[1.02] active:scale-95 transition-all">
                        Simpan Transaksi
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>