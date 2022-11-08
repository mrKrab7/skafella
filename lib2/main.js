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
const constructor = () => {
    const postData = async (url, data) => {


        let res = await fetch(url, {
            method: "POST",
            body: data
        });
        return await res.json();
    }

    let images = document.querySelector('.canvas-result');

    let outerCanvas = new EasyC(document.querySelector('#fasad'));
    let innerCanvas = new EasyC(document.querySelector('#inner'));
    let canvasTrigger = document.querySelectorAll('.canvas-trigger');
    let count = 0
    let resObject

    let formData = new FormData

    formData.append('getImg', '')
    postData('/zed/modules/canvas/canvas_load.php', formData)
        .then((res) => {


            Render(res)

            canvasTrigger.forEach(canvasTriggerItem => {
                canvasTriggerItem.addEventListener('click', () => {
                    Render(res)
                })
            })

        })


    // function canvasLoadingImages(resObject) {
    //     let cashAray = [];
    //
    //     function loadSrc() {
    //
    //         for (const key in resObject) {
    //             for (const keyKey in resObject[key]) {
    //                 resObject[key][keyKey].forEach(item => {
    //                     cashAray.push(item.src)
    //
    //                 })
    //
    //             }
    //
    //         }
    //     }
    //
    //     loadSrc()
    //     console.log(cashAray)
    //
    //     function loadImg(url) {
    //         return new Promise(function (resolve, reject) {
    //                 let img = new Image()
    //                 img.onload = function () {
    //                     resolve(url)
    //                 }
    //                 img.onerror = function () {
    //                     reject(url);
    //                 };
    //                 img.src = url;
    //             }
    //         )
    //     }
    //
    //     cashAray.forEach(async (item, i) => {
    //         // console.log(item)
    //         await loadImg(item)
    //         console.log(i + '    ' + item)
    //
    //     })
    //
    //


    //

    function Render(imgObject) {


        if (outerCanvas.objects.length >= 1 || innerCanvas.objects.length >= 1) {
            outerCanvas.objects = [];
            innerCanvas.objects = [];
            // outerCanvas.clean()
            // innerCanvas.clean()
            count += 10
        }

        function canvasRender(renderItems, canvas, location) {
            const settingsCanvas = {

                background: {
                    x: 500,
                    z: 1
                },
                frameDoor1: {
                    x: 278,
                    z: 7
                },
                frameDoor2: {
                    x: 429,
                    z: 7
                },
                frameDoor3: {
                    x: 599,
                    z: 7
                },

                frameDoor4: {
                    x: 789,
                    z: 7
                },
                textureDoor1: {
                    z: 3,
                    x: 288
                },
                textureDoor2: {
                    z: 3,
                    x: 430
                },
                textureDoor3: {
                    x: 600,
                    z: 3
                },
                textureDoor4: {
                    x: 785,
                    z: 3
                },

                textureCenter1: {
                    x: 288,
                    z: 4
                },
                textureCenter2: {
                    z: 4,
                    x: 430
                },
                textureCenter3: {
                    x: 600,
                    z: 4
                },
                textureCenter4: {
                    x: 785,
                    z: 4
                },


                section1: {
                    z: 0,
                    x: 296
                },
                section2: {
                    z: 0,
                    x: 425
                },
                section3: {
                    z: 0,
                    x: 576
                },
                section4: {
                    z: 0,
                    x: 749
                },
            }


            renderItems.forEach(itemImg => {

                // console.log(itemImg)
                let currentObjImg = Object.assign({}, settingsCanvas[itemImg.locationOnCanvas])

                currentObjImg.type = 'image'
                currentObjImg.src = itemImg.src
                currentObjImg.locationOnCanvas = itemImg.locationOnCanvas
                currentObjImg.z = currentObjImg.z + count
                currentObjImg.y = 325


                if (location === 'right') {
                    currentObjImg.scale = [-1, 1]
                    let x = currentObjImg.x
                    currentObjImg.x = 1000 - x
                }

                // console.log(currentObjImg)
                canvas.objects.push(currentObjImg)


            })


            // console.log(sheet.objects)

        }

        function searchCheckedElem(selectorElem) {
            let nodeList = document.querySelectorAll(selectorElem)
            let checkedElement

            if (nodeList.length > 1) {

                nodeList.forEach(item => {
                    if (item.checked) {
                        checkedElement = item.dataset.article

                    }
                })
            }
            if (nodeList.length === 1) {

                nodeList.forEach(item => {
                    if (item.checked) {
                        return checkedElement = '1'
                    } else checkedElement = '0'
                })

            }
            return checkedElement
        }


        if (typeof imgObject === 'object' && imgObject !== null) {
            let parameterAmountDoors = searchCheckedElem('input[name="section-amount"]'); // получаем id выбраного параметра отвечающего за количество дверей
            let parameterLocation = searchCheckedElem('input[name="location"]'); // получаем id выбраного параметра отвечающего за расположение шкафа
            let parameterZakladnaia = searchCheckedElem('#zakladnaia');
            let parameterFrame = searchCheckedElem('input[name="frame-type"]')
            let parameterFrameType1 = searchCheckedElem('input[name="type-frame-1"]')
            let parameterFrameType2 = searchCheckedElem('input[name="type-frame-2"]')
            let parameterDoorBackground1 = searchCheckedElem('input[name="texture-doors1"]')
            let parameterDoorBackground2 = searchCheckedElem('input[name="texture-doors2"]')
            let parameterDoorCenter1 = searchCheckedElem('input[name="texture-center-doors1"]')
            let parameterDoorCenter2 = searchCheckedElem('input[name="texture-center-doors2"]')

            // let parameterSectionsColor = document.querySelector('input[name="section-color"]:checked').id;
            let parameterTextureCenterColor = searchCheckedElem('input[name="door-left-texture-center"]');

            console.log(imgObject);
            (function backgroundRender() {  // фунция отвечающия за рендер фона
                let arrayBackgroundImages = imgObject.backgroundFon  // сохроняем в переменую все доступные варианты по выбору фона
                let background = arrayBackgroundImages.filter(item => {
                    if (item.zakladnaia === parameterZakladnaia && item.typeDoor === parameterAmountDoors && item.type === parameterLocation) {
                        return item
                    }
                    if (parameterLocation === 'right') {
                        if (item.zakladnaia === parameterZakladnaia && item.typeDoor === parameterAmountDoors && item.type === 'left') {
                            return item
                        }
                    }
                }) // производим поиск нужного фона по выбраным параметрам
                console.log(background)
                // console.log('фон', background)
                canvasRender(background, outerCanvas, parameterLocation)
                // canvasRender(background, innerCanvas)

            }());


            (function DoorsRender() {  // фунция отвечающия за рендер рамок
                function sortLastNumber(arr) {
                    return arr.sort((a, b) => a.locationOnCanvas[a.locationOnCanvas.length - 1] - b.locationOnCanvas[b.locationOnCanvas.length - 1])
                }

                function doorsFilter(doors) {
                    let frameArray1 = imgObject.frameFacade.filter(item => {
                        if (item.DoorHandle === parameterFrame && item.type === parameterFrameType1) {
                            return item
                        }
                    })
                    sortLastNumber(frameArray1)
                    if (doors === '4') {
                        resultArray.push(frameArray1[0])
                        resultArray.push(frameArray1[3])

                    }
                    if (doors === '3') {
                        resultArray.push(frameArray1[0])
                        resultArray.push(frameArray1[2])
                    }
                    if (doors === '2') {
                        resultArray.push(frameArray1[0])

                    }

                    let frameArray2 = imgObject.frameFacade.filter(item => {
                        if (item.DoorHandle === parameterFrame && item.type === parameterFrameType2) {
                            return item
                        }
                    })
                    sortLastNumber(frameArray2)
                    if (doors === '4') {
                        resultArray.push(frameArray2[1])
                        resultArray.push(frameArray2[2])
                    }
                    if (doors === '3' || doors === '2') {
                        resultArray.push(frameArray2[1])

                    }


                    let textureFacade1 = imgObject.textureFacade.filter(item => {
                        if (item.article === parameterDoorBackground1) {
                            return item
                        }
                    })
                    // console.log(textureFacade1)
                    if (doors === '4') {
                        resultArray.push(textureFacade1[0])
                        resultArray.push(textureFacade1[3])
                    }
                    if (doors === '3') {
                        resultArray.push(textureFacade1[0])
                        resultArray.push(textureFacade1[2])
                    }
                    if (doors === '2') {
                        resultArray.push(textureFacade1[0])

                    }


                    // console.log(parameterDoorBackground1)
                    let textureFacade2 = imgObject.textureFacade.filter(item => {
                        if (item.article === parameterDoorBackground1) {
                            return item
                        }
                    })
                    if (doors === '4') {
                        resultArray.push(textureFacade2[1])
                        resultArray.push(textureFacade2[2])
                    }

                    if (doors === '3' || doors === '2') {
                        resultArray.push(textureFacade2[1])
                    }

                    // console.log(textureFacade1)
                    let textureCenter1 = imgObject.textureCenterFacade.filter(item => {
                        if (item.article === parameterDoorCenter1 && item.type === parameterFrameType1) {
                            return item
                        }
                    })
                    console.log(textureCenter1)
                    sortLastNumber(textureCenter1)
                    console.log(textureCenter1)
                    if (textureCenter1.length >= 1 && doors === '4') {
                        resultArray.push(textureCenter1[0])
                        resultArray.push(textureCenter1[3])
                    }
                    if (textureCenter1.length >= 1 && doors === '3') {
                        resultArray.push(textureCenter1[0])
                        resultArray.push(textureCenter1[2])
                    }
                    if (textureCenter1.length >= 1 && doors === '2') {
                        resultArray.push(textureCenter1[0])

                    }
                    console.log(parameterDoorCenter2)
                    let textureCenter2 = imgObject.textureCenterFacade.filter(item => {
                        if (item.article === parameterDoorCenter2 && item.type === parameterFrameType2) {
                            return item
                        }
                    })
                    sortLastNumber(textureCenter2)
                    if (textureCenter2.length >= 1 && doors === '4') {
                        resultArray.push(textureCenter2[1])
                        resultArray.push(textureCenter2[2])
                    }
                    if (textureCenter2.length >= 1 && doors === '3') {
                        resultArray.push(textureCenter2[1])
                    }
                    if (textureCenter2.length >= 1 && doors === '2') {
                        resultArray.push(textureCenter2[1])
                    }

                }


                let resultArray = []

                doorsFilter(parameterAmountDoors);



                // console.log(resultArray)
                canvasRender(resultArray, outerCanvas, parameterLocation)
            })()


        }


        innerCanvas.draw()
        outerCanvas.draw()
        console.log(outerCanvas.objects)

    }


    // canvasLoadingImages(jsonSkafella)


}
constructor()
select()


// const inputLimitation = () => {
//     let allInput = document.querySelectorAll('.measurement-input');
//     let withInput = document.querySelector('#width');
//     allInput.forEach(item => {
//         item.addEventListener('input', function () {
//
//             this.value = this.value.replace(/[^0-9]/g, '')
//             if (this.value.length > 4) {
//                 this.value = this.value.slice(0, 4)
//             }
//
//
//         })
//     })
//     withInput.addEventListener('input', function () {
//         let activeRadioButton = document.querySelector('input[name="col-section"]:checked')
//         let maxWidth = +activeRadioButton.dataset.maxWidth
//         let minWidth = +activeRadioButton.dataset.minWidth
//
//         let currentValue = this.value
//         if (+this.value > maxWidth) {
//             this.value = maxWidth
//
//         } else if (+this.value < minWidth && this.value.length > 3) {
//
//             this.value = minWidth
//
//
//         } else this.value = currentValue
//     })
//
// }
// inputLimitation()
// const radioButtonLimitation = () => {
//     inputLimitation()
//     let allRadioButtonDoors = document.querySelectorAll('input[name="col-doors"]')
//     let allRadioButtonSections = document.querySelectorAll('input[name="col-section"]')
//
//     function checkColDoors() {
//         let checkedRadioButtonDoorsId = document.querySelector('input[name="col-doors"]:checked').id
//
//         allRadioButtonSections.forEach(item => {
//             if (checkedRadioButtonDoorsId !== 'typeDoor0') {
//                 item.setAttribute('disabled', '')
//                 item.removeAttribute('checked', '')
//
//             } else {
//                 item.removeAttribute('disabled', '')
//                 item.removeAttribute('checked', '')
//             }
//
//             if (item.dataset.sections === checkedRadioButtonDoorsId) {
//
//
//                 item.removeAttribute('disabled', '')
//                 item.checked = true
//
//             }
//         })
//     }
//
//
//     allRadioButtonDoors.forEach(itemButtonDo0rs => {
//         itemButtonDo0rs.addEventListener('click', checkColDoors)
//     })
// }
// radioButtonLimitation()

