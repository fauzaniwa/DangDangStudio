<?php
require_once './process/config.php';
session_start();

// 1. Proteksi Halaman
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Query mengambil data artikel dan join dengan tabel admins menggunakan kolom fullname
$sql = "SELECT a.*, adm.fullname as author_name 
        FROM articles a 
        LEFT JOIN admins adm ON a.admin_id = adm.id 
        ORDER BY a.created_at DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Article Management - DangDang Studio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="assets/tailwind-config.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
    <style>
        .animate-fade-in { animation: fadeIn 0.3s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
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
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
                        <div>
                            <h1 class="text-3xl font-extrabold text-brandPrimary tracking-tight">Studio Articles</h1>
                            <p class="text-sm text-gray-500 font-medium">Kelola berita, dev-log, dan update terbaru studio.</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="relative hidden md:block">
                                <input type="text" id="searchInput" onkeyup="searchTable()" placeholder="Search article..." class="pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 text-xs focus:ring-2 focus:ring-brandGold outline-none w-64 transition-all">
                                <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <a href="article_add.php" class="flex items-center gap-2 px-6 py-3 bg-brandGold text-white rounded-2xl font-bold shadow-lg shadow-brandGold/20 hover:scale-105 transition-all text-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Write Article
                            </a>
                        </div>
                    </div>

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
                                        <p class="text-sm text-emerald-700/80">Artikel baru telah ditambahkan.</p>
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
                                        <p class="text-sm text-amber-700/80">Artikel telah dihapus secara permanen dari sistem.</p>
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
                                        <p class="text-sm text-emerald-700/80">Data artikel telah diubah.</p>
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

                    <div class="bg-white rounded-[32px] border border-gray-100 shadow-sm overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse" id="articleTable">
                                <thead>
                                    <tr class="bg-gray-50/50 border-b border-gray-100">
                                        <th class="px-8 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Cover & Title</th>
                                        <th class="px-6 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Category</th>
                                        <th class="px-6 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Author</th>
                                        <th class="px-6 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Status</th>
                                        <th class="px-6 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    <?php if (mysqli_num_rows($result) > 0) : ?>
                                        <?php while ($art = mysqli_fetch_assoc($result)) : ?>
                                            <tr class="group hover:bg-gray-50/50 transition-colors">
                                                <td class="px-8 py-5">
                                                    <div class="flex items-center gap-4">
                                                        <?php 
                                                        $cover_img = $art['cover_image'] ? "../uploads/articles/".$art['cover_image'] : "assets/img/placeholder.jpg"; 
                                                        ?>
                                                        <img src="<?php echo $cover_img; ?>" class="w-16 h-10 rounded-lg object-cover shadow-sm bg-gray-100">
                                                        <div>
                                                            <p class="text-sm font-bold text-brandPrimary group-hover:text-brandGold transition-colors"><?php echo htmlspecialchars($art['title']); ?></p>
                                                            <p class="text-[10px] text-gray-400 font-medium"><?php echo date('d M Y', strtotime($art['created_at'])); ?></p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-5">
                                                    <span class="px-3 py-1 bg-brandGold/5 text-brandGold text-[10px] font-bold rounded-full border border-brandGold/10">
                                                        <?php echo $art['category']; ?>
                                                    </span>
                                                </td>
                                                <td class="px-6 py-5">
                                                    <p class="text-xs font-bold text-brandPrimary"><?php echo htmlspecialchars($art['author_name'] ?? 'System'); ?></p>
                                                </td>
                                                <td class="px-6 py-5">
                                                    <?php if($art['status'] == 'Published'): ?>
                                                        <span class="flex items-center gap-1.5 text-brandTeal text-[10px] font-bold uppercase">
                                                            <span class="w-1.5 h-1.5 rounded-full bg-brandTeal animate-pulse"></span> Published
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="flex items-center gap-1.5 text-gray-400 text-[10px] font-bold uppercase">
                                                            <span class="w-1.5 h-1.5 rounded-full bg-gray-300"></span> Draft
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="px-6 py-5">
                                                    <div class="flex items-center justify-center gap-2">
                                                        <a href="article_edit.php?id=<?php echo $art['id']; ?>" class="w-9 h-9 flex items-center justify-center rounded-xl bg-gray-50 text-brandPrimary hover:bg-brandGold hover:text-white transition shadow-sm">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                                        </a>
                                                        <button onclick="confirmDeleteArticle('<?php echo $art['id']; ?>', '<?php echo addslashes(htmlspecialchars($art['title'])); ?>')" class="w-9 h-9 flex items-center justify-center rounded-xl bg-gray-50 text-brandAccent hover:bg-brandAccent hover:text-white transition shadow-sm">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="px-8 py-20 text-center">
                                                <p class="text-gray-400 font-medium">No articles found. Start writing your first story!</p>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
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
        <div class="flex min-h-full items-center justify-center p-4 text-center">
            <div class="relative transform overflow-hidden rounded-[32px] bg-white p-8 text-left shadow-2xl transition-all w-full max-w-md animate-fade-in">
                <div class="flex flex-col items-center">
                    <div class="w-20 h-20 bg-red-50 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-10 h-10 text-brandAccent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-brandPrimary mb-2">Delete Article?</h3>
                    <p class="text-sm text-gray-500 text-center mb-8">
                        Are you sure you want to delete <span id="delete-article-title" class="font-bold text-brandPrimary"></span>? This action cannot be undone.
                    </p>
                    <div class="flex gap-3 w-full">
                        <button onclick="closeModalDelete()" class="flex-1 py-4 bg-gray-50 text-gray-400 rounded-2xl font-bold hover:bg-gray-100 transition">Cancel</button>
                        <a id="btn-confirm-delete" href="#" class="flex-1 py-4 bg-brandAccent text-white rounded-2xl font-bold shadow-lg shadow-brandAccent/20 hover:scale-[1.02] transition-all text-center">Delete Now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Fungsi Pencarian Sederhana
        function searchTable() {
            let input = document.getElementById("searchInput");
            let filter = input.value.toUpperCase();
            let table = document.getElementById("articleTable");
            let tr = table.getElementsByTagName("tr");

            for (let i = 1; i < tr.length; i++) {
                let tdTitle = tr[i].getElementsByTagName("td")[0];
                if (tdTitle) {
                    let textValue = tdTitle.textContent || tdTitle.innerText;
                    tr[i].style.display = textValue.toUpperCase().indexOf(filter) > -1 ? "" : "none";
                }
            }
        }

        // Fungsi Modal
        function confirmDeleteArticle(id, title) {
            document.getElementById('delete-article-title').innerText = title;
            document.getElementById('btn-confirm-delete').href = "process/process_delete_article.php?id=" + id;
            document.getElementById('modal-delete').classList.remove('hidden');
        }

        function closeModalDelete() {
            document.getElementById('modal-delete').classList.add('hidden');
        }
    </script>
</body>
</html>