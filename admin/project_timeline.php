<?php
require_once 'process/config.php';
session_start();

// Query mengambil data timeline dan join dengan nama perusahaan client
$query = "SELECT pt.*, c.company_name 
          FROM project_timelines pt 
          LEFT JOIN clients c ON pt.client_id = c.id 
          ORDER BY pt.deadline_date ASC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Timeline - DangDang Studio</title>
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
                    <?php if (isset($_GET['status'])): ?>
                        <div class="mb-6 p-4 rounded-2xl <?php echo $_GET['status'] == 'success' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-red-50 text-red-600 border border-red-100'; ?> flex items-center gap-3 animate-fade-in">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-sm font-bold"><?php echo htmlspecialchars($_GET['msg']); ?></span>
                        </div>
                    <?php endif; ?>

                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                        <div>
                            <h1 class="text-2xl font-bold text-brandPrimary">Project Timeline</h1>
                            <p class="text-sm text-gray-500">Monitor all tasks and creative progress in one place.</p>
                        </div>
                        <div class="flex gap-2">
                            <a href="project_timeline_add.php" class="px-6 py-3 bg-brandPrimary text-white rounded-2xl text-sm font-bold shadow-xl shadow-brandPrimary/20 hover:scale-105 active:scale-95 transition-all flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Create New Task
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-6">
                        <?php if (mysqli_num_rows($result) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <div class="bg-white p-6 rounded-[32px] shadow-sm border border-gray-100 hover:shadow-md transition-all group">
                                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                                        <div class="flex items-start gap-4">
                                            <div class="w-14 h-14 rounded-2xl bg-gray-50 flex items-center justify-center text-<?php echo $row['color_label']; ?> border border-gray-100 shadow-inner">
                                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="flex items-center gap-2 mb-1">
                                                    <h3 class="font-bold text-lg text-brandPrimary"><?php echo htmlspecialchars($row['project_name']); ?></h3>
                                                    <?php if ($row['priority'] == 'High'): ?>
                                                        <span class="px-2 py-0.5 bg-red-100 text-red-600 text-[10px] font-black rounded-lg uppercase tracking-tighter">Urgent</span>
                                                    <?php endif; ?>
                                                </div>
                                                <p class="text-xs text-gray-400 font-medium">
                                                    Client: <span class="text-gray-600"><?php echo $row['company_name'] ?? 'Internal Project'; ?></span>
                                                </p>
                                            </div>
                                        </div>

                                        <div class="flex flex-row md:flex-col items-center md:items-end justify-between md:justify-start gap-2">
                                            <select onchange="updateStatus(<?php echo $row['id']; ?>, this.value)"
                                                class="text-[10px] font-bold uppercase tracking-widest px-4 py-2 rounded-full border-none ring-1 ring-gray-100 shadow-sm cursor-pointer
                                                <?php
                                                if ($row['status'] == 'Done') echo 'bg-emerald-50 text-emerald-600';
                                                elseif ($row['status'] == 'In Progress') echo 'bg-blue-50 text-blue-600';
                                                else echo 'bg-gray-100 text-gray-500';
                                                ?>">
                                                <option value="Pending" <?php echo $row['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                                <option value="In Progress" <?php echo $row['status'] == 'In Progress' ? 'selected' : ''; ?>>In Progress</option>
                                                <option value="Done" <?php echo $row['status'] == 'Done' ? 'selected' : ''; ?>>Done</option>
                                            </select>
                                            <p class="text-[11px] text-brandAccent font-bold italic">
                                                Due: <?php echo date('d M Y', strtotime($row['deadline_date'])); ?> • <?php echo date('H:i', strtotime($row['deadline_time'])); ?>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="mt-6 flex flex-col md:flex-row md:items-center justify-between pt-5 border-t border-gray-50 gap-4">
                                        <div class="flex flex-wrap gap-2">
                                            <?php
                                            $tags = json_decode($row['team_tags'], true);
                                            if (!empty($tags)) {
                                                foreach ($tags as $t_id) {
                                                    $t_query = mysqli_query($conn, "SELECT team_name, color_class FROM teams WHERE id = '$t_id'");
                                                    if ($t = mysqli_fetch_assoc($t_query)) {
                                                        echo "<span class='px-3 py-1 rounded-xl {$t['color_class']} bg-opacity-10 text-[9px] font-black uppercase tracking-wider' style='color: inherit;'>{$t['team_name']}</span>";
                                                    }
                                                }
                                            }
                                            ?>
                                        </div>

                                        <div class="flex items-center gap-3">
                                            <?php if (!empty($row['brief_link'])): ?>
                                                <a href="<?php echo $row['brief_link']; ?>" target="_blank" class="p-2 text-gray-400 hover:text-brandGold transition" title="Brief Link">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                                    </svg>
                                                </a>
                                            <?php endif; ?>

                                            <div class="h-4 w-[1px] bg-gray-100 mx-1"></div>

                                            <button onclick="confirmDelete(<?php echo $row['id']; ?>, '<?php echo $row['project_name']; ?>')" class="p-2 text-gray-300 hover:text-red-500 transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>

                                            <a href="project_timeline_edit.php?id=<?php echo $row['id']; ?>" class="px-4 py-2 bg-gray-50 text-brandPrimary text-xs font-bold rounded-xl hover:bg-brandPrimary hover:text-white transition-all">
                                                Edit Task
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <div class="text-center py-20 bg-white rounded-[32px] border-2 border-dashed border-gray-100">
                                <p class="text-gray-400 font-medium">No tasks found. Start by creating a new one!</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <?php include_once '_footer.php'; ?>
            </main>
        </div>
    </div>

    <div id="modal-delete" class="fixed inset-0 z-[110] hidden overflow-y-auto">
        <div class="fixed inset-0 bg-brandPrimary/40 backdrop-blur-sm transition-opacity" onclick="closeModalDelete()"></div>

        <div class="flex min-h-full items-center justify-center p-4 text-center">
            <div class="relative transform overflow-hidden rounded-[32px] bg-white p-8 text-left shadow-2xl transition-all w-full max-w-sm">
                <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-red-50 mb-6">
                    <svg class="h-10 w-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>

                <div class="text-center">
                    <h3 class="text-xl font-bold text-brandPrimary mb-2">Delete Task?</h3>
                    <p class="text-sm text-gray-500 mb-8">
                        Apakah Anda yakin ingin menghapus proyek <span id="delete-client-name" class="font-bold text-brandAccent"></span>? Data yang dihapus tidak dapat dikembalikan.
                    </p>
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="closeModalDelete()" class="flex-1 py-4 bg-gray-50 text-gray-400 rounded-2xl font-bold hover:bg-gray-100 transition">
                        Cancel
                    </button>
                    <a id="btn-confirm-delete" href="#" class="flex-1 py-4 bg-red-500 text-white rounded-2xl font-bold shadow-lg shadow-red-200 hover:bg-red-600 transition text-center">
                        Delete
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function updateStatus(id, newStatus) {
            // Tampilkan loading sederhana pada kursor
            document.body.style.cursor = 'wait';

            fetch('process/process_update_status.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `id=${id}&status=${encodeURIComponent(newStatus)}`
                })
                .then(res => {
                    if (!res.ok) throw new Error('Network response was not ok');
                    return res.json();
                })
                .then(data => {
                    document.body.style.cursor = 'default';
                    if (data.success) {
                        // Berikan feedback instan sebelum reload (opsional)
                        // Atau langsung reload untuk memperbarui log dan warna badge
                        window.location.href = `project_timeline.php?status=success&msg=Status updated to ${newStatus}`;
                    } else {
                        alert("Gagal update: " + data.message);
                    }
                })
                .catch(error => {
                    document.body.style.cursor = 'default';
                    console.error('Error:', error);
                    alert("Terjadi kesalahan sistem saat update status.");
                });
        }

        function confirmDelete(id, name) {
            const modal = document.getElementById('modal-delete');
            document.getElementById('delete-client-name').innerText = name;
            document.getElementById('btn-confirm-delete').href = `process/process_delete_timeline.php?id=${id}`;
            modal.classList.remove('hidden');
        }

        function closeModalDelete() {
            document.getElementById('modal-delete').classList.add('hidden');
        }
    </script>
</body>

</html>