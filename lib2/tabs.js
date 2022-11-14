const tabsCalculator = (headerSelector, tabSelector, contentSelector, activeClass, nextButton, prevButton) => {
    const header = document.querySelector(headerSelector),
        tab = document.querySelectorAll(tabSelector),
        content = document.querySelectorAll(contentSelector),
        prevBtn = document.querySelectorAll(prevButton),
        nextBtn = document.querySelectorAll(nextButton)
    let previewTabActive = +header.dataset.tabActive - 1
    let cabinetParameters = document.querySelectorAll('input[name="cabinet-parameters"]')
    let cabinetParametersChecked = document.querySelector('input[name="cabinet-parameters"]:checked').id
    let currentTab = 0
    let modal = document.querySelector('.modal-calc')
    let headerItem = document.querySelectorAll('.calculator-header__item')

    function coupePlus() {

        hideTabContent()
        showTabContent(currentTab)
        if (currentTab < 2){
            currentTab++
            hideTabContent()
            showTabContent(currentTab)
        } else {
            modal.style.display = 'block'
            document.body.style.overflow
        }
        console.log(currentTab)
    }  // фунция для шкафа купе

    function coupeMinus() {
        currentTab--
        hideTabContent()
        showTabContent(currentTab)
        console.log(currentTab)
    } // фунция для шкафа купе

    function coupe1Plus() {

        if (currentTab === 0) {
            currentTab = 1
        } else {
            modal.style.display = 'block'
        }
        hideTabContent()
        showTabContent(currentTab)
        console.log(currentTab)
    } //фунция для  Только двери

    function coupe1Minus() {
      if (currentTab === 0) {
          currentTab = 2
          hideTabContent()
          showTabContent(currentTab)
      } else {
          modal.style.display = 'block'
      }


    } //фунция для  Только двери

    function coupe2Plus() {

        if (currentTab === 0) {
            currentTab = 2
        } else {
            modal.style.display = 'block'
        }
        hideTabContent()
        showTabContent(currentTab)
        console.log(currentTab)
    } //фунция для  Только внутреннее наполнение

    function coupe2Minus() {
        if (currentTab === 2) {
            currentTab = 0
            hideTabContent()
            showTabContent(currentTab)
        }


    }// фунция для  Только внутреннее наполнение
    console.log(cabinetParametersChecked)
    if (cabinetParametersChecked !== null && cabinetParametersChecked === 'coupe') {

        nextBtn.forEach(item => {
            item.addEventListener('click', coupePlus)
        })
        prevBtn.forEach(item => {
            item.addEventListener('click', coupeMinus)
        })
    }
    if (cabinetParametersChecked !== null && cabinetParametersChecked === 'coupe1') {

        nextBtn.forEach(item => {
            item.addEventListener('click', coupe1Plus)
        })

        prevBtn.forEach(item => {
            item.addEventListener('click', coupe1Minus)
        })

    }
    if (cabinetParametersChecked !== null && cabinetParametersChecked === 'coupe2') {

        nextBtn.forEach(item => {
            item.addEventListener('click', coupe2Plus)
        })

        prevBtn.forEach(item => {
            item.addEventListener('click', coupe2Minus)
        })

    }

    cabinetParameters.forEach(item => {

        item.addEventListener('click', function () {
            headerItem.forEach(item => {
                item.style.pointerEvents = 'auto'
                item.style.opacity = '1'
            })
            currentTab = 0
            nextBtn.forEach(item => {
                item.removeEventListener('click', coupePlus)
                item.removeEventListener('click', coupe1Plus)
                item.removeEventListener('click', coupe2Plus)
            })
            prevBtn.forEach(item => {
                item.removeEventListener('click', coupeMinus)
                item.removeEventListener('click', coupe1Minus)
                item.removeEventListener('click', coupe2Minus)
            })
            console.log(this)

            if (this.id === 'coupe') {
                console.log(1)
                nextBtn.forEach(item => {
                    item.addEventListener('click', coupePlus)
                })
                prevBtn.forEach(item => {
                    item.addEventListener('click', coupeMinus)
                })
            }
            if (this.id === 'coupe1') {
                headerItem[2].style.pointerEvents = 'none'
                headerItem[2].style.opacity = '.5'
                nextBtn.forEach(item => {
                    item.addEventListener('click', coupe1Plus)
                })

                prevBtn.forEach(item => {
                    item.addEventListener('click', coupe1Minus)
                })


            }

            if (this.id === 'coupe2') {
                headerItem[1].style.pointerEvents = 'none'
                headerItem[1].style.opacity = '.5'
                nextBtn.forEach(item => {
                    item.addEventListener('click', coupe2Plus)
                })

                prevBtn.forEach(item => {
                    item.addEventListener('click', coupe2Minus)
                })

            }
        })

    })


    function hideTabContent() {
        content.forEach((item) => {
            item.style.display = 'none'
        });
        tab.forEach((item) => {
            item.classList.remove((activeClass))
        })
    }

    function showTabContent(i = 0) {
        if (i > content.length - 1) {
            content[content.length - 1].style.display = 'block';
            tab[content.length - 1].classList.add(activeClass);

        } else {
            content[i].style.display = 'block';
            tab[i].classList.add(activeClass);
        }

    }

    header.addEventListener('click', (e) => {

        const target = e.target


        tab.forEach((item, i) => {
            if (target === item || target.parentNode === item) {
                currentTab = i
                hideTabContent();
                showTabContent(i)

            }
        })

    })
    hideTabContent()

    if (isNaN(previewTabActive) || previewTabActive === -1) {
        showTabContent(0)
    } else showTabContent(previewTabActive)


}
// tabs('.card-product__header-wrapper', '.card-product__header-tab', '.card-product__tab-content', 'card-product__tab-active')
tabsCalculator('.calculator-header', '.calculator-header__item', '.calculator-content', 'calculator__item-active', '.calculator__next', '.calculator__prev')
const tabs = (headerSelector, tabSelector, contentSelector, activeClass,) => {
    const header = document.querySelector(headerSelector),
        tab = document.querySelectorAll(tabSelector),
        content = document.querySelectorAll(contentSelector)


    function hideTabContent() {
        content.forEach((item) => {
            item.style.display = 'none'
        });
        tab.forEach((item) => {
            item.classList.remove((activeClass))
        })
    }

    function showTabContent(i = 0) {

        content[i].style.display = 'block';
        tab[i].classList.add(activeClass);

    }

    header.addEventListener('click', (e) => {

        const target = e.target


        tab.forEach((item, i) => {
            if (target === item || target.parentNode === item) {
                currentTab = i
                hideTabContent();
                showTabContent(i)

            }
        })

    })
    hideTabContent();
    showTabContent();


}
tabs('.calc__doors-header', '.calc__doors-tab', '.calc__doors-content', 'calc__doors-active')