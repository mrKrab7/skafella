const tabsCalculator = (headerSelector, tabSelector, contentSelector, activeClass, nextButton) => {
    const header = document.querySelector(headerSelector),
        tab = document.querySelectorAll(tabSelector),
        content = document.querySelectorAll(contentSelector),

        nextBtn = document.querySelectorAll(nextButton)
    let previewTabActive = +header.dataset.tabActive - 1
    currentTab = 0
    // console.log(currentTab)
    if (nextBtn !== undefined) {
        nextBtn.forEach(item => {
            item.addEventListener('click', function () {

                hideTabContent();
                currentTab++
                showTabContent(currentTab)

            })
        })

    }


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
    hideTabContent()

    if (isNaN(previewTabActive) || previewTabActive === -1) {
        showTabContent(0)
    } else showTabContent(previewTabActive)


}
// tabs('.card-product__header-wrapper', '.card-product__header-tab', '.card-product__tab-content', 'card-product__tab-active')
tabsCalculator('.calculator-header', '.calculator-header__item', '.calculator-content', 'calculator__item-active', '.calculator__next')
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