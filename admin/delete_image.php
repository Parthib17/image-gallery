<?php
header('Content-Type: application/json');
require_once '../includes/admin_auth.php';
require_once '../includes/db_connect.php';
require_once '../includes/config.php';

$conn = getDBConnection();
$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
  $file_name = isset($_POST['file']) ? basename($_POST['file']) : '';

  if ($id > 0 && $file_name) {
    // Define file path using UPLOAD_DIR
    $file_path = '../' . UPLOAD_DIR . $file_name;

    // Delete from database
    $stmt = $conn->prepare("DELETE FROM images WHERE id = ? AND file_name = ?");
    $stmt->bind_param("is", $id, $file_name);

    if ($stmt->execute()) {
      // Delete file from local folder
      if (file_exists($file_path)) {
        if (unlink($file_path)) {
          $response['success'] = true;
        } else {
          $response['message'] = 'Failed to delete file from ' . UPLOAD_DIR . '.';
        }
      } else {
        $response['success'] = true; // File already gone, consider success
      }
    } else {
      $response['message'] = 'Failed to delete image from database.';
    }

    $stmt->close();
  } else {
    $response['message'] = 'Invalid image ID or file name.';
  }
} else {
  $response['message'] = 'Invalid request method.';
}

$conn->close();
echo json_encode($response);
