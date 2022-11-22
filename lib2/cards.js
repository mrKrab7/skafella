function getCards() {
    let sortedTrigger = document.querySelectorAll('.sorted-trigger')
    let headerPrice = document.querySelector('.price-sort')
    const postData = async (url, data) => {


        let res = await fetch(url, {
            method: "POST",
            body: data
        });
        return await res.json();
    }

    function removeCheckedInFilter(elementSelector) {
        let elements = document.querySelectorAll(elementSelector)
        elements.forEach(item => {
            item.addEventListener('click', function () {
                if (this.classList.contains('header-filter-active')) {
                    item.checked = false
                    this.classList.remove('header-filter-active')
                } else this.classList.add('header-filter-active')

            })
        })
    }

    removeCheckedInFilter('input[name="header-sorted"]')

    function searchCheckedElements(elementSelector) {
        let elements = document.querySelectorAll(elementSelector)
        let obj = {}
        elements.forEach((item, index) => {
            if (item.checked) {
                let postName = item.dataset.post
                let postValue = item.dataset.article
                obj[index] = `${postName}-${postValue} `
            }
        })
        return JSON.stringify(obj)


    }

    function searchAdditionalParam(elementSelector) {


        let additionalParam = document.querySelectorAll(elementSelector)
        let checkedElem = ''
        additionalParam.forEach(item => {
            if (item.checked) {
                let dataPost = item.dataset.post
                checkedElem = dataPost
            }

        })
        return checkedElem
    }

    function parse(res) {
        let numberDisplayCards = 12
        function parsePagination(arr) {

            let btnWrapper = document.querySelector('.navigation__page-wrapper')
            let pagination = document.querySelector('.pagination')

            function numberofbuttons(arr, num) {              // расчёт количества кнопок для погинаций
                return Math.ceil(arr.length / num)
            }

            function paintPaginationButton(count) {
                let htmlButton = ''
                for (let i = 1; i <= count; i++) {
                    if (i === 1) {
                        htmlButton += `<span class="my-page-link pagination_active">${i}</span>`
                    } else htmlButton += `<span class="my-page-link ">${i}</span>`

                }
                return htmlButton
            }

            function slicePagination() {
                btnWrapper.innerHTML = paintPaginationButton(numberofbuttons(arr, 2))
                let btn = btnWrapper.querySelectorAll('.page-item')
                console.log(btn)
                if (btn.length >= 4) {
                    let lastIndex = btn.length - 1
                    console.log(lastIndex)
                    btn.forEach((item, i) => {
                        console.log(i)
                        if (i >= 3) {
                            console.log(item)
                            item.style.display = 'none'
                        }
                        if (i === lastIndex) {
                            item.classList.add('last__pagination')
                        }


                    })

                }


            }

            btnWrapper.innerHTML = paintPaginationButton(numberofbuttons(arr, numberDisplayCards))
            btnWrapper.addEventListener('click', function (e) {
                let target = e.target
                let paginationItem = btnWrapper.querySelectorAll("span")
                if (target.classList.contains('my-page-link')) {
                    let number = target.textContent
                    var start = numberDisplayCards * (number - 1);
                    var end = numberDisplayCards * number;
                    paginationItem.forEach(item => {
                        item.classList.remove('pagination_active')
                    })
                    target.classList.add('pagination_active')
                    parseCards(res.slice(start, end))
                }


            })


        }

        function parseCards(arr) {
            let cardWrapper = document.querySelector('.cards-wrapper')

            let hit = ''
            let popular = ''
            let htmlResult = ''
            arr.forEach(card => {
                if (card.hit === '1') {
                    hit = 'cards-flag-hit'

                } else hit = ''
                if (card.new === '1') {
                    popular = 'cards-flag-new'

                } else popular = ''
                htmlResult += `<div class="col-md-6 col-lg-4 mt-5">
                <div class="card products__cards border-0 cards-flag ${hit} ${popular} h-100">
                    <img src="${card.img}" class="card-img-top" alt="...">
                        <div class="card-body">
                            <div class="products__cards-subtitle">${card.name}
                            </div>
                            <ul class="">
                                ${card.desc}
                            </ul>
                            <div class="products__cards-price">Цена ${card.cost} руб</div>
                        </div>
                        <div class="card-footer bg-white border-0">
                                <a href="/cost?id=${card.id}">
                                 <button class="button products__cards-btn-green">Заказать похожий</button>
                                </a>
                                 <a href="${card.url}">
                                  <button class="button products__cards-btn">Подробнее</button>
                                 </a>
                           
                           
                        </div>
                </div>
            </div>`


            })

            cardWrapper.innerHTML = htmlResult
        }

        parseCards(res.slice(0, numberDisplayCards))
        parsePagination(res)
    }

    (function firstLoadCards() {

        let formData = new FormData()
        formData.append('cards', searchCheckedElements('input[name="type-sorted"]'))
        formData.append('cost', searchAdditionalParam('input[name="header-sorted"]'))
        formData.append('dop', searchAdditionalParam())


        postData('/zed/modules/canvas/canvas_cards.php', formData)
            .then(res => {

                parse(res)

            })
    })()


    sortedTrigger.forEach(item => {
        item.addEventListener('click', function () {
            let formData = new FormData()
            if (this.classList.contains('price-sort')) {
                let postName = this.dataset.post
                if (postName === '') {
                    this.dataset.post = 'decrease'

                }
                if (postName === 'increase') {
                    this.dataset.post = 'decrease'
                }
                if (postName === 'decrease') {
                    this.dataset.post = 'increase'
                }


                formData.append('cards', searchCheckedElements('input[name="type-sorted"]'))
                formData.append('cost', this.dataset.post)
                formData.append('dop', searchAdditionalParam('input[name="header-sorted"]'))


            } else {


                formData.append('cards', searchCheckedElements('input[name="type-sorted"]'))
                formData.append('dop', searchAdditionalParam('input[name="header-sorted"]'))
                formData.append('cost', headerPrice.dataset.post)


            }

            postData('/zed/modules/canvas/canvas_cards.php', formData)
                .then(res => {

                    parse(res)

                })


        })

    })

}

getCards()