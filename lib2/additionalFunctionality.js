// function showHeaderFixed() {
//     let headerFixed = document.querySelector('.header-fix__wrapper')
//     window.addEventListener('scroll', function (e) {
//         if (this.scrollY > 1000) {
//             headerFixed.classList.add('header-show')
//         } else headerFixed.classList.remove('header-show')
//     })
// }

// function scrollTop() {
//     let header = document.querySelector('header')
//     let scrollBtn = document.querySelector('.btn-scroll')
//     scrollBtn.addEventListener('click', function () {
//         header.scrollIntoView({
//             behavior: "smooth",
//             block: "start"
//         });
//     })
// }


// function maskNumber() {
//     document.addEventListener('DOMContentLoaded', () => {
//         let numberInputs = document.querySelectorAll("[data-mask][data-slots]")
//         for (const el of numberInputs) {
//
//             const pattern = el.getAttribute("data-mask"),
//                 slots = new Set(el.dataset.slots || "_"),
//                 prev = (j => Array.from(pattern, (c, i) => slots.has(c) ? j = i + 1 : j))(0),
//                 first = [...pattern].findIndex(c => slots.has(c)),
//                 accept = new RegExp(el.dataset.accept || "\\d", "g"),
//                 clean = input => {
//                     input = input.match(accept) || [];
//                     return Array.from(pattern, c =>
//                         input[0] === c || slots.has(c) ? input.shift() || c : c
//                     );
//                 },
//                 format = () => {
//                     const [i, j] = [el.selectionStart, el.selectionEnd].map(i => {
//                         i = clean(el.value.slice(0, i)).findIndex(c => slots.has(c));
//                         return i < 0 ? prev[prev.length - 1] : back ? prev[i - 1] || first : i;
//                     });
//                     el.value = clean(el.value).join``;
//                     el.setSelectionRange(i, j);
//                     back = false;
//                 };
//             let back = false;
//             el.addEventListener("keydown", (e) => back = e.key === "Backspace");
//             el.addEventListener("input", format);
//
//
//             el.addEventListener("focus", format);
//             el.addEventListener("blur", () => el.value === pattern && (el.value = ""));
//         }
//         numberInputs.forEach(item => {
//             item.addEventListener('input', function () {
//
//
//                 let number = this.value.replace(/[^0-9]/g, '')
//
//                 if (number.length < 11) {
//                     this.setCustomValidity('вы не вели номер телефона')
//                 } else {
//                     this.setCustomValidity('')
//                 }
//
//
//                 console.log(number)
//                 console.log(number.length)
//             })
//         })
//
//
//     });
// }
function HeaderDropMenu() {
    let button = document.querySelector('.close-menu-btn')
    let menu = document.querySelector('.header__nav_content')
    let openMenu = document.querySelector('.nav-drop')
    let menuContent = document.querySelector('.header__nav_content')
    openMenu.addEventListener('mouseover', function () {
        menu.style.visibility = 'visible'
        menu.style.opacity = '1'
        openMenu.classList.add('arrow-transform')
    })
    openMenu.addEventListener('click', function () {
        menu.style.visibility = 'visible'
        menu.style.opacity = '1'
        openMenu.classList.add('arrow-transform')

    })
    button.addEventListener('click', function () {
        menu.style.visibility = 'hidden'
        menu.style.opacity = '0'
        openMenu.classList.remove('arrow-transform')
        // console.log(1)
    })
    menuContent.addEventListener('mouseleave', function () {
        menu.style.visibility = 'hidden'
        menu.style.opacity = '0'
        openMenu.classList.remove('arrow-transform')
    })

}



function burgerMenu() {
    let triggerMenu = document.querySelector('.burger')
    let menu = document.querySelectorAll('.burger-menu')
    let mainMenu = document.querySelector('.burger-main-menu')
    let closeMenu = document.querySelectorAll('.close-burger-menu')
    let triggerSubMenu = document.querySelectorAll('.burger-menu-arrow')
    triggerMenu.addEventListener('click', function () {
        mainMenu.style.transform = 'translateX(0%)'
    })
    closeMenu.forEach(closeTrigger => {
        closeTrigger.addEventListener('click', function () {
            menu.forEach(menuItem => {
                menuItem.style.transform = 'translateX(100%)'
            })
        })
    })


    triggerSubMenu.forEach(item => {
        item.addEventListener('click', function () {
            let subMenu  = item.nextElementSibling
            let prevSteep = subMenu.querySelector('.burger-prev-steep')
            prevSteep.textContent = item.textContent
            subMenu.style.transform = 'translateX(0%)'
            subMenu.addEventListener('click', function () {
                subMenu.style.transform = 'translateX(100%)'
            })
        })
    })
}
function hidingParameters() {
    if (window.outerWidth < 576) {
        let btn = document.querySelector('.show-all-parameters')
        let parameters = document.querySelectorAll( '.type-sorted-button');
        parameters.forEach((item, i) => {
            if (i <= 5) {
                item.classList.add('d-block')
            } else {
                item.classList.add('d-none')
            }
        })
        btn.addEventListener('click', function () {
            parameters.forEach(item => {
                item.classList.remove('d-none')
            })
            this.classList.add('d-none')
        })
    }

}
function select() {

    document.querySelectorAll('.select').forEach(function (dropDownWrapper) {

        const dropDownBtn = dropDownWrapper.querySelector('.select-btn-drop');
        console.log(dropDownBtn)
        const dropDownList = dropDownWrapper.querySelector('.select__list');
        const dropDownListItems = dropDownList.querySelectorAll('.select__list-item');
        const dropDownInput = dropDownWrapper.querySelector('.select__input-hidden');
        // проверка на выбраный параметр в интпуте по умолчанию
        if (dropDownInput.dataset.active !== '') {
            dropDownListItems.forEach(item => {
                if (dropDownInput.dataset.active === item.dataset.value) {
                    dropDownBtn.textContent = item.textContent
                    dropDownBtn.classList.add('aside-input-text')
                }
            })
        }
        // Клик по кнопке. Открыть/Закрыть select
        dropDownBtn.addEventListener('click', function (e) {
            dropDownList.classList.toggle('select__list--visible');
            // this.classList.add('select__button--active');
            this.classList.toggle('select-open');



        });

        // Выбор элемента списка. Запомнить выбранное значение. Закрыть дропдаун
        dropDownListItems.forEach(function (listItem) {
            listItem.addEventListener('click', function (e) {
                e.stopPropagation();
                dropDownBtn.innerText = this.innerText;
                dropDownBtn.focus();

                // dropDownInput.value = this.dataset.value;
                dropDownInput.value = 2;
                console.log(dropDownInput.value)
                dropDownList.classList.remove('select__list--visible');
                dropDownBtn.classList.remove('select-open');
                dropDownBtn.classList.add('aside-input-text');

            });
        });

        // Клик снаружи дропдауна. Закрыть дропдаун
        document.addEventListener('click', function (e) {
            if (e.target !== dropDownBtn) {
                dropDownBtn.classList.remove('select__button--active');
                dropDownBtn.classList.remove('select-open');
                dropDownList.classList.remove('select__list--visible');
            }
        });

        // Нажатие на Tab или Escape. Закрыть дропдаун
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Tab' || e.key === 'Escape') {
                dropDownBtn.classList.remove('select__button--active');
                dropDownList.classList.remove('select__list--visible');
                dropDownBtn.classList.remove('select-open');
            }
        });
    });
}
select()
// hidingParameters()

// burgerMenu()
// HeaderDropMenu()
// showHeaderFixed()
// scrollTop()
// maskNumber()
