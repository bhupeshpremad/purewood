// Header Search Input
$(document).ready(function () {
  $('#desktopSearchIcon').on('click', function () {
    $('#desktopSearchBox').toggleClass('d-none');
    $('#searchInputField').focus();
  });

  $('#closeSearchBox').on('click', function () {
    $('#desktopSearchBox').addClass('d-none');
  });

  $(document).on('click', function (e) {
    if (!$(e.target).closest('#desktopSearchBox, #desktopSearchIcon').length) {
      $('#desktopSearchBox').addClass('d-none');
    }
  });
});

// Home Banner Slider
document.addEventListener('DOMContentLoaded', function () {
  const banner = document.querySelector('#bannerSlider');
  if (banner) {
    new Splide(banner, {
      type: 'loop',
      autoplay: true,
      interval: 4000,
      pauseOnHover: false,
      arrows: false,
      pagination: false,
    }).mount();
  }

  const thumbnailSlider = document.querySelector('.thumbnail-slider');
  if (thumbnailSlider) {
    new Splide(thumbnailSlider, {
      type: 'slide',
      perPage: 4,
      gap: '10px',
      pagination: false,
      breakpoints: {
        768: { perPage: 3 },
        576: { perPage: 2 }
      }
    }).mount();
  }

  const gallery = document.getElementById('lightgallery');
  if (gallery) {
    lightGallery(gallery, {
      selector: '.gallery-item',
      plugins: [lgZoom],
      download: false,
      share: false,
    });
  }

  const mainImg = document.getElementById('mainProductImage');
  const anchor = document.querySelector('#lightgallery a.gallery-item');
  if (mainImg && anchor) {
    document.querySelectorAll('.thumbnail-slider img').forEach(function (img) {
      img.addEventListener('click', function () {
        const fullSize = this.getAttribute('data-fullsize');
        mainImg.src = fullSize;
        anchor.href = fullSize;
      });
    });
  }
});
