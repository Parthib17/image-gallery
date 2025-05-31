document.addEventListener('DOMContentLoaded', () => {
  const galleryImages = document.querySelectorAll('.gallery-image');
  const lightbox = document.getElementById('lightbox');
  const lightboxImage = document.getElementById('lightbox-image');
  const lightboxTitle = document.getElementById('lightbox-title');
  const lightboxDescription = document.getElementById('lightbox-description');
  const closeLightbox = document.getElementById('close-lightbox');

  function resizeLightboxImage() {
      const maxHeight = window.innerHeight * 0.6; // 60% of window height for image
      lightboxImage.style.maxHeight = `${maxHeight}px`;
      lightboxImage.style.width = 'auto';
  }

  galleryImages.forEach(image => {
      image.addEventListener('click', () => {
          lightboxImage.src = image.dataset.full;
          lightboxTitle.textContent = image.dataset.title || 'No Title';
          lightboxDescription.textContent = image.dataset.description || 'No Description';
          lightbox.classList.remove('hidden');
          setTimeout(() => lightbox.classList.add('show'), 10); // Delay for transition
          resizeLightboxImage();
      });
  });

  closeLightbox.addEventListener('click', () => {
      lightbox.classList.remove('show');
      setTimeout(() => lightbox.classList.add('hidden'), 300); // Match transition duration
  });

  lightbox.addEventListener('click', (e) => {
      if (e.target === lightbox) {
          lightbox.classList.remove('show');
          setTimeout(() => lightbox.classList.add('hidden'), 300);
      }
  });

  window.addEventListener('resize', resizeLightboxImage);
});