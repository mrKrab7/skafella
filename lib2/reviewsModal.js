function reviewsModal() {
    const postData = async (url, data, type) => {


        let res = await fetch(url, {
            method: "POST",
            body: data
        });
        if (type === 'json') {
            return await res.json();
        }
        if (type === 'text') {
            return await res.text();
        }

    }
    function openModal(obj) {
        let modal = document.querySelector('.modal-review')
        let photo = modal.querySelector('.reviews__user')
        let name = modal.querySelector('.reviews__username')
        let date = modal.querySelector('.reviews__date')
        let stars = modal.querySelectorAll('.star_review')
        let descr = modal.querySelector('.modal-review__text')
        let img = modal.querySelector('.modal-review__img-wrapper')

        descr.textContent = obj.descr
        photo.src = obj.user_photo
        name.textContent = obj.username
        date.textContent = obj.date
        modal.style.display = 'block'
        document.body.style.overflow = 'hidden'


        stars.forEach(item => {

            let value = item.dataset.value
            if (value <= obj.star) {
                item.classList.add('star-active')
            } else item.classList.remove('star-active')
        })
        if (obj.images !== '') {
            let srcArray = []
            let str = ''
            srcArray = obj.images.match(/<img [^>]*src="[^"]*"[^>]*>/gm)
                .map(x => x.replace(/.*src="([^"]*)".*/, '$1'))
            console.log(srcArray)

            srcArray.forEach(src => {
                str += `<div class=" col-3 col-md-3 d-flex justify-content-center">
                 <a data-fancybox="reviews-gallery" href="${src}">
                <div class="modal-review__img"><img class="img-fluid" src="${src}" alt=""></div>
                </a>
            </div>`
            })
            img.innerHTML = str
        } else img.innerHTML = ''


        console.log(modal)
        console.log(obj)
    }
    let nameUserSlide = document.querySelectorAll('.swiper-slide .reviews__username')
    let video = document.querySelectorAll('.reviews-video__wrapper')

    let reviewsModal = document.querySelectorAll('.reviews-modal')
    video.forEach(item => {
        item.addEventListener('click', function () {
            this.classList.remove('reviews-video__wrapper')
            let img = this.querySelector('img')
            let frameWrapper = this.querySelector('.ratio')
            let frame = this.querySelector('iframe')
            let idVideo = img.dataset.youtube
            let linkFrame = `https://www.youtube.com/embed/${idVideo}?feature=oembed&autoplay=1`
            img.style.display = 'none'
            console.log(frame)
            frame.src = linkFrame
            frameWrapper.classList.remove('d-none')


            console.log(img)
        })
    })
    if (window.innerWidth <= 425) {
        nameUserSlide.forEach(item => {
            item.innerHTML = item.textContent.slice(0, 8) + '...'
        })
    }

    reviewsModal.forEach(item => {
        item.addEventListener('click', function () {
            let id = item.dataset.id
            let formData = new FormData
            formData.append('id', id)
            postData('/zed/modules/feedback/sendmessage.php', formData, 'json')
                .then((res) => {
                    openModal(res)
                })
        })
    })

}

reviewsModal()
