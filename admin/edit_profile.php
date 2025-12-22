<?php
require_once './process/config.php';
session_start();

// 1. Cek apakah session ada, jika tidak, tendang ke login
if (!isset($_SESSION['admin_id']) || empty($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$admin_id = $_SESSION['admin_id'];

// 2. Gunakan Prepared Statements (Lebih Aman)
$stmt = $conn->prepare("SELECT fullname, email, role, profile_picture FROM admins WHERE id = ?");
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

// 3. Jika data TIDAK ditemukan di database, paksa logout untuk keamanan
if (!$data) {
    session_destroy();
    header("Location: login.php?error=user_not_found");
    exit();
}

// 4. Set default value untuk menghindari error "Deprecated" (Passing null)
$fullname = $data['fullname'] ?? '';
$email    = $data['email']    ?? '';
$role     = $data['role']     ?? 'Admin';
$pp       = $data['profile_picture'] ?? '';

$profile_pic = !empty($pp) && file_exists('uploads/profiles/' . $pp)
    ? 'uploads/profiles/' . $pp
    : 'https://ui-avatars.com/api/?name=' . urlencode($fullname) . '&background=random';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - DangDang Studio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="assets/tailwind-config.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
                    <div class="max-w-4xl mx-auto">
                        <div class="mb-10">
                            <h1 class="text-3xl font-extrabold text-brandPrimary tracking-tight">Account Settings</h1>
                            <p class="text-sm text-gray-500 font-medium">Perbarui informasi personal dan keamanan akun Anda.</p>
                            <?php if (isset($_GET['status'])): ?>
                                <div id="feedback-alert" class="mb-8 animate-fade-in">
                                    <?php if ($_GET['status'] == 'success'): ?>
                                        <div class="flex items-center gap-4 p-5 bg-green-50 border border-green-100 rounded-[24px]">
                                            <div class="w-12 h-12 bg-green-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-green-200">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <h4 class="text-sm font-bold text-green-800">Perubahan Disimpan!</h4>
                                                <p class="text-xs text-green-600/80 font-medium">Informasi profil Anda telah berhasil diperbarui ke sistem.</p>
                                            </div>
                                            <button onclick="this.parentElement.parentElement.remove()" class="ml-auto text-green-400 hover:text-green-600">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    <?php elseif ($_GET['status'] == 'pass_mismatch'): ?>
                                        <div class="flex items-center gap-4 p-5 bg-orange-50 border border-orange-100 rounded-[24px]">
                                            <div class="w-12 h-12 bg-orange-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-orange-200">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <h4 class="text-sm font-bold text-orange-800">Password Tidak Cocok</h4>
                                                <p class="text-xs text-orange-600/80 font-medium">Konfirmasi password baru tidak sesuai. Silakan coba lagi.</p>
                                            </div>
                                        </div>
                                    <?php elseif ($_GET['status'] == 'error'): ?>
                                        <div class="flex items-center gap-4 p-5 bg-red-50 border border-red-100 rounded-[24px]">
                                            <div class="w-12 h-12 bg-red-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-red-200">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <h4 class="text-sm font-bold text-red-800">Terjadi Kesalahan</h4>
                                                <p class="text-xs text-red-600/80 font-medium">Gagal memperbarui data. Pastikan koneksi database stabil.</p>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>


                            <?php endif; ?>
                        </div>

                        <form action="./process/process_update_profile.php" method="POST" enctype="multipart/form-data" class="space-y-8 pb-20">
                            <div class="bg-white rounded-[40px] border border-gray-100 shadow-sm overflow-hidden">
                                <div class="h-32 bg-gradient-to-r from-brandPrimary to-brandAccent"></div>
                                <div class="px-8 pb-10">
                                    <div class="relative flex justify-between items-end -mt-12 mb-8">
                                        <div class="relative group">
                                            <div class="w-32 h-32 rounded-[32px] border-8 border-white bg-gray-200 overflow-hidden shadow-lg">
                                                <img id="avatar-preview" src="<?= $profile_pic ?>" class="w-full h-full object-cover">
                                            </div>
                                            <label class="absolute bottom-2 right-2 w-10 h-10 bg-brandGold text-white rounded-xl flex items-center justify-center cursor-pointer shadow-lg hover:scale-110 transition-transform">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg>
                                                <input type="file" name="avatar" class="hidden" onchange="previewAvatar(this)" accept="image/*">
                                            </label>
                                        </div>
                                        <div class="pb-2">
                                            <span class="px-4 py-2 bg-brandPrimary/5 text-brandPrimary rounded-full text-[10px] font-black uppercase tracking-widest"><?= htmlspecialchars($role) ?></span>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div class="space-y-1">
                                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.15em] ml-1">Full Name</label>
                                            <input type="text" name="fullname" value="<?= htmlspecialchars($fullname) ?>" required class="w-full px-6 py-4 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-brandGold outline-none transition font-bold text-brandPrimary">
                                        </div>
                                        <div class="space-y-1">
                                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.15em] ml-1">Email Address</label>
                                            <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" required class="w-full px-6 py-4 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-brandGold outline-none transition font-bold text-brandPrimary">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white rounded-[40px] p-8 md:p-10 border border-gray-100 shadow-sm">
                                <h2 class="text-xl font-bold text-brandPrimary mb-6 flex items-center gap-3">
                                    <span class="w-1.5 h-6 bg-brandAccent rounded-full"></span> Change Password
                                </h2>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-1">
                                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.15em] ml-1">New Password</label>
                                        <input type="password" name="new_password" placeholder="••••••••" class="w-full px-6 py-4 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-brandAccent outline-none transition font-bold text-brandPrimary">
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.15em] ml-1">Confirm New Password</label>
                                        <input type="password" name="confirm_password" placeholder="••••••••" class="w-full px-6 py-4 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-brandAccent outline-none transition font-bold text-brandPrimary">
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-end gap-4">
                                <button type="button" onclick="history.back()" class="px-8 py-4 text-sm font-bold text-gray-400 hover:text-brandPrimary transition">Batal</button>
                                <button type="submit" class="px-10 py-4 bg-brandPrimary text-white rounded-2xl font-bold shadow-xl shadow-brandPrimary/20 hover:scale-105 transition-all">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <?php include_once '_footer.php'; ?>
            </main>
        </div>
    </div>

    <script>
        function previewAvatar(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('avatar-preview').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        setTimeout(() => {
            const alert = document.getElementById('feedback-alert');
            if (alert) {
                alert.style.opacity = '0';
                alert.style.transition = 'opacity 0.5s ease';
                setTimeout(() => alert.remove(), 500);
            }
        }, 5000);
    </script>
</body>

</html>