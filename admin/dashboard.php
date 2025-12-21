<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - DangDang Studio 2025</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="assets/tailwind-config.js"></script>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="assets/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

</head>

<body class="bg-gray-50 text-slate-800">

    <div class="flex flex-col h-screen overflow-hidden">
        <?php
        include_once '_header.php';
        ?>


        <div class="flex flex-1 overflow-hidden">

            <?php
            include_once '_aside.php';
            ?>

            <div id="overlay" class="fixed inset-0 bg-brandPrimary/60 backdrop-blur-sm z-40 hidden md:hidden"></div>

            <main class="flex-1 flex flex-col min-w-0 overflow-y-auto">

                <?php
                include_once '_topbar.php';
                ?>

                <div class="p-6 md:p-10 flex-1">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-brandPrimary">
                            <p class="text-xs text-gray-400 font-bold uppercase">Active Projects</p>
                            <h3 class="text-3xl font-bold mt-2 text-brandPrimary">24</h3>
                        </div>
                        <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-brandTeal">
                            <p class="text-xs text-gray-400 font-bold uppercase">Completed</p>
                            <h3 class="text-3xl font-bold mt-2 text-brandTeal">1,204</h3>
                        </div>
                        <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-brandAccent">
                            <p class="text-xs text-gray-400 font-bold uppercase">Pending Task</p>
                            <h3 class="text-3xl font-bold mt-2 text-brandAccent">12</h3>
                        </div>
                        <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-brandGold">
                            <p class="text-xs text-gray-400 font-bold uppercase">Revenue</p>
                            <h3 class="text-3xl font-bold mt-2 text-brandGold">$42.8k</h3>
                        </div>
                    </div>

                    <div class="mt-10 bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-6 border-b border-gray-50 flex justify-between items-center">
                            <h3 class="font-bold text-brandPrimary text-lg">Project Distribution</h3>
                            <button class="text-brandTeal text-sm font-bold hover:underline">View All</button>
                        </div>
                        <div class="h-80 flex flex-col items-center justify-center space-y-4">
                            <div class="w-16 h-16 rounded-full bg-gray-50 flex items-center justify-center">
                                <svg class="w-8 h-8 text-gray-200" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                                    <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                                </svg>
                            </div>
                            <p class="text-gray-400 text-sm">Waiting for data visualization...</p>
                        </div>
                    </div>
                </div>

                <?php
                include_once '_footer.php';
                ?>


            </main>
        </div>
    </div>

    <script src="assets/script.js"></script>
</body>

</html>