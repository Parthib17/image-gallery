<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Upload Images</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    #lightbox {
      transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
      opacity: 0;
      transform: scale(0.95);
    }

    #lightbox.show {
      opacity: 1;
      transform: scale(1);
    }

    /* Modern delete button */
    .delete-btn {
      opacity: 0;
      transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
      transform: scale(0.9);
      background: rgba(239, 68, 68, 0.9);
      backdrop-filter: blur(4px);
      width: 28px;
      height: 28px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    .image-card:hover .delete-btn {
      opacity: 1;
      transform: scale(1);
    }

    .delete-btn:hover {
      background: rgba(220, 38, 38, 1);
      transform: scale(1.1) !important;
      box-shadow: 0 3px 6px rgba(0, 0, 0, 0.3);
    }

    /* Custom scrollbar */
    .image-scroll-container {
      overflow-x: auto;
      scroll-behavior: smooth;
      scroll-snap-type: x proximity;
      will-change: transform;
      padding-bottom: 8px;
      /* Space for scrollbar */
    }

    .image-scroll-container::-webkit-scrollbar {
      height: 8px;
    }

    .image-scroll-container::-webkit-scrollbar-track {
      background: #dbeafe;
      border-radius: 4px;
    }

    .image-scroll-container::-webkit-scrollbar-thumb {
      background: #2563eb;
      border-radius: 4px;
      transition: background 0.2s;
    }

    .image-scroll-container::-webkit-scrollbar-thumb:hover {
      background: #1e40af;
    }

    /* Custom scrollbar for Firefox */
    .image-scroll-container {
      scrollbar-width: thin;
      scrollbar-color: #2563eb #dbeafe;
    }

    /* Zoom controls */
    .zoom-controls {
      position: absolute;
      bottom: 20px;
      right: 20px;
      display: flex;
      gap: 10px;
      z-index: 10;
    }

    .zoom-btn {
      background-color: rgba(0, 0, 0, 0.7);
      color: white;
      width: 40px;
      height: 40px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    .zoom-btn:hover {
      background-color: rgba(0, 0, 0, 0.9);
      transform: scale(1.1);
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    }

    /* Smoother zoom transition */
    #lightbox-image {
      transition: transform 0.1s cubic-bezier(0.4, 0, 0.2, 1);
    }
  </style>
</head>

<body class="bg-gradient-to-b from-white to-gray-100 min-h-screen">
  <?php
  require_once '../includes/admin_auth.php';
  require_once '../includes/config.php';
  ?>
  <header class="bg-gradient-to-r from-white to-blue-100 shadow-lg p-4 sticky top-0 z-10">
    <div class="container mx-auto flex justify-between items-center">
      <h1 class="text-2xl font-bold text-gray-800">Upload Images</h1>
      <nav class="flex space-x-2">
        <a href="logout.php" class="bg-gradient-to-r from-blue-500 to-purple-600 text-white px-4 py-2 rounded-md shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition duration-300 ease-in-out font-medium flex items-center">
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
          </svg>
          Logout
        </a>
      </nav>
    </div>
  </header>

  <main class="container mx-auto p-4">
    <form action="upload_handler.php" method="post" enctype="multipart/form-data" class="max-w-md mx-auto bg-white p-6 rounded-lg shadow-2xl mb-8">
      <div class="mb-4">
        <label for="images" class="block text-sm font-medium text-gray-700">Select Images (Multiple)</label>
        <input type="file" name="images[]" id="images" accept="image/*" multiple required class="mt-1 block w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
      </div>
      <div class="mb-4">
        <label for="title" class="block text-sm font-medium text-gray-700">Title <span class="text-red-500">*</span></label>
        <input type="text" name="title" id="title" required class="mt-1 block w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
      </div>
      <div class="mb-4">
        <label for="description" class="block text-sm font-medium text-gray-700">Description <span class="text-red-500">*</span></label>
        <textarea name="description" id="description" required class="mt-1 block w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"></textarea>
      </div>
      <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded-md shadow-md hover:bg-blue-600 transition duration-200">Upload</button>
    </form>

    <!-- Uploaded Images -->
    <div class="mb-8">
      <h2 class="text-xl font-bold text-gray-800 mb-4">Uploaded Images</h2>
      <div class="image-scroll-container flex overflow-x-auto space-x-4 pb-2" id="scroll-container">
        <?php
        require_once '../includes/db_connect.php';
        $conn = getDBConnection();
        $result = $conn->query("SELECT * FROM images ORDER BY uploaded_at DESC");

        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            $image_path = '../' . UPLOAD_DIR . $row['file_name'];
            $file_exists = file_exists($image_path);

            if ($file_exists) {
              echo '
              <div class="image-card flex-none w-48 bg-white rounded-lg shadow-lg shadow-inner overflow-hidden relative">
                  <img src="' . htmlspecialchars($image_path) . '" alt="' . htmlspecialchars($row['title']) . '" 
                       class="w-full h-32 object-cover cursor-pointer gallery-image"
                       data-full="' . htmlspecialchars($image_path) . '"
                       data-title="' . htmlspecialchars($row['title']) . '"
                       data-description="' . htmlspecialchars($row['description']) . '">
                  <p class="text-sm text-gray-700 p-2 truncate">' . htmlspecialchars($row['title']) . '</p>
                  <button class="delete-btn absolute top-2 right-2" 
                          data-id="' . $row['id'] . '" 
                          data-file="' . htmlspecialchars($row['file_name']) . '"
                          title="Delete Image">
                      <i class="fas fa-trash-alt text-xs text-white"></i>
                  </button>
              </div>';
            } else {
              echo '<div class="flex-none w-48 bg-white rounded-lg shadow-lg p-4 text-center">
                      <p class="text-red-500 text-sm">Image not found</p>
                      <p class="text-xs text-gray-500 truncate">' . htmlspecialchars($row['file_name']) . '</p>
                    </div>';
            }
          }
        } else {
          echo '<p class="text-gray-600">No images uploaded yet.</p>';
        }
        $conn->close();
        ?>
      </div>
    </div>
  </main>

  <!-- Lightbox -->
  <div id="lightbox" class="fixed inset-0 bg-black bg-opacity-75 hidden flex items-center justify-center z-50">
    <div class="relative max-w-4xl w-full bg-white rounded-lg shadow-2xl p-4 flex flex-col max-h-[90vh]">
      <div class="flex-1 flex items-center justify-center overflow-auto relative">
        <img id="lightbox-image" src="" alt="Full Image" class="w-full h-auto rounded-md max-h-[60vh] object-contain">
        <div class="zoom-controls">
          <button id="zoom-in" class="zoom-btn">
            <i class="fas fa-plus"></i>
          </button>
          <button id="zoom-out" class="zoom-btn">
            <i class="fas fa-minus"></i>
          </button>
          <button id="zoom-reset" class="zoom-btn">
            <i class="fas fa-sync-alt"></i>
          </button>
        </div>
      </div>
      <div class="bg-gray-900 bg-opacity-75 text-white p-4 rounded-b-md">
        <h2 id="lightbox-title" class="text-xl font-bold"></h2>
        <p id="lightbox-description" class="mt-2"></p>
      </div>
      <button id="close-lightbox" class="absolute top-2 right-2 text-white text-2xl bg-gray-800 rounded-full w-10 h-10 flex items-center justify-center hover:bg-gray-700 transition duration-200">×</button>
    </div>
  </div>

  <!-- Popup for success/error messages -->
  <div id="popup" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-lg shadow-2xl max-w-sm w-full">
      <h2 id="popup-title" class="text-xl font-bold mb-2"></h2>
      <p id="popup-message" class="mb-4"></p>
      <button id="close-popup" class="bg-blue-500 text-white px-4 py-2 rounded-md shadow-md hover:bg-blue-600 transition duration-200">OK</button>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      // Handle upload popup
      const urlParams = new URLSearchParams(window.location.search);
      const status = urlParams.get('status');
      const message = urlParams.get('message');
      const popup = document.getElementById('popup');
      const popupTitle = document.getElementById('popup-title');
      const popupMessage = document.getElementById('popup-message');
      const closePopup = document.getElementById('close-popup');

      if (status) {
        popup.classList.remove('hidden');
        if (status === 'success') {
          popupTitle.textContent = 'Success';
          popupTitle.classList.add('text-green-600');
          popupMessage.textContent = 'Images uploaded successfully!';
        } else if (status === 'error') {
          popupTitle.textContent = 'Error';
          popupTitle.classList.add('text-red-600');
          popupMessage.textContent = message ? decodeURIComponent(message).replace(/\|/g, '\n') : 'An error occurred.';
        }
      }

      closePopup.addEventListener('click', () => {
        popup.classList.add('hidden');
        window.history.replaceState({}, document.title, window.location.pathname);
      });

      // Lightbox functionality
      const galleryImages = document.querySelectorAll('.gallery-image');
      const lightbox = document.getElementById('lightbox');
      const lightboxImage = document.getElementById('lightbox-image');
      const lightboxTitle = document.getElementById('lightbox-title');
      const lightboxDescription = document.getElementById('lightbox-description');
      const closeLightbox = document.getElementById('close-lightbox');
      const zoomInBtn = document.getElementById('zoom-in');
      const zoomOutBtn = document.getElementById('zoom-out');
      const zoomResetBtn = document.getElementById('zoom-reset');

      let currentScale = 1;
      let zoomInterval = null;

      function resizeLightboxImage() {
        const maxHeight = window.innerHeight * 0.6;
        lightboxImage.style.maxHeight = `${maxHeight}px`;
        lightboxImage.style.width = 'auto';
        lightboxImage.style.transform = `scale(${currentScale})`;
      }

      galleryImages.forEach(image => {
        image.addEventListener('click', () => {
          lightboxImage.src = image.dataset.full;
          lightboxTitle.textContent = image.dataset.title || 'No Title';
          lightboxDescription.textContent = image.dataset.description || 'No Description';
          currentScale = 1;
          lightbox.classList.remove('hidden');
          setTimeout(() => lightbox.classList.add('show'), 10);
          resizeLightboxImage();
        });
      });

      // Zoom functionality with long press
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

      // Delete functionality
      const deleteButtons = document.querySelectorAll('.delete-btn');
      deleteButtons.forEach(button => {
        button.addEventListener('click', (e) => {
          e.stopPropagation();
          if (confirm('Are you sure you want to delete this image?')) {
            const id = button.dataset.id;
            const file = button.dataset.file;
            fetch('delete_image.php', {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `id=${id}&file=${file}`
              })
              .then(response => response.json())
              .then(data => {
                popup.classList.remove('hidden');
                if (data.success) {
                  popupTitle.textContent = 'Success';
                  popupTitle.classList.add('text-green-600');
                  popupMessage.textContent = 'Image deleted successfully!';
                  button.closest('.image-card').remove();
                } else {
                  popupTitle.textContent = 'Error';
                  popupTitle.classList.add('text-red-600');
                  popupMessage.textContent = data.message || 'Failed to delete image.';
                }
              })
              .catch(error => {
                popup.classList.remove('hidden');
                popupTitle.textContent = 'Error';
                popupTitle.classList.add('text-red-600');
                popupMessage.textContent = 'Failed to delete image: ' + error.message;
              });
          }
        });
      });

      // Draggable scroll functionality
      const scrollContainer = document.getElementById('scroll-container');
      if (scrollContainer) {
        let isDown = false;
        let startX;
        let scrollLeft;

        scrollContainer.addEventListener('mousedown', (e) => {
          isDown = true;
          startX = e.pageX - scrollContainer.offsetLeft;
          scrollLeft = scrollContainer.scrollLeft;
          scrollContainer.style.cursor = 'grabbing';
          scrollContainer.style.userSelect = 'none';
        });

        scrollContainer.addEventListener('mouseleave', () => {
          isDown = false;
          scrollContainer.style.cursor = 'grab';
        });

        scrollContainer.addEventListener('mouseup', () => {
          isDown = false;
          scrollContainer.style.cursor = 'grab';
          scrollContainer.style.removeProperty('user-select');
        });

        scrollContainer.addEventListener('mousemove', (e) => {
          if (!isDown) return;
          e.preventDefault();
          const x = e.pageX - scrollContainer.offsetLeft;
          const walk = (x - startX) * 2; // Adjust scroll speed
          scrollContainer.scrollLeft = scrollLeft - walk;
        });

        // Set grab cursor by default
        scrollContainer.style.cursor = 'grab';
      }
    });
  </script>
</body>

</html>