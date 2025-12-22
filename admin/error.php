<?php
$error_code = isset($_GET['code']) ? $_GET['code'] : '404';

$error_data = [
    '404' => [
        'title' => 'Page Not Found',
        'desc' => 'Oops! Sepertinya halaman yang Anda cari telah pindah dimensi atau tidak pernah ada.',
        'icon' => 'M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
    ],
    '403' => [
        'title' => 'Access Denied',
        'desc' => 'Maaf, Anda tidak memiliki izin untuk mengakses area terlarang ini.',
        'icon' => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-11V7a4 4 0 00-8 0v4h8z'
    ],
    '500' => [
        'title' => 'Server Error',
        'desc' => 'Terjadi gangguan pada sistem internal kami. Tim developer kami sedang memperbaikinya.',
        'icon' => 'M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4'
    ]
];

$current_error = isset($error_data[$error_code]) ? $error_data[$error_code] : $error_data['404'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $error_code; ?> - DangDang Studio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="assets/tailwind-config.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .error-text {
            font-size: clamp(8rem, 20vw, 15rem);
            line-height: 1;
        }
    </style>
</head>
<body class="bg-[#FBFBFB] flex items-center justify-center min-h-screen p-6 overflow-hidden">

    <div class="max-w-2xl w-full text-center relative">
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 -z-10 overflow-hidden">
            <span class="error-text font-black text-gray-100/80 select-none italic">
                <?php echo $error_code; ?>
            </span>
        </div>

        <div class="relative z-10">
            <div class="w-24 h-24 bg-white rounded-[32px] shadow-2xl shadow-brandPrimary/10 flex items-center justify-center mx-auto mb-8 border border-gray-50">
                <svg class="w-12 h-12 text-brandGold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="<?php echo $current_error['icon']; ?>"></path>
                </svg>
            </div>

            <h1 class="text-4xl md:text-5xl font-black text-brandPrimary mb-4 tracking-tight">
                <?php echo $current_error['title']; ?>
            </h1>
            
            <p class="text-gray-500 text-lg mb-10 max-w-md mx-auto leading-relaxed">
                <?php echo $current_error['desc']; ?>
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="dashboard.php" class="px-8 py-4 bg-brandPrimary text-white rounded-2xl font-bold shadow-xl shadow-brandPrimary/20 hover:scale-[1.02] active:scale-95 transition-all w-full sm:w-auto">
                    Back to Dashboard
                </a>
                <button onclick="history.back()" class="px-8 py-4 bg-white text-gray-400 rounded-2xl font-bold border border-gray-100 hover:bg-gray-50 transition-all w-full sm:w-auto">
                    Go Back
                </button>
            </div>
        </div>

        <div class="mt-20">
            <p class="text-[10px] font-bold text-gray-300 uppercase tracking-[0.3em]">DangDang Studio System</p>
        </div>
    </div>

</body>
</html>