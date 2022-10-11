const swiper = new Swiper('.swiper', {
    // Optional parameters
    direction: 'horizontal',
    loop: true,
    autoplay: {
        delay: 3500,
        disableOnInteraction: false,

      },

  breakpoints: {
    // when window width is <= 320px
    320: {
      slidesPerView: 1,
      spaceBetween: 0
    },
    // when window width is <= 480px
    480: {
      slidesPerView: 1,
      spaceBetween: 0
    },
    // when window width is <= 640px
    640: {
      slidesPerView: 1,
      spaceBetween: 0
    }
  },

    //   freeMode: false,
      speed: 2000,
    //   freeModeMomentum: true,

    // If we need pagination
    pagination: {
      el: '.swiper-pagination',
      clickable: true,

      dynamicBullets: true,

    },

    // Navigation arrows
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },

    // And if we need scrollbar
    scrollbar: {
      // el: '.swiper-scrollbar',
    },
  });



