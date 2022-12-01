function setHeightCard(selector) {   // функция которая делает все карточки в слайдере одинаковой высоты
    let swiper = document.querySelector(selector);
    // console.log(pro)
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
if (document.querySelector('.main-swiper') !== null) {
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
}

if (document.querySelector('.projects__swiper') !== null) {
    // setHeightCard('.projects__swiper')
    let cardsSwiper = new Swiper(".projects__swiper", {
        slidesPerView: 2,
        spaceBetween: 20,

        navigation: {
            nextEl: "#project__swiperNext",
            prevEl: "#project__swiperPrev",
        },

        loop: true,
        // autoplay: {
        //     delay: 5000,
        //     disableOnInteraction: false,
        // },
        breakpoints: {
            // mobile + tablet - 320-990
            320: {
                slidesPerView: 1,
                spaceBetween: 0,
            },
            // desktop >= 991
            768: {
                slidesPerView: 2
            }
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
}
if (document.querySelector('.reviews-swiper') !== null) {
    let reviewsSwiper = new Swiper(".reviews-swiper", {
        slidesPerView: 3,
        spaceBetween: 45,
        slidesPerGroup: 3,


        navigation: {
            nextEl: ".reviews__swiper-button-next",
            prevEl: ".reviews__swiper-button-prev",
        },
        breakpoints: {
            // mobile + tablet - 320-990
            320: {
                slidesPerView: 1,
                spaceBetween: 60,
                slidesPerGroup: 1,
            },
            // desktop >= 991
            576: {
                slidesPerView: 2,
                slidesPerGroup: 2,

            },
            1199: {
                slidesPerView: 3,
                slidesPerGroup: 3,
            }
        },

        loop: true,
        // autoplay: {
        //     delay: 10000,
        //     disableOnInteraction: false,
        // },
        // on: {
        //     init() {
        //         this.el.addEventListener('mouseenter', () => {
        //             this.autoplay.stop();
        //         });
        //
        //         this.el.addEventListener('mouseleave', () => {
        //             this.autoplay.start();
        //         });
        //     }
        // }
    });
}
if (document.querySelector('.card-product__swiper') !== null) {
    var swiper = new Swiper(".card-product__swiper", {
        slidesPerView: 4,
        spaceBetween: 30,
        slidesPerGroup: 4,

        breakpoints: {
            // mobile + tablet - 320-990
            320: {
                slidesPerView: 3,
                spaceBetween: 10,
                slidesPerGroup: 1,
            },
            // desktop >= 991
            768: {
                slidesPerView: 4,
                slidesPerGroup: 4,

            },

        },
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
    });
    function hoverChangeImg () {
        let bigImg = document.querySelector('.img-big')
        let imagesHover = document.querySelectorAll('.small-img')
        function clearBigImg() {
            bigImg.src  = bigImg.dataset.src
        }
        imagesHover.forEach(img => {
            img.addEventListener('mouseover', function (e) {

                let target = e.target
                if (target.src !== undefined) {
                    bigImg.src = target.src
                }

            })
            img.addEventListener('mouseout', clearBigImg)
            img.addEventListener('click', clearBigImg)
        })




    }
    hoverChangeImg()



}



if (document.querySelector('.cards-swiper__also-buy') !== null) {
    var cardsProductsSwiper = new Swiper(".cards-swiper__also-buy", {

        // slidesPerView: 3,
        // spaceBetween: 30,
        // slidesPerGroup: 3,
        breakpoints: {
            320: {
                slidesPerView: 1,
                spaceBetween: 30,
                slidesPerGroup: 1,
            },
            768: {
                slidesPerView: 2,
                spaceBetween: 30,
                slidesPerGroup: 2,
            },
            // desktop >= 991
            1200: {
                slidesPerView: 3,
                slidesPerGroup: 3,
                spaceBetween: 30,

            },
        },
        navigation: {
            nextEl: ".also-buy-button-next",
            prevEl: ".also-buy-button-prev",
        },
        loop: true
    });
    setHeightCard('.cards-swiper__also-buy')
}

if (document.querySelector('.cards-swiper__similar-product') !== null) {
    var cardsProductsSwiper2 = new Swiper(".cards-swiper__similar-product", {

        slidesPerView: 3,
        spaceBetween: 30,
        slidesPerGroup: 3,
        breakpoints: {
            320: {
                slidesPerView: 1,
                spaceBetween: 30,
                slidesPerGroup: 1,
            },
            768: {
                slidesPerView: 2,
                spaceBetween: 30,
                slidesPerGroup: 2,
            },
            // desktop >= 991
            1200: {
                slidesPerView: 3,
                slidesPerGroup: 3,
                spaceBetween: 30,

            }
        },

        navigation: {
            nextEl: ".similar-products-button-next",
            prevEl: ".similar-products-button-prev",
        },
        loop: true
    });
    setHeightCard('.cards-swiper__similar-product')
}







