// function uxRequest(params, timeout = 5000) {
//
//     const xhrPromise = new Promise((resolve, reject) => {
//
//         params = Object.assign({
//             'url': '',
//             'data': '',
//             'method': 'POST',
//             'headers': {},
//             'credentials': false,
//             'responseType': false
//         }, params);
//
//         //if not url
//         if (!params.url) reject({
//             'description': 'not url'
//         });
//
//         let xhr = new XMLHttpRequest();
//
//         xhr.open(params.method, params.url);
//
//         //adding headers
//         for (let key in params.headers) {
//             xhr.setRequestHeader(key, params.headers[key])
//         }
//
//         xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
//
//         if (params.credentials) {
//             xhr.withCredentials = true;
//         }
//
//         xhr.onreadystatechange = () => {
//
//             if (xhr.readyState != 4) return;//do nothing
//
//             if (xhr.status == 200) {
//                 resolve(xhr);
//             } else {
//                 reject({
//                     'status': xhr.status,
//                     'statusText': xhr.statusText,
//                     'description': 'response error'
//                 });
//             }
//         };
//
//         xhr.onerror = () => {
//             reject({
//                 'status': xhr.status,
//                 'statusText': xhr.statusText,
//                 'description': 'load error'
//             });
//         };
//
//         if (params.responseType) {
//             //if response must be BLOB
//             if (params.responseType == 'blob') {
//                 xhr.responseType = "arraybuffer";
//             } else {
//                 xhr.responseType = params.responseType;
//             }
//         }
//
//         xhr.send(params.data);
//     });
//
//     const timeoutPromise = new Promise((resolve, reject) => {
//         console.log('timeout start');
//         let id = setTimeout(() => {
//             console.log('timeout end');
//             clearTimeout(id);
//             reject({
//                 'description': 'timeout',
//                 'code': 504
//             })
//         }, timeout)
//     })
//
//     return Promise.race([
//         xhrPromise,
//         timeoutPromise
//     ]);
// }
function sendProject() {
    function httpGet(url, data, progressBar) {

        return new Promise(function (resolve, reject) {
            let textStatus = document.querySelector('.send__progres-btn')
            textStatus.classList.remove('uploaded')
            progressBar.style.width = '0'
            var xhr = new XMLHttpRequest();


            xhr.upload.addEventListener('progress', function (event) {
                    const percentLoaded = Math.round((event.loaded / event.total) * 100)
                    progressBar.style.width = percentLoaded + '%'
                    if (percentLoaded === 100) {

                        textStatus.classList.add('uploaded')

                    }
                }
            )


            xhr.onload = function () {
                if (this.status == 200) {
                    resolve(this.response);
                } else {
                    var error = new Error(this.statusText);
                    error.code = this.status;
                    reject(error);
                }
            };

            xhr.onerror = function () {
                reject(new Error("Network Error"));
            };
            xhr.open('POST', url, true);
            xhr.send(data);
        });

    }

    const postData = async (url, data) => {


        let res = await fetch(url, {
            method: "POST",
            body: data
        });
        return await res.text();
    }
    let fileName = ''
    let form = document.querySelector('form[name="project"]')
    let button = form.querySelector('button')
    let fileInput = document.querySelector('#send-project')
    let progressBar = document.querySelector('.send__progres');
    let previewInput = document.querySelector('.send__input-file')
    let progressInput = document.querySelector('.send__progres-btn')
    let inputs = form.querySelectorAll('input')

    function sendForm(res) {
        if (res.length > 10) {
            button.removeAttribute('disabled')
            fileName = res
        }

        if (res === '3') {
            progressInput.classList.remove('d-block')
            previewInput.classList.remove('d-none')
            alert('Слишком большой файл')
        }
        if (res === '2') {
            progressInput.classList.remove('d-block')
            previewInput.classList.remove('d-none')
            alert('Формат файла не подходит')
        }
    }


    fileInput.addEventListener('change', function () {
        let file = fileInput.files[0]
        let maxSize = 8 * 1024
        console.log(file.size > maxSize)
        if (file.size > 8 * 1024) {

            alert('Слишком большой файл')
            return
        }
        // progressBar.style.width = '0%'
        previewInput.classList.add('d-none')
        progressInput.classList.add('d-block')
        const formSent = new FormData()
        let date = Date.now()
        formSent.append('userImage', file, date)


        httpGet('/zed/file/upload.php', formSent, progressBar)
            .then(res => {
                console.log(res)
                sendForm(res)
            })

    })
    form.addEventListener('submit', (e) => {
        e.preventDefault()
        let formData = new FormData(form)
        formData.append('file', fileName)
        postData('/zed/modules/feedback/sendmessage.php', formData)
            .then(res => {
                console.log(res)
            })
            .catch(() => alert('Что-то пошло не так...'))
            .finally(() => {
                let modal =  document.querySelector('.modal-feedback')
                inputs.forEach(item => {
                    item.value = ''
                })
                progressInput.classList.remove('d-block')
                previewInput.classList.remove('d-none')
                modal.style.display = 'block'

            });
    })

}

sendProject()
