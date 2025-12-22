<?php
require_once './process/config.php';
session_start();

// Ambil data dari database
$query = "SELECT * FROM team ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
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
    <script src="assets/script.js"></script>
    <style>
        .animate-slide-up {
            animation: slideUp 0.5s ease-out forwards;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
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

                <div class="p-6 md:p-10">

                    <?php if (isset($_GET['status'])): ?>
                        <div id="alert-feedback" class="mb-8 animate-slide-up">
                            <?php if ($_GET['status'] == 'success'): ?>
                                <div class="flex items-center gap-4 p-5 bg-emerald-50 border border-emerald-100 rounded-[24px]">
                                    <div class="w-12 h-12 bg-emerald-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-emerald-200">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-emerald-900">Berhasil!</h4>
                                        <p class="text-sm text-emerald-700/80">Anggota tim baru telah resmi bergabung dalam roster.</p>
                                    </div>
                                    <button onclick="this.parentElement.parentElement.remove()" class="ml-auto text-emerald-400 hover:text-emerald-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            <?php elseif ($_GET['status'] == 'deleted'): ?>
                                <div class="flex items-center gap-4 p-5 bg-amber-50 border border-amber-100 rounded-[24px]">
                                    <div class="w-12 h-12 bg-amber-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-amber-200">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-amber-900">Data Dihapus</h4>
                                        <p class="text-sm text-amber-700/80">Anggota tim telah dihapus secara permanen dari sistem.</p>
                                    </div>
                                    <button onclick="this.parentElement.parentElement.remove()" class="ml-auto text-amber-400 hover:text-amber-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            <?php elseif ($_GET['status'] == 'updated'): ?>
                                <div class="flex items-center gap-4 p-5 bg-emerald-50 border border-emerald-100 rounded-[24px]">
                                    <div class="w-12 h-12 bg-emerald-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-emerald-200">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-emerald-900">Berhasil!</h4>
                                        <p class="text-sm text-emerald-700/80">Data anggota tim telah diubah.</p>
                                    </div>
                                    <button onclick="this.parentElement.parentElement.remove()" class="ml-auto text-emerald-400 hover:text-emerald-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                                
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12">
                        <div class="space-y-1">
                            <h1 class="text-3xl font-extrabold text-brandPrimary tracking-tight">Studio Roster</h1>
                            <p class="text-sm text-gray-500 font-medium">Manajemen data anggota dan kontak DangDang Studio.</p>
                        </div>
                        <a href="team_add.php" class="flex items-center gap-3 px-8 py-4 bg-brandPrimary text-white rounded-2xl font-bold shadow-xl shadow-brandPrimary/20 hover:scale-[1.03] transition-all text-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Add New Staff
                        </a>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-8">
                        <?php while ($member = mysqli_fetch_assoc($result)) :
                            $img_path = !empty($member['member_image']) ? "../uploads/team/" . $member['member_image'] : "assets/img/default-avatar.jpg";
                        ?>
                            <div class="group relative bg-white rounded-[32px] overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-500 border border-gray-100 flex flex-col">

                                <div class="relative aspect-[3/4] overflow-hidden bg-gray-100">
                                    <img src="<?php echo $img_path; ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" onerror="this.src='https://ui-avatars.com/api/?name=<?php echo urlencode($member['name']); ?>&background=random'">

                                    <div class="absolute top-4 left-4">
                                        <span class="px-3 py-1.5 rounded-xl text-[10px] font-bold uppercase tracking-wider bg-white/90 backdrop-blur-md text-brandPrimary shadow-sm border border-white/50">
                                            <?php echo $member['status']; ?>
                                        </span>
                                    </div>

                                    <div class="absolute inset-x-0 bottom-0 p-4 translate-y-full group-hover:translate-y-0 transition-transform duration-300 bg-gradient-to-t from-brandPrimary/90 to-transparent flex justify-center gap-3">
                                        <a href="team_edit.php?id=<?php echo $member['id']; ?>" class="w-10 h-10 rounded-xl bg-white text-brandPrimary flex items-center justify-center hover:bg-brandGold hover:text-white transition shadow-lg">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                            </svg>
                                        </a>
                                        <button onclick="openDeleteTeamModal('<?php echo $member['id']; ?>', '<?php echo htmlspecialchars($member['name']); ?>')" class="w-10 h-10 rounded-xl bg-white text-red-500 flex items-center justify-center hover:bg-red-500 hover:text-white transition shadow-lg">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <div class="p-5 flex-1 flex flex-col">
                                    <div class="mb-4">
                                        <p class="text-[10px] font-bold text-brandGold uppercase tracking-widest mb-1"><?php echo $member['division']; ?></p>
                                        <h3 class="text-lg font-bold text-brandPrimary leading-tight mb-1"><?php echo $member['name']; ?></h3>
                                        <div class="flex items-center gap-2">
                                            <div class="w-1.5 h-1.5 rounded-full bg-brandTeal"></div>
                                            <span class="text-[11px] font-bold text-gray-400 uppercase tracking-tighter"><?php echo $member['level']; ?></span>
                                        </div>
                                    </div>

                                    <div class="mt-auto space-y-2">
                                        <a href="mailto:<?php echo $member['email']; ?>" class="flex items-center justify-center gap-2 w-full py-2.5 bg-gray-50 hover:bg-brandPrimary hover:text-white rounded-xl text-[11px] font-bold text-gray-500 transition-all border border-gray-100">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                            </svg>
                                            EMAIL STAFF
                                        </a>
                                        <a href="https://wa.me/<?php echo $member['phone']; ?>" target="_blank" class="flex items-center justify-center gap-2 w-full py-2.5 bg-emerald-50 hover:bg-emerald-500 hover:text-white rounded-xl text-[11px] font-bold text-emerald-600 transition-all border border-emerald-100">
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.025 3.334l-.671 2.451 2.516-.659c1.011.544 2.109.874 3.243.874 3.181 0 5.765-2.586 5.766-5.766 0-3.18-2.585-5.766-5.766-5.766zm3.346 8.165c-.143.404-.834.733-1.144.779-.271.04-.616.059-1.02-.072-.258-.083-.585-.195-.975-.365-1.66-.723-2.735-2.42-2.818-2.531-.083-.111-.676-.899-.676-1.714 0-.814.428-1.214.581-1.381.153-.167.333-.209.444-.209.111 0 .222 0 .319.005.101.005.237-.038.371.289.143.351.49 1.196.533 1.281.043.085.071.184.014.298-.057.114-.085.184-.171.284-.085.1-.176.223-.252.3-.095.097-.194.204-.083.394.111.19.493.815 1.057 1.319.727.648 1.341.85 1.531.944.19.095.302.079.414-.05.111-.129.479-.559.607-.75.129-.191.258-.16.435-.095.177.065 1.121.528 1.316.626.195.098.324.146.371.228.047.081.047.472-.096.876z" />
                                            </svg>
                                            WHATSAPP
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
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
        function openDeleteTeamModal(id, name) {
            document.getElementById('memberNameDisplay').innerText = name;
            document.getElementById('confirmDeleteTeamBtn').href = `./process/delete_team.php?id=${id}`;
            document.getElementById('deleteTeamModal').classList.remove('hidden');
        }

        function closeDeleteTeamModal() {
            document.getElementById('deleteTeamModal').classList.add('hidden');
        }
        // Auto hide alert setelah 5 detik
        setTimeout(() => {
            const alert = document.getElementById('alert-feedback');
            if (alert) alert.style.display = 'none';
        }, 5000);
    </script>
</body>

</html>