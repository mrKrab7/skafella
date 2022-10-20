function showHeaderFixed() {
    let headerFixed = document.querySelector('.header-fix__wrapper')
    window.addEventListener('scroll', function (e) {
        if (this.scrollY > 1000) {
            headerFixed.classList.add('header-show')
        } else headerFixed.classList.remove('header-show')
    })
}

function scrollTop() {
    let header = document.querySelector('header')
    let scrollBtn = document.querySelector('.btn-scroll')
    scrollBtn.addEventListener('click', function () {
        header.scrollIntoView({
            behavior: "smooth",
            block: "start"
        });
    })
}


function maskNumber() {
    document.addEventListener('DOMContentLoaded', () => {
        let numberInputs = document.querySelectorAll("[data-mask][data-slots]")
        for (const el of numberInputs) {

            const pattern = el.getAttribute("data-mask"),
                slots = new Set(el.dataset.slots || "_"),
                prev = (j => Array.from(pattern, (c, i) => slots.has(c) ? j = i + 1 : j))(0),
                first = [...pattern].findIndex(c => slots.has(c)),
                accept = new RegExp(el.dataset.accept || "\\d", "g"),
                clean = input => {
                    input = input.match(accept) || [];
                    return Array.from(pattern, c =>
                        input[0] === c || slots.has(c) ? input.shift() || c : c
                    );
                },
                format = () => {
                    const [i, j] = [el.selectionStart, el.selectionEnd].map(i => {
                        i = clean(el.value.slice(0, i)).findIndex(c => slots.has(c));
                        return i < 0 ? prev[prev.length - 1] : back ? prev[i - 1] || first : i;
                    });
                    el.value = clean(el.value).join``;
                    el.setSelectionRange(i, j);
                    back = false;
                };
            let back = false;
            el.addEventListener("keydown", (e) => back = e.key === "Backspace");
            el.addEventListener("input", format);


            el.addEventListener("focus", format);
            el.addEventListener("blur", () => el.value === pattern && (el.value = ""));
        }
        numberInputs.forEach(item => {
            item.addEventListener('input', function () {


                let number = this.value.replace(/[^0-9]/g, '')

                if (number.length < 11) {
                    this.setCustomValidity('вы не вели номер телефона')
                } else {
                    this.setCustomValidity('')
                }


                console.log(number)
                console.log(number.length)
            })
        })


    });
}

showHeaderFixed()
scrollTop()
maskNumber()
