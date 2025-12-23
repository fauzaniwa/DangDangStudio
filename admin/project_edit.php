<?php
require_once 'process/config.php';
session_start();

// 1. Proteksi Admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// 2. Ambil dan Validasi ID dari URL (Gunakan Prepared Statement seperti mekanis timeline)
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    header("Location: project_manager.php?status=error&msg=Invalid Project ID");
    exit();
}

// 3. Ambil data proyek
$stmt = $conn->prepare("SELECT * FROM projects WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $data = $result->fetch_assoc();
} else {
    header("Location: project_manager.php?status=error&msg=Project not found");
    exit();
}

// 4. Inisialisasi variabel (Mencegah error null di PHP 8.1+)
$project_name = $data['project_name'] ?? '';
$project_icon = $data['project_icon'] ?? '';
$genre        = $data['genre'] ?? '';
$status       = $data['status'] ?? '';
$progress     = $data['progress'] ?? 0;
$brief        = $data['brief'] ?? '';
$platforms_selected = json_decode($data['platforms'] ?? '[]', true) ?: [];
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Project - DangDang Studio</title>
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
                    <div class="max-w-5xl mx-auto">
                        <div class="flex items-center justify-between mb-8">
                            <a href="project_manager.php" class="inline-flex items-center gap-2 text-sm font-bold text-gray-400 hover:text-brandPrimary transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Back to Project Manager
                            </a>
                        </div>

                        <form action="process/process_edit_project.php" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-12 gap-8 pb-20">
                            
                            <input type="hidden" name="id" value="<?= $id ?>">

                            <div class="lg:col-span-4 space-y-6">
                                <div class="bg-white p-6 rounded-[32px] border border-gray-100 shadow-sm">
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4 ml-2">Project Identity (Icon)</label>
                                    <div class="relative aspect-square rounded-[24px] border-2 border-dashed border-gray-200 bg-gray-50 flex items-center justify-center overflow-hidden group hover:border-brandGold transition-all">
                                        <input type="file" name="project_icon" id="project_icon" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer z-20">
                                        
                                        <?php $has_icon = !empty($project_icon); ?>
                                        <img id="icon-prev" src="uploads/projects/<?= $project_icon ?>" class="absolute inset-0 w-full h-full object-cover <?= $has_icon ? '' : 'hidden' ?> z-10">

                                        <div id="icon-placeholder" class="text-center p-4 <?= $has_icon ? 'hidden' : '' ?>">
                                            <div class="w-12 h-12 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-3 group-hover:bg-brandGold/10 transition-colors">
                                                <svg class="w-6 h-6 text-gray-400 group-hover:text-brandGold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                </svg>
                                            </div>
                                            <p class="text-[10px] font-bold text-gray-400 uppercase">Change Icon</p>
                                        </div>
                                    </div>
                                    <p class="mt-4 text-[9px] text-gray-400 text-center italic">Kosongkan jika tidak ingin mengganti icon.</p>
                                </div>

                                <div class="bg-white p-6 rounded-[32px] border border-gray-100 shadow-sm">
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4 ml-2 flex justify-between items-center">
                                        Target Platforms
                                        <button type="button" onclick="openModal('modal-platform')" class="text-brandGold hover:underline normal-case tracking-normal">+ Add New</button>
                                    </label>
                                    <div id="platform-container" class="grid grid-cols-1 gap-2">
                                        <?php
                                        $p_query = mysqli_query($conn, "SELECT * FROM game_platforms ORDER BY platform_name ASC");
                                        while ($plt = mysqli_fetch_assoc($p_query)): 
                                            $is_checked = in_array($plt['platform_name'], $platforms_selected) ? 'checked' : '';
                                        ?>
                                            <label class="flex items-center gap-3 p-3 rounded-xl border border-gray-50 hover:bg-gray-50 cursor-pointer transition group">
                                                <input type="checkbox" name="platforms[]" value="<?= $plt['platform_name']; ?>" <?= $is_checked ?> class="w-4 h-4 rounded border-gray-300 text-brandGold focus:ring-brandGold">
                                                <span class="text-xs font-bold text-slate-600 group-hover:text-brandPrimary"><?= $plt['platform_name']; ?></span>
                                            </label>
                                        <?php endwhile; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="lg:col-span-8 space-y-6">
                                <div class="bg-white rounded-[32px] p-8 md:p-10 shadow-sm border border-gray-100 space-y-6">
                                    <h2 class="text-xl font-bold text-brandPrimary flex items-center gap-3">
                                        <span class="w-2 h-8 bg-brandGold rounded-full"></span> Edit Project Intel
                                    </h2>

                                    <div>
                                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Project Name</label>
                                        <input type="text" name="project_name" required value="<?= htmlspecialchars($project_name) ?>" class="w-full px-6 py-4 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-brandGold outline-none transition font-bold text-brandPrimary">
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1 flex justify-between">
                                                Game Genre
                                                <button type="button" onclick="openModal('modal-genre')" class="text-brandGold hover:underline">+ Add New</button>
                                            </label>
                                            <select name="genre" id="select-genre" class="w-full px-6 py-4 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-brandGold outline-none transition font-bold text-sm">
                                                <?php
                                                $g_query = mysqli_query($conn, "SELECT * FROM game_genres ORDER BY genre_name ASC");
                                                while ($g = mysqli_fetch_assoc($g_query)) {
                                                    $is_selected = ($g['genre_name'] == $genre) ? 'selected' : '';
                                                    echo "<option value='{$g['genre_name']}' $is_selected>{$g['genre_name']}</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1 flex justify-between">
                                                Current Status
                                                <button type="button" onclick="openModal('modal-status')" class="text-brandGold hover:underline">+ Add New</button>
                                            </label>
                                            <select name="status" id="select-status" class="w-full px-6 py-4 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-brandGold outline-none transition font-bold text-sm">
                                                <?php
                                                $s_query = mysqli_query($conn, "SELECT * FROM project_statuses ORDER BY status_name ASC");
                                                while ($s = mysqli_fetch_assoc($s_query)) {
                                                    $is_selected = ($s['status_name'] == $status) ? 'selected' : '';
                                                    echo "<option value='{$s['status_name']}' $is_selected>{$s['status_name']}</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div>
                                        <div class="flex justify-between items-center mb-3">
                                            <label class="block text-[10px] font-bold text-gray-400 uppercase ml-1">Current Progress</label>
                                            <span id="progress-val" class="px-3 py-1 bg-brandGold/10 text-brandGold rounded-lg text-xs font-black"><?= $progress ?>%</span>
                                        </div>
                                        <input type="range" name="progress" min="0" max="100" value="<?= $progress ?>" oninput="updateProgress(this.value)" class="w-full h-2 bg-gray-100 rounded-lg appearance-none cursor-pointer accent-brandGold">
                                    </div>

                                    <div>
                                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Development Brief / Roadmap</label>
                                        <textarea name="brief" rows="5" class="w-full px-6 py-5 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-brandGold outline-none transition text-sm text-gray-600 leading-relaxed"><?= htmlspecialchars($brief) ?></textarea>
                                    </div>

                                    <div class="pt-4">
                                        <button type="submit" class="w-full py-5 bg-brandPrimary text-white rounded-[24px] font-bold text-lg shadow-2xl shadow-brandPrimary/20 hover:scale-[1.01] active:scale-95 transition-all">
                                            Update Project Data
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

    <div id="modal-platform" class="fixed inset-0 z-[60] hidden flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
        <div class="bg-white w-full max-w-md rounded-[32px] p-8 shadow-2xl">
            <h3 class="text-xl font-bold text-brandPrimary mb-4">Add New Platform</h3>
            <input type="text" id="new-platform-name" placeholder="Platform Name (e.g. Meta Quest)" class="w-full px-6 py-4 rounded-2xl border border-gray-100 bg-gray-50 outline-none mb-6">
            <div class="flex gap-3">
                <button onclick="closeModal('modal-platform')" class="flex-1 py-4 text-sm font-bold text-gray-400">Cancel</button>
                <button onclick="saveOption('platform')" class="flex-1 py-4 bg-emerald-500 text-white rounded-2xl font-bold shadow-lg">Save Platform</button>
            </div>
        </div>
    </div>

    <div id="modal-genre" class="fixed inset-0 z-[60] hidden flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
        <div class="bg-white w-full max-w-md rounded-[32px] p-8 shadow-2xl">
            <h3 class="text-xl font-bold text-brandPrimary mb-4">Add New Genre</h3>
            <input type="text" id="new-genre-name" placeholder="Genre Name (e.g. Open World)" class="w-full px-6 py-4 rounded-2xl border border-gray-100 bg-gray-50 outline-none mb-6">
            <div class="flex gap-3">
                <button onclick="closeModal('modal-genre')" class="flex-1 py-4 text-sm font-bold text-gray-400">Cancel</button>
                <button onclick="saveOption('genre')" class="flex-1 py-4 bg-brandPrimary text-white rounded-2xl font-bold shadow-lg">Save Genre</button>
            </div>
        </div>
    </div>

    <div id="modal-status" class="fixed inset-0 z-[60] hidden flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
        <div class="bg-white w-full max-w-md rounded-[32px] p-8 shadow-2xl">
            <h3 class="text-xl font-bold text-brandPrimary mb-4">Add New Status</h3>
            <input type="text" id="new-status-name" placeholder="Status Name (e.g. Polishing)" class="w-full px-6 py-4 rounded-2xl border border-gray-100 bg-gray-50 outline-none mb-6">
            <div class="flex gap-3">
                <button onclick="closeModal('modal-status')" class="flex-1 py-4 text-sm font-bold text-gray-400">Cancel</button>
                <button onclick="saveOption('status')" class="flex-1 py-4 bg-brandGold text-white rounded-2xl font-bold shadow-lg">Save Status</button>
            </div>
        </div>
    </div>
    <script>
        function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
        function closeModal(id) { document.getElementById(id).classList.add('hidden'); }

        function saveOption(type) {
            let inputId, selectId;
            if(type === 'genre') { inputId = 'new-genre-name'; selectId = 'select-genre'; }
            else if(type === 'status') { inputId = 'new-status-name'; selectId = 'select-status'; }
            else { inputId = 'new-platform-name'; selectId = 'platform-container'; }

            const val = document.getElementById(inputId).value;
            if (!val) return alert('Nama tidak boleh kosong!');

            fetch('process/process_quick_add.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `type=${type}&name=${encodeURIComponent(val)}`
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    if (type === 'platform') {
                        const container = document.getElementById('platform-container');
                        const newLabel = document.createElement('label');
                        newLabel.className = "flex items-center gap-3 p-3 rounded-xl border border-gray-50 hover:bg-gray-50 cursor-pointer transition group";
                        newLabel.innerHTML = `<input type="checkbox" name="platforms[]" value="${val}" checked class="w-4 h-4 rounded border-gray-300 text-brandGold focus:ring-brandGold"><span class="text-xs font-bold text-slate-600 group-hover:text-brandPrimary">${val}</span>`;
                        container.appendChild(newLabel);
                    } else {
                        const select = document.getElementById(selectId);
                        const opt = document.createElement('option');
                        opt.value = val; opt.innerHTML = val; opt.selected = true;
                        select.appendChild(opt);
                    }
                    closeModal(`modal-${type}`);
                    document.getElementById(inputId).value = '';
                } else {
                    alert(data.message);
                }
            });
        }

        document.getElementById('project_icon').addEventListener('change', function() {
            const preview = document.getElementById('icon-prev');
            const placeholder = document.getElementById('icon-placeholder');
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                }
                reader.readAsDataURL(file);
            }
        });

        function updateProgress(val) {
            document.getElementById('progress-val').innerText = val + '%';
        }
    </script>
</body>
</html>