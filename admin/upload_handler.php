<?php
require_once '../includes/db_connect.php';
require_once '../includes/config.php';
require_once '../includes/admin_auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['images'])) {
  $conn = getDBConnection();

  $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
  $title = $conn->real_escape_string($_POST['title'] ?? '');
  $description = $conn->real_escape_string($_POST['description'] ?? '');
  $errors = [];

  foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
    $file_type = $_FILES['images']['type'][$key];
    $file_size = $_FILES['images']['size'][$key];
    $file_tmp = $_FILES['images']['tmp_name'][$key];
    $file_name = uniqid() . '_' . basename($_FILES['images']['name'][$key]);
    $target_path = '../' . UPLOAD_DIR . $file_name;

    if (!in_array($file_type, $allowed_types)) {
      $errors[] = "File " . $_FILES['images']['name'][$key] . ": Only JPG, PNG, and GIF files are allowed.";
      continue;
    }

    if ($file_size > MAX_FILE_SIZE) {
      $errors[] = "File " . $_FILES['images']['name'][$key] . ": File size exceeds 5MB limit.";
      continue;
    }

    // Move file without resizing (temporary workaround)
    if (move_uploaded_file($file_tmp, $target_path)) {
      // Store in database
      $sql = "INSERT INTO images (file_name, title, description) VALUES ('$file_name', '$title', '$description')";
      if (!$conn->query($sql)) {
        $errors[] = "Database error for " . $_FILES['images']['name'][$key] . ": " . $conn->error;
      }
    } else {
      $errors[] = "Failed to upload " . $_FILES['images']['name'][$key];
    }
  }

  $conn->close();

  if (empty($errors)) {
    header('Location: upload.php?status=success');
    exit;
  } else {
    $error_message = urlencode(implode('|', $errors));
    header("Location: upload.php?status=error&message=$error_message");
    exit;
  }
} else {
  header('Location: upload.php?status=error&message=' . urlencode('Invalid request.'));
  exit;
}
