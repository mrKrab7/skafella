let mainSwiper = new Swiper(".main-swiper", {


    pagination: {
        el: ".swiper-pagination",
        clickable: true,
    },
    navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
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

let cardsSwiper = new Swiper(".projects__swiper", {




    navigation: {
        nextEl: "#project__swiperNext",
        prevEl: "#project__swiperPrev",
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

let reviewsSwiper = new Swiper(".reviews-swiper", {
    slidesPerView: 3,
    spaceBetween: 50,
    slidesPerGroup: 3,


    navigation: {
        nextEl: ".reviews__swiper-button-next",
        prevEl: ".reviews__swiper-button-prev",
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