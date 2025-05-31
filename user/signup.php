<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Signup</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gradient-to-b from-white to-gray-100 min-h-screen flex items-center justify-center">
  <div class="max-w-md w-full bg-white p-6 rounded-lg shadow-2xl">
    <h2 class="text-2xl font-bold text-gray-800 text-center mb-6">User Signup</h2>
    <?php
    $show_success_popup = false;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      require_once '../includes/db_connect.php';
      require_once '../includes/auth.php';
      $conn = getDBConnection();
      $username = $_POST['username'] ?? '';
      $email = $_POST['email'] ?? '';
      $password = $_POST['password'] ?? '';
      if (registerUser($username, $email, $password, $conn)) {
        $show_success_popup = true;
      } else {
        echo '<p class="text-red-500 text-center mb-4">Registration failed. Username or email may already be taken.</p>';
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
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <input type="email" name="email" id="email" required class="mt-1 block w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
      </div>
      <div>
        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
        <input type="password" name="password" id="password" required class="mt-1 block w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
      </div>
      <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded-md shadow-md hover:bg-blue-600 transition duration-200">Sign Up</button>
    </form>
    <p class="mt-4 text-center"><a href="login.php" class="text-blue-500 hover:text-blue-600 font-medium transition duration-200">Already have an account? Log in</a></p>
    <p class="mt-2 text-center"><a href="../admin/login.php" class="text-blue-500 hover:text-blue-600 font-medium transition duration-200">Admin Login</a></p>
  </div>

  <!-- Popup for success message -->
  <div id="popup" class="fixed inset-0 bg-black bg-opacity-50 <?php echo $show_success_popup ? '' : 'hidden'; ?> flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-lg shadow-2xl max-w-sm w-full">
      <h2 class="text-xl font-bold text-green-600 mb-2">Success</h2>
      <p class="mb-4">Registration successful!</p>
      <a href="login.php" class="bg-blue-500 text-white px-4 py-2 rounded-md shadow-md hover:bg-blue-600 transition duration-200 inline-block">Go to Login</a>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const popup = document.getElementById('popup');
      const closeButton = popup.querySelector('a');
      if (popup && !popup.classList.contains('hidden')) {
        closeButton.addEventListener('click', () => {
          popup.classList.add('hidden');
        });
      }
    });
  </script>
</body>

</html>