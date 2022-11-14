const modals = () => {

    function bindModal(triggerSelector, modalSelector, closeSelector) {
        if (triggerSelector !== '') {
            const trigger = document.querySelectorAll(triggerSelector)

            trigger.forEach((item) => {
                item.addEventListener('click', (e) => {
                    if (modal.classList.contains('modal-call')) {
                        const modalTitle = modal.querySelector('.modal-title');
                        const modalForm = modal.querySelector('form');
                        let title = item.dataset.titleModal
                        let typeForm = item.dataset.typeModal
                        modalForm.name = typeForm
                        modalTitle.innerHTML = title


                    }


                    if (e.target) {
                        e.preventDefault()
                    }
                    modal.style.display = 'block'
                    document.body.style.overflow = 'hidden'

                });
            })
        }


        const modal = document.querySelector(modalSelector),
            close = document.querySelector(closeSelector);
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


};
modals();