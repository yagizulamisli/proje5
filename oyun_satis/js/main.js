// Menü aç/kapat
document.addEventListener("DOMContentLoaded", function () {
    const menu = document.querySelector('.menu-icon');
    const navbar = document.querySelector('.menu');
    const bell = document.querySelector('.notification');
    const cartIcon = document.getElementById("cart-icon");
    const cartPanel = document.getElementById("cart-panel");

    // Menü tıklama
    if (menu && navbar && bell) {
        menu.addEventListener("click", () => {
            navbar.classList.toggle('active');
            menu.classList.toggle('move');
            bell.classList.remove('active');
        });
    }

    // Bildirim zili tıklama
    const bellIcon = document.getElementById('bell-icon');
    if (bellIcon && bell) {
        bellIcon.addEventListener("click", () => {
            bell.classList.toggle('active');
        });
    }



    // Scroll ilerleme efekti
    window.onscroll = function () {
        const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
        const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        const scrolled = (winScroll / height) * 100;
        const scrollBar = document.getElementById('scroll-bar');
        if (scrollBar) scrollBar.style.width = scrolled + '%';
    };

    // Swiper (slider)
    new Swiper(".trending-content", {
        slidesPerView: 1,
        spaceBetween: 10,
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        breakpoints: {
            640: {
                slidesPerView: 2,
                spaceBetween: 10,
            },
            768: {
                slidesPerView: 3,
                spaceBetween: 15,
            },
            1068: {
                slidesPerView: 4,
                spaceBetween: 20,
            },
        }
    });
});
 document.addEventListener("DOMContentLoaded", function () {
    const cartIcon = document.getElementById("cart-icon");
    const cartPanel = document.getElementById("cart-panel");

    cartIcon.addEventListener("click", function (e) {
      e.stopPropagation();
      cartPanel.classList.toggle("show");
    });

    document.addEventListener("click", function (e) {
      if (!cartPanel.contains(e.target) && !cartIcon.contains(e.target)) {
        cartPanel.classList.remove("show");
      }
    });
  });