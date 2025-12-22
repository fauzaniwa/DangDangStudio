<?php
require_once './process/config.php';
session_start();

// Proteksi Halaman
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Ambil Data Testimoni dari Database
$query = "SELECT * FROM testimonials ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Testimonials - DangDang Studio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="assets/tailwind-config.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
    <style>
        .animate-fade-in { animation: fadeIn 0.4s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .animate-slide-up { animation: slideUp 0.5s ease-out; }
        @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="bg-[#FBFBFB] text-slate-800">

    <div class="flex flex-col h-screen overflow-hidden">
        <?php include_once '_header.php'; ?>
        <div class="flex flex-1 overflow-hidden">
            <?php include_once '_aside.php'; ?>
            <main class="flex-1 flex flex-col min-w-0 overflow-y-auto">
                <?php include_once '_topbar.php'; ?>

                <div class="p-6 md:p-10 flex-1">
                    
                    <?php if (isset($_GET['status'])): ?>
                        <div id="alert-feedback" class="mb-8 animate-slide-up">
                            <?php if ($_GET['status'] == 'success'): ?>
                                <div class="flex items-center gap-4 p-5 bg-emerald-50 border border-emerald-100 rounded-[24px]">
                                    <div class="w-12 h-12 bg-emerald-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-emerald-200">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-emerald-900">Berhasil!</h4>
                                        <p class="text-sm text-emerald-700/80">Testimonial baru telah ditambahkan.</p>
                                    </div>
                                    <button onclick="this.parentElement.parentElement.remove()" class="ml-auto text-emerald-400 hover:text-emerald-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>
                            <?php elseif ($_GET['status'] == 'deleted'): ?>
                                <div class="flex items-center gap-4 p-5 bg-amber-50 border border-amber-100 rounded-[24px]">
                                    <div class="w-12 h-12 bg-amber-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-amber-200">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-amber-900">Data Dihapus</h4>
                                        <p class="text-sm text-amber-700/80">Testimonial telah dihapus secara permanen dari sistem.</p>
                                    </div>
                                    <button onclick="this.parentElement.parentElement.remove()" class="ml-auto text-amber-400 hover:text-amber-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>
                            <?php elseif ($_GET['status'] == 'updated'): ?>
                                <div class="flex items-center gap-4 p-5 bg-emerald-50 border border-emerald-100 rounded-[24px]">
                                    <div class="w-12 h-12 bg-emerald-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-emerald-200">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-emerald-900">Berhasil!</h4>
                                        <p class="text-sm text-emerald-700/80">Data testimonial telah diubah.</p>
                                    </div>
                                    <button onclick="this.parentElement.parentElement.remove()" class="ml-auto text-emerald-400 hover:text-emerald-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
                        <div>
                            <h1 class="text-3xl font-extrabold text-brandPrimary tracking-tight">Player Reviews</h1>
                            <p class="text-sm text-gray-500 font-medium">Apa kata mereka tentang karya DangDang Studio.</p>
                        </div>
                        <a href="testimonial_add.php" class="flex items-center gap-2 px-6 py-3 bg-brandGold text-white rounded-2xl font-bold shadow-lg shadow-brandGold/20 hover:scale-105 transition-all text-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Add Testimonial
                        </a>
                    </div>

                    <div class="columns-1 md:columns-2 lg:columns-3 gap-6 space-y-6">
                        <?php while ($tm = mysqli_fetch_assoc($result)): ?>
                            <div class="break-inside-avoid bg-white rounded-[32px] p-8 border border-gray-100 shadow-sm hover:shadow-xl hover:shadow-brandPrimary/5 transition-all duration-300 group relative animate-fade-in">
                                <div class="absolute top-6 right-8 text-gray-50 group-hover:text-brandGold/10 transition-colors">
                                    <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21L14.017 18C14.017 16.8954 14.9124 16 16.017 16H19.017C19.5693 16 20.017 15.5523 20.017 15V9C20.017 8.44772 19.5693 8 19.017 8H15.017C14.4647 8 14.017 8.44772 14.017 9V12C14.017 12.5523 13.5693 13 13.017 13H11.017V21H14.017ZM5.017 21L5.017 18C5.017 16.8954 5.91243 16 7.017 16H10.017C10.5693 16 11.017 15.5523 11.017 15V9C11.017 8.44772 10.5693 8 10.017 8H6.017C5.46472 8 5.017 8.44772 5.017 9V12C5.017 12.5523 4.5693 13 4.017 13H2.017V21H5.017Z"></path></svg>
                                </div>

                                <div class="flex gap-1 mb-4">
                                    <?php for($i=0; $i<$tm['stars']; $i++): ?>
                                        <svg class="w-4 h-4 text-brandGold" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    <?php endfor; ?>
                                </div>

                                <p class="text-sm leading-relaxed text-gray-600 mb-8 relative z-10 italic">"<?php echo htmlspecialchars($tm['content']); ?>"</p>

                                <div class="flex items-center justify-between pt-6 border-t border-gray-50">
                                    <div class="flex items-center gap-3">
                                        <?php if($tm['avatar']): ?>
                                            <img src="../uploads/testimonials/<?php echo $tm['avatar']; ?>" class="w-10 h-10 rounded-full border-2 border-white shadow-sm object-cover">
                                        <?php else: ?>
                                            <div class="w-10 h-10 rounded-full bg-brandGold text-white flex items-center justify-center text-xs font-bold">
                                                <?php echo strtoupper(substr($tm['name'], 0, 1)); ?>
                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <p class="text-xs font-extrabold text-brandPrimary"><?php echo htmlspecialchars($tm['name']); ?></p>
                                            <p class="text-[10px] text-gray-400 font-medium"><?php echo htmlspecialchars($tm['role']); ?></p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button onclick='openEditModal(<?php echo json_encode($tm); ?>)' class="p-2 text-gray-400 hover:text-brandPrimary transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </button>
                                        <button onclick="confirmDeleteTestimonial('<?php echo $tm['id']; ?>', '<?php echo addslashes($tm['name']); ?>')" class="p-2 text-gray-400 hover:text-brandAccent transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
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

    <div id="modal-edit" class="fixed inset-0 z-[100] hidden overflow-y-auto">
        <div class="fixed inset-0 bg-brandPrimary/40 backdrop-blur-sm transition-opacity" onclick="closeModalEdit()"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative transform overflow-hidden rounded-[32px] bg-white p-8 md:p-10 shadow-2xl transition-all w-full max-w-2xl animate-fade-in">
                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-2xl font-bold text-brandPrimary flex items-center gap-3">
                        <span class="w-2 h-8 bg-brandGold rounded-full"></span> Edit Review
                    </h2>
                    <button onclick="closeModalEdit()" class="text-gray-400 hover:text-brandPrimary">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form action="process/process_edit_testimonial.php" method="POST" enctype="multipart/form-data" class="space-y-6">
                    <input type="hidden" name="id" id="edit-id">
                    
                    <div class="flex flex-col md:flex-row gap-6 items-center bg-gray-50 p-6 rounded-3xl border border-gray-100">
                        <div class="relative group">
                            <div class="w-20 h-20 rounded-full bg-white border-2 border-brandGold flex items-center justify-center overflow-hidden">
                                <img id="edit-avatar-prev" class="w-full h-full object-cover">
                            </div>
                            <input type="file" name="avatar" accept="image/*" onchange="previewEditAvatar(this)" class="absolute inset-0 opacity-0 cursor-pointer">
                        </div>
                        <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-4 w-full">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1 ml-1">Full Name</label>
                                <input type="text" name="name" id="edit-name" required class="w-full px-5 py-3 rounded-xl border border-gray-200 focus:border-brandGold outline-none font-bold text-brandPrimary text-sm">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1 ml-1">Role</label>
                                <input type="text" name="role" id="edit-role" required class="w-full px-5 py-3 rounded-xl border border-gray-200 focus:border-brandGold outline-none font-bold text-brandPrimary text-sm">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Rating</label>
                        <div class="flex gap-2">
                            <input type="hidden" name="stars" id="edit-stars-input">
                            <?php for($i=1; $i<=5; $i++): ?>
                                <button type="button" onclick="setEditRating(<?php echo $i; ?>)" class="edit-star-btn text-gray-200 transition-colors" data-value="<?php echo $i; ?>">
                                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                </button>
                            <?php endfor; ?>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Content</label>
                        <textarea name="content" id="edit-content" required rows="4" class="w-full px-5 py-4 rounded-2xl border border-gray-200 focus:border-brandGold outline-none text-sm text-gray-600 italic"></textarea>
                    </div>

                    <div class="flex gap-3 pt-4">
                        <button type="button" onclick="closeModalEdit()" class="flex-1 py-4 bg-gray-50 text-gray-400 rounded-2xl font-bold">Cancel</button>
                        <button type="submit" class="flex-1 py-4 bg-brandGold text-white rounded-2xl font-bold shadow-lg shadow-brandGold/20">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="modal-delete" class="fixed inset-0 z-[100] hidden overflow-y-auto">
        <div class="fixed inset-0 bg-brandPrimary/40 backdrop-blur-sm transition-opacity" onclick="closeModalDelete()"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative transform overflow-hidden rounded-[32px] bg-white p-8 shadow-2xl transition-all w-full max-w-md animate-fade-in text-center">
                <div class="w-20 h-20 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-brandAccent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-brandPrimary mb-2">Hapus Testimoni?</h3>
                <p class="text-sm text-gray-500 mb-8">Testimoni dari <span id="delete-name" class="font-bold"></span> akan dihapus permanen.</p>
                <div class="flex gap-3">
                    <button onclick="closeModalDelete()" class="flex-1 py-4 bg-gray-50 text-gray-400 rounded-2xl font-bold">Batal</button>
                    <a id="btn-confirm-delete" href="#" class="flex-1 py-4 bg-brandAccent text-white rounded-2xl font-bold shadow-lg shadow-brandAccent/20 text-center">Hapus</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // --- LOGIKA EDIT ---
        function openEditModal(data) {
            document.getElementById('edit-id').value = data.id;
            document.getElementById('edit-name').value = data.name;
            document.getElementById('edit-role').value = data.role;
            document.getElementById('edit-content').value = data.content;
            
            const imgPrev = document.getElementById('edit-avatar-prev');
            if(data.avatar) {
                imgPrev.src = '../uploads/testimonials/' + data.avatar;
            } else {
                imgPrev.src = 'assets/img/default-avatar.png'; // Pastikan file ini ada
            }

            setEditRating(data.stars);
            document.getElementById('modal-edit').classList.remove('hidden');
        }

        function closeModalEdit() {
            document.getElementById('modal-edit').classList.add('hidden');
        }

        function setEditRating(val) {
            document.getElementById('edit-stars-input').value = val;
            const stars = document.querySelectorAll('.edit-star-btn');
            stars.forEach(star => {
                const starVal = parseInt(star.getAttribute('data-value'));
                star.classList.toggle('text-brandGold', starVal <= val);
                star.classList.toggle('text-gray-200', starVal > val);
            });
        }

        function previewEditAvatar(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => document.getElementById('edit-avatar-prev').src = e.target.result;
                reader.readAsDataURL(input.files[0]);
            }
        }

        // --- LOGIKA DELETE ---
        function confirmDeleteTestimonial(id, name) {
            document.getElementById('delete-name').innerText = name;
            document.getElementById('btn-confirm-delete').href = 'process/process_delete_testimonial.php?id=' + id;
            document.getElementById('modal-delete').classList.remove('hidden');
        }

        function closeModalDelete() {
            document.getElementById('modal-delete').classList.add('hidden');
        }

        // --- AUTO CLOSE ALERT ---
        window.addEventListener('load', function() {
            const alert = document.getElementById('alert-feedback');
            if (alert) {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-10px)';
                    alert.style.transition = 'all 0.5s ease';
                    setTimeout(() => alert.remove(), 500);
                }, 5000);
            }
        });
    </script>
</body>
</html>