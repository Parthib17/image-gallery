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
    echo '<div class="error-message bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <span>Registration failed. Username or email may already be taken.</span>
              </div>';
  }
  $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up - Image Gallery</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    /* Background animation */
    .animated-bg {
      background: linear-gradient(-45deg, #667eea, #764ba2, #f093fb, #f5576c);
      background-size: 400% 400%;
      animation: gradientShift 15s ease infinite;
    }

    @keyframes gradientShift {
      0% {
        background-position: 0% 50%;
      }

      50% {
        background-position: 100% 50%;
      }

      100% {
        background-position: 0% 50%;
      }
    }

    /* Floating elements */
    .floating-element {
      position: absolute;
      opacity: 0.1;
      animation: float 6s ease-in-out infinite;
    }

    .floating-element:nth-child(1) {
      top: 15%;
      left: 15%;
      animation-delay: 0s;
    }

    .floating-element:nth-child(2) {
      top: 25%;
      right: 15%;
      animation-delay: 2s;
    }

    .floating-element:nth-child(3) {
      bottom: 25%;
      left: 25%;
      animation-delay: 4s;
    }

    .floating-element:nth-child(4) {
      bottom: 15%;
      right: 25%;
      animation-delay: 1s;
    }

    @keyframes float {

      0%,
      100% {
        transform: translateY(0px) rotate(0deg);
      }

      50% {
        transform: translateY(-20px) rotate(180deg);
      }
    }

    /* Card animations */
    .auth-card {
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

    /* Input animations */
    .input-group {
      position: relative;
    }

    .input-field {
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      border: 2px solid #e5e7eb;
      background: rgba(255, 255, 255, 0.9);
    }

    .input-field:focus {
      border-color: #667eea;
      box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
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

    .input-field:focus+.input-label,
    .input-field:not(:placeholder-shown)+.input-label {
      transform: translateY(-24px) scale(0.85);
      color: #667eea;
      font-weight: 500;
    }

    /* Button animations */
    .auth-button {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      position: relative;
      overflow: hidden;
    }

    .auth-button:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
    }

    .auth-button:active {
      transform: translateY(0);
    }

    .auth-button::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
      transition: left 0.5s;
    }

    .auth-button:hover::before {
      left: 100%;
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
      0% {
        transform: rotate(0deg);
      }

      100% {
        transform: rotate(360deg);
      }
    }

    /* Error message animation */
    .error-message {
      animation: shake 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }

    @keyframes shake {

      0%,
      100% {
        transform: translateX(0);
      }

      25% {
        transform: translateX(-5px);
      }

      75% {
        transform: translateX(5px);
      }
    }

    /* Success popup */
    .success-popup {
      animation: popIn 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }

    @keyframes popIn {
      from {
        opacity: 0;
        transform: scale(0.8);
      }

      to {
        opacity: 1;
        transform: scale(1);
      }
    }

    /* Link hover effects */
    .auth-link {
      position: relative;
      transition: all 0.3s ease;
    }

    .auth-link::after {
      content: '';
      position: absolute;
      width: 0;
      height: 2px;
      bottom: -2px;
      left: 50%;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      transition: all 0.3s ease;
      transform: translateX(-50%);
    }

    .auth-link:hover::after {
      width: 100%;
    }

    /* Icon animations */
    .icon-bounce {
      animation: bounce 2s infinite;
    }

    @keyframes bounce {

      0%,
      20%,
      50%,
      80%,
      100% {
        transform: translateY(0);
      }

      40% {
        transform: translateY(-10px);
      }

      60% {
        transform: translateY(-5px);
      }
    }

    /* Password strength indicator */
    .strength-indicator {
      height: 4px;
      border-radius: 2px;
      transition: all 0.3s ease;
    }

    .strength-weak {
      background: #ef4444;
      width: 33%;
    }

    .strength-medium {
      background: #f59e0b;
      width: 66%;
    }

    .strength-strong {
      background: #10b981;
      width: 100%;
    }
  </style>
</head>

<body class="animated-bg min-h-screen flex items-center justify-center relative overflow-hidden">
  <!-- Floating background elements -->
  <div class="floating-element">
    <i class="fas fa-user-plus text-6xl text-white"></i>
  </div>
  <div class="floating-element">
    <i class="fas fa-envelope text-4xl text-white"></i>
  </div>
  <div class="floating-element">
    <i class="fas fa-lock text-5xl text-white"></i>
  </div>
  <div class="floating-element">
    <i class="fas fa-heart text-3xl text-white"></i>
  </div>

  <div class="auth-card max-w-md w-full mx-4 p-8 rounded-2xl shadow-2xl relative z-10">
    <!-- Header -->
    <div class="text-center mb-8">
      <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-green-500 to-blue-600 rounded-full mb-4 icon-bounce">
        <i class="fas fa-user-plus text-2xl text-white"></i>
      </div>
      <h2 class="text-3xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent">
        Join Image Gallery
      </h2>
      <p class="text-gray-600 mt-2">Create your account and start exploring</p>
    </div>

    <!-- Error Message -->


    <!-- Signup Form -->
    <form method="post" class="space-y-6" id="signupForm">
      <div class="input-group">
        <input
          type="text"
          name="username"
          id="username"
          required
          placeholder=" "
          class="input-field w-full px-4 py-3 rounded-lg text-gray-800 focus:outline-none">
        <label for="username" class="input-label">Username</label>
      </div>

      <div class="input-group">
        <input
          type="email"
          name="email"
          id="email"
          required
          placeholder=" "
          class="input-field w-full px-4 py-3 rounded-lg text-gray-800 focus:outline-none">
        <label for="email" class="input-label">Email Address</label>
      </div>

      <div class="input-group">
        <input
          type="password"
          name="password"
          id="password"
          required
          placeholder=" "
          class="input-field w-full px-4 py-3 rounded-lg text-gray-800 focus:outline-none">
        <label for="password" class="input-label">Password</label>
        <div class="mt-2">
          <div class="strength-indicator" id="strengthIndicator"></div>
          <p class="text-xs text-gray-500 mt-1" id="strengthText">Password strength</p>
        </div>
      </div>

      <button
        type="submit"
        class="auth-button w-full py-3 px-4 rounded-lg text-white font-semibold text-lg shadow-lg flex items-center justify-center"
        id="signupButton">
        <span id="buttonText">Create Account</span>
        <div id="buttonSpinner" class="spinner ml-2 hidden"></div>
      </button>
    </form>

    <!-- Links -->
    <div class="mt-8 space-y-4 text-center">
      <p class="text-gray-600">
        Already have an account?
        <a href="login.php" class="auth-link text-blue-600 hover:text-blue-700 font-semibold">
          Sign in here
        </a>
      </p>
      <div class="border-t border-gray-200 pt-4">
        <a href="../admin/login.php" class="auth-link text-purple-600 hover:text-purple-700 font-medium">
          <i class="fas fa-user-shield mr-1"></i>
          Admin Access
        </a>
      </div>
    </div>

    <!-- Footer -->
    <div class="mt-8 text-center text-sm text-gray-500">
      <p>&copy; 2024 Image Gallery. All rights reserved.</p>
    </div>
  </div>

  <!-- Success Popup -->
  <div id="successPopup" class="fixed inset-0 bg-black bg-opacity-50 <?php echo $show_success_popup ? '' : 'hidden'; ?> flex items-center justify-center z-50">
    <div class="success-popup bg-white p-8 rounded-2xl shadow-2xl max-w-sm w-full mx-4 text-center">
      <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-full mb-4">
        <i class="fas fa-check text-2xl text-white"></i>
      </div>
      <h2 class="text-2xl font-bold text-green-600 mb-2">Welcome Aboard!</h2>
      <p class="text-gray-600 mb-6">Your account has been created successfully. You can now sign in and start exploring the gallery.</p>
      <a href="login.php" class="auth-button inline-block px-6 py-3 rounded-lg text-white font-semibold shadow-lg">
        Continue to Login
      </a>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const form = document.getElementById('signupForm');
      const button = document.getElementById('signupButton');
      const buttonText = document.getElementById('buttonText');
      const buttonSpinner = document.getElementById('buttonSpinner');
      const passwordInput = document.getElementById('password');
      const strengthIndicator = document.getElementById('strengthIndicator');
      const strengthText = document.getElementById('strengthText');

      // Password strength checker
      passwordInput.addEventListener('input', () => {
        const password = passwordInput.value;
        const strength = checkPasswordStrength(password);

        strengthIndicator.className = 'strength-indicator';

        if (strength.score === 0) {
          strengthIndicator.classList.add('strength-weak');
          strengthText.textContent = 'Weak password';
          strengthText.className = 'text-xs text-red-500 mt-1';
        } else if (strength.score === 1) {
          strengthIndicator.classList.add('strength-medium');
          strengthText.textContent = 'Medium strength';
          strengthText.className = 'text-xs text-yellow-500 mt-1';
        } else {
          strengthIndicator.classList.add('strength-strong');
          strengthText.textContent = 'Strong password';
          strengthText.className = 'text-xs text-green-500 mt-1';
        }
      });

      function checkPasswordStrength(password) {
        let score = 0;
        if (password.length >= 8) score++;
        if (/[A-Z]/.test(password)) score++;
        if (/[0-9]/.test(password)) score++;
        if (/[^A-Za-z0-9]/.test(password)) score++;

        return {
          score: Math.min(score, 2)
        };
      }

      form.addEventListener('submit', (e) => {
        // Show loading state
        button.disabled = true;
        button.classList.add('opacity-75');
        buttonText.textContent = 'Creating Account...';
        buttonSpinner.classList.remove('hidden');
      });

      // Add input validation feedback
      const inputs = document.querySelectorAll('.input-field');
      inputs.forEach(input => {
        input.addEventListener('blur', () => {
          if (input.type === 'email') {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(input.value)) {
              input.classList.add('border-red-300');
              input.classList.remove('border-green-300');
            } else {
              input.classList.add('border-green-300');
              input.classList.remove('border-red-300');
            }
          } else if (input.value.trim() === '') {
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

      // Handle success popup
      const popup = document.getElementById('successPopup');
      if (popup && !popup.classList.contains('hidden')) {
        // Auto-close after 5 seconds
        setTimeout(() => {
          window.location.href = 'login.php';
        }, 5000);
      }
    });
  </script>
</body>

</html>