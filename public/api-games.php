<?php
require_once '../admin/process/config.php';

header('Content-Type: application/json');

$offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
$limit = 10;
$category = isset($_GET['category']) ? mysqli_real_escape_string($conn, $_GET['category']) : '';
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

$where = "WHERE 1=1";
if ($category != '') {
    $where .= " AND category = '$category'";
}
if ($search != '') {
    $where .= " AND (title LIKE '%$search%' OR short_desc LIKE '%$search%')";
}

$query = "SELECT id, title, slug, category, short_desc, header_image, created_at 
          FROM games 
          $where 
          ORDER BY created_at DESC 
          LIMIT $offset, $limit";

$result = mysqli_query($conn, $query);
$data = [];

while ($row = mysqli_fetch_assoc($result)) {
    // Format tanggal jika diperlukan
    $row['date_formatted'] = date('M Y', strtotime($row['created_at']));
    $data[] = $row;
}

echo json_encode($data);