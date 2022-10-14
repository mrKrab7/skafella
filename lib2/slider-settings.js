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
        delay: 10000,
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

var swiper = new Swiper(".card-product__swiper", {
    slidesPerView: 4,
    spaceBetween: 30,
    slidesPerGroup: 4,
    navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
    },
});


var cardsProductsSwiper = new Swiper(".cards-swiper__also-buy", {

    slidesPerView: 3,
    spaceBetween: 30,
    slidesPerGroup: 3,
    navigation: {
        nextEl:  ".also-buy-button-next",
        prevEl: ".also-buy-button-prev",
    },
    loop : true
});

var cardsProductsSwiper2 = new Swiper(".cards-swiper__similar-product", {

    slidesPerView: 3,
    spaceBetween: 30,
    slidesPerGroup: 3,
    navigation: {
        nextEl:  ".similar-products-button-next",
        prevEl: ".similar-products-button-prev",
    },
    loop : true
});

function setHeightCard(selector) {   // функция которая делает все карточки в слайдере одинаковой высоты
    let swiper = document.querySelector(selector);
    let cards = swiper.querySelectorAll('.card')
    let maxHeight = 0
    cards.forEach(item => {
        if (maxHeight < item.offsetHeight) {
            maxHeight = item.offsetHeight
        }
    })
    cards.forEach(item => {
        item.style.height = maxHeight + 'px'
    })
}

setHeightCard('.cards-swiper__also-buy')
setHeightCard('.cards-swiper__similar-product')