<?php
require_once './process/config.php';
session_start();

if (!isset($_SESSION['admin_id']) || !isset($_GET['id'])) {
    header("Location: invoice_maker.php");
    exit();
}

$id = mysqli_real_escape_string($conn, $_GET['id']);

// 1. Ambil Header Invoice (Termasuk kolom tax_percentage yang baru)
$query_inv = "SELECT * FROM invoices WHERE id = '$id'";
$res_inv = mysqli_query($conn, $query_inv);
$inv_data = mysqli_fetch_assoc($res_inv);

if (!$inv_data) { die("Invoice tidak ditemukan."); }

// 2. Ambil Items Detail
$query_items = "SELECT * FROM invoice_items WHERE invoice_id = '$id'";
$res_items = mysqli_query($conn, $query_items);

// 3. Ambil Data Client untuk Dropdown
$query_clients = "SELECT id, company_name FROM clients ORDER BY company_name ASC";
$result_clients = mysqli_query($conn, $query_clients);

// Logika status pajak: aktif jika tax_amount > 0
$is_tax_active = ($inv_data['tax_amount'] > 0);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Invoice - DangDang Studio</title>
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
                    <a href="invoice_maker.php" class="inline-flex items-center gap-2 text-sm font-bold text-gray-400 hover:text-brandPrimary transition mb-6">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Back to Manager
                    </a>

                    <form action="process/process_edit_invoice.php" method="POST" id="editInvoiceForm">
                        <input type="hidden" name="id" value="<?= $id ?>">
                        
                        <div class="bg-white rounded-[32px] shadow-sm border border-gray-100 overflow-hidden">
                            <div class="p-8 border-b border-gray-100 bg-gray-50/50 grid grid-cols-1 md:grid-cols-3 gap-8">
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Client</label>
                                    <select name="client_id" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-brandPrimary outline-none transition">
                                        <?php while ($c = mysqli_fetch_assoc($result_clients)): ?>
                                            <option value="<?= $c['id'] ?>" <?= ($c['id'] == $inv_data['client_id']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($c['company_name']) ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Invoice No</label>
                                    <input type="text" value="<?= $inv_data['invoice_no'] ?>" class="w-full px-4 py-3 rounded-xl bg-gray-200/50 text-gray-500 font-bold outline-none cursor-not-allowed" readonly>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Due Date</label>
                                    <input type="date" name="due_date" required value="<?= $inv_data['due_date'] ?>" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-brandPrimary outline-none">
                                </div>
                            </div>

                            <div class="p-8">
                                <table class="w-full text-left">
                                    <thead>
                                        <tr class="border-b border-gray-100">
                                            <th class="py-4 text-[10px] font-bold uppercase text-gray-400">Description</th>
                                            <th class="py-4 px-4 text-[10px] font-bold uppercase text-gray-400 w-32">Qty</th>
                                            <th class="py-4 px-4 text-[10px] font-bold uppercase text-gray-400 w-48 text-right">Price (Rp)</th>
                                            <th class="py-4 text-[10px] font-bold uppercase text-gray-400 w-48 text-right">Total</th>
                                            <th class="py-4 w-12 text-right"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="itemRows">
                                        <?php while ($item = mysqli_fetch_assoc($res_items)): ?>
                                        <tr class="item-row border-b border-gray-50 hover:bg-gray-50/30 transition-all">
                                            <td class="py-4">
                                                <input type="text" name="desc[]" value="<?= htmlspecialchars($item['description']) ?>" required class="w-full bg-transparent border-b border-transparent focus:border-brandPrimary outline-none text-sm font-medium">
                                            </td>
                                            <td class="py-4 px-4">
                                                <input type="number" name="qty[]" value="<?= $item['qty'] ?>" class="qty-input w-full bg-blue-50/30 border border-blue-100 rounded-lg px-2 py-1 text-center font-bold text-brandPrimary outline-none">
                                            </td>
                                            <td class="py-4 px-4">
                                                <input type="number" name="price[]" value="<?= (int)$item['price'] ?>" class="price-input w-full bg-blue-50/30 border border-blue-100 rounded-lg px-2 py-1 text-right font-bold text-brandPrimary outline-none">
                                            </td>
                                            <td class="py-4 text-sm font-bold text-right text-brandPrimary">
                                                <span class="row-total"><?= number_format($item['row_total'], 0, ',', '.') ?></span>
                                            </td>
                                            <td class="py-4 text-right">
                                                <button type="button" class="remove-row text-gray-300 hover:text-red-500 transition p-2">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </td>
                                        </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                                <button type="button" id="addItem" class="mt-6 inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-brandTeal/10 text-brandTeal text-xs font-bold hover:bg-brandTeal hover:text-white transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    Add Line Item
                                </button>
                            </div>

                            <div class="p-8 bg-gray-50/50 flex flex-col md:flex-row justify-between items-start gap-8 border-t border-gray-100">
                                <div class="w-full md:w-1/2">
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Notes</label>
                                    <textarea name="notes" rows="4" class="w-full p-4 rounded-2xl border border-gray-200 bg-white outline-none text-sm resize-none focus:border-brandPrimary transition shadow-sm"><?= htmlspecialchars($inv_data['notes']) ?></textarea>
                                </div>
                                
                                <div class="w-full md:w-1/3 space-y-4">
                                    <div id="taxBox" class="p-5 bg-white rounded-[24px] border border-gray-200 shadow-sm transition-all duration-300">
                                        <div class="flex justify-between items-center mb-3">
                                            <div class="flex items-center gap-2">
                                                <label class="relative inline-flex items-center cursor-pointer">
                                                    <input type="checkbox" id="taxEnableToggle" class="sr-only peer" <?= $is_tax_active ? 'checked' : '' ?>>
                                                    <div class="w-9 h-5 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-brandPrimary"></div>
                                                </label>
                                                <span class="text-[10px] font-black uppercase text-gray-400">Pajak (PPN)</span>
                                            </div>
                                            <div class="flex items-center gap-1 bg-gray-50 px-2 py-1 rounded-lg border border-gray-100" id="taxRateWrapper">
                                                <input type="number" name="tax_percentage" id="taxRate" value="<?= ($inv_data['tax_percentage'] > 0) ? $inv_data['tax_percentage'] : '11' ?>" step="0.1" class="w-10 text-right bg-transparent outline-none text-sm font-bold text-brandPrimary">
                                                <span class="text-xs font-bold text-brandPrimary">%</span>
                                            </div>
                                        </div>
                                        <div class="flex justify-between items-center pt-2 border-t border-dashed border-gray-100">
                                            <span class="text-xs font-medium text-gray-400">Nilai Pajak</span>
                                            <span id="taxAmountDisplay" class="text-sm font-bold text-brandPrimary">Rp <?= number_format($inv_data['tax_amount'], 0, ',', '.') ?></span>
                                            <input type="hidden" name="tax_amount" id="inputTaxAmount" value="<?= $inv_data['tax_amount'] ?>">
                                        </div>
                                    </div>

                                    <div class="flex justify-between items-center py-4 px-4 bg-brandPrimary/5 rounded-2xl border border-brandPrimary/10">
                                        <span class="text-sm font-bold text-brandPrimary">Grand Total</span>
                                        <span id="grandTotalDisplay" class="text-2xl font-black text-brandPrimary">Rp <?= number_format($inv_data['total_amount'], 0, ',', '.') ?></span>
                                        <input type="hidden" name="total_amount" id="inputTotalAmount" value="<?= $inv_data['total_amount'] ?>">
                                    </div>

                                    <button type="submit" class="w-full py-4 bg-brandPrimary text-white rounded-2xl font-bold shadow-xl shadow-brandPrimary/20 hover:scale-[1.02] active:scale-95 transition-all uppercase text-xs tracking-widest">
                                        Update Invoice
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
            }

            // Jalankan kalkulasi saat input berubah
            itemRows.addEventListener('input', calculateInvoice);
            taxRateInput.addEventListener('input', calculateInvoice);
            taxEnableToggle.addEventListener('change', calculateInvoice);

            // Add Row
            document.getElementById('addItem').addEventListener('click', function() {
                const newRow = `
                <tr class="item-row border-b border-gray-50 hover:bg-gray-50/30 transition-all">
                    <td class="py-4"><input type="text" name="desc[]" required class="w-full bg-transparent border-b border-transparent focus:border-brandPrimary outline-none text-sm font-medium"></td>
                    <td class="py-4 px-4"><input type="number" name="qty[]" value="1" class="qty-input w-full bg-blue-50/30 border border-blue-100 rounded-lg px-2 py-1 text-center font-bold text-brandPrimary outline-none"></td>
                    <td class="py-4 px-4"><input type="number" name="price[]" value="0" class="price-input w-full bg-blue-50/30 border border-blue-100 rounded-lg px-2 py-1 text-right font-bold text-brandPrimary outline-none"></td>
                    <td class="py-4 text-sm font-bold text-right text-brandPrimary"><span class="row-total">0</span></td>
                    <td class="py-4 text-right">
                        <button type="button" class="remove-row text-gray-300 hover:text-red-500 transition p-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </td>
                </tr>`;
                itemRows.insertAdjacentHTML('beforeend', newRow);
            });

            // Remove Row
            itemRows.addEventListener('click', e => {
                if (e.target.closest('.remove-row') && document.querySelectorAll('.item-row').length > 1) {
                    e.target.closest('.item-row').remove();
                    calculateInvoice();
                }
            });

            // Jalankan kalkulasi pertama kali saat halaman dimuat
            calculateInvoice();
        });
    </script>
</body>
</html>