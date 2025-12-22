<?php
require_once './process/config.php';
session_start();

// Proteksi Halaman: Hanya Super Admin yang bisa masuk ke menu ini (Opsional tapi disarankan)
if (!isset($_SESSION['is_logged_in'])) {
    header("Location: login.php");
    exit();
}

// Cek role user saat ini untuk kontrol UI
$current_user_role = $_SESSION['admin_role'];

// Ambil data dari tabel admins
$query = "SELECT id, fullname, email, role, profile_picture, created_at FROM admins ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Manager - DangDang Studio</title>
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
                            <?php if (isset($_GET['status'])): ?>
                                <div class="mb-6 p-4 rounded-2xl text-xs font-bold animate-fade-in border <?php
                                                                                                            echo $_GET['status'] == 'updated' || $_GET['status'] == 'deleted'
                                                                                                                ? 'bg-green-500/10 border-green-500 text-green-600'
                                                                                                                : 'bg-red-500/10 border-red-500 text-red-600';
                                                                                                            ?>">
                                    <?php
                                    if ($_GET['status'] == 'updated') echo "Data admin berhasil diperbarui!";
                                    if ($_GET['status'] == 'deleted') echo "Akun admin telah dihapus dari sistem.";
                                    if ($_GET['status'] == 'self_delete_error') echo "Anda tidak bisa menghapus akun Anda sendiri!";
                                    if ($_GET['status'] == 'error') echo "Terjadi kesalahan pada sistem.";
                                    ?>
                                </div>
                            <?php endif; ?>
                            <h1 class="text-3xl font-extrabold text-brandPrimary tracking-tight">Admin Manager</h1>
                            <p class="text-sm text-gray-500 font-medium">Kelola hak akses dan akun pengelola dashboard studio.</p>
                        </div>

                        <?php if ($current_user_role === 'Super Admin'): ?>
                            <a href="admin_add.php" class="flex items-center gap-2 px-6 py-3 bg-brandPrimary text-white rounded-2xl font-bold shadow-lg shadow-brandPrimary/20 hover:scale-105 transition-all text-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                </svg>
                                Add New Admin
                            </a>
                        <?php endif; ?>
                    </div>

                    <div class="bg-white rounded-[32px] border border-gray-100 shadow-sm overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-gray-50/50 border-b border-gray-100">
                                        <th class="px-8 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Administrator</th>
                                        <th class="px-6 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Role</th>
                                        <th class="px-6 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Joined Date</th>

                                        <?php if ($current_user_role === 'Super Admin'): ?>
                                            <th class="px-6 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Actions</th>
                                        <?php endif; ?>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    <?php while ($adm = mysqli_fetch_assoc($result)): ?>
                                        <tr class="group hover:bg-gray-50/50 transition-colors">
                                            <td class="px-8 py-5">
                                                <div class="flex items-center gap-4">
                                                    <?php
                                                    $avatar = !empty($adm['profile_picture']) ? 'uploads/profiles/' . $adm['profile_picture'] : 'https://ui-avatars.com/api/?name=' . urlencode($adm['fullname']) . '&background=random';
                                                    ?>
                                                    <img src="<?php echo $avatar; ?>" class="w-10 h-10 rounded-full object-cover ring-2 ring-gray-100">
                                                    <div>
                                                        <p class="text-sm font-bold text-brandPrimary"><?php echo htmlspecialchars($adm['fullname']); ?></p>
                                                        <p class="text-[10px] text-gray-400 font-medium"><?php echo htmlspecialchars($adm['email']); ?></p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-5">
                                                <?php
                                                $roleClass = match ($adm['role']) {
                                                    'Super Admin' => 'bg-brandGold/10 text-brandGold',
                                                    'Marketing'   => 'bg-blue-100 text-blue-600',
                                                    'Security'    => 'bg-red-100 text-red-600',
                                                    default       => 'bg-gray-100 text-gray-500'
                                                };
                                                ?>
                                                <span class="px-3 py-1 rounded-lg <?php echo $roleClass; ?> text-[10px] font-bold uppercase tracking-wider">
                                                    <?php echo $adm['role']; ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-5">
                                                <p class="text-xs text-gray-500 font-medium"><?php echo date('d M Y', strtotime($adm['created_at'])); ?></p>
                                            </td>

                                            <?php if ($current_user_role === 'Super Admin'): ?>
                                                <td class="px-6 py-5">
                                                    <div class="flex items-center justify-center gap-2">
                                                        <button onclick="openModalEdit('<?= $adm['id'] ?>', '<?= addslashes($adm['fullname']) ?>', '<?= $adm['email'] ?>', '<?= $adm['role'] ?>')"
                                                            class="w-9 h-9 flex items-center justify-center rounded-xl bg-gray-50 text-gray-400 hover:text-brandPrimary hover:bg-brandPrimary/10 transition shadow-sm">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                            </svg>
                                                        </button>

                                                        <?php if ($adm['id'] != $_SESSION['admin_id']): ?>
                                                            <button onclick="confirmDeleteAdmin('<?php echo $adm['id']; ?>', '<?php echo addslashes($adm['fullname']); ?>')" class="w-9 h-9 flex items-center justify-center rounded-xl bg-gray-50 text-gray-400 hover:text-brandAccent hover:bg-brandAccent/10 transition shadow-sm">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                                </svg>
                                                            </button>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            <?php endif; ?>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <?php include_once '_footer.php'; ?>
            </main>
        </div>
    </div>

    <div id="modal-delete" class="fixed inset-0 z-[100] hidden overflow-y-auto">
        <div class="fixed inset-0 bg-brandPrimary/40 backdrop-blur-sm transition-opacity" onclick="closeModalDelete()"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative transform overflow-hidden rounded-[32px] bg-white p-8 shadow-2xl transition-all w-full max-w-md animate-fade-in text-center">
                <div class="w-20 h-20 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-brandAccent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-brandPrimary mb-2 tracking-tight">Hapus Akses Admin?</h3>
                <p class="text-sm text-gray-500 mb-8">Akun <span id="delete-admin-title" class="font-bold"></span> tidak akan bisa lagi mengakses dashboard ini.</p>
                <div class="flex gap-3">
                    <button onclick="closeModalDelete()" class="flex-1 py-4 bg-gray-50 text-gray-400 rounded-2xl font-bold hover:bg-gray-100 transition">Batal</button>
                    <a id="btn-confirm-delete" href="#" class="flex-1 py-4 bg-brandAccent text-white rounded-2xl font-bold shadow-lg shadow-brandAccent/20 text-center">Hapus Akses</a>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-edit" class="fixed inset-0 z-[100] hidden overflow-y-auto">
        <div class="fixed inset-0 bg-brandPrimary/40 backdrop-blur-sm transition-opacity" onclick="closeModalEdit()"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative transform overflow-hidden rounded-[32px] bg-white p-8 shadow-2xl transition-all w-full max-w-md animate-fade-in">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-brandPrimary tracking-tight">Edit Akun Admin</h3>
                    <button onclick="closeModalEdit()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form action="./process/admin_update.php" method="POST">
                    <input type="hidden" name="admin_id" id="edit-id">

                    <div class="space-y-4">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Nama Lengkap</label>
                            <input type="text" name="fullname" id="edit-fullname" required class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-sm focus:ring-2 focus:ring-brandPrimary/20 focus:border-brandPrimary outline-none transition-all">
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Email Address</label>
                            <input type="email" name="email" id="edit-email" required class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-sm focus:ring-2 focus:ring-brandPrimary/20 focus:border-brandPrimary outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Akses Role</label>
                            <select name="role" id="edit-role" required class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-sm focus:ring-2 focus:ring-brandPrimary/20 focus:border-brandPrimary outline-none transition-all appearance-none">
                                <option value="Super Admin">Super Admin</option>
                                <option value="Editor">Editor</option>
                                <option value="Marketing">Marketing</option>
                                <option value="Security">Security</option>
                            </select>
                        </div>

                        <div class="p-4 bg-brandGold/5 rounded-2xl border border-brandGold/20">
                            <label class="block text-[10px] font-bold text-brandGold uppercase tracking-widest mb-2 ml-1">Ganti Password (Opsional)</label>
                            <input type="password" name="password" placeholder="Kosongkan jika tidak diubah" class="w-full px-5 py-4 bg-white border border-gray-100 rounded-xl text-sm focus:ring-2 focus:ring-brandGold/20 focus:border-brandGold outline-none transition-all">
                            <p class="text-[9px] text-gray-400 mt-2">*Minimal 8 karakter jika ingin mengganti</p>
                        </div>
                    </div>

                    <div class="flex gap-3 mt-8">
                        <button type="button" onclick="closeModalEdit()" class="flex-1 py-4 bg-gray-50 text-gray-400 rounded-2xl font-bold hover:bg-gray-100 transition">Batal</button>
                        <button type="submit" class="flex-1 py-4 bg-brandPrimary text-white rounded-2xl font-bold shadow-lg shadow-brandPrimary/20">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Perbarui fungsi untuk menerima parameter role
        function openModalEdit(id, name, email, role) {
            document.getElementById('edit-id').value = id;
            document.getElementById('edit-fullname').value = name;
            document.getElementById('edit-email').value = email;
            document.getElementById('edit-role').value = role; // Set nilai select sesuai role admin
            document.getElementById('modal-edit').classList.remove('hidden');
        }

        function closeModalEdit() {
            document.getElementById('modal-edit').classList.add('hidden');
        }

        function confirmDeleteAdmin(id, name) {
            document.getElementById('delete-admin-title').innerText = name;
            document.getElementById('btn-confirm-delete').href = './process/admin_delete.php?id=' + id;
            document.getElementById('modal-delete').classList.remove('hidden');
        }

        function closeModalDelete() {
            document.getElementById('modal-delete').classList.add('hidden');
        }
    </script>
</body>

</html>