var mainSwiper = new Swiper(".main-swiper", {


    pagination: {
        el: ".swiper-pagination",
        clickable: true,
    },

    loop: true,
    autoplay: {
        delay: 5000,
        disableOnInteraction: false,
    },
    on: {
        init() {
            this.el.addEventListener('mouseenter', () => {
                this.autoplay.stop();
            });

            this.el.addEventListener('mouseleave', () => {
                this.autoplay.start();
            });
        }
    }
});