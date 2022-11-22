function sendReviews() {
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
    let form = document.querySelector('.reviews-form')
    let btn = form.querySelector('.card-product__review-btn')
    let stars = document.querySelectorAll('.star_review')
    let input = form.querySelector('input');
    let textArea = form.querySelector('textarea');
    let currentStarIndex = 0

    function fillStar(item) {
        currentStarIndex = item.dataset.value
        stars.forEach(star => {
            let index = star.dataset.value
            if (index <= currentStarIndex) {
                star.classList.add('star-active')
            } else star.classList.remove('star-active')
        })
    }

    btn.addEventListener('click', function () {
        if (currentStarIndex === 0) {
            this.type = 'button'
            this.classList.add('review__alert')
        } else {
            this.type = 'submit'

        }
    })

    stars.forEach(item => {
        item.addEventListener('click', function () {
            btn.classList.remove('review__alert')
            fillStar(item)
        })
    })


    form.addEventListener('submit', function (e) {
        e.preventDefault()
        let formData = new FormData(form)
        formData.append('otziv',' ')
        formData.append('star', currentStarIndex)
        postData('/zed/modules/feedback/sendmessage.php', formData, 'text')
            .then((res) => {
                console.log(res)
            })
            .finally(()=> {
                let modal = document.querySelector('.modal-feedback')
                let desr = modal.querySelector('.modal__descr')
                desr.style.width = '330px'
                desr.innerHTML = 'Ваш отзыв будет опубликован после проверки администратором!'
                modal.style.display = 'block'
                input.value = ''
                textArea.value = ''
                stars.forEach(item => {
                    item.classList.remove('star-active')
                })
            })




    })

}

sendReviews()