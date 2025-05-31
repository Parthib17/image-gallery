<?php
header('Content-Type: application/json');
require_once 'includes/db_connect.php';
require_once 'includes/config.php';

$conn = getDBConnection();
$response = [];

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
if ($search !== '') {
  $stmt = $conn->prepare("SELECT file_name, title, description FROM images WHERE title LIKE ? ORDER BY uploaded_at DESC");
  $search_term = '%' . $search . '%';
  $stmt->bind_param("s", $search_term);
  $stmt->execute();
  $result = $stmt->get_result();

  while ($row = $result->fetch_assoc()) {
    $response[] = [
      'file_path' => UPLOAD_DIR . $row['file_name'],
      'title' => htmlspecialchars($row['title']),
      'description' => htmlspecialchars($row['description'])
    ];
  }

  $stmt->close();
} else {
  $result = $conn->query("SELECT file_name, title, description FROM images ORDER BY uploaded_at DESC");
  while ($row = $result->fetch_assoc()) {
    $response[] = [
      'file_path' => UPLOAD_DIR . $row['file_name'],
      'title' => htmlspecialchars($row['title']),
      'description' => htmlspecialchars($row['description'])
    ];
  }
}

$conn->close();
echo json_encode($response);
