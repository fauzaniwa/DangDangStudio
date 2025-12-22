<?php
require_once 'process/config.php';
$id = $_GET['id'];

// Ambil data invoice & client
$query = "SELECT i.*, c.* FROM invoices i JOIN clients c ON i.client_id = c.id WHERE i.id = '$id'";
$inv = mysqli_fetch_assoc(mysqli_query($conn, $query));

// Ambil item
$items = mysqli_query($conn, "SELECT * FROM invoice_items WHERE invoice_id = '$id'");
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Invoice #<?php echo $inv['invoice_no']; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            margin: 0;
            padding: 0;
        }

        /* Pengaturan Khusus Layar Browser */
        @media screen {
            .page-a4 {
                width: 210mm;
                min-height: 297mm;
                padding: 20mm;
                margin: 20px auto;
                background: white;
                box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            }
        }

        /* Pengaturan Khusus Printer (Mode Cetak) */
        @media print {
            @page {
                size: A4;
                /* Margin fisik kertas diatur di sini untuk semua halaman */
                margin: 20mm;
            }

            body {
                background: white !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .no-print {
                display: none !important;
            }

            .page-a4 {
                box-shadow: none !important;
                margin: 0 !important;
                width: 100% !important;
                padding: 0 !important;
                /* Padding 0 karena margin sudah diatur di @page */
                display: block !important;
            }

            /* Menghindari pemotongan baris tabel di tengah-tengah */
            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            thead {
                display: table-header-group;
                /* Opsional: Header tabel muncul lagi di hal 2 */
            }
        }

        .text-brand-teal {
            color: #14B8A6;
        }

        .bg-brand-teal {
            background-color: #14B8A6;
        }
    </style>


</head>

<body class="bg-slate-50">

    <div class="no-print sticky top-0 z-50 bg-slate-900/90 backdrop-blur-md border-b border-slate-700 shadow-2xl">
        <div class="max-w-[210mm] mx-auto flex items-center justify-between px-6 py-4">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-brand-teal/20 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-brand-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <span class="font-bold text-slate-200 uppercase tracking-wider text-sm">
                    Invoice #<?php echo str_pad($inv['invoice_no'], 3, '0', STR_PAD_LEFT); ?>
                </span>
            </div>

            <div class="flex items-center gap-3">
                <button onclick="window.close()" class="px-5 py-2.5 text-sm bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-xl font-bold transition-all border border-slate-600">
                    Tutup
                </button>
                <button onclick="window.print()" class="px-6 py-2.5 text-sm bg-brand-teal hover:bg-teal-600 text-white rounded-xl font-bold shadow-lg shadow-teal-500/20 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    Cetak Invoice
                </button>
            </div>
        </div>
    </div>

    <div class="page-a4 print-area">
        <div class="content-wrapper">
            <div class="flex justify-between items-center mb-10">
                <h1 class="text-6xl font-extrabold text-brand-teal tracking-tighter">Invoice</h1>
                <div class="flex flex-col items-end">
                    <?php
                    $logo_path = '../assets/img/logo-dangdang.png';
                    if (file_exists($logo_path)): ?>
                        <img src="<?php echo $logo_path; ?>" alt="Logo" class="h-16 object-contain mb-2">
                    <?php else: ?>
                        <div class="h-16 flex items-center">
                            <span class="text-3xl font-black text-slate-800">DANG<span class="text-brand-teal">DANG</span></span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <hr class="border-gray-100 mb-8">

            <div class="flex justify-between mb-12">
                <div>
                    <p class="text-gray-400 font-bold text-xs uppercase tracking-widest mb-1">Invoice Number</p>
                    <h2 class="text-2xl font-bold text-slate-800"><?php echo str_pad($inv['invoice_no'], 3, '0', STR_PAD_LEFT); ?></h2>
                </div>
                <div class="text-right">
                    <p class="text-gray-400 font-bold text-xs uppercase tracking-widest mb-1">Invoice Date</p>
                    <h2 class="text-2xl font-bold text-slate-800"><?php echo date('Y-m-d', strtotime($inv['invoice_date'])); ?></h2>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-10 mb-12">
                <div>
                    <h3 class="text-brand-teal font-extrabold text-sm uppercase tracking-wider mb-4">Billed To:</h3>
                    <p class="font-bold text-slate-800 text-lg"><?php echo $inv['company_name']; ?></p>
                    <p class="text-slate-600 font-semibold mt-1"><?php echo $inv['pic_name']; ?></p>
                    <p class="text-slate-500 text-sm"><?php echo $inv['pic_phone'] ?? $inv['email']; ?></p>
                </div>
                <div class="text-right">
                    <h3 class="text-brand-teal font-extrabold text-sm uppercase tracking-wider mb-4">Payment Info:</h3>
                    <p class="font-bold text-slate-800 text-lg">Muhammad Fauzan Aztera</p>
                    <p class="text-slate-500 text-sm">Bank Mandiri</p>
                    <p class="text-slate-500 text-sm tracking-widest">1080027486480</p>
                </div>
            </div>

            <table class="w-full mb-8 border-collapse">
                <thead>
                    <tr class="bg-brand-teal text-white">
                        <th class="py-4 px-6 text-left text-xs font-bold uppercase tracking-widest rounded-tl-xl">Description</th>
                        <th class="py-4 px-6 text-center text-xs font-bold uppercase tracking-widest">Unit Cost</th>
                        <th class="py-4 px-6 text-center text-xs font-bold uppercase tracking-widest">Quantity</th>
                        <th class="py-4 px-6 text-right text-xs font-bold uppercase tracking-widest rounded-tr-xl">Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 border-x border-b border-gray-100">
                    <?php while ($item = mysqli_fetch_assoc($items)): ?>
                        <tr class="text-sm">
                            <td class="py-6 px-6 font-medium text-slate-700 w-1/2"><?php echo $item['description']; ?></td>
                            <td class="py-6 px-6 text-center text-slate-600">Rp <?php echo number_format($item['price'], 0, ',', '.'); ?></td>
                            <td class="py-6 px-6 text-center text-slate-600"><?php echo $item['qty']; ?></td>
                            <td class="py-6 px-6 text-right font-bold text-slate-800">Rp <?php echo number_format($item['row_total'], 0, ',', '.'); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <div class="flex justify-end mb-16">
                <div class="flex gap-10 items-center">
                    <span class="text-2xl font-extrabold text-slate-800">Total</span>
                    <span class="text-2xl font-extrabold text-slate-900">Rp <?php echo number_format($inv['total_amount'], 0, ',', '.'); ?></span>
                </div>
            </div>

            <div class="mb-10">
                <h3 class="text-brand-teal font-extrabold text-sm uppercase tracking-wider mb-3">Term and Conditions</h3>
                <p class="text-[11px] text-slate-400 leading-relaxed max-w-2xl">
                    We want to ensure that you fully understand how our services operate. Please take a moment to carefully read our terms and conditions.
                </p>
            </div>
        </div>

        <div class="mt-10">
            <hr class="border-gray-100 mb-8">
            <div class="text-center mb-10">
                <h2 class="text-2xl font-extrabold text-brand-teal">Thank You For Your Business!</h2>
            </div>

            <div class="flex justify-between items-end text-[11px] text-slate-500">
                <div>
                    <p class="font-extrabold text-slate-800 text-sm mb-1">DangDang Studio</p>
                    <p>Jalan Gegerkalong Tengah No. 16 B Gegerkalong, Sukasari</p>
                    <p>40152</p>
                </div>
                <div class="text-right">
                    <p>studiodangdang.com</p>
                    <p>support@dangdangstudio.com</p>
                </div>
            </div>
        </div>
    </div>

</body>

</html>