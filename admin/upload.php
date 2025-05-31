<?php
// Start output buffering to prevent header issues
ob_start();
require_once '../includes/admin_auth.php';
require_once '../includes/config.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Upload Images - Bento Gallery Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
  <style>
    /* Simplified styles */
    body {
      background-color: #f8fafc;
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    .header {
      background-color: #1e40af;
      color: white;
    }

    .upload-area {
      border: 2px dashed #cbd5e1;
      background-color: white;
    }

    .upload-area.dragover {
      border-color: #3b82f6;
      background-color: #f0f7ff;
    }

    .form-card {
      background-color: white;
      border: 1px solid #e2e8f0;
    }

    .input-field {
      border: 1px solid #e2e8f0;
      background-color: white;
    }

    .input-field:focus {
      border-color: #3b82f6;
      outline: none;
    }

    .admin-button {
      background-color: #1e40af;
      color: white;
    }

    .admin-button:hover {
      background-color: #1e3a8a;
    }

    .image-card {
      background-color: white;
      border: 1px solid #e2e8f0;
    }

    .delete-btn {
      background-color: #ef4444;
      color: white;
    }

    .delete-btn:hover {
      background-color: #dc2626;
    }

    .editor-modal {
      background-color: rgba(0, 0, 0, 0.7);
    }

    .editor-content {
      background-color: white;
    }

    .lightbox {
      background-color: rgba(0, 0, 0, 0.8);
    }

    .lightbox-content {
      background-color: white;
    }

    .control-btn {
      background-color: #4f46e5;
      color: white;
    }

    .control-btn:hover {
      background-color: #4338ca;
    }

    .preview-image {
      border: 1px solid #e2e8f0;
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

.delete-btn {
  opacity: 1; /* Changed from 0 to 1 to make it always visible */
  transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
  transform: scale(1); /* Changed from 0.9 to 1 for full size */
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

/* Remove or modify the hover effect if needed */
.image-card:hover .delete-btn {
  opacity: 1; /* Already visible, no change needed */
  transform: scale(1); /* No scale change on hover */
}


    /* Responsive adjustments */
    @media (max-width: 768px) {
      .image-grid {
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
      }
    }
  </style>
</head>

<body class="min-h-screen">
  <!-- Header -->
  <header class="header shadow-md p-4 sticky top-0 z-40">
    <div class="container mx-auto flex justify-between items-center">
      <div class="flex items-center">
        <i class="fas fa-upload text-xl text-white mr-3"></i>
        <h1 class="text-xl font-bold text-white">Upload Images</h1>
      </div>
      <nav class="flex space-x-4">
        <a href="logout.php" class="bg-white bg-opacity-20 text-white px-3 py-1 rounded hover:bg-opacity-30 flex items-center">
          <i class="fas fa-sign-out-alt mr-2"></i>
          Logout
        </a>
      </nav>
    </div>
  </header>

  <main class="container mx-auto p-4">
    <!-- Upload Form -->
    <div class="form-card max-w-2xl mx-auto p-6 rounded-lg shadow-md mb-8">
      <div class="text-center mb-6">
        <div class="inline-flex items-center justify-center w-12 h-12 bg-blue-600 rounded-full mb-3">
          <i class="fas fa-cloud-upload-alt text-xl text-white"></i>
        </div>
        <h2 class="text-2xl font-bold text-blue-800">
          Upload New Images
        </h2>
        <p class="text-gray-600 mt-1">Add images to your gallery</p>
      </div>

      <form id="uploadForm" action="upload_handler.php" method="post" enctype="multipart/form-data" class="space-y-4">
        <!-- File Upload Area -->
        <div class="upload-area p-6 rounded-lg text-center cursor-pointer" id="uploadArea">
          <input type="file" name="images[]" id="images" accept="image/*" multiple class="hidden">
          <div id="uploadContent">
            <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-3"></i>
            <p class="text-md font-semibold text-gray-700 mb-1">Drop images here or click to browse</p>
            <p class="text-sm text-gray-500">Supports multiple images (JPG, PNG, GIF)</p>
          </div>
          <div id="previewContainer" class="hidden mt-4">
            <h3 class="text-md font-semibold mb-3">Selected Images:</h3>
            <div id="previewGrid" class="grid grid-cols-2 md:grid-cols-3 gap-3"></div>
          </div>
        </div>

        <!-- Form Fields -->
        <div class="grid md:grid-cols-1 gap-4">
          <div>
            <label for="title" class="block text-sm font-semibold text-gray-700 mb-1">
              <i class="fas fa-pencil-alt mr-1"></i>Title *
            </label>
            <input type="text" name="title" id="title" required
              class="input-field w-full px-3 py-2 rounded focus:outline-none"
              placeholder="Image title">
          </div>
        </div>

        <div>
          <label for="description" class="block text-sm font-semibold text-gray-700 mb-1">
            <i class="fas fa-align-left mr-1"></i>Description *
          </label>
          <textarea name="description" id="description" required rows="3"
            class="input-field w-full px-3 py-2 rounded focus:outline-none resize-none"
            placeholder="Image description..."></textarea>
        </div>

        <button type="submit" id="uploadBtn"
          class="admin-button w-full py-3 px-4 rounded text-white font-semibold shadow flex items-center justify-center">
          <i class="fas fa-upload mr-2"></i>
          <span id="uploadText">Upload Images</span>
          <div id="uploadSpinner" class="spinner ml-2 hidden"></div>
        </button>
      </form>
    </div>

    <!-- Uploaded Images Section -->
    <div class="mb-6">
      <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-bold text-gray-800 flex items-center">
          <i class="fas fa-images mr-2 text-blue-600"></i>
          Uploaded Images
        </h2>
        <div class="flex items-center">
          <span class="text-sm text-gray-600">
            <?php
            require_once '../includes/db_connect.php';
            $conn = getDBConnection();
            $count_result = $conn->query("SELECT COUNT(*) as total FROM images");
            $total_images = $count_result->fetch_assoc()['total'];
            echo $total_images . ' image' . ($total_images != 1 ? 's' : '');
            ?>
          </span>
        </div>
      </div>
      <div class="mb-8">
        <h2 class="text-xl font-bold text-gray-800 mb-4">
          Uploaded Images
        </h2>

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
                  <button class="delete-btn absolute top-2 right-2 z-10" 
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


    </div>
  </main>

  <!-- Image Editor Modal -->
  <div id="imageEditor" class="editor-modal fixed inset-0 hidden flex items-center justify-center z-50">
    <div class="editor-content max-w-3xl w-full mx-3 p-4">
      <div class="flex items-center justify-between mb-4">
        <h3 class="text-xl font-bold text-gray-800">
          <i class="fas fa-edit mr-1"></i>Edit Image
        </h3>
        <button id="closeEditor" class="text-gray-500 hover:text-gray-700 text-xl">
          <i class="fas fa-times"></i>
        </button>
      </div>

      <div class="grid lg:grid-cols-3 gap-4">
        <!-- Image Preview -->
        <div class="lg:col-span-2">
          <div class="cropper-container">
            <img id="cropperImage" src="/placeholder.svg" alt="Image to edit" style="max-width: 100%;">
          </div>
        </div>

        <!-- Controls -->
        <div class="space-y-4">
          <div>
            <h4 class="font-semibold text-gray-700 mb-2">Crop & Resize</h4>
            <div class="flex flex-col gap-2">
              <button class="control-btn w-full py-2" onclick="setCropRatio(1)">
                <i class="fas fa-square mr-1"></i>Square (1:1)
              </button>
              <button class="control-btn w-full py-2" onclick="setCropRatio(16/9)">
                <i class="fas fa-tv mr-1"></i>Landscape (16:9)
              </button>
              <button class="control-btn w-full py-2" onclick="setCropRatio(4/3)">
                <i class="fas fa-image mr-1"></i>Standard (4:3)
              </button>
              <button class="control-btn w-full py-2" onclick="setCropRatio(0)">
                <i class="fas fa-expand-arrows-alt mr-1"></i>Free Crop
              </button>
            </div>
          </div>

          <div>
            <h4 class="font-semibold text-gray-700 mb-2">Rotate</h4>
            <div class="flex flex-col gap-2">
              <button class="control-btn w-full py-2" onclick="rotateImage(-90)">
                <i class="fas fa-undo mr-1"></i>Rotate Left
              </button>
              <button class="control-btn w-full py-2" onclick="rotateImage(90)">
                <i class="fas fa-redo mr-1"></i>Rotate Right
              </button>
            </div>
          </div>

          <div>
            <h4 class="font-semibold text-gray-700 mb-2">Actions</h4>
            <div class="flex flex-col gap-2">
              <button class="control-btn w-full py-2" onclick="resetCropper()">
                <i class="fas fa-sync-alt mr-1"></i>Reset
              </button>
              <button class="control-btn w-full py-2 bg-green-600" onclick="applyCrop()">
                <i class="fas fa-check mr-1"></i>Apply Changes
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Lightbox -->
  <div id="lightbox" class="lightbox fixed inset-0 hidden flex items-center justify-center z-50">
    <div class="lightbox-content max-w-4xl w-full mx-3 p-4 flex flex-col max-h-[90vh]">
      <div class="flex-1 flex items-center justify-center overflow-auto relative">
        <img id="lightboxImage" src="/placeholder.svg" alt="Full Image" class="max-w-full max-h-full object-contain rounded">
        <div class="absolute bottom-2 right-2 flex gap-2">
          <button id="zoomIn" class="bg-black bg-opacity-70 text-white w-10 h-10 rounded-full flex items-center justify-center hover:bg-opacity-90">
            <i class="fas fa-plus"></i>
          </button>
          <button id="zoomOut" class="bg-black bg-opacity-70 text-white w-10 h-10 rounded-full flex items-center justify-center hover:bg-opacity-90">
            <i class="fas fa-minus"></i>
          </button>
          <button id="zoomReset" class="bg-black bg-opacity-70 text-white w-10 h-10 rounded-full flex items-center justify-center hover:bg-opacity-90">
            <i class="fas fa-sync-alt"></i>
          </button>
        </div>
      </div>
      <div class="bg-gray-50 p-3 rounded mt-3">
        <h3 id="lightboxTitle" class="text-lg font-bold text-gray-800 mb-1"></h3>
        <p id="lightboxDescription" class="text-gray-600 text-sm"></p>
      </div>
      <button id="closeLightbox" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-xl bg-white rounded-full w-10 h-10 flex items-center justify-center shadow">
        <i class="fas fa-times"></i>
      </button>
    </div>
  </div>

  <!-- Success/Error Popup -->
  <div id="popup" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm w-full mx-3 text-center">
      <div id="popupIcon" class="w-14 h-14 mx-auto mb-3 rounded-full flex items-center justify-center">
        <i id="popupIconClass" class="text-xl"></i>
      </div>
      <h2 id="popupTitle" class="text-xl font-bold mb-2"></h2>
      <p id="popupMessage" class="text-gray-600 mb-4 text-sm"></p>
      <button id="closePopup" class="admin-button px-4 py-2 rounded text-white font-semibold">
        OK
      </button>
    </div>
  </div>

  <script>
    // [Keep all the JavaScript code exactly the same as in your original version]
    // The JavaScript functionality doesn't need to change, only the styling
    let cropper = null;
    let currentFiles = [];
    let currentScale = 1;

    document.addEventListener('DOMContentLoaded', () => {
      initializeUpload();
      initializeLightbox();
      initializeDeleteButtons();
      handleUrlParams();
    });

    function initializeUpload() {
      const uploadArea = document.getElementById('uploadArea');
      const fileInput = document.getElementById('images');
      const uploadForm = document.getElementById('uploadForm');

      // Click to upload
      uploadArea.addEventListener('click', () => fileInput.click());

      // Drag and drop
      uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.classList.add('dragover');
      });

      uploadArea.addEventListener('dragleave', () => {
        uploadArea.classList.remove('dragover');
      });

      uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
        const files = Array.from(e.dataTransfer.files).filter(file => file.type.startsWith('image/'));
        handleFiles(files);
      });

      // File input change
      fileInput.addEventListener('change', (e) => {
        const files = Array.from(e.target.files);
        handleFiles(files);
      });

      // Form submission
      uploadForm.addEventListener('submit', (e) => {
        e.preventDefault();
        if (currentFiles.length === 0) {
          showPopup('error', 'No Images Selected', 'Please select at least one image to upload.');
          return;
        }
        submitForm();
      });
    }

    function handleFiles(files) {
      if (files.length === 0) return;

      currentFiles = files;
      displayPreviews(files);

      // Show editor for first image if only one file
      if (files.length === 1) {
        setTimeout(() => openImageEditor(files[0]), 500);
      }
    }

    function displayPreviews(files) {
      const previewContainer = document.getElementById('previewContainer');
      const previewGrid = document.getElementById('previewGrid');
      const uploadContent = document.getElementById('uploadContent');

      previewGrid.innerHTML = '';

      files.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = (e) => {
          const div = document.createElement('div');
          div.className = 'relative group';
          div.innerHTML = `
            <img src="${e.target.result}" alt="Preview" class="preview-image w-full h-28 object-cover">
            <button onclick="editImage(${index})" class="absolute inset-0 bg-black bg-opacity-50 text-white opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center rounded">
              <i class="fas fa-edit"></i>
            </button>
            <button onclick="removeImage(${index})" class="absolute top-1 right-1 bg-red-500 text-white w-5 h-5 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
              <i class="fas fa-times text-xs"></i>
            </button>
          `;
          previewGrid.appendChild(div);
        };
        reader.readAsDataURL(file);
      });

      uploadContent.classList.add('hidden');
      previewContainer.classList.remove('hidden');
    }

    function editImage(index) {
      if (currentFiles[index]) {
        openImageEditor(currentFiles[index], index);
      }
    }

    function removeImage(index) {
      currentFiles.splice(index, 1);
      if (currentFiles.length === 0) {
        document.getElementById('previewContainer').classList.add('hidden');
        document.getElementById('uploadContent').classList.remove('hidden');
      } else {
        displayPreviews(currentFiles);
      }
    }

    function openImageEditor(file, index = 0) {
      const modal = document.getElementById('imageEditor');
      const image = document.getElementById('cropperImage');

      const reader = new FileReader();
      reader.onload = (e) => {
        image.src = e.target.result;
        modal.classList.remove('hidden');

        // Initialize cropper
        if (cropper) {
          cropper.destroy();
        }

        cropper = new Cropper(image, {
          aspectRatio: NaN,
          viewMode: 1,
          dragMode: 'move',
          autoCropArea: 1,
          restore: false,
          guides: true,
          center: true,
          highlight: false,
          cropBoxMovable: true,
          cropBoxResizable: true,
          toggleDragModeOnDblclick: false,
        });

        // Store current file index
        modal.dataset.fileIndex = index;
      };
      reader.readAsDataURL(file);
    }

    function setCropRatio(ratio) {
      if (cropper) {
        cropper.setAspectRatio(ratio);
      }
    }

    function rotateImage(degrees) {
      if (cropper) {
        cropper.rotate(degrees);
      }
    }

    function resetCropper() {
      if (cropper) {
        cropper.reset();
      }
    }

    function applyCrop() {
      if (!cropper) return;

      const canvas = cropper.getCroppedCanvas({
        width: 800,
        height: 600,
        imageSmoothingEnabled: true,
        imageSmoothingQuality: 'high',
      });

      canvas.toBlob((blob) => {
        const fileIndex = parseInt(document.getElementById('imageEditor').dataset.fileIndex);
        const originalFile = currentFiles[fileIndex];

        // Create new file with edited content
        const editedFile = new File([blob], originalFile.name, {
          type: originalFile.type,
          lastModified: Date.now(),
        });

        currentFiles[fileIndex] = editedFile;
        displayPreviews(currentFiles);
        closeImageEditor();

        showPopup('success', 'Image Edited', 'Your image has been successfully edited.');
      }, currentFiles[0].type, 0.9);
    }

    function closeImageEditor() {
      const modal = document.getElementById('imageEditor');
      modal.classList.add('hidden');
      if (cropper) {
        cropper.destroy();
        cropper = null;
      }
    }

    function submitForm() {
      const uploadBtn = document.getElementById('uploadBtn');
      const uploadText = document.getElementById('uploadText');
      const uploadSpinner = document.getElementById('uploadSpinner');

      // Show loading state
      uploadBtn.disabled = true;
      uploadText.textContent = 'Uploading...';
      uploadSpinner.classList.remove('hidden');

      const formData = new FormData();

      // Add files
      currentFiles.forEach((file, index) => {
        formData.append('images[]', file);
      });

      // Add form fields
      formData.append('title', document.getElementById('title').value);
      formData.append('description', document.getElementById('description').value);

      fetch('upload_handler.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.text())
        .then(data => {
          // Reset form
          uploadBtn.disabled = false;
          uploadText.textContent = 'Upload Images';
          uploadSpinner.classList.add('hidden');

          if (data.includes('success')) {
            showPopup('success', 'Upload Successful', 'Your images have been uploaded successfully!');
            resetForm();
            setTimeout(() => location.reload(), 2000);
          } else {
            showPopup('error', 'Upload Failed', 'There was an error uploading your images. Please try again.');
          }
        })
        .catch(error => {
          uploadBtn.disabled = false;
          uploadText.textContent = 'Upload Images';
          uploadSpinner.classList.add('hidden');
          showPopup('error', 'Upload Error', 'Network error occurred. Please check your connection and try again.');
        });
    }

    function resetForm() {
      document.getElementById('uploadForm').reset();
      currentFiles = [];
      document.getElementById('previewContainer').classList.add('hidden');
      document.getElementById('uploadContent').classList.remove('hidden');
    }

    function initializeLightbox() {
      const galleryImages = document.querySelectorAll('.gallery-image');
      const lightbox = document.getElementById('lightbox');
      const lightboxImage = document.getElementById('lightboxImage');
      const lightboxTitle = document.getElementById('lightboxTitle');
      const lightboxDescription = document.getElementById('lightboxDescription');

      galleryImages.forEach(image => {
        image.addEventListener('click', () => {
          lightboxImage.src = image.dataset.full;
          lightboxTitle.textContent = image.dataset.title;
          lightboxDescription.textContent = image.dataset.description;
          lightbox.classList.remove('hidden');
          currentScale = 1;
          lightboxImage.style.transform = `scale(${currentScale})`;
        });
      });

      // Zoom controls
      document.getElementById('zoomIn').addEventListener('click', () => {
        currentScale = Math.min(currentScale + 0.2, 3);
        lightboxImage.style.transform = `scale(${currentScale})`;
      });

      document.getElementById('zoomOut').addEventListener('click', () => {
        currentScale = Math.max(currentScale - 0.2, 0.5);
        lightboxImage.style.transform = `scale(${currentScale})`;
      });

      document.getElementById('zoomReset').addEventListener('click', () => {
        currentScale = 1;
        lightboxImage.style.transform = `scale(${currentScale})`;
      });

      // Close lightbox
      document.getElementById('closeLightbox').addEventListener('click', () => {
        lightbox.classList.add('hidden');
      });

      lightbox.addEventListener('click', (e) => {
        if (e.target === lightbox) {
          lightbox.classList.add('hidden');
        }
      });
    }

    function initializeDeleteButtons() {
      const deleteButtons = document.querySelectorAll('.delete-btn');
      deleteButtons.forEach(button => {
        button.addEventListener('click', (e) => {
          e.stopPropagation();
          if (confirm('Are you sure you want to delete this image?')) {
            deleteImage(button.dataset.id, button.dataset.file, button);
          }
        });
      });
    }

    function deleteImage(id, file, button) {
      fetch('delete_image.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          body: `id=${id}&file=${file}`
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            button.closest('.image-card').remove();
            showPopup('success', 'Image Deleted', 'The image has been successfully deleted.');
          } else {
            showPopup('error', 'Delete Failed', data.message || 'Failed to delete image.');
          }
        })
        .catch(error => {
          showPopup('error', 'Delete Error', 'Network error occurred while deleting the image.');
        });
    }

    function showPopup(type, title, message) {
      const popup = document.getElementById('popup');
      const popupIcon = document.getElementById('popupIcon');
      const popupIconClass = document.getElementById('popupIconClass');
      const popupTitle = document.getElementById('popupTitle');
      const popupMessage = document.getElementById('popupMessage');

      if (type === 'success') {
        popupIcon.className = 'w-14 h-14 mx-auto mb-3 rounded-full flex items-center justify-center bg-green-100';
        popupIconClass.className = 'text-xl fas fa-check text-green-600';
        popupTitle.className = 'text-xl font-bold mb-2 text-green-600';
      } else {
        popupIcon.className = 'w-14 h-14 mx-auto mb-3 rounded-full flex items-center justify-center bg-red-100';
        popupIconClass.className = 'text-xl fas fa-exclamation-triangle text-red-600';
        popupTitle.className = 'text-xl font-bold mb-2 text-red-600';
      }

      popupTitle.textContent = title;
      popupMessage.textContent = message;
      popup.classList.remove('hidden');
    }

    function handleUrlParams() {
      const urlParams = new URLSearchParams(window.location.search);
      const status = urlParams.get('status');
      const message = urlParams.get('message');

      if (status) {
        if (status === 'success') {
          showPopup('success', 'Upload Successful', 'Images uploaded successfully!');
        } else if (status === 'error') {
          showPopup('error', 'Upload Failed', message ? decodeURIComponent(message) : 'An error occurred.');
        }

        // Clean URL
        window.history.replaceState({}, document.title, window.location.pathname);
      }
    }

    // Close popup
    document.getElementById('closePopup').addEventListener('click', () => {
      document.getElementById('popup').classList.add('hidden');
    });

    // Close editor
    document.getElementById('closeEditor').addEventListener('click', closeImageEditor);

    // Keyboard shortcuts
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') {
        document.getElementById('lightbox').classList.add('hidden');
        closeImageEditor();
        document.getElementById('popup').classList.add('hidden');
      }
    });
  </script>
</body>

</html>
<?php
ob_end_flush();
?>