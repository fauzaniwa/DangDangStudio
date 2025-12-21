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
                            <h1 class="text-3xl font-extrabold text-brandPrimary tracking-tight">System Logs</h1>
                            <p class="text-sm text-gray-500 font-medium">Rekaman aktivitas terbaru dari sistem manajemen studio.</p>
                        </div>
                        <div class="flex gap-3">
                            <button class="px-5 py-2.5 bg-white border border-gray-200 text-gray-500 rounded-xl text-xs font-bold hover:bg-gray-50 transition">
                                Export CSV
                            </button>
                            <button class="px-5 py-2.5 bg-brandAccent/10 text-brandAccent rounded-xl text-xs font-bold hover:bg-brandAccent hover:text-white transition">
                                Clear Logs
                            </button>
                        </div>
                    </div>

                    <div class="bg-white rounded-[32px] border border-gray-100 shadow-sm overflow-hidden">
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
                                    <?php
                                    $logs = [
                                        ['time' => '22 Des 2025, 10:45', 'user' => 'Aditya Pratama', 'role' => 'Super Admin', 'action' => 'Deleted Game Project: Nusantara Runner', 'type' => 'danger', 'ip' => '192.168.1.1'],
                                        ['time' => '22 Des 2025, 09:12', 'user' => 'Rina Wijaya', 'role' => 'Editor', 'action' => 'Published new article "DevLog #12"', 'type' => 'success', 'ip' => '192.168.1.42'],
                                        ['time' => '21 Des 2025, 23:58', 'user' => 'Fajar Sidik', 'role' => 'Marketing', 'action' => 'Scheduled Social Media post for Twitter', 'type' => 'info', 'ip' => '110.12.33.21'],
                                        ['time' => '21 Des 2025, 20:05', 'user' => 'System', 'role' => 'Security', 'action' => 'Failed login attempt detected', 'type' => 'warning', 'ip' => '203.11.2.99'],
                                    ];

                                    foreach ($logs as $log): 
                                        $dotColor = match($log['type']) {
                                            'danger' => 'bg-red-500',
                                            'success' => 'bg-green-500',
                                            'warning' => 'bg-brandGold',
                                            default => 'bg-brandPrimary',
                                        };
                                    ?>
                                        <tr class="hover:bg-gray-50/30 transition-colors">
                                            <td class="px-8 py-5">
                                                <p class="text-xs font-bold text-gray-400"><?php echo $log['time']; ?></p>
                                            </td>
                                            <td class="px-6 py-5">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center text-[10px] font-black text-brandPrimary uppercase">
                                                        <?php echo substr($log['user'], 0, 2); ?>
                                                    </div>
                                                    <div>
                                                        <p class="text-xs font-bold text-brandPrimary"><?php echo $log['user']; ?></p>
                                                        <p class="text-[9px] text-gray-400 font-medium tracking-tight uppercase"><?php echo $log['role']; ?></p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-5">
                                                <div class="flex items-center gap-3">
                                                    <span class="w-2 h-2 rounded-full <?php echo $dotColor; ?>"></span>
                                                    <p class="text-xs font-medium text-slate-600"><?php echo $log['action']; ?></p>
                                                </div>
                                            </td>
                                            <td class="px-8 py-5 text-right font-mono text-[10px] text-gray-400">
                                                <?php echo $log['ip']; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="p-6 bg-gray-50/50 border-t border-gray-100 flex justify-between items-center">
                            <p class="text-[10px] font-bold text-gray-400 uppercase">Showing 1 to 4 of 128 logs</p>
                            <div class="flex gap-2">
                                <button class="w-8 h-8 flex items-center justify-center rounded-lg bg-white border border-gray-200 text-gray-400 hover:text-brandPrimary hover:border-brandPrimary transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"></path></svg></button>
                                <button class="w-8 h-8 flex items-center justify-center rounded-lg bg-brandPrimary text-white font-bold text-xs">1</button>
                                <button class="w-8 h-8 flex items-center justify-center rounded-lg bg-white border border-gray-200 text-gray-400 hover:text-brandPrimary hover:border-brandPrimary transition font-bold text-xs">2</button>
                                <button class="w-8 h-8 flex items-center justify-center rounded-lg bg-white border border-gray-200 text-gray-400 hover:text-brandPrimary hover:border-brandPrimary transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"></path></svg></button>
                            </div>
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