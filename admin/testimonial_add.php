<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Testimonial - DangDang Studio</title>
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
                    <div class="max-w-3xl mx-auto">
                        <a href="testimonials.php" class="inline-flex items-center gap-2 text-sm font-bold text-gray-400 hover:text-brandPrimary transition mb-8">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            Back to Reviews
                        </a>

                        <form action="process_add_testimonial.php" method="POST" enctype="multipart/form-data" class="space-y-8 pb-20">
                            
                            <div class="bg-white rounded-[32px] p-8 md:p-10 shadow-sm border border-gray-100 space-y-8">
                                <h2 class="text-xl font-bold text-brandPrimary flex items-center gap-3">
                                    <span class="w-2 h-8 bg-brandGold rounded-full"></span> Reviewer Profile
                                </h2>

                                <div class="flex flex-col md:flex-row gap-8 items-start md:items-center">
                                    <div class="relative group">
                                        <div class="w-24 h-24 rounded-full bg-gray-50 border-2 border-dashed border-gray-200 flex items-center justify-center overflow-hidden transition-all group-hover:border-brandGold shadow-sm">
                                            <input type="file" name="avatar" accept="image/*" onchange="previewAvatar(this)" class="absolute inset-0 opacity-0 cursor-pointer z-20">
                                            <img id="avatar-prev" class="absolute inset-0 w-full h-full object-cover hidden z-10">
                                            <svg id="avatar-placeholder" class="w-8 h-8 text-gray-300 z-0" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                                        </div>
                                        <p class="text-[9px] font-bold text-gray-400 text-center mt-3 uppercase tracking-tighter">Click to Upload</p>
                                    </div>

                                    <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-6 w-full">
                                        <div>
                                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1 tracking-widest">Full Name</label>
                                            <input type="text" name="name" required placeholder="Ex: Alex Johnson" class="w-full px-6 py-4 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-brandGold outline-none transition font-bold text-brandPrimary">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1 tracking-widest">Role / Occupation</label>
                                            <input type="text" name="role" required placeholder="Ex: Professional Gamer" class="w-full px-6 py-4 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-brandGold outline-none transition font-bold text-brandPrimary">
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-3 ml-1 tracking-widest">Player Rating</label>
                                    <div class="flex gap-2" id="star-container">
                                        <input type="hidden" name="stars" id="stars-input" value="5">
                                        <?php for($i=1; $i<=5; $i++): ?>
                                            <button type="button" onclick="setRating(<?php echo $i; ?>)" class="star-btn p-1 text-brandGold transition hover:scale-125" data-value="<?php echo $i; ?>">
                                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                            </button>
                                        <?php endfor; ?>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-3 ml-1 tracking-widest">Testimonial Content</label>
                                    <textarea name="content" required rows="6" placeholder="Paste the review or testimonial here..." class="w-full px-6 py-5 rounded-3xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-brandGold outline-none transition text-sm text-gray-600 leading-relaxed italic"></textarea>
                                </div>

                                <div class="pt-6">
                                    <button type="submit" class="w-full py-5 bg-brandGold text-white rounded-[24px] font-bold text-lg shadow-xl shadow-brandGold/30 hover:scale-[1.02] active:scale-95 transition-all">
                                        Publish Testimonial
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <?php include_once '_footer.php'; ?>
            </main>
        </div>
    </div>

    <script src="assets/script.js"></script>
    <script>
        // Preview Avatar
        function previewAvatar(input) {
            const preview = document.getElementById('avatar-prev');
            const placeholder = document.getElementById('avatar-placeholder');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Star Rating Logic
        function setRating(val) {
            const input = document.getElementById('stars-input');
            const stars = document.querySelectorAll('.star-btn');
            input.value = val;
            
            stars.forEach(star => {
                const starVal = parseInt(star.getAttribute('data-value'));
                if(starVal <= val) {
                    star.classList.remove('text-gray-200');
                    star.classList.add('text-brandGold');
                } else {
                    star.classList.remove('text-brandGold');
                    star.classList.add('text-gray-200');
                }
            });
        }

        // Initialize rating as 5 stars
        setRating(5);
    </script>
</body>
</html>