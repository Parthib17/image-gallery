<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Login</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gradient-to-b from-white to-gray-100 min-h-screen flex items-center justify-center">
  <div class="max-w-md w-full bg-white p-6 rounded-lg shadow-2xl">
    <h2 class="text-2xl font-bold text-gray-800 text-center mb-6">User Login</h2>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      require_once '../includes/db_connect.php';
      require_once '../includes/auth.php';
      $conn = getDBConnection();
      $username = $_POST['username'] ?? '';
      $password = $_POST['password'] ?? '';
      if (loginUser($username, $password, $conn)) {
        header('Location: ../index.php');
        exit;
      } else {
        echo '<p class="text-red-500 text-center mb-4">Invalid credentials.</p>';
      }
      $conn->close();
    }
    ?>
    <form method="post" class="space-y-4">
      <div>
        <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
        <input type="text" name="username" id="username" required class="mt-1 block w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
      </div>
      <div>
        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
        <input type="password" name="password" id="password" required class="mt-1 block w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
      </div>
      <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded-md shadow-md hover:bg-blue-600 transition duration-200">Login</button>
    </form>
    <p class="mt-4 text-center"><a href="signup.php" class="text-blue-500 hover:text-blue-600 font-medium transition duration-200">Don't have an account? Sign up</a></p>
    <p class="mt-2 text-center"><a href="../admin/login.php" class="text-blue-500 hover:text-blue-600 font-medium transition duration-200">Admin Login</a></p>
  </div>
</body>

</html>