function showHeaderFixed() {
    let headerFixed = document.querySelector('.header-fix__wrapper')
    window.addEventListener('scroll', function (e) {
        if (this.scrollY > 1000) {
            headerFixed.classList.add('header-show')
        } else headerFixed.classList.remove('header-show')
    })
}
showHeaderFixed()

function scrollTop() {
    let header = document.querySelector('header')
    let scrollBtn = document.querySelector('.btn-scroll')
    scrollBtn.addEventListener('click', function () {
        header.scrollIntoView({
            behavior: "smooth",
            block:    "start"
        });
    })
}
scrollTop()

