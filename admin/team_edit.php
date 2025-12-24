<?php
require_once './process/config.php';
session_start();

// Proteksi akses
if (!isset($_SESSION['admin_id'])) {
    header("Location: login");
    exit();
}

// Cek ID yang akan diedit
if (!isset($_GET['id'])) {
    header("Location: team.php");
    exit();
}

$id = mysqli_real_escape_string($conn, $_GET['id']);
$query = "SELECT * FROM team WHERE id = '$id'";
$result = mysqli_query($conn, $query);
$member = mysqli_fetch_assoc($result);

if (!$member) {
    header("Location: team.php");
    exit();
}

// Ambil data divisi dari database untuk dropdown
$div_query = mysqli_query($conn, "SELECT * FROM divisions ORDER BY division_name ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Staff - DangDang Studio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="assets/tailwind-config.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
    <style>
        .animate-fade-in { animation: fadeIn 0.4s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="bg-[#FBFBFB] text-slate-800 font-['Plus_Jakarta_Sans']">

    <div class="flex flex-col h-screen overflow-hidden">
        <?php include_once '_header.php'; ?>
        <div class="flex flex-1 overflow-hidden">
            <?php include_once '_aside.php'; ?>
            <main class="flex-1 flex flex-col min-w-0 overflow-y-auto">
                <?php include_once '_topbar.php'; ?>

                <div class="p-6 md:p-10 flex-1">
                    <div class="max-w-4xl mx-auto">
                        <a href="team.php" class="inline-flex items-center gap-2 text-sm font-bold text-gray-400 hover:text-brandPrimary transition mb-8">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            Kembali ke Roster Tim
                        </a>

                        <form action="./process/process_edit_team.php" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-8 pb-20">
                            <input type="hidden" name="id" value="<?php echo $member['id']; ?>">
                            
                            <div class="lg:col-span-1 space-y-4">
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-2">Foto Profil (3:4)</label>
                                <div class="relative aspect-[3/4] bg-white rounded-[32px] border-2 border-dashed border-gray-200 p-2 group hover:border-brandGold transition-all duration-300 overflow-hidden shadow-sm">
                                    <input type="file" name="member_image" accept="image/*" onchange="previewPortrait(this)" class="absolute inset-0 opacity-0 cursor-pointer z-20">
                                    
                                    <div class="w-full h-full rounded-[24px] overflow-hidden bg-gray-50 flex items-center justify-center relative">
                                        <?php 
                                            $current_img = !empty($member['member_image']) ? "../uploads/team/" . $member['member_image'] : "";
                                            $has_img = !empty($member['member_image']) && file_exists($current_img);
                                        ?>
                                        <img id="portrait-prev" 
                                             src="<?php echo $has_img ? $current_img : ''; ?>" 
                                             class="w-full h-full object-cover <?php echo $has_img ? '' : 'hidden'; ?> z-10">
                                        
                                        <div id="portrait-placeholder" class="text-center p-4 z-0 <?php echo $has_img ? 'hidden' : ''; ?>">
                                            <div class="w-12 h-12 bg-white rounded-2xl shadow-sm flex items-center justify-center mx-auto mb-3">
                                                <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </div>
                                            <p class="text-[10px] font-bold text-gray-400 uppercase">Upload Potrait</p>
                                        </div>
                                    </div>

                                    <div id="change-photo-btn" class="absolute bottom-6 inset-x-6 z-30 <?php echo $has_img ? 'block' : 'hidden'; ?> group-hover:block animate-fade-in">
                                        <div class="bg-brandPrimary/90 backdrop-blur text-white py-2 rounded-xl text-center text-[10px] font-bold uppercase tracking-wider shadow-lg">Ganti Foto</div>
                                    </div>
                                </div>
                                <p class="text-[9px] text-gray-400 text-center italic px-4">* Biarkan kosong jika tidak ingin mengubah foto.</p>
                            </div>

                            <div class="lg:col-span-2 space-y-6">
                                <div class="bg-white rounded-[32px] p-8 shadow-sm border border-gray-100 space-y-6">
                                    <h2 class="text-xl font-bold text-brandPrimary mb-4 flex items-center gap-3">
                                        <span class="w-2 h-8 bg-brandGold rounded-full"></span> Edit Data Staff
                                    </h2>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div class="md:col-span-2">
                                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Nama Lengkap</label>
                                            <input type="text" name="name" required value="<?php echo htmlspecialchars($member['name']); ?>" placeholder="Masukkan nama lengkap..." class="w-full px-6 py-4 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-brandGold outline-none transition font-semibold text-brandPrimary">
                                        </div>

                                        <div>
                                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Divisi Kerja</label>
                                            <div class="flex gap-2">
                                                <select name="division" id="division_select" class="flex-1 px-6 py-4 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-brandGold outline-none transition font-bold text-sm">
                                                    <?php while($div = mysqli_fetch_assoc($div_query)): ?>
                                                        <option value="<?= htmlspecialchars($div['division_name']); ?>" <?= ($member['division'] == $div['division_name']) ? 'selected' : ''; ?>>
                                                            <?= htmlspecialchars($div['division_name']); ?>
                                                        </option>
                                                    <?php endwhile; ?>
                                                </select>
                                                <button type="button" onclick="openDivModal()" class="px-4 bg-gray-50 border border-gray-100 rounded-2xl text-gray-400 hover:text-brandPrimary hover:border-brandPrimary transition group">
                                                    <svg class="w-5 h-5 group-hover:rotate-90 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                                </button>
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Level / Jabatan</label>
                                            <input type="text" name="level" value="<?php echo htmlspecialchars($member['level']); ?>" placeholder="Contoh: Senior Staff" class="w-full px-6 py-4 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-brandGold outline-none transition font-bold text-sm">
                                        </div>

                                        <div>
                                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Status Pekerjaan</label>
                                            <div class="flex flex-wrap gap-2">
                                                <?php 
                                                $statuses = ["Full-time", "Intern", "Freelance"];
                                                foreach($statuses as $s) :
                                                    $checked = ($member['status'] == $s) ? "checked" : "";
                                                ?>
                                                <label class="flex-1">
                                                    <input type="radio" name="status" value="<?php echo $s; ?>" <?php echo $checked; ?> class="hidden peer">
                                                    <div class="text-center py-3 rounded-xl border border-gray-100 bg-gray-50 peer-checked:bg-brandPrimary peer-checked:text-white peer-checked:border-brandPrimary transition cursor-pointer font-bold text-[10px] uppercase"><?php echo $s; ?></div>
                                                </label>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Email Studio</label>
                                            <input type="email" name="email" value="<?php echo htmlspecialchars($member['email']); ?>" placeholder="nama@dangdang.com" class="w-full px-6 py-4 rounded-2xl border border-gray-100 bg-gray-50 outline-none text-sm font-medium">
                                        </div>

                                        <div>
                                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Nomor WhatsApp</label>
                                            <div class="relative">
                                                <span class="absolute left-6 top-1/2 -translate-y-1/2 text-sm font-bold text-gray-400">+62</span>
                                                <input type="tel" name="phone" value="<?php echo htmlspecialchars($member['phone']); ?>" placeholder="812345..." class="w-full pl-16 pr-6 py-4 rounded-2xl border border-gray-100 bg-gray-50 outline-none text-sm font-medium">
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Username Instagram</label>
                                            <div class="relative">
                                                <span class="absolute left-6 top-1/2 -translate-y-1/2 text-sm font-bold text-gray-400">@</span>
                                                <input type="text" name="instagram" value="<?php echo htmlspecialchars($member['instagram'] ?? ''); ?>" placeholder="username" class="w-full pl-12 pr-6 py-4 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-brandGold outline-none transition text-sm font-medium">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="pt-6">
                                        <button type="submit" class="w-full py-5 bg-brandPrimary text-white rounded-[24px] font-bold text-lg shadow-2xl shadow-brandPrimary/20 hover:scale-[1.02] active:scale-95 transition-all">
                                            Update Data Staff
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <?php include_once '_footer.php'; ?>
            </main>
        </div>
    </div>

    <div id="divModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
        <div class="fixed inset-0 bg-brandPrimary/40 backdrop-blur-sm" onclick="closeDivModal()"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative bg-white w-full max-w-sm rounded-[32px] shadow-2xl p-8 animate-fade-in text-center border border-gray-100">
                <div class="w-16 h-16 bg-brandPrimary/5 rounded-2xl flex items-center justify-center mx-auto mb-4 text-brandPrimary">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2-2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <h3 class="text-xl font-black text-brandPrimary mb-2">Tambah Divisi</h3>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-6">Create New Department</p>
                <input type="text" id="new_division_name" placeholder="Contoh: Technical Artist" class="w-full px-6 py-4 rounded-xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-brandGold outline-none transition font-bold text-sm mb-6">
                <div class="flex gap-3">
                    <button type="button" onclick="closeDivModal()" class="flex-1 py-4 text-sm font-bold text-gray-400 hover:text-gray-600 transition">Batal</button>
                    <button type="button" onclick="submitDivision()" class="flex-1 py-4 bg-brandPrimary text-white rounded-xl font-bold text-sm shadow-lg shadow-brandPrimary/20 hover:opacity-90 transition">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function previewPortrait(input) {
            const preview = document.getElementById('portrait-prev');
            const placeholder = document.getElementById('portrait-placeholder');
            const btn = document.getElementById('change-photo-btn');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                    btn.classList.remove('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        function openDivModal() { document.getElementById('divModal').classList.remove('hidden'); }
        function closeDivModal() { document.getElementById('divModal').classList.add('hidden'); }
        function submitDivision() {
            const nameInput = document.getElementById('new_division_name');
            const name = nameInput.value.trim();
            if (!name) return alert('Nama divisi tidak boleh kosong!');
            const formData = new URLSearchParams();
            formData.append('division_name', name);
            fetch('process/process_add_division.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    const select = document.getElementById('division_select');
                    const option = document.createElement('option');
                    option.value = name;
                    option.text = name;
                    option.selected = true;
                    select.add(option);
                    nameInput.value = '';
                    closeDivModal();
                } else { alert(data.message); }
            })
            .catch(err => alert('Terjadi kesalahan sistem.'));
        }
    </script>
</body>
</html>