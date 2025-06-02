<?php
session_start();

// Secure login for admin
function loginAdmin($username, $password, $conn)
{
  $sql = "SELECT id, password FROM admins WHERE username = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('s', $username);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($row = $result->fetch_assoc()) {
    if (password_verify($password, $row['password'])) {
      $_SESSION['admin_id'] = $row['id'];
      return true;
    }
  }
  return false;
}

// Secure login for user
function loginUser($username, $password, $conn)
{
  $sql = "SELECT id, password FROM users WHERE username = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('s', $username);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($row = $result->fetch_assoc()) {
    if (password_verify($password, $row['password'])) {
      $_SESSION['user_id'] = $row['id'];
      return true;
    }
  }
  return false;
}

// Secure registration
function registerUser($username, $email, $password, $conn)
{
  $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hash password

  $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('sss', $username, $email, $hashedPassword);

  try {
    return $stmt->execute();
  } catch (mysqli_sql_exception $e) {
    return false; // Likely a duplicate username or email
  }
}

// Session checkers
function isAdminLoggedIn()
{
  return isset($_SESSION['admin_id']);
}

function isUserLoggedIn()
{
  return isset($_SESSION['user_id']);
}
?>
