const forms = () => {
    const form = document.querySelector('.modal-form'),
        inputs = form.querySelectorAll('input'),
        modals = document.querySelector('.modal-call'),
        finalModal = document.querySelector('.modal-feedback');


    const postData = async (url, data) => {


        let res = await fetch(url, {
            method: "POST",
            body: data
        });

        return await res.text();
    };

    const clearInputs = () => {
        inputs.forEach(item => {
            item.value = '';
        });
    };

    form.addEventListener('submit', (e) => {
        e.preventDefault();

        const formData = new FormData(form);

        formData.append('modal_type',form.dataset.name)


        postData('/zed/modules/feedback/server.php', formData)
            .then(res => {

                console.log(res);


            })
            .catch(() => alert('Что-то пошло не так...'))
            .finally(() => {
                modals.style.display = 'none'
                finalModal.style.display = 'block'
                clearInputs();

            });
    });

};
forms()


