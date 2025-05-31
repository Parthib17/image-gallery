<?php
// Start output buffering to prevent header issues
ob_start();
// Move all PHP includes to the top
require_once 'includes/user_auth.php';
require_once 'includes/config.php';
require_once 'includes/db_connect.php';
$conn = getDBConnection();

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
if ($search !== '') {
  $stmt = $conn->prepare("SELECT * FROM images WHERE title LIKE ? ORDER BY uploaded_at DESC");
  $search_term = '%' . $search . '%';
  $stmt->bind_param("s", $search_term);
  $stmt->execute();
  $result = $stmt->get_result();
} else {
  $result = $conn->query("SELECT * FROM images ORDER BY uploaded_at DESC");
}

$images = [];
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $images[] = $row;
  }
}

if (isset($stmt)) {
  $stmt->close();
}
$conn->close();

// Flush output buffer after PHP logic
ob_end_flush();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Image Gallery</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    /* Tightly Packed Bento Grid Layout */
    .bento-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
      grid-auto-rows: 150px;
      grid-auto-flow: dense;
      /* This fills gaps automatically */
      gap: 8px;
      padding: 16px;
    }

    .bento-item {
      position: relative;
      background: white;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      cursor: pointer;
    }

    .bento-item:hover {
      transform: translateY(-4px) scale(1.02);
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
      z-index: 10;
    }

    /* Tightly packed size variations */
    .bento-item.size-1x1 {
      grid-row: span 1;
      grid-column: span 1;
    }

    .bento-item.size-1x2 {
      grid-row: span 2;
      grid-column: span 1;
    }

    .bento-item.size-2x1 {
      grid-row: span 1;
      grid-column: span 2;
    }

    .bento-item.size-2x2 {
      grid-row: span 2;
      grid-column: span 2;
    }

    .bento-item.size-1x3 {
      grid-row: span 3;
      grid-column: span 1;
    }

    .bento-item.size-3x1 {
      grid-row: span 1;
      grid-column: span 3;
    }

    .bento-item.size-2x3 {
      grid-row: span 3;
      grid-column: span 2;
    }

    .bento-item img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.3s ease;
    }

    .bento-item:hover img {
      transform: scale(1.05);
    }

    .bento-overlay {
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      background: linear-gradient(transparent, rgba(0, 0, 0, 0.85));
      color: white;
      padding: 12px;
      transform: translateY(100%);
      transition: transform 0.3s ease;
    }

    .bento-item:hover .bento-overlay {
      transform: translateY(0);
    }

    .bento-title {
      font-size: 0.9rem;
      font-weight: 600;
      margin-bottom: 2px;
      line-height: 1.2;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }

    .bento-description {
      font-size: 0.75rem;
      opacity: 0.9;
      line-height: 1.3;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }

    /* Responsive adjustments for tight packing */
    @media (max-width: 1200px) {
      .bento-grid {
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        grid-auto-rows: 140px;
      }
    }

    @media (max-width: 768px) {
      .bento-grid {
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        grid-auto-rows: 120px;
        gap: 6px;
      }

      .bento-item.size-2x2,
      .bento-item.size-2x3,
      .bento-item.size-3x1 {
        grid-row: span 2;
        grid-column: span 2;
      }

      .bento-item.size-1x3 {
        grid-row: span 2;
        grid-column: span 1;
      }
    }

    @media (max-width: 480px) {
      .bento-grid {
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
        grid-auto-rows: 110px;
        gap: 4px;
        padding: 12px;
      }

      .bento-item.size-2x2,
      .bento-item.size-2x3,
      .bento-item.size-3x1,
      .bento-item.size-1x3 {
        grid-row: span 1;
        grid-column: span 1;
      }

      .bento-item.size-2x1 {
        grid-column: span 2;
      }
    }

    /* Lightbox styles */
    #lightbox {
      transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
      opacity: 0;
      transform: scale(0.95);
    }

    #lightbox.show {
      opacity: 1;
      transform: scale(1);
    }

    .lightbox-controls {
      position: absolute;
      bottom: 20px;
      right: 20px;
      display: flex;
      gap: 12px;
      z-index: 10;
    }

    .control-btn {
      background-color: #93c5fd;
      color: #1f2937;
      width: 44px;
      height: 44px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      box-shadow: 0 3px 8px rgba(0, 0, 0, 0.15);
    }

    .control-btn:hover {
      background-color: #2563eb;
      color: white;
      transform: scale(1.15);
      box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
    }

    #lightbox-image {
      transition: transform 0.1s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .nav-arrow {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      background-color: #93c5fd;
      color: #1f2937;
      width: 52px;
      height: 52px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      box-shadow: 0 3px 8px rgba(0, 0, 0, 0.15);
      z-index: 10;
    }

    .nav-arrow:hover {
      background-color: #2563eb;
      color: white;
      transform: translateY(-50%) scale(1.15);
      box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
    }

    .nav-arrow.prev {
      left: 20px;
    }

    .nav-arrow.next {
      right: 20px;
    }

    /* Search bar styling */
    .search-container {
      position: relative;
      max-width: 500px;
      margin: 0 auto 1.5rem;
    }

    .search-input {
      width: 100%;
      padding: 0.75rem 2.5rem 0.75rem 2.5rem;
      border: 2px solid #e5e7eb;
      border-radius: 9999px;
      font-size: 1rem;
      transition: all 0.3s ease-in-out;
      background: white;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .search-input:focus {
      outline: none;
      border-color: #2563eb;
      box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1), 0 4px 20px rgba(0, 0, 0, 0.15);
    }

    .search-icon,
    .clear-icon {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      color: #6b7280;
    }

    .search-icon {
      left: 0.75rem;
    }

    .clear-icon {
      right: 0.75rem;
      cursor: pointer;
      display: none;
    }

    .search-input:not(:placeholder-shown)+.search-icon+.clear-icon {
      display: block;
    }

    .clear-icon:hover {
      color: #2563eb;
    }

    /* Header styling */
    .header-gradient {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    /* Lightbox info */
    .lightbox-info {
      background-color: rgba(224, 242, 254, 0.95);
      color: #1f2937;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      transition: background-color 0.3s ease-in-out;
      backdrop-filter: blur(10px);
    }

    .lightbox-info:hover {
      background-color: #e0f2fe;
    }

    /* Footer styles */
    .footer {
      background: linear-gradient(to top, #e0f2fe, #bae6fd);
      color: #1f2937;
      padding: 2rem 0;
      box-shadow: 0 -2px 4px rgba(0, 0, 0, 0.1);
    }

    .footer a {
      color: #1f2937;
      transition: color 0.3s ease;
    }

    .footer a:hover {
      color: #2563eb;
    }

    .footer .social-icons a {
      color: #6b7280;
      font-size: 1.5rem;
      margin: 0 0.5rem;
    }

    .footer .social-icons a:hover {
      color: #2563eb;
    }

    .back-to-top {
      position: fixed;
      bottom: 1rem;
      right: 1rem;
      background-color: #93c5fd;
      color: #1f2937;
      width: 50px;
      height: 50px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      opacity: 0;
      visibility: hidden;
      transition: all 0.3s ease;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
      z-index: 1000;
    }

    .back-to-top.show {
      opacity: 1;
      visibility: visible;
    }

    .back-to-top:hover {
      background-color: #2563eb;
      color: white;
      transform: scale(1.1);
    }

    /* Loading animation */
    .loading {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 200px;
      grid-column: 1 / -1;
    }

    .spinner {
      width: 40px;
      height: 40px;
      border: 4px solid #e5e7eb;
      border-top: 4px solid #2563eb;
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

    /* Ensure content is pushed above footer */
    body {
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    main {
      flex: 1 0 auto;
    }

    .footer {
      flex-shrink: 0;
    }

    /* No results styling */
    .no-results {
      grid-column: 1 / -1;
      text-align: center;
      padding: 3rem 1rem;
    }
  </style>
</head>

<body class="bg-gradient-to-br from-sky-50 via-blue-50 to-indigo-100 min-h-screen">
  <header class="header-gradient shadow-xl p-4 sticky top-0 z-50">
    <div class="container mx-auto flex justify-between items-center">
      <h1 class="text-3xl font-bold text-white drop-shadow-lg">
        <i class="fas fa-th mr-2"></i>Image Gallery
      </h1>
      <nav>
        <a href="user/logout.php" class="bg-white bg-opacity-20 backdrop-blur-sm text-white px-6 py-3 rounded-full shadow-lg hover:bg-opacity-30 transform hover:-translate-y-1 transition duration-300 ease-in-out font-medium flex items-center">
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
          </svg>
          Logout
        </a>
      </nav>
    </div>
  </header>

  <main class="container mx-auto p-4">
    <!-- Search Bar -->
    <div class="search-container">
      <input type="text" id="search-input" class="search-input" placeholder="Search images by title..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
      <i class="fas fa-search search-icon"></i>
      <i class="fas fa-times clear-icon" id="clear-search"></i>
    </div>

    <!-- Tightly Packed Bento Grid -->
    <div class="bento-grid" id="image-grid">
      <?php
      if (count($images) > 0) {
        // Smart size distribution for tight packing
        $sizePattern = [
          'size-3x3',
          'size-2x1',
          'size-1x2',
          'size-2x2',
          'size-1x2',
          'size-3x2',
          'size-2x1',
          'size-2x3',
          'size-2x2',
          'size-2x1',
          'size-3x1',
          'size-1x3',
          'size-2x1',
          'size-2x2',
          'size-3x2',
          'size-1x1'
        ];

        foreach ($images as $index => $row) {
          $sizeClass = $sizePattern[$index % count($sizePattern)];
          echo '
            <div class="bento-item ' . $sizeClass . '">
              <img src="' . UPLOAD_DIR . $row['file_name'] . '" alt="' . htmlspecialchars($row['title']) . '" 
                   class="gallery-image"
                   data-full="' . UPLOAD_DIR . $row['file_name'] . '"
                   data-title="' . htmlspecialchars($row['title']) . '"
                   data-description="' . htmlspecialchars($row['description']) . '">
              <div class="bento-overlay">
                <div class="bento-title">' . htmlspecialchars($row['title']) . '</div>
                <div class="bento-description">' . htmlspecialchars($row['description']) . '</div>
              </div>
            </div>';
        }
      } else {
        echo '<div class="no-results">
                <i class="fas fa-images text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-600 text-xl">No images found.</p>
              </div>';
      }
      ?>
    </div>
  </main>

  <!-- Footer -->
  <footer class="footer">
    <div class="container mx-auto px-4">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        <!-- Navigation Links -->
        <div>
          <h3 class="text-lg font-semibold mb-4">Explore</h3>
          <ul class="space-y-2">
            <li><a href="#" class="hover:underline">Home</a></li>
            <li><a href="#" class="hover:underline">About</a></li>
            <li><a href="#" class="hover:underline">Contact</a></li>
            <li><a href="#" class="hover:underline">Privacy Policy</a></li>
          </ul>
        </div>
        <!-- Social Media -->
        <div>
          <h3 class="text-lg font-semibold mb-4">Follow Us</h3>
          <div class="social-icons flex">
            <a href="#" title="Twitter/X"><i class="fab fa-x-twitter"></i></a>
            <a href="#" title="Facebook"><i class="fab fa-facebook"></i></a>
            <a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
            <a href="#" title="LinkedIn"><i class="fab fa-linkedin"></i></a>
          </div>
        </div>
        <!-- Contact Info -->
        <div>
          <h3 class="text-lg font-semibold mb-4">Contact</h3>
          <p>Email: <a href="mailto:info@imagegallery.com" class="hover:underline">info@imagegallery.com</a></p>
          <p>Phone: <a href="tel:+1234567890" class="hover:underline">+1 (234) 567-890</a></p>
        </div>
        <!-- Newsletter Signup -->
        <div>
          <h3 class="text-lg font-semibold mb-4">Newsletter</h3>
          <p class="mb-4">Stay updated with our latest images!</p>
          <div class="flex">
            <input type="email" class="flex-1 px-3 py-2 border border-gray-300 rounded-l-full" placeholder="Enter your email">
            <button class="bg-blue-500 text-white px-4 py-2 rounded-r-full hover:bg-blue-600 transition">Subscribe</button>
          </div>
        </div>
      </div>
      <div class="mt-8 border-t border-gray-300 pt-4 text-center">
        <p>&copy; <?php echo date('Y'); ?> Packed Bento Gallery. All rights reserved.</p>
      </div>
    </div>
  </footer>

  <!-- Back to Top Button -->
  <button class="back-to-top" title="Back to Top">
    <i class="fas fa-arrow-up"></i>
  </button>

  <!-- Lightbox -->
  <div id="lightbox" class="fixed inset-0 bg-black bg-opacity-80 hidden flex items-center justify-center z-50">
    <div class="relative max-w-5xl w-full bg-white rounded-2xl shadow-2xl p-6 flex flex-col max-h-[90vh] mx-4">
      <div class="flex-1 flex items-center justify-center overflow-auto relative">
        <img id="lightbox-image" src="/placeholder.svg" alt="Full Image" class="w-full h-auto rounded-lg max-h-[60vh] object-contain">
        <div class="lightbox-controls">
          <button id="zoom-in" class="control-btn" title="Zoom In">
            <i class="fas fa-plus"></i>
          </button>
          <button id="zoom-out" class="control-btn" title="Zoom Out">
            <i class="fas fa-minus"></i>
          </button>
          <button id="zoom-reset" class="control-btn" title="Reset Zoom">
            <i class="fas fa-sync-alt"></i>
          </button>
          <a id="download-btn" href="#" download class="control-btn" title="Download Image">
            <i class="fas fa-download"></i>
          </a>
        </div>
        <button id="prev-image" class="nav-arrow prev" title="Previous Image">
          <i class="fas fa-chevron-left"></i>
        </button>
        <button id="next-image" class="nav-arrow next" title="Next Image">
          <i class="fas fa-chevron-right"></i>
        </button>
      </div>
      <div class="lightbox-info p-4 rounded-b-lg mt-4">
        <h3 id="lightbox-title" class="text-2xl font-bold mb-2"></h3>
        <p id="lightbox-description" class="text-gray-700"></p>
      </div>
      <button id="close-lightbox" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 text-3xl bg-white rounded-full w-12 h-12 flex items-center justify-center shadow-lg transition duration-200" title="Close">×</button>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      // Lightbox functionality
      const lightbox = document.getElementById('lightbox');
      const lightboxImage = document.getElementById('lightbox-image');
      const lightboxTitle = document.getElementById('lightbox-title');
      const lightboxDescription = document.getElementById('lightbox-description');
      const closeLightbox = document.getElementById('close-lightbox');
      const zoomInBtn = document.getElementById('zoom-in');
      const zoomOutBtn = document.getElementById('zoom-out');
      const zoomResetBtn = document.getElementById('zoom-reset');
      const downloadBtn = document.getElementById('download-btn');
      const prevImageBtn = document.getElementById('prev-image');
      const nextImageBtn = document.getElementById('next-image');
      const searchInput = document.getElementById('search-input');
      const clearSearch = document.getElementById('clear-search');
      const imageGrid = document.getElementById('image-grid');

      let currentScale = 1;
      let zoomInterval = null;
      let currentImageIndex = -1;
      let galleryImagesArray = [];

      function resizeLightboxImage() {
        const maxHeight = window.innerHeight * 0.6;
        lightboxImage.style.maxHeight = `${maxHeight}px`;
        lightboxImage.style.width = 'auto';
        lightboxImage.style.transform = `scale(${currentScale})`;
      }

      function updateGalleryImages() {
        galleryImagesArray = Array.from(document.querySelectorAll('.gallery-image')).map(img => ({
          full: img.dataset.full,
          title: img.dataset.title || 'No Title',
          description: img.dataset.description || 'No Description'
        }));
      }

      function showImage(index) {
        if (index >= 0 && index < galleryImagesArray.length) {
          currentImageIndex = index;
          const image = galleryImagesArray[index];
          lightboxImage.src = image.full;
          lightboxTitle.textContent = image.title;
          lightboxDescription.textContent = image.description;
          downloadBtn.href = image.full;
          downloadBtn.download = image.full.split('/').pop();
          prevImageBtn.style.display = currentImageIndex > 0 ? 'flex' : 'none';
          nextImageBtn.style.display = currentImageIndex < galleryImagesArray.length - 1 ? 'flex' : 'none';
          currentScale = 1;
          lightboxImage.style.transform = `scale(${currentScale})`;
        }
      }

      function updateLightboxListeners() {
        const galleryImages = document.querySelectorAll('.gallery-image');
        galleryImages.forEach((image, index) => {
          image.addEventListener('click', () => {
            updateGalleryImages();
            currentImageIndex = index;
            showImage(currentImageIndex);
            lightbox.classList.remove('hidden');
            setTimeout(() => lightbox.classList.add('show'), 10);
            resizeLightboxImage();
          });
        });
      }

      updateLightboxListeners();

      // Navigation
      prevImageBtn.addEventListener('click', () => {
        if (currentImageIndex > 0) {
          showImage(currentImageIndex - 1);
        }
      });

      nextImageBtn.addEventListener('click', () => {
        if (currentImageIndex < galleryImagesArray.length - 1) {
          showImage(currentImageIndex + 1);
        }
      });

      // Keyboard navigation
      document.addEventListener('keydown', (e) => {
        if (lightbox.classList.contains('show')) {
          if (e.key === 'ArrowLeft' && currentImageIndex > 0) {
            showImage(currentImageIndex - 1);
          } else if (e.key === 'ArrowRight' && currentImageIndex < galleryImagesArray.length - 1) {
            showImage(currentImageIndex + 1);
          } else if (e.key === 'Escape') {
            closeLightbox.click();
          }
        }
      });

      // Zoom functionality
      function startZoomIn() {
        zoomInterval = setInterval(() => {
          currentScale = Math.min(currentScale + 0.03, 3);
          lightboxImage.style.transform = `scale(${currentScale})`;
        }, 30);
      }

      function startZoomOut() {
        zoomInterval = setInterval(() => {
          currentScale = Math.max(currentScale - 0.03, 0.5);
          lightboxImage.style.transform = `scale(${currentScale})`;
        }, 30);
      }

      function stopZoom() {
        clearInterval(zoomInterval);
        zoomInterval = null;
      }

      zoomInBtn.addEventListener('mousedown', (e) => {
        e.stopPropagation();
        startZoomIn();
      });

      zoomInBtn.addEventListener('touchstart', (e) => {
        e.preventDefault();
        e.stopPropagation();
        startZoomIn();
      });

      zoomOutBtn.addEventListener('mousedown', (e) => {
        e.stopPropagation();
        startZoomOut();
      });

      zoomOutBtn.addEventListener('touchstart', (e) => {
        e.preventDefault();
        e.stopPropagation();
        startZoomOut();
      });

      zoomInBtn.addEventListener('mouseup', stopZoom);
      zoomInBtn.addEventListener('mouseleave', stopZoom);
      zoomInBtn.addEventListener('touchend', stopZoom);
      zoomInBtn.addEventListener('touchcancel', stopZoom);

      zoomOutBtn.addEventListener('mouseup', stopZoom);
      zoomOutBtn.addEventListener('mouseleave', stopZoom);
      zoomOutBtn.addEventListener('touchend', stopZoom);
      zoomOutBtn.addEventListener('touchcancel', stopZoom);

      zoomResetBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        currentScale = 1;
        lightboxImage.style.transform = `scale(${currentScale})`;
      });

      closeLightbox.addEventListener('click', () => {
        lightbox.classList.remove('show');
        setTimeout(() => lightbox.classList.add('hidden'), 300);
      });

      lightbox.addEventListener('click', (e) => {
        if (e.target === lightbox) {
          lightbox.classList.remove('show');
          setTimeout(() => lightbox.classList.add('hidden'), 300);
        }
      });

      window.addEventListener('resize', resizeLightboxImage);

      // Search functionality
      function performSearch() {
        const searchTerm = searchInput.value.trim();
        const url = new URL(window.location);
        if (searchTerm) {
          url.searchParams.set('search', searchTerm);
        } else {
          url.searchParams.delete('search');
        }
        window.location = url;
      }

      searchInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
          performSearch();
        }
      });

      clearSearch.addEventListener('click', () => {
        searchInput.value = '';
        const url = new URL(window.location);
        url.searchParams.delete('search');
        window.location = url;
      });

      // Live search with AJAX and tight packing
      let timeout;
      searchInput.addEventListener('input', () => {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
          const searchTerm = searchInput.value.trim();

          // Show loading state
          imageGrid.innerHTML = '<div class="loading"><div class="spinner"></div></div>';

          fetch(`search_images.php?search=${encodeURIComponent(searchTerm)}`)
            .then(response => {
              if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
              }
              return response.json();
            })
            .then(data => {
              imageGrid.innerHTML = '';
              if (data.length > 0) {
                // Smart size distribution for search results
                const sizePattern = [
                  'size-2x2', 'size-1x1', 'size-1x2', 'size-1x1', 'size-2x1', 'size-1x1',
                  'size-1x1', 'size-1x3', 'size-1x1', 'size-1x1', 'size-2x1', 'size-1x2',
                  'size-1x1', 'size-1x1', 'size-2x2', 'size-1x1', 'size-1x1', 'size-1x2',
                  'size-3x1', 'size-1x1', 'size-1x1', 'size-1x1', 'size-2x1', 'size-1x1'
                ];

                data.forEach((image, index) => {
                  const sizeClass = sizePattern[index % sizePattern.length];
                  const div = document.createElement('div');
                  div.className = `bento-item ${sizeClass}`;
                  div.innerHTML = `
                    <img src="${image.file_path}" alt="${image.title}" 
                         class="gallery-image"
                         data-full="${image.file_path}"
                         data-title="${image.title}"
                         data-description="${image.description}">
                    <div class="bento-overlay">
                      <div class="bento-title">${image.title}</div>
                      <div class="bento-description">${image.description}</div>
                    </div>
                  `;
                  imageGrid.appendChild(div);
                });
                updateLightboxListeners();
              } else {
                imageGrid.innerHTML = `
                  <div class="no-results">
                    <i class="fas fa-search text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-600 text-xl">No images found matching your search.</p>
                  </div>
                `;
              }
            })
            .catch(error => {
              console.error('Search error:', error);
              imageGrid.innerHTML = `
                <div class="no-results">
                  <i class="fas fa-exclamation-triangle text-6xl text-red-300 mb-4"></i>
                  <p class="text-red-600 text-xl">Error loading images: ${error.message}</p>
                </div>
              `;
            });
        }, 300);
      });

      // Back to Top Button
      const backToTop = document.querySelector('.back-to-top');
      window.addEventListener('scroll', () => {
        if (window.scrollY > 300) {
          backToTop.classList.add('show');
        } else {
          backToTop.classList.remove('show');
        }
      });

      backToTop.addEventListener('click', () => {
        window.scrollTo({
          top: 0,
          behavior: 'smooth'
        });
      });

      // Optimize grid packing on window resize
      let resizeTimeout;
      window.addEventListener('resize', () => {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(() => {
          // Force grid recalculation
          const grid = document.querySelector('.bento-grid');
          grid.style.display = 'none';
          grid.offsetHeight; // Trigger reflow
          grid.style.display = 'grid';
        }, 100);
      });
    });
  </script>
</body>

</html>