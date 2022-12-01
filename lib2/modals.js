const modals = () => {

    function bindModal(triggerSelector, modalSelector, closeSelector) {
        const modal = document.querySelector(modalSelector),
            close = document.querySelector(closeSelector);
        if (triggerSelector !== '') {
            const trigger = document.querySelectorAll(triggerSelector)

            trigger.forEach((item) => {
                item.addEventListener('click', (e) => {
                    if (modal.classList.contains('modal-call')) {
                        let emailInput = document.querySelector('.modal__input-email')
                        let messageInput = document.querySelector('.modal__input-message')
                        const modalTitle = modal.querySelector('.modal-title');
                        const modalForm = modal.querySelector('form');
                        let title = item.dataset.titleModal
                        let typeForm = item.dataset.typeModal


                        modalForm.dataset.name = typeForm
                        modalTitle.innerHTML = title

                        if (typeForm === 'question') {
                            emailInput.classList.remove('d-none')
                            messageInput.classList.remove('d-none')
                            modal.classList.add('question-modal')
                        } else {
                            emailInput.classList.add('d-none')
                            modal.classList.remove('question-modal')
                            messageInput.classList.add('d-none')
                        }


                    }


                    if (e.target) {
                        e.preventDefault()
                    }
                    modal.style.display = 'block'
                    document.body.style.overflow = 'hidden'

                });
            })
        }


        if (modal !== null) {
            close.addEventListener('click', () => {
                modal.style.display = 'none'
                document.body.style.overflow = ''

            });


            modal.addEventListener('click', function (e) {

                if (e.target == modal) {
                    modal.style.display = 'none'
                    document.body.style.overflow = ''

                }
            })
        }


    }


    bindModal('.call-btn', '.modal-call', '.modal-call .modal__btn-close');
    bindModal('.design__button', '.modal-call', '.modal-call .modal__btn-close');
    bindModal('.header__nav-question', '.modal-call', '.modal-call .modal__btn-close');
    bindModal('.question-mobile', '.modal-call', '.modal-call .modal__btn-close');
    bindModal('.section1', '.modal-section-1', '.modal-section-1 .modal__btn-close');
    bindModal('.section2', '.modal-section-2', '.modal-section-2 .modal__btn-close');
    bindModal('.section3', '.modal-section-3', '.modal-section-3 .modal__btn-close');
    bindModal('.section4', '.modal-section-4', '.modal-section-4 .modal__btn-close');
    bindModal('.plus-1', '.modal-section-1', '.modal-section-1 .modal__btn-close');
    bindModal('.plus-2', '.modal-section-2', '.modal-section-1 .modal__btn-close');
    bindModal('.plus-3', '.modal-section-3', '.modal-section-1 .modal__btn-close');
    bindModal('.plus-4', '.modal-section-4', '.modal-section-1 .modal__btn-close');


    bindModal('', '.modal-calc', '.calculator__prev-steep');
    bindModal('', '.modal-feedback', '.modal-feedback .modal__btn-close');
    bindModal('', '.modal-review', '.modal-review .modal__btn-close');


};
modals();


function closeSectionModal() {
    let closeTrigger = document.querySelectorAll('.close-modal')

    if (closeTrigger !== null) {
        closeTrigger.forEach(item => {
            item.addEventListener('click', function () {

                let modal = item.closest('.modal-section')
                modal.style.display = 'none'
                document.body.style.overflow = ''
            })
        })
    }
}

closeSectionModal()