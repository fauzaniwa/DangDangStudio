<?php
require_once 'process/config.php';
session_start();

// Proteksi akses
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Ambil ID klien dari URL
$id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : '';

if (empty($id)) {
    header("Location: client_list.php");
    exit();
}

// Query ambil data klien
$query = "SELECT * FROM clients WHERE id = '$id'";
$result = mysqli_query($conn, $query);
$client = mysqli_fetch_assoc($result);

// Jika klien tidak ditemukan
if (!$client) {
    header("Location: client_list.php?status=error&msg=Client not found");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Client - DangDang Studio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="assets/tailwind-config.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
</head>

<body class="bg-gray-50 text-slate-800">

    <div class="flex flex-col h-screen overflow-hidden">
        <?php include_once '_header.php'; ?>

        <div class="flex flex-1 overflow-hidden">
            <?php include_once '_aside.php'; ?>

            <main class="flex-1 flex flex-col min-w-0 overflow-y-auto">
                <?php include_once '_topbar.php'; ?>

                <div class="p-6 md:p-10 flex-1">
                    <a href="client_list.php" class="inline-flex items-center gap-2 text-sm font-bold text-gray-400 hover:text-brandTeal transition mb-6">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Client List
                    </a>

                    <div class="max-w-4xl bg-white rounded-[32px] shadow-sm border border-gray-100 overflow-hidden">
                        <?php if (isset($_GET['status']) && $_GET['status'] == 'error'): ?>
                            <div class="p-4 bg-red-50 border-b border-red-100 text-red-600 text-sm font-bold flex items-center gap-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Gagal memperbarui: <?php echo htmlspecialchars($_GET['msg']); ?>
                            </div>
                        <?php endif; ?>

                        <div class="p-8 border-b border-gray-50 bg-gradient-to-r from-white to-gray-50/50">
                            <h1 class="text-2xl font-bold text-brandPrimary">Update Client Data</h1>
                            <p class="text-sm text-gray-500 font-medium">Perbarui informasi profil dan kontak klien Anda.</p>
                        </div>

                        <form action="./process/process_edit_client.php" method="POST" class="p-8 space-y-8">
                            <input type="hidden" name="id" value="<?php echo $client['id']; ?>">

                            <div class="space-y-4">
                                <h2 class="text-xs font-bold uppercase tracking-[0.2em] text-brandTeal">Company Information</h2>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="md:col-span-2">
                                        <label class="block text-xs font-bold text-gray-400 mb-2 uppercase">Company Name</label>
                                        <input type="text" name="company_name" required value="<?php echo htmlspecialchars($client['company_name']); ?>"
                                            class="w-full px-5 py-3 rounded-2xl border border-gray-100 bg-gray-50/30 focus:bg-white focus:border-brandTeal focus:ring-4 focus:ring-brandTeal/5 outline-none transition">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-400 mb-2 uppercase">Industry Category</label>
                                        <select name="category" class="w-full px-5 py-3 rounded-2xl border border-gray-100 bg-gray-50/30 focus:bg-white focus:border-brandTeal outline-none transition appearance-none">
                                            <?php 
                                            $categories = ["Corporate", "F&B", "Technology", "Fashion", "Government"];
                                            foreach($categories as $cat): ?>
                                                <option value="<?php echo $cat; ?>" <?php echo ($client['category'] == $cat) ? 'selected' : ''; ?>>
                                                    <?php echo $cat; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-400 mb-2 uppercase">Website / Social Media</label>
                                        <input type="text" name="website" value="<?php echo htmlspecialchars($client['website']); ?>"
                                            class="w-full px-5 py-3 rounded-2xl border border-gray-100 bg-gray-50/30 focus:bg-white focus:border-brandTeal outline-none transition">
                                    </div>
                                </div>
                            </div>

                            <hr class="border-gray-50">

                            <div class="space-y-4">
                                <h2 class="text-xs font-bold uppercase tracking-[0.2em] text-brandGold">PIC Information</h2>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-400 mb-2 uppercase">Full Name</label>
                                        <input type="text" name="pic_name" required value="<?php echo htmlspecialchars($client['pic_name']); ?>"
                                            class="w-full px-5 py-3 rounded-2xl border border-gray-100 bg-gray-50/30 focus:bg-white focus:border-brandGold focus:ring-4 focus:ring-brandGold/5 outline-none transition">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-400 mb-2 uppercase">Email Address</label>
                                        <input type="email" name="email" required value="<?php echo htmlspecialchars($client['email']); ?>"
                                            class="w-full px-5 py-3 rounded-2xl border border-gray-100 bg-gray-50/30 focus:bg-white focus:border-brandGold outline-none transition">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-400 mb-2 uppercase">Phone Number</label>
                                        <input type="tel" name="phone" value="<?php echo htmlspecialchars($client['phone']); ?>"
                                            class="w-full px-5 py-3 rounded-2xl border border-gray-100 bg-gray-50/30 focus:bg-white focus:border-brandGold outline-none transition">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-400 mb-2 uppercase">Position / Job Title</label>
                                        <input type="text" name="position" value="<?php echo htmlspecialchars($client['position']); ?>"
                                            class="w-full px-5 py-3 rounded-2xl border border-gray-100 bg-gray-50/30 focus:bg-white focus:border-brandGold outline-none transition">
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-end gap-4 pt-6">
                                <a href="client_list.php" class="px-6 py-3 text-sm font-bold text-gray-400 hover:text-brandAccent transition">Cancel Changes</a>
                                <button type="submit" class="px-10 py-3 bg-brandTeal text-white rounded-2xl font-bold shadow-xl shadow-brandTeal/20 hover:scale-105 active:scale-95 transition-all">
                                    Update Client
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <?php include_once '_footer.php'; ?>
            </main>
        </div>
    </div>
</body>
</html>