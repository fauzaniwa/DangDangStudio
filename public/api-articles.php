<?php
require_once '../admin/process/config.php';

// Mendapatkan parameter dari URL
$limit = 10;
$offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
$category = isset($_GET['category']) ? mysqli_real_escape_string($conn, $_GET['category']) : '';
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

// Membangun klausa WHERE
$where = ["articles.status = 'published'"];
if ($category) {
    $where[] = "articles.category = '$category'";
}
if ($search) {
    $where[] = "(articles.title LIKE '%$search%' OR articles.content LIKE '%$search%')";
}
$where_sql = implode(" AND ", $where);

// Query Utama: Pastikan profile_picture terpilih dari tabel admins
$sql = "SELECT 
            articles.id, 
            articles.title, 
            articles.slug, 
            articles.cover_image, 
            articles.category, 
            articles.content, 
            articles.created_at, 
            admins.fullname, 
            admins.profile_picture 
        FROM articles 
        LEFT JOIN admins ON articles.admin_id = admins.id 
        WHERE $where_sql 
        ORDER BY articles.created_at DESC 
        LIMIT $offset, $limit";

$result = mysqli_query($conn, $sql);
$articles = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Format tanggal agar lebih readable di frontend
        $row['date_formatted'] = date('F d, Y', strtotime($row['created_at']));
        
        // Membersihkan konten dari tag HTML dan membatasi jumlah karakter untuk excerpt
        // Menggunakan 120 karakter agar pas dengan layout 3 kolom
        $row['excerpt'] = mb_strimwidth(strip_tags($row['content']), 0, 120, "...");
        
        // Memastikan data fullname tidak null untuk inisial
        $row['fullname'] = $row['fullname'] ?? 'Admin';
        
        // Menambahkan hasil ke array
        $articles[] = $row;
    }
}

// Set header sebagai JSON
header('Content-Type: application/json');

// Mengirimkan data
if (empty($articles) && $offset === 0 && !empty($search)) {
    // Bisa mengirimkan pesan khusus jika hasil pencarian kosong
    echo json_encode([]);
} else {
    echo json_encode($articles);
}