<?php
require_once 'process/config.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login");
    exit();
}

$query = "SELECT * FROM partners ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Partner List - DangDang Studio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="assets/tailwind-config.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="bg-[#F8FAFC] text-slate-800 font-['Plus_Jakarta_Sans']">

    <div class="flex flex-col h-screen overflow-hidden">
        <?php include_once '_header.php'; ?>
        <div class="flex flex-1 overflow-hidden">
            <?php include_once '_aside.php'; ?>
            <main class="flex-1 flex flex-col min-w-0 overflow-y-auto">
                <?php include_once '_topbar.php'; ?>

                <div class="p-6 md:p-10 flex-1">
                    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
                        <div>
                            <h1 class="text-4xl font-black text-brandPrimary tracking-tight">Our Partners</h1>
                            <p class="text-sm text-gray-500 font-medium mt-1">Manage collaborators and industry networks.</p>
                        </div>
                        <button onclick="openPartnerModal()" class="flex items-center gap-2 px-6 py-4 bg-brandPrimary text-white rounded-2xl font-bold shadow-xl shadow-brandPrimary/20 hover:scale-[1.02] active:scale-95 transition-all text-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Add New Partner
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        <?php if (mysqli_num_rows($result) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <div class="bg-white p-6 rounded-[32px] border border-gray-100 shadow-sm hover:shadow-xl transition-all group relative">
                                    <div class="flex flex-col items-center text-center">
                                        <div class="w-24 h-24 rounded-2xl bg-gray-50 flex items-center justify-center mb-4 overflow-hidden border border-gray-100 p-4">
                                            <img src="../uploads/partners/<?php echo $row['partner_logo'] ?: 'default_logo.png'; ?>" 
                                                 class="max-w-full max-h-full object-contain group-hover:scale-110 transition-transform duration-500"
                                                 onerror="this.src='https://ui-avatars.com/api/?name=<?php echo urlencode($row['partner_name']); ?>&background=random'">
                                        </div>
                                        <h3 class="font-black text-brandPrimary text-lg mb-1"><?php echo htmlspecialchars($row['partner_name']); ?></h3>
                                        <span class="px-3 py-1 bg-brandPrimary/5 text-brandPrimary text-[10px] font-bold rounded-full uppercase tracking-widest mb-4 italic">
                                            <?php echo htmlspecialchars($row['cooperation_type']); ?>
                                        </span>
                                    </div>
                                    
                                    <div class="mt-6 flex items-center justify-center gap-2 pt-4 border-t border-gray-50">
                                        <button onclick='editPartner(<?php echo json_encode($row); ?>)' class="p-2 text-gray-400 hover:text-brandPrimary hover:bg-gray-50 rounded-xl transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>
                                        <button onclick="confirmDeletePartner('<?php echo $row['id']; ?>', '<?php echo addslashes($row['partner_name']); ?>')" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <div class="col-span-full py-20 text-center bg-white rounded-[40px] border border-dashed border-gray-200 text-gray-400 font-bold">
                                No partners found. Start by adding one!
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <?php include_once '_footer.php'; ?>
            </main>
        </div>
    </div>

    <div id="partnerModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
        <div class="fixed inset-0 bg-brandPrimary/40 backdrop-blur-sm" onclick="closePartnerModal()"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <form action="process/process_partner.php" method="POST" enctype="multipart/form-data" class="relative bg-white w-full max-w-lg rounded-[40px] shadow-2xl p-8 md:p-10 animate-fade-in">
                <input type="hidden" name="id" id="partner_id">
                
                <div class="flex justify-between items-center mb-8">
                    <h2 id="modalTitle" class="text-2xl font-black text-brandPrimary">Add Partner</h2>
                    <button type="button" onclick="closePartnerModal()" class="text-gray-400 hover:text-brandPrimary transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                
                <div class="space-y-6">
                    <div class="flex flex-col items-center gap-4 p-6 bg-gray-50 rounded-[32px] border border-dashed border-gray-200">
                        <div class="w-28 h-28 rounded-2xl bg-white flex items-center justify-center overflow-hidden border border-gray-100 shadow-sm">
                            <img id="logo-preview" src="https://ui-avatars.com/api/?name=Logo&background=F1F5F9&color=CBD5E1" class="max-w-full max-h-full object-contain">
                        </div>
                        <input type="file" name="partner_logo" id="partner_logo_input" accept="image/*" class="hidden">
                        <button type="button" onclick="document.getElementById('partner_logo_input').click()" class="text-[10px] font-black uppercase text-brandPrimary bg-white px-4 py-2 rounded-full border border-gray-100 shadow-sm">Change Logo</button>
                    </div>

                    <div>
                        <label class="text-[10px] font-bold uppercase text-gray-400 tracking-widest ml-1">Partner Name</label>
                        <input type="text" name="partner_name" id="partner_name" required class="w-full bg-gray-50 border-none rounded-2xl px-5 py-4 text-sm focus:ring-2 focus:ring-brandPrimary transition-all mt-1">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-[10px] font-bold uppercase text-gray-400 tracking-widest ml-1">Type</label>
                            <select name="cooperation_type" id="cooperation_type" class="w-full bg-gray-50 border-none rounded-2xl px-5 py-4 text-sm focus:ring-2 focus:ring-brandPrimary transition-all mt-1">
                                <option value="Publisher">Publisher</option>
                                <option value="Outsourcing">Outsourcing</option>
                                <option value="Co-Development">Co-Development</option>
                                <option value="Sponsor">Sponsor</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold uppercase text-gray-400 tracking-widest ml-1">Since</label>
                            <input type="date" name="partnership_date" id="partnership_date" class="w-full bg-gray-50 border-none rounded-2xl px-5 py-4 text-sm focus:ring-2 focus:ring-brandPrimary transition-all mt-1">
                        </div>
                    </div>

                    <div>
                        <label class="text-[10px] font-bold uppercase text-gray-400 tracking-widest ml-1">Description</label>
                        <textarea name="description" id="description" rows="3" class="w-full bg-gray-50 border-none rounded-2xl px-5 py-4 text-sm focus:ring-2 focus:ring-brandPrimary transition-all mt-1"></textarea>
                    </div>
                </div>

                <div class="flex gap-4 mt-10">
                    <button type="button" onclick="closePartnerModal()" class="flex-1 py-4 text-sm font-bold text-gray-400 hover:text-gray-600 transition">Cancel</button>
                    <button type="submit" class="flex-1 py-4 bg-brandPrimary text-white rounded-2xl font-bold text-sm shadow-xl shadow-brandPrimary/20 hover:bg-opacity-90 transition">Save Partner</button>
                </div>
            </form>
        </div>
    </div>

    <div id="deletePartnerModal" class="fixed inset-0 z-[110] hidden overflow-y-auto">
        <div class="fixed inset-0 bg-brandPrimary/40 backdrop-blur-sm" onclick="closeDeleteModal()"></div>
        <div class="flex min-h-full items-center justify-center p-4 text-center">
            <div class="relative bg-white p-8 rounded-[40px] shadow-2xl w-full max-w-sm animate-fade-in">
                <div class="w-20 h-20 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-6 text-red-500">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </div>
                <h3 class="text-xl font-black text-brandPrimary mb-2">Delete Partner?</h3>
                <p class="text-sm text-gray-400 mb-8">Anda akan menghapus <span id="delete-partner-name" class="font-bold text-brandGold"></span> secara permanen.</p>
                <div class="flex gap-3">
                    <button onclick="closeDeleteModal()" class="flex-1 py-4 text-sm font-bold text-gray-400 hover:text-gray-600">Batal</button>
                    <a id="btn-confirm-delete-partner" href="#" class="flex-1 py-4 bg-red-600 text-white rounded-2xl font-bold text-sm shadow-lg shadow-red-200">Ya, Hapus</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // --- LOGIC PREVIEW GAMBAR ---
        document.getElementById('partner_logo_input').addEventListener('change', function(event) {
            const reader = new FileReader();
            reader.onload = function() {
                if (reader.readyState === 2) {
                    document.getElementById('logo-preview').src = reader.result;
                }
            }
            if (event.target.files[0]) {
                reader.readAsDataURL(event.target.files[0]);
            }
        });

        // --- LOGIC MAIN MODAL ---
        function openPartnerModal() {
            document.getElementById('modalTitle').innerText = "Add Partner";
            document.getElementById('partner_id').value = "";
            document.getElementById('partner_name').value = "";
            document.getElementById('partnership_date').value = "";
            document.getElementById('description').value = "";
            document.getElementById('logo-preview').src = "https://ui-avatars.com/api/?name=Logo&background=F1F5F9&color=CBD5E1";
            document.getElementById('partnerModal').classList.remove('hidden');
        }

        function editPartner(data) {
            document.getElementById('modalTitle').innerText = "Edit Partner";
            document.getElementById('partner_id').value = data.id;
            document.getElementById('partner_name').value = data.partner_name;
            document.getElementById('cooperation_type').value = data.cooperation_type;
            document.getElementById('partnership_date').value = data.partnership_date;
            document.getElementById('description').value = data.description;
            
            const logoPath = data.partner_logo ? '../uploads/partners/' + data.partner_logo : 'https://ui-avatars.com/api/?name=' + encodeURIComponent(data.partner_name);
            document.getElementById('logo-preview').src = logoPath;
            document.getElementById('partnerModal').classList.remove('hidden');
        }

        function closePartnerModal() {
            document.getElementById('partnerModal').classList.add('hidden');
        }

        // --- LOGIC DELETE MODAL ---
        function confirmDeletePartner(id, name) {
            document.getElementById('delete-partner-name').innerText = name;
            document.getElementById('btn-confirm-delete-partner').href = 'process/process_delete_partner.php?id=' + id;
            document.getElementById('deletePartnerModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deletePartnerModal').classList.add('hidden');
        }
    </script>
</body>
</html>