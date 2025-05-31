<?php
// Start output buffering to prevent header issues
ob_start();
// Include files
require_once '../includes/db_connect.php';
require_once '../includes/auth.php';

// Process login form
$error_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $conn = getDBConnection();
  $username = $_POST['username'] ?? '';
  $password = $_POST['password'] ?? '';
  if (loginAdmin($username, $password, $conn)) {
    header('Location: upload.php');
    exit;
  } else {
    $error_message = 'Invalid administrator credentials.';
  }
  $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login - Packed Bento Gallery</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    /* Background animation with admin theme */
    .animated-bg {
      background: linear-gradient(-45deg, #1e293b, #334155, #475569, #64748b);
      background-size: 400% 400%;
      animation: gradientShift 15s ease infinite;
    }

    @keyframes gradientShift {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }

    /* Floating elements with admin icons */
    .floating-element {
      position: absolute;
      opacity: 0.08;
      animation: float 8s ease-in-out infinite;
    }

    .floating-element:nth-child(1) {
      top: 10%;
      left: 10%;
      animation-delay: 0s;
    }

    .floating-element:nth-child(2) {
      top: 20%;
      right: 10%;
      animation-delay: 3s;
    }

    .floating-element:nth-child(3) {
      bottom: 20%;
      left: 20%;
      animation-delay: 6s;
    }

    .floating-element:nth-child(4) {
      bottom: 10%;
      right: 20%;
      animation-delay: 1.5s;
    }

    .floating-element:nth-child(5) {
      top: 50%;
      left: 5%;
      animation-delay: 4.5s;
    }

    @keyframes float {
      0%, 100% { transform: translateY(0px) rotate(0deg); }
      50% { transform: translateY(-25px) rotate(180deg); }
    }

    /* Admin card with enhanced security theme */
    .admin-card {
      backdrop-filter: blur(20px);
      background: rgba(255, 255, 255, 0.95);
      border: 1px solid rgba(255, 255, 255, 0.2);
      animation: slideUp 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    }

    @keyframes slideUp {
      from {
        opacity: 0;
        transform: translateY(30px) scale(0.95);
      }
      to {
        opacity: 1;
        transform: translateY(0) scale(1);
      }
    }

    /* Input animations with admin styling */
    .input-group {
      position: relative;
    }

    .input-field {
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      border: 2px solid #e5e7eb;
      background: rgba(255, 255, 255, 0.9);
    }

    .input-field:focus {
      border-color: #3b82f6;
      box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
      background: rgba(255, 255, 255, 1);
      transform: translateY(-2px);
    }

    .input-label {
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      position: absolute;
      left: 12px;
      top: 12px;
      background: white;
      padding: 0 8px;
      color: #6b7280;
      pointer-events: none;
    }

    .input-field:focus + .input-label,
    .input-field:not(:placeholder-shown) + .input-label {
      transform: translateY(-24px) scale(0.85);
      color: #3b82f6;
      font-weight: 600;
    }

    /* Admin button with security theme */
    .admin-button {
      background: linear-gradient(135deg, #1e40af 0%, #3730a3 100%);
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      position: relative;
      overflow: hidden;
    }

    .admin-button:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 25px rgba(30, 64, 175, 0.4);
    }

    .admin-button:active {
      transform: translateY(0);
    }

    .admin-button::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
      transition: left 0.5s;
    }

    .admin-button:hover::before {
      left: 100%;
    }

    /* Security badge animation */
    .security-badge {
      animation: pulse 2s infinite;
    }

    @keyframes pulse {
      0%, 100% { transform: scale(1); }
      50% { transform: scale(1.05); }
    }

    /* Loading spinner */
    .spinner {
      width: 20px;
      height: 20px;
      border: 2px solid rgba(255, 255, 255, 0.3);
      border-top: 2px solid white;
      border-radius: 50%;
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    /* Error message animation */
    .error-message {
      animation: shake 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }

    @keyframes shake {
      0%, 100% { transform: translateX(0); }
      25% { transform: translateX(-5px); }
      75% { transform: translateX(5px); }
    }

    /* Link hover effects */
    .admin-link {
      position: relative;
      transition: all 0.3s ease;
    }

    .admin-link::after {
      content: '';
      position: absolute;
      width: 0;
      height: 2px;
      bottom: -2px;
      left: 50%;
      background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
      transition: all 0.3s ease;
      transform: translateX(-50%);
    }

    .admin-link:hover::after {
      width: 100%;
    }

    /* Icon animations */
    .icon-rotate {
      animation: rotate 3s linear infinite;
    }

    @keyframes rotate {
      from { transform: rotate(0deg); }
      to { transform: rotate(360deg); }
    }

    /* Admin header glow */
    .admin-header {
      text-shadow: 0 0 20px rgba(59, 130, 246, 0.3);
    }

    /* Icon bounce animation */
    .icon-bounce {
      animation: bounce 2s infinite;
    }

    @keyframes bounce {
      0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
      40% { transform: translateY(-10px); }
      60% { transform: translateY(-5px); }
    }
  </style>
</head>

<body class="animated-bg min-h-screen flex items-center justify-center relative overflow-hidden">
  <!-- Floating background elements with admin theme -->
  <div class="floating-element">
    <i class="fas fa-shield-alt text-8xl text-white"></i>
  </div>
  <div class="floating-element">
    <i class="fas fa-cog text-6xl text-white"></i>
  </div>
  <div class="floating-element">
    <i class="fas fa-database text-7xl text-white"></i>
  </div>
  <div class="floating-element">
    <i class="fas fa-lock text-5xl text-white"></i>
  </div>
  <div class="floating-element">
    <i class="fas fa-server text-6xl text-white"></i>
  </div>

  <div class="admin-card max-w-md w-full mx-4 p-8 rounded-2xl shadow-2xl relative z-10">
    <!-- Header -->
    <div class="text-center mb-8">
      <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-full mb-4 icon-bounce">
        <i class="fas fa-user-shield text-2xl text-white"></i>
      </div>
      <h2 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-indigo-700 bg-clip-text text-transparent">
        Admin Access
      </h2>
      <p class="text-gray-600 mt-2">Administrative portal for Bento Gallery</p>
    </div>

    <!-- Error Message -->
    <?php if (!empty($error_message)): ?>
      <div class="error-message bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 flex items-center">
        <i class="fas fa-exclamation-circle mr-2"></i>
        <span><?php echo $error_message; ?></span>
      </div>
    <?php endif; ?>

    <!-- Admin Login Form -->
    <form method="post" class="space-y-6" id="adminForm">
      <div class="input-group">
        <input 
          type="text" 
          name="username" 
          id="username" 
          required 
          placeholder=" "
          class="input-field w-full px-4 py-3 rounded-lg text-gray-800 focus:outline-none"
          autocomplete="username"
        >
        <label for="username" class="input-label">Administrator Username</label>
      </div>

      <div class="input-group">
        <input 
          type="password" 
          name="password" 
          id="password" 
          required 
          placeholder=" "
          class="input-field w-full px-4 py-3 rounded-lg text-gray-800 focus:outline-none"
          autocomplete="current-password"
        >
        <label for="password" class="input-label">Administrator Password</label>
      </div>

      <button 
        type="submit" 
        class="admin-button w-full py-3 px-4 rounded-lg text-white font-semibold text-lg shadow-lg flex items-center justify-center"
        id="adminButton"
      >
        <i class="fas fa-key mr-2"></i>
        <span id="buttonText">Sign In</span>
        <div id="buttonSpinner" class="spinner ml-2 hidden"></div>
      </button>
    </form>

    <!-- Links -->
    <div class="mt-8 space-y-4 text-center">
      <p class="text-gray-600">
        Not an administrator? 
        <a href="../user/login.php" class="admin-link text-blue-600 hover:text-blue-700 font-semibold">
          User Login
        </a>
      </p>
    </div>

    <!-- Footer -->
    <div class="mt-8 text-center text-sm text-gray-500">
      <p>&copy; 2024 Bento Gallery Admin Panel</p>
    </div>
  </div>

  <!-- Background security pattern -->
  <div class="fixed inset-0 pointer-events-none opacity-5">
    <div class="absolute top-10 left-10">
      <i class="fas fa-cog text-4xl text-white icon-rotate"></i>
    </div>
    <div class="absolute top-20 right-20">
      <i class="fas fa-server text-3xl text-white"></i>
    </div>
    <div class="absolute bottom-20 left-20">
      <i class="fas fa-database text-5xl text-white"></i>
    </div>
    <div class="absolute bottom-10 right-10">
      <i class="fas fa-shield-alt text-4xl text-white"></i>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const form = document.getElementById('adminForm');
      const button = document.getElementById('adminButton');
      const buttonText = document.getElementById('buttonText');
      const buttonSpinner = document.getElementById('buttonSpinner');

      form.addEventListener('submit', (e) => {
        // Show loading state
        button.disabled = true;
        button.classList.add('opacity-75');
        buttonText.textContent = 'Signing In...';
        buttonSpinner.classList.remove('hidden');
      });

      // Enhanced input validation for admin
      const inputs = document.querySelectorAll('.input-field');
      inputs.forEach(input => {
        input.addEventListener('blur', () => {
          if (input.value.trim() === '') {
            input.classList.add('border-red-300');
            input.classList.remove('border-green-300');
          } else {
            input.classList.add('border-green-300');
            input.classList.remove('border-red-300');
          }
        });

        input.addEventListener('focus', () => {
          input.classList.remove('border-red-300', 'border-green-300');
        });
      });

      // Add enter key support
      inputs.forEach(input => {
        input.addEventListener('keypress', (e) => {
          if (e.key === 'Enter') {
            form.submit();
          }
        });
      });

      // Auto-focus first input
      setTimeout(() => {
        document.getElementById('username').focus();
      }, 500);
    });
  </script>
</body>

</html>
<?php
// Flush the output buffer and send output to browser
ob_end_flush();
?>