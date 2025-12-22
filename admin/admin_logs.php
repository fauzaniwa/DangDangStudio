<?php
require_once './process/config.php';
session_start();

// Proteksi Halaman: Hanya Super Admin yang boleh melihat logs
if (!isset($_SESSION['is_logged_in']) || $_SESSION['admin_role'] !== 'Super Admin') {
    header("Location: dashboard.php");
    exit();
}

// Ambil data logs dari database dengan JOIN ke tabel admins
$query = "SELECT al.*, a.fullname, a.role 
          FROM admin_logs al 
          LEFT JOIN admins a ON al.admin_id = a.id 
          ORDER BY al.created_at DESC 
          LIMIT 50"; // Kita batasi 50 log terbaru saja
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Logs - DangDang Studio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="assets/tailwind-config.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
    <style>
        @media print {

            /* 1. Sembunyikan semua elemen kecuali area konten utama */
            body * {
                visibility: hidden;
            }

            /* 2. Tampilkan area tabel dan isinya saja */
            #printable-area,
            #printable-area * {
                visibility: visible;
            }

            /* 3. Atur posisi tabel agar mulai dari pojok kiri atas kertas */
            #printable-area {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }

            /* 4. Optimasi tampilan tabel untuk kertas A4 */
            table {
                width: 100%;
                border: 1px solid #eee;
                table-layout: auto;
            }

            th,
            td {
                font-size: 10pt !important;
                /* Ukuran font ideal untuk print */
                padding: 10px !important;
            }

            /* Sembunyikan tombol navigasi/pagination saat print */
            .no-print {
                display: none !important;
            }

            /* Pengaturan Kertas A4 */
            @page {
                size: A4;
                margin: 1cm;
            }
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
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
                        <div>
                            <?php if (isset($_GET['status']) && $_GET['status'] == 'cleared'): ?>
                                <div class="mb-6 p-4 bg-brandGold/20 border border-brandGold/50 rounded-2xl text-brandGold text-xs font-bold flex items-center gap-3 animate-fade-in">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Log sistem berhasil dibersihkan!
                                </div>
                            <?php endif; ?>
                            <h1 class="text-3xl font-extrabold text-brandPrimary tracking-tight">System Logs</h1>
                            <p class="text-sm text-gray-500 font-medium">Rekaman aktivitas terbaru dari sistem manajemen studio.</p>
                        </div>
                        <div class="flex gap-3">
                            <button onclick="window.print()" class="px-5 py-2.5 bg-white border border-gray-200 text-gray-500 rounded-xl text-xs font-bold hover:bg-gray-50 transition">
                                Print Report (A4)
                            </button>
                            <a href="./process/clear_logs.php" onclick="return confirm('Apakah Anda yakin ingin menghapus semua log?')" class="px-5 py-2.5 bg-brandAccent/10 text-brandAccent rounded-xl text-xs font-bold hover:bg-brandAccent hover:text-white transition">
                                Clear Logs
                            </a>
                        </div>
                    </div>

                    <div id="printable-area" class="bg-white rounded-[32px] border border-gray-100 shadow-sm overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-gray-50/50 border-b border-gray-100">
                                        <th class="px-8 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest w-48">Timestamp</th>
                                        <th class="px-6 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest w-64">Administrator</th>
                                        <th class="px-6 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Activity</th>
                                        <th class="px-8 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-right">IP Address</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    <?php if (mysqli_num_rows($result) > 0): ?>
                                        <?php while ($row = mysqli_fetch_assoc($result)):
                                            // 1. Perbaikan: Menggunakan kolom 'type' sesuai struktur DB Anda
                                            $dotColor = match ($row['type']) {
                                                'danger'  => 'bg-red-500',
                                                'success' => 'bg-green-500',
                                                'warning' => 'bg-brandGold',
                                                'info'    => 'bg-blue-500',
                                                default   => 'bg-brandPrimary',
                                            };

                                            $userName = $row['fullname'] ?? 'System / Anonymous';
                                            $userRole = $row['role'] ?? 'Guest';
                                        ?>
                                            <tr class="hover:bg-gray-50/30 transition-colors">
                                                <td class="px-8 py-5">
                                                    <p class="text-xs font-bold text-gray-400">
                                                        <?php echo date('d M Y, H:i', strtotime($row['created_at'])); ?>
                                                    </p>
                                                </td>
                                                <td class="px-6 py-5">
                                                    <div class="flex items-center gap-3">
                                                        <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center text-[10px] font-black text-brandPrimary uppercase">
                                                            <?php echo strtoupper(substr($userName, 0, 2)); ?>
                                                        </div>
                                                        <div>
                                                            <p class="text-xs font-bold text-brandPrimary"><?php echo $userName; ?></p>
                                                            <p class="text-[9px] text-gray-400 font-medium tracking-tight uppercase"><?php echo $userRole; ?></p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-5">
                                                    <div class="flex items-center gap-3">
                                                        <span class="w-2 h-2 rounded-full <?php echo $dotColor; ?>"></span>
                                                        <p class="text-xs font-medium text-slate-600"><?php echo $row['activity']; ?></p>
                                                    </div>
                                                </td>
                                                <td class="px-8 py-5 text-right font-mono text-[10px] text-gray-400">
                                                    <?php echo $row['ip_address']; ?>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4" class="px-8 py-10 text-center text-gray-400 text-sm">
                                                Belum ada rekaman aktivitas di database.
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="p-6 bg-gray-50/50 border-t border-gray-100 flex justify-between items-center">
                            <p class="text-[10px] font-bold text-gray-400 uppercase">
                                Menampilkan <?php echo mysqli_num_rows($result); ?> aktivitas terbaru
                            </p>
                        </div>
                    </div>
                </div>

                <?php include_once '_footer.php'; ?>
            </main>
        </div>
    </div>

    <script src="assets/script.js"></script>
</body>

</html>