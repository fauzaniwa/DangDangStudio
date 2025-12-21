const menuBtn = document.getElementById("menuBtn");
const sidebar = document.getElementById("sidebar");
const overlay = document.getElementById("overlay");

function toggleMenu() {
  sidebar.classList.toggle("-translate-x-full");
  overlay.classList.toggle("hidden");
}

menuBtn.addEventListener("click", toggleMenu);
overlay.addEventListener("click", toggleMenu);

// Dropdown Profile Logic
const profileBtn = document.getElementById("profileBtn");
const dropdownMenu = document.getElementById("dropdownMenu");

// Toggle dropdown saat tombol diklik
profileBtn.addEventListener("click", (e) => {
  e.stopPropagation(); // Mencegah event bubbling
  dropdownMenu.classList.toggle("hidden");
});

// Menutup dropdown jika user mengklik di mana saja di luar menu
window.addEventListener("click", (e) => {
  if (!profileBtn.contains(e.target)) {
    dropdownMenu.classList.add("hidden");
  }
});
// Function untuk Toggle Modal Transaksi
function toggleModal() {
    const modal = document.getElementById('transactionModal');
    if (modal.classList.contains('hidden')) {
        modal.classList.remove('hidden');
        // Tambahkan sedikit delay untuk animasi (opsional)
        setTimeout(() => modal.classList.add('opacity-100'), 10);
    } else {
        modal.classList.add('hidden');
    }
}

// Tutup modal jika user klik di area luar (overlay)
window.onclick = function(event) {
    const modal = document.getElementById('transactionModal');
    if (event.target == modal) {
        toggleModal();
    }
}


/**
 * DangDang Studio - Core Dashboard Scripts
 */

// Global Variables untuk Modal
const modalDelete = document.getElementById('modal-delete');
const deleteTitleSpan = document.getElementById('delete-article-title');
const btnConfirmDelete = document.getElementById('btn-confirm-delete');

/**
 * 1. LOGIKA MODAL PENGHAPUSAN
 * Fungsi untuk membuka modal konfirmasi hapus secara dinamis
 * @param {string|number} id - ID data yang akan dihapus
 * @param {string} title - Judul/Nama data untuk ditampilkan di modal
 * @param {string} type - Tipe data (optional, default: article)
 */
function confirmDeleteArticle(id, title) {
    if (!modalDelete) return;

    // Masukkan judul data ke dalam teks konfirmasi
    if (deleteTitleSpan) {
        deleteTitleSpan.innerText = `"${title}"`;
    }
    
    // Arahkan link 'Ya, Hapus' ke file proses PHP yang sesuai
    if (btnConfirmDelete) {
        btnConfirmDelete.href = `process_delete_article.php?id=${id}`;
    }

    // Tampilkan Modal dengan animasi
    modalDelete.classList.remove('hidden');
    modalDelete.classList.add('flex'); // Pastikan flex aktif untuk centering
    
    // Kunci scroll body agar tidak bergeser saat modal buka
    document.body.style.overflow = 'hidden';
}

/**
 * Fungsi untuk menutup modal
 */
function closeModalDelete() {
    if (!modalDelete) return;

    modalDelete.classList.add('hidden');
    modalDelete.classList.remove('flex');
    
    // Kembalikan scroll body
    document.body.style.overflow = 'auto';
}

/**
 * 2. EVENT LISTENER UNTUK PENCARIAN (LIVE FILTER)
 * Sederhana: Memfilter baris tabel berdasarkan input
 */
const tableSearch = document.querySelector('input[placeholder="Search article..."]');
if (tableSearch) {
    tableSearch.addEventListener('keyup', function(e) {
        const term = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('tbody tr');

        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(term) ? '' : 'none';
        });
    });
}

/**
 * 3. LOGIKA UNTUK NOTIFIKASI AUTO-HIDE
 * Jika ada elemen notifikasi sukses/gagal, hilangkan otomatis setelah 3 detik
 */
const alertBox = document.querySelector('.alert-notification');
if (alertBox) {
    setTimeout(() => {
        alertBox.classList.add('opacity-0', 'translate-y-[-20px]');
        setTimeout(() => alertBox.remove(), 500);
    }, 3000);
}

// Menutup modal jika user menekan tombol 'Escape' di keyboard
document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
        closeModalDelete();
    }
});

// Menutup modal jika user klik di area luar modal (backdrop)
if (modalDelete) {
    modalDelete.addEventListener('click', (event) => {
        // Jika yang diklik adalah backdrop (bukan konten putih)
        if (event.target === modalDelete) {
            closeModalDelete();
        }
    });
}