let tabs = (headerSelector, tabSelector, contentSelector, activeClass) => {
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
if (document.querySelector('.card-product__header-wrapper') !== null) {
    tabs('.card-product__header-wrapper', '.card-product__header-tab', '.card-product__tab-content', 'card-product__tab-active')
}

