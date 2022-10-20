const tabs = (headerSelector, tabSelector, contentSelector, activeClass) => {
    const header = document.querySelector(headerSelector),
        tab = document.querySelectorAll(tabSelector),
        content = document.querySelectorAll(contentSelector);
    let previewTabActive = +header.dataset.tabActive - 1


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
        console.log(previewTabActive)
        const target = e.target


        tab.forEach((item, i) => {
            if (target === item || target.parentNode === item) {

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
tabs('.calculator-header','.calculator-header__item','.calculator-content', 'calculator__item-active' )
