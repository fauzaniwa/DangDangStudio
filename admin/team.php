<?php
require_once './process/config.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login");
    exit();
}

// Ambil data tim
$query = "SELECT * FROM team ORDER BY division ASC, name ASC";
$result = mysqli_query($conn, $query);

$grouped_team = [];
$all_divisions = []; // Untuk daftar tombol filter
while ($row = mysqli_fetch_assoc($result)) {
    $grouped_team[$row['division']][] = $row;
    if (!in_array($row['division'], $all_divisions)) {
        $all_divisions[] = $row['division'];
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Team Roster - DangDang Studio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="assets/tailwind-config.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
    <style>
        .animate-slide-up { animation: slideUp 0.5s ease-out forwards; }
        @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .div-divider { height: 2px; background: linear-gradient(to right, #E2E8F0, transparent); }
        .filter-btn.active { background-color: #FF6136; color: white; border-color: #FF6136; }
    </style>
</head>

<body class="bg-[#FBFBFB] text-slate-800 font-['Plus_Jakarta_Sans']">

    <div class="flex flex-col h-screen overflow-hidden">
        <?php include_once '_header.php'; ?>
        <div class="flex flex-1 overflow-hidden">
            <?php include_once '_aside.php'; ?>
            <main class="flex-1 flex flex-col min-w-0 overflow-y-auto">
                <?php include_once '_topbar.php'; ?>

                <div class="p-6 md:p-10">
                    
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
                        <div class="space-y-1">
                            <h1 class="text-3xl font-extrabold text-brandPrimary tracking-tight">Studio Roster</h1>
                            <p class="text-sm text-gray-500 font-medium">Manajemen data anggota berdasarkan divisi studio.</p>
                        </div>
                        <a href="team_add.php" class="flex items-center gap-3 px-8 py-4 bg-brandPrimary text-white rounded-2xl font-bold shadow-xl shadow-brandPrimary/20 hover:scale-[1.03] transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Add New Staff
                        </a>
                    </div>

                    <div class="flex flex-wrap items-center gap-2 mb-12 bg-white p-2 rounded-[24px] border border-gray-100 w-fit shadow-sm">
                        <button onclick="filterSelection('all')" class="filter-btn active px-6 py-3 rounded-xl text-[10px] font-bold uppercase tracking-wider border border-transparent transition-all">
                            Semua Divisi
                        </button>
                        <?php foreach($all_divisions as $div): ?>
                            <button onclick="filterSelection('<?= strtolower(str_replace(' ', '-', $div)) ?>')" class="filter-btn px-6 py-3 rounded-xl text-[10px] font-bold uppercase tracking-wider text-gray-400 border border-transparent hover:text-brandPrimary transition-all">
                                <?= $div ?>
                            </button>
                        <?php endforeach; ?>
                    </div>

                    <div id="team-container">
                        <?php if (empty($grouped_team)): ?>
                            <div class="text-center py-20 bg-white rounded-[40px] border border-dashed border-gray-200">
                                <p class="text-gray-400 font-medium">Belum ada data anggota tim.</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($grouped_team as $division_name => $members) : 
                                $div_slug = strtolower(str_replace(' ', '-', $division_name));
                            ?>
                                <div class="division-group mb-16 animate-slide-up" data-category="<?= $div_slug ?>">
                                    <div class="flex items-center gap-4 mb-8">
                                        <h2 class="text-xs font-black text-brandGold uppercase tracking-[0.2em] whitespace-nowrap"><?= htmlspecialchars($division_name) ?></h2>
                                        <div class="div-divider flex-1"></div>
                                        <span class="text-[10px] font-bold text-gray-300 bg-gray-50 px-3 py-1 rounded-full"><?= count($members) ?> Staff</span>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-8">
                                        <?php foreach ($members as $member) : 
                                            // ... (Logika gambar sama seperti sebelumnya)
                                            $img_path = "../uploads/team/" . $member['member_image'];
                                            $final_img = (!empty($member['member_image']) && file_exists($img_path)) ? $img_path : 'https://ui-avatars.com/api/?name='.urlencode($member['name']).'&background=random&color=fff&bold=true';
                                        ?>
                                            <div class="group relative bg-white rounded-[32px] overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-500 border border-gray-100 flex flex-col">
                                                <div class="relative aspect-[3/4] overflow-hidden bg-gray-100">
                                                    <img src="<?= $final_img ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                                    <div class="absolute top-4 left-4">
                                                        <span class="px-3 py-1.5 rounded-xl text-[10px] font-bold uppercase tracking-wider bg-white/90 backdrop-blur-md text-brandPrimary shadow-sm border border-white/50">
                                                            <?= $member['status']; ?>
                                                        </span>
                                                    </div>
                                                    <div class="absolute inset-x-0 bottom-0 p-4 translate-y-full group-hover:translate-y-0 transition-transform duration-300 bg-gradient-to-t from-brandPrimary/90 to-transparent flex justify-center gap-3">
                                                        <a href="team_edit.php?id=<?= $member['id']; ?>" class="w-10 h-10 rounded-xl bg-white text-brandPrimary flex items-center justify-center hover:bg-brandGold hover:text-white transition shadow-lg">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                                        </a>
                                                        <button onclick="openDeleteTeamModal('<?= $member['id']; ?>', '<?= addslashes($member['name']); ?>')" class="w-10 h-10 rounded-xl bg-white text-red-500 flex items-center justify-center hover:bg-red-500 hover:text-white transition shadow-lg">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="p-5 flex-1 flex flex-col">
                                                    <h3 class="text-lg font-bold text-brandPrimary leading-tight mb-1"><?= $member['name']; ?></h3>
                                                    <div class="flex items-center gap-2 mb-4">
                                                        <div class="w-1.5 h-1.5 rounded-full bg-brandGold"></div>
                                                        <span class="text-[11px] font-bold text-gray-400 uppercase tracking-tighter"><?= $member['level']; ?></span>
                                                    </div>
                                                    <div class="mt-auto space-y-2">
                                                        <a href="mailto:<?= $member['email']; ?>" class="flex items-center justify-center gap-2 w-full py-2.5 bg-gray-50 hover:bg-brandPrimary hover:text-white rounded-xl text-[11px] font-bold text-gray-500 transition-all border border-gray-100 uppercase tracking-widest">Email Staff</a>
                                                        <a href="https://wa.me/<?= $member['phone']; ?>" target="_blank" class="flex items-center justify-center gap-2 w-full py-2.5 bg-emerald-50 hover:bg-emerald-500 hover:text-white rounded-xl text-[11px] font-bold text-emerald-600 transition-all border border-emerald-100 uppercase tracking-widest">
                                                        WhatsApp
                                                    </a>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <?php include_once '_footer.php'; ?>
            </main>
        </div>
    </div>

    <div id="deleteTeamModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
        <div class="fixed inset-0 bg-brandPrimary/40 backdrop-blur-sm transition-opacity"></div>
        <div class="relative flex min-h-screen items-center justify-center p-4 text-center">
            <div class="relative w-full max-w-md transform overflow-hidden rounded-[32px] bg-white p-8 text-left align-middle shadow-2xl transition-all">
                <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-3xl bg-red-50 mb-6">
                    <svg class="h-10 w-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <div class="text-center">
                    <h3 class="text-xl font-bold text-brandPrimary mb-2">Remove Member?</h3>
                    <p class="text-sm text-gray-500 mb-8">
                        Apakah anda yakin ingin menghapus <span id="memberNameDisplay" class="font-bold text-brandPrimary"></span> dari roster tim?
                    </p>
                </div>
                <div class="flex gap-4">
                    <button type="button" onclick="closeDeleteTeamModal()" class="flex-1 px-6 py-4 rounded-2xl bg-gray-100 text-gray-500 font-bold hover:bg-gray-200 transition-all">Cancel</button>
                    <a id="confirmDeleteTeamBtn" href="#" class="flex-1 px-6 py-4 rounded-2xl bg-red-500 text-white font-bold shadow-lg shadow-red-200 hover:bg-red-600 transition-all text-center">Delete</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function filterSelection(category) {
            const groups = document.getElementsByClassName('division-group');
            const btns = document.getElementsByClassName('filter-btn');

            // Update Active Button
            for (let btn of btns) {
                btn.classList.remove('active');
                btn.classList.add('text-gray-400');
                if (btn.innerText.toLowerCase().replace(/ /g, '-') === category || (category === 'all' && btn.innerText.toLowerCase() === 'semua divisi')) {
                    btn.classList.add('active');
                    btn.classList.remove('text-gray-400');
                }
            }

            // Filter Content
            for (let group of groups) {
                if (category === 'all' || group.getAttribute('data-category') === category) {
                    group.style.display = 'block';
                } else {
                    group.style.display = 'none';
                }
            }
        }

        function openDeleteTeamModal(id, name) {
            document.getElementById('memberNameDisplay').innerText = name;
            document.getElementById('confirmDeleteTeamBtn').href = `./process/delete_team.php?id=${id}`;
            document.getElementById('deleteTeamModal').classList.remove('hidden');
        }

        function closeDeleteTeamModal() {
            document.getElementById('deleteTeamModal').classList.add('hidden');
        }

        setTimeout(() => {
            const alert = document.getElementById('alert-feedback');
            if (alert) alert.style.display = 'none';
        }, 5000);
    </script>
</body>

</html>