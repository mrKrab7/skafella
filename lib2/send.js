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


function sendProject() {
    let fileInput = document.querySelector('#send-project')

    fileInput.addEventListener('change', function () {
        let file = fileInput.files[0]
        let progressBar = document.querySelector('.send__progres');
        let previewInput = document.querySelector('.send__input-file')
        let progressInput = document.querySelector('.send__progres-btn')
        // progressBar.style.width = '0%'
        previewInput.classList.add('d-none')
        progressInput.classList.add('d-block')
        const formSent = new FormData()
        formSent.append('file', file)
        httpGet('/modules/canvas/project.php', file, progressBar)
            .then(res => {
                console.log(res)
            })
    })

}

sendProject()