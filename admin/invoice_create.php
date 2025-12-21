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
                    <a href="invoice_maker.php" class="inline-flex items-center gap-2 text-sm font-bold text-gray-400 hover:text-brandPrimary transition mb-6">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Manager
                    </a>

                    <form action="process_invoice.php" method="POST" id="invoiceForm">
                        <div class="bg-white rounded-[32px] shadow-sm border border-gray-100 overflow-hidden">
                            <div class="p-8 border-b border-gray-50 bg-gray-50/30 grid grid-cols-1 md:grid-cols-3 gap-8">
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Select Client</label>
                                    <select name="client_id" class="w-full px-4 py-3 rounded-xl border border-gray-200 outline-none focus:border-brandPrimary transition">
                                        <option value="">Choose Client...</option>
                                        <option value="1">PT. Maju Bersama</option>
                                        <option value="2">StartUp Digital</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Invoice Number</label>
                                    <input type="text" name="invoice_no" value="INV/<?php echo date('Ymd'); ?>/001" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-100 outline-none" readonly>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Due Date</label>
                                    <input type="date" name="due_date" class="w-full px-4 py-3 rounded-xl border border-gray-200 outline-none focus:border-brandPrimary transition">
                                </div>
                            </div>

                            <div class="p-8">
                                <table class="w-full text-left" id="itemsTable">
                                    <thead>
                                        <tr class="border-b border-gray-100">
                                            <th class="py-4 text-[10px] font-bold uppercase text-gray-400">Description</th>
                                            <th class="py-4 px-4 text-[10px] font-bold uppercase text-gray-400 w-32">Qty</th>
                                            <th class="py-4 px-4 text-[10px] font-bold uppercase text-gray-400 w-48">Price (Rp)</th>
                                            <th class="py-4 text-[10px] font-bold uppercase text-gray-400 w-48 text-right">Total</th>
                                            <th class="py-4 w-12"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="itemRows">
                                        <tr class="item-row border-b border-gray-50">
                                            <td class="py-4">
                                                <input type="text" name="desc[]" placeholder="Item/Service name" class="w-full bg-transparent outline-none text-sm font-medium">
                                            </td>
                                            <td class="py-4 px-4">
                                                <input type="number" name="qty[]" value="1" min="1" class="qty-input w-full bg-transparent outline-none text-sm font-bold text-brandPrimary">
                                            </td>
                                            <td class="py-4 px-4">
                                                <input type="number" name="price[]" value="0" class="price-input w-full bg-transparent outline-none text-sm font-bold text-brandPrimary">
                                            </td>
                                            <td class="py-4 text-sm font-bold text-right text-brandPrimary">
                                                <span class="row-total">0</span>
                                            </td>
                                            <td class="py-4 text-right">
                                                <button type="button" class="remove-row text-gray-300 hover:text-brandAccent">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <button type="button" id="addItem" class="mt-6 flex items-center gap-2 text-xs font-bold text-brandTeal hover:underline">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Add Line Item
                                </button>
                            </div>

                            <div class="p-8 bg-gray-50/50 flex flex-col md:flex-row justify-between items-start gap-8">
                                <div class="w-full md:w-1/2">
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Notes / Payment Instructions</label>
                                    <textarea name="notes" rows="4" class="w-full p-4 rounded-2xl border border-gray-200 bg-white outline-none text-sm" placeholder="Bank BCA - 123456789 a/n DangDang Studio"></textarea>
                                </div>
                                <div class="w-full md:w-1/3 space-y-3">
                                    <div class="flex justify-between items-center text-sm font-medium text-gray-500">
                                        <span>Subtotal</span>
                                        <span id="subtotal">Rp 0</span>
                                    </div>
                                    <div class="flex justify-between items-center text-sm font-medium text-gray-500">
                                        <span>Tax (11%)</span>
                                        <span id="tax">Rp 0</span>
                                    </div>
                                    <div class="flex justify-between items-center pt-3 border-t border-gray-200">
                                        <span class="text-lg font-bold text-brandPrimary">Grand Total</span>
                                        <span id="grandTotal" class="text-lg font-bold text-brandPrimary">Rp 0</span>
                                    </div>
                                    <button type="submit" class="w-full mt-4 py-4 bg-brandPrimary text-white rounded-2xl font-bold shadow-xl shadow-brandPrimary/20 hover:scale-[1.02] transition-all">
                                        Finalize & Generate Invoice
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <?php include_once '_footer.php'; ?>
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const itemRows = document.getElementById('itemRows');
            const addItemBtn = document.getElementById('addItem');

            // Hitung total saat input berubah
            itemRows.addEventListener('input', calculateInvoice);

            // Tambah baris baru
            addItemBtn.addEventListener('click', function() {
                const newRow = `
                <tr class="item-row border-b border-gray-50">
                    <td class="py-4">
                        <input type="text" name="desc[]" placeholder="Item/Service name" class="w-full bg-transparent outline-none text-sm font-medium">
                    </td>
                    <td class="py-4 px-4">
                        <input type="number" name="qty[]" value="1" min="1" class="qty-input w-full bg-transparent outline-none text-sm font-bold text-brandPrimary">
                    </td>
                    <td class="py-4 px-4">
                        <input type="number" name="price[]" value="0" class="price-input w-full bg-transparent outline-none text-sm font-bold text-brandPrimary">
                    </td>
                    <td class="py-4 text-sm font-bold text-right text-brandPrimary">
                        <span class="row-total">0</span>
                    </td>
                    <td class="py-4 text-right">
                        <button type="button" class="remove-row text-gray-300 hover:text-brandAccent">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </td>
                </tr>`;
                itemRows.insertAdjacentHTML('beforeend', newRow);
            });

            // Hapus baris
            itemRows.addEventListener('click', function(e) {
                if (e.target.closest('.remove-row')) {
                    const row = e.target.closest('.item-row');
                    if (document.querySelectorAll('.item-row').length > 1) {
                        row.remove();
                        calculateInvoice();
                    }
                }
            });

            function calculateInvoice() {
                let subtotal = 0;
                document.querySelectorAll('.item-row').forEach(row => {
                    const qty = row.querySelector('.qty-input').value || 0;
                    const price = row.querySelector('.price-input').value || 0;
                    const total = qty * price;
                    row.querySelector('.row-total').textContent = total.toLocaleString('id-ID');
                    subtotal += total;
                });

                const tax = subtotal * 0.11;
                const grandTotal = subtotal + tax;

                document.getElementById('subtotal').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
                document.getElementById('tax').textContent = 'Rp ' + tax.toLocaleString('id-ID');
                document.getElementById('grandTotal').textContent = 'Rp ' + grandTotal.toLocaleString('id-ID');
            }
        });
    </script>
</body>

</html>