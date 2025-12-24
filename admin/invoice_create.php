<?php
require_once './process/config.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Ambil data client
$query_clients = "SELECT id, company_name FROM clients ORDER BY company_name ASC";
$result_clients = mysqli_query($conn, $query_clients);

// Generate Invoice Number
$date_code = date('Ymd');
$query_last_id = "SELECT id FROM invoices ORDER BY id DESC LIMIT 1";
$res_last_id = mysqli_query($conn, $query_last_id);
$last_id_data = mysqli_fetch_assoc($res_last_id);
$next_id = ($last_id_data['id'] ?? 0) + 1;
$invoice_no = "INV/" . $date_code . "/" . str_pad($next_id, 3, '0', STR_PAD_LEFT);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Invoice - DangDang Studio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="assets/tailwind-config.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        input[type=number]::-webkit-inner-spin-button, input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
    </style>
</head>
<body class="bg-gray-50 text-slate-800">

    <div class="flex flex-col h-screen overflow-hidden">
        <?php include_once '_header.php'; ?>
        <div class="flex flex-1 overflow-hidden">
            <?php include_once '_aside.php'; ?>

            <main class="flex-1 flex flex-col min-w-0 overflow-y-auto">
                <?php include_once '_topbar.php'; ?>

                <div class="p-6 md:p-10 flex-1">
                    <div class="flex items-center justify-between mb-6">
                        <a href="invoice_maker.php" class="inline-flex items-center gap-2 text-sm font-bold text-gray-400 hover:text-brandPrimary transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            Back to Manager
                        </a>
                    </div>

                    <form action="process/process_invoice.php" method="POST" id="invoiceForm">
                        <div class="bg-white rounded-[32px] shadow-sm border border-gray-100 overflow-hidden">
                            
                            <div class="p-8 border-b border-gray-100 bg-gray-50/50 grid grid-cols-1 md:grid-cols-3 gap-8">
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Select Client</label>
                                    <select name="client_id" required class="w-full px-4 py-3 rounded-xl border bg-white border-gray-200 outline-none focus:border-brandPrimary transition font-medium">
                                        <option value="">Choose Client...</option>
                                        <?php while ($row = mysqli_fetch_assoc($result_clients)): ?>
                                            <option value="<?= $row['id']; ?>"><?= htmlspecialchars($row['company_name']); ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Invoice Number</label>
                                    <input type="text" name="invoice_no" value="<?= $invoice_no; ?>" class="w-full px-4 py-3 rounded-xl border border-transparent bg-gray-200/50 text-gray-500 font-bold outline-none cursor-not-allowed" readonly>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Due Date</label>
                                    <input type="date" name="due_date" required value="<?= date('Y-m-d', strtotime('+7 days')); ?>" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white outline-none focus:border-brandPrimary transition">
                                </div>
                            </div>

                            <div class="p-8">
                                <table class="w-full text-left" id="itemsTable">
                                    <thead>
                                        <tr class="border-b border-gray-100">
                                            <th class="py-4 text-[10px] font-bold uppercase text-gray-400">Description</th>
                                            <th class="py-4 px-4 text-[10px] font-bold uppercase text-gray-400 w-32">Qty</th>
                                            <th class="py-4 px-4 text-[10px] font-bold uppercase text-gray-400 w-48 text-right">Price (Rp)</th>
                                            <th class="py-4 text-[10px] font-bold uppercase text-gray-400 w-48 text-right">Total</th>
                                            <th class="py-4 w-12"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="itemRows">
                                        <tr class="item-row border-b border-gray-50 group hover:bg-gray-50/30 transition-all">
                                            <td class="py-4">
                                                <input type="text" name="desc[]" required placeholder="Service Description..." class="w-full bg-transparent border-b border-transparent focus:border-brandPrimary outline-none text-sm font-medium py-1">
                                            </td>
                                            <td class="py-4 px-4">
                                                <input type="number" name="qty[]" value="1" min="1" class="qty-input w-full bg-blue-50/30 border border-blue-100 rounded-lg px-2 py-1 outline-none text-sm font-bold text-brandPrimary text-center focus:bg-blue-100/50">
                                            </td>
                                            <td class="py-4 px-4">
                                                <input type="number" name="price[]" value="0" min="0" class="price-input w-full bg-blue-50/30 border border-blue-100 rounded-lg px-2 py-1 outline-none text-sm font-bold text-brandPrimary text-right focus:bg-blue-100/50">
                                            </td>
                                            <td class="py-4 text-sm font-bold text-right text-brandPrimary">
                                                <span class="row-total">0</span>
                                            </td>
                                            <td class="py-4 text-right">
                                                <button type="button" class="remove-row text-gray-300 hover:text-red-500 transition p-2">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <button type="button" id="addItem" class="mt-6 inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-brandTeal/10 text-brandTeal text-xs font-bold hover:bg-brandTeal hover:text-white transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    Add Line Item
                                </button>
                            </div>

                            <div class="p-8 bg-gray-50/50 flex flex-col md:flex-row justify-between items-start gap-8 border-t border-gray-100">
                                <div class="w-full md:w-1/2">
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Notes / Payment Instructions</label>
                                    <textarea name="notes" rows="4" class="w-full p-4 rounded-2xl border border-gray-200 bg-white outline-none text-sm resize-none focus:border-brandPrimary transition shadow-sm" placeholder="Bank BCA - 123456789 a/n DangDang Studio"></textarea>
                                </div>
                                
                                <div class="w-full md:w-1/3 space-y-4">
                                    <div id="taxBox" class="p-5 bg-white rounded-[24px] border border-gray-200 shadow-sm transition-all duration-300">
                                        <div class="flex justify-between items-center mb-3">
                                            <div class="flex items-center gap-2">
                                                <label class="relative inline-flex items-center cursor-pointer">
                                                    <input type="checkbox" id="taxEnableToggle" class="sr-only peer" checked>
                                                    <div class="w-9 h-5 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-brandPrimary"></div>
                                                </label>
                                                <span class="text-[10px] font-black uppercase text-gray-400">Pajak (PPN)</span>
                                            </div>
                                            <div class="flex items-center gap-1 bg-gray-50 px-2 py-1 rounded-lg border border-gray-100" id="taxRateWrapper">
                                                <input type="number" name="tax_percentage" id="taxRate" value="11" step="0.1" class="w-10 text-right bg-transparent outline-none text-sm font-bold text-brandPrimary">
                                                <span class="text-xs font-bold text-brandPrimary">%</span>
                                            </div>
                                        </div>
                                        <div class="flex justify-between items-center pt-2 border-t border-dashed border-gray-100">
                                            <span class="text-xs font-medium text-gray-400">Nilai Pajak</span>
                                            <span id="taxAmountDisplay" class="text-sm font-bold text-brandPrimary">Rp 0</span>
                                            <input type="hidden" name="tax_amount" id="inputTaxAmount" value="0">
                                        </div>
                                    </div>

                                    <div class="flex justify-between items-center py-4 px-4 bg-brandPrimary/5 rounded-2xl border border-brandPrimary/10">
                                        <span class="text-sm font-bold text-brandPrimary">Grand Total</span>
                                        <span id="grandTotalDisplay" class="text-2xl font-black text-brandPrimary">Rp 0</span>
                                        <input type="hidden" name="total_amount" id="inputTotalAmount" value="0">
                                    </div>

                                    <button type="submit" class="w-full py-4 bg-brandPrimary text-white rounded-2xl font-bold shadow-xl shadow-brandPrimary/30 hover:scale-[1.02] active:scale-95 transition-all uppercase text-xs tracking-widest">
                                        Generate Invoice
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const itemRows = document.getElementById('itemRows');
            const taxRateInput = document.getElementById('taxRate');
            const taxEnableToggle = document.getElementById('taxEnableToggle');

            function calculateInvoice() {
                let subtotal = 0;
                document.querySelectorAll('.item-row').forEach(row => {
                    const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
                    const price = parseFloat(row.querySelector('.price-input').value) || 0;
                    const total = qty * price;
                    row.querySelector('.row-total').textContent = total.toLocaleString('id-ID');
                    subtotal += total;
                });

                let taxPercent = 0;
                if (taxEnableToggle.checked) {
                    taxPercent = parseFloat(taxRateInput.value) || 0;
                    document.getElementById('taxRateWrapper').style.opacity = "1";
                    taxRateInput.readOnly = false;
                } else {
                    taxPercent = 0;
                    document.getElementById('taxRateWrapper').style.opacity = "0.3";
                    taxRateInput.readOnly = true;
                }
                
                const taxAmount = subtotal * (taxPercent / 100);
                const grandTotal = subtotal + taxAmount;

                document.getElementById('taxAmountDisplay').textContent = 'Rp ' + taxAmount.toLocaleString('id-ID');
                document.getElementById('grandTotalDisplay').textContent = 'Rp ' + grandTotal.toLocaleString('id-ID');

                document.getElementById('inputTaxAmount').value = taxAmount;
                document.getElementById('inputTotalAmount').value = grandTotal;
                
                // Jika pajak dimatikan, set input hidden tax_percentage ke 0 agar DB konsisten
                if (!taxEnableToggle.checked) {
                    // Kita bisa memanipulasi value input taxRate sementara atau biarkan 0 saat di backend
                }
            }

            itemRows.addEventListener('input', calculateInvoice);
            taxRateInput.addEventListener('input', calculateInvoice);
            taxEnableToggle.addEventListener('change', calculateInvoice);

            document.getElementById('addItem').addEventListener('click', function() {
                const newRow = `
                <tr class="item-row border-b border-gray-50 group hover:bg-gray-50/30 transition-all">
                    <td class="py-4"><input type="text" name="desc[]" required class="w-full bg-transparent border-b border-transparent focus:border-brandPrimary outline-none text-sm font-medium py-1"></td>
                    <td class="py-4 px-4"><input type="number" name="qty[]" value="1" class="qty-input w-full bg-blue-50/30 border border-blue-100 rounded-lg px-2 py-1 outline-none text-sm font-bold text-brandPrimary text-center"></td>
                    <td class="py-4 px-4"><input type="number" name="price[]" value="0" class="price-input w-full bg-blue-50/30 border border-blue-100 rounded-lg px-2 py-1 outline-none text-sm font-bold text-brandPrimary text-right"></td>
                    <td class="py-4 text-sm font-bold text-right text-brandPrimary"><span class="row-total">0</span></td>
                    <td class="py-4 text-right">
                        <button type="button" class="remove-row text-gray-300 hover:text-red-500 transition p-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </td>
                </tr>`;
                itemRows.insertAdjacentHTML('beforeend', newRow);
            });

            itemRows.addEventListener('click', e => {
                if (e.target.closest('.remove-row') && document.querySelectorAll('.item-row').length > 1) {
                    e.target.closest('.item-row').remove();
                    calculateInvoice();
                }
            });
        });
    </script>
</body>
</html>