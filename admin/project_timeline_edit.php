<?php
require_once 'process/config.php';
session_start();

// 1. Proteksi Admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// 2. Ambil dan Validasi ID dari URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    header("Location: project_timeline.php?status=error&msg=Invalid Project ID");
    exit();
}

// 3. Ambil data dengan Prepared Statement
$stmt = $conn->prepare("SELECT * FROM project_timelines WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $data = $result->fetch_assoc();
} else {
    header("Location: project_timeline.php?status=error&msg=Project not found in database");
    exit();
}

// 4. Inisialisasi variabel untuk menghindari error Undefined Index / Null
$project_name  = $data['project_name'] ?? '';
$client_id     = $data['client_id'] ?? '';
$priority      = $data['priority'] ?? 'Normal';
$deadline_date = $data['deadline_date'] ?? '';
$deadline_time = $data['deadline_time'] ?? '';
$brief_link    = $data['brief_link'] ?? '';
$color_label   = $data['color_label'] ?? 'brandTeal';
$notes         = $data['notes'] ?? '';
$team_tags     = json_decode($data['team_tags'] ?? '[]', true) ?: [];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Task - DangDang Studio</title>
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
                    <a href="project_timeline.php" class="inline-flex items-center gap-2 text-sm font-bold text-gray-400 hover:text-brandPrimary transition mb-6">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Discard & Back
                    </a>

                    <div class="max-w-4xl bg-white rounded-[32px] shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-8 border-b border-gray-50 bg-gradient-to-r from-white to-gray-50/50">
                            <h1 class="text-2xl font-bold text-brandPrimary">Edit Task Details</h1>
                            <p class="text-sm text-gray-500 font-medium">Updating project: <span class="text-brandAccent"><?php echo htmlspecialchars($project_name); ?></span></p>
                        </div>

                        <form action="process/process_edit_timeline.php" method="POST" class="p-8 space-y-8">
                            <input type="hidden" name="id" value="<?php echo $id; ?>">
                            
                            <div class="space-y-4">
                                <h2 class="text-xs font-bold uppercase tracking-[0.2em] text-brandTeal">General Information</h2>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="md:col-span-2">
                                        <label class="block text-xs font-bold text-gray-400 mb-2 uppercase">Project Name</label>
                                        <input type="text" name="project_name" required value="<?php echo htmlspecialchars($project_name); ?>"
                                            class="w-full px-5 py-3 rounded-2xl border border-gray-100 bg-gray-50/30 focus:bg-white focus:border-brandPrimary focus:ring-4 focus:ring-brandPrimary/5 outline-none transition">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-400 mb-2 uppercase">Client Reference</label>
                                        <select name="client_id" class="w-full px-5 py-3 rounded-2xl border border-gray-100 bg-gray-50/30 focus:bg-white focus:border-brandPrimary outline-none transition appearance-none">
                                            <option value="">-- Internal Project --</option>
                                            <?php
                                            $client_query = mysqli_query($conn, "SELECT id, company_name FROM clients ORDER BY company_name ASC");
                                            while($row = mysqli_fetch_assoc($client_query)) {
                                                $selected = ($row['id'] == $client_id) ? 'selected' : '';
                                                echo "<option value='{$row['id']}' $selected>{$row['company_name']}</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-400 mb-2 uppercase">Task Priority</label>
                                        <select name="priority" class="w-full px-5 py-3 rounded-2xl border border-gray-100 bg-gray-50/30 focus:bg-white focus:border-brandPrimary outline-none transition appearance-none text-sm font-bold">
                                            <option value="Normal" <?php echo $priority == 'Normal' ? 'selected' : ''; ?>>Normal</option>
                                            <option value="Medium" <?php echo $priority == 'Medium' ? 'selected' : ''; ?>>Medium</option>
                                            <option value="High" <?php echo $priority == 'High' ? 'selected' : ''; ?>>High / Urgent</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <hr class="border-gray-50">

                            <div class="space-y-4">
                                <h2 class="text-xs font-bold uppercase tracking-[0.2em] text-brandGold">Deadline & Resources</h2>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-400 mb-2 uppercase">Deadline Date</label>
                                        <input type="date" name="deadline_date" required value="<?php echo $deadline_date; ?>"
                                            class="w-full px-5 py-3 rounded-2xl border border-gray-100 bg-gray-50/30 focus:bg-white focus:border-brandGold outline-none transition">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-400 mb-2 uppercase">Deadline Time</label>
                                        <input type="time" name="deadline_time" required value="<?php echo !empty($deadline_time) ? date('H:i', strtotime($deadline_time)) : '00:00'; ?>"
                                            class="w-full px-5 py-3 rounded-2xl border border-gray-100 bg-gray-50/30 focus:bg-white focus:border-brandGold outline-none transition">
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-xs font-bold text-gray-400 mb-2 uppercase text-brandAccent">Brief Link (Google Drive / Notion)</label>
                                        <input type="url" name="brief_link" value="<?php echo htmlspecialchars($brief_link); ?>" placeholder="https://drive.google.com/..."
                                            class="w-full px-5 py-3 rounded-2xl border border-gray-100 bg-gray-50/30 focus:bg-white focus:border-brandGold outline-none transition">
                                    </div>
                                </div>
                            </div>

                            <hr class="border-gray-50">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div>
                                    <label class="block text-xs font-bold text-gray-400 mb-4 uppercase tracking-widest">Assign Team</label>
                                    <div id="team-checkbox-container" class="grid grid-cols-2 gap-3">
                                        <?php
                                        $team_query = mysqli_query($conn, "SELECT * FROM teams ORDER BY team_name ASC");
                                        while ($team = mysqli_fetch_assoc($team_query)) : 
                                            $checked = in_array($team['id'], $team_tags) ? 'checked' : '';
                                        ?>
                                            <label class="relative flex items-center group cursor-pointer">
                                                <input type="checkbox" name="team_tag[]" value="<?php echo $team['id']; ?>" class="peer hidden" <?php echo $checked; ?>>
                                                <div class="w-full p-3 rounded-2xl border border-gray-100 bg-gray-50/50 peer-checked:border-brandPrimary peer-checked:bg-white peer-checked:shadow-sm transition-all flex items-center gap-2 text-[10px] font-bold text-gray-400 peer-checked:text-brandPrimary uppercase">
                                                    <div class="w-2 h-2 rounded-full <?php echo $team['color_class']; ?>"></div>
                                                    <?php echo htmlspecialchars($team['team_name']); ?>
                                                </div>
                                            </label>
                                        <?php endwhile; ?>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-400 mb-4 uppercase">Timeline Label Color</label>
                                    <div class="flex gap-4">
                                        <?php 
                                        $labels = ['brandTeal', 'brandGold', 'brandAccent', 'brandPrimary'];
                                        foreach($labels as $color_opt): 
                                            $checked_radio = ($color_label == $color_opt) ? 'checked' : '';
                                        ?>
                                            <label class="cursor-pointer">
                                                <input type="radio" name="color_label" value="<?php echo $color_opt; ?>" class="hidden peer" <?php echo $checked_radio; ?>>
                                                <div class="w-12 h-12 rounded-2xl bg-<?php echo $color_opt; ?> border-4 border-transparent peer-checked:border-white peer-checked:ring-4 peer-checked:ring-<?php echo $color_opt; ?>/20 shadow-lg transition-all hover:scale-110"></div>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-4">
                                <label class="block text-xs font-bold text-gray-400 mb-2 uppercase">Project Notes / Instructions</label>
                                <textarea name="notes" rows="5" class="w-full px-5 py-4 rounded-[24px] border border-gray-100 bg-gray-50/30 focus:bg-white focus:border-brandPrimary outline-none transition text-sm leading-relaxed"><?php echo htmlspecialchars($notes); ?></textarea>
                            </div>

                            <div class="flex items-center justify-end gap-4 pt-6">
                                <a href="project_timeline.php" class="px-6 py-3 text-sm font-bold text-gray-400 hover:text-brandAccent transition">Cancel</a>
                                <button type="submit" class="px-10 py-4 bg-brandPrimary text-white rounded-2xl font-bold shadow-xl shadow-brandPrimary/20 hover:scale-105 active:scale-95 transition-all">
                                    Update Timeline
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