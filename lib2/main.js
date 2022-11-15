function synchronizationCheckbox(name) {
    let checkbox = document.querySelectorAll(name)
    console.log(checkbox)
    checkbox.forEach(item => {
        item.addEventListener('click', function () {
            if (item.checked) {
                checkbox.forEach(checkbox => {
                    checkbox.checked = true
                })
            } else {
                checkbox.forEach(checkbox => {
                    checkbox.checked = false
                })
            }
        })
    })
}
function getImgCanvas () {
    let canvas = document.querySelector('#fasad')
    let img = document.querySelector('.fasad-img-canvas')

    img.onload = function () {
        img.src = canvas.toDataURL('image/jpeg', 1.0)
    }
    console.log(url)
    img.src = url
}

synchronizationCheckbox('input[name="zakladnaia"]')
synchronizationCheckbox('input[name="stretch-ceiling"]')

const constructor = () => {
    const postData = async (url, data, type) => {


        let res = await fetch(url, {
            method: "POST",
            body: data
        });
        if (type === 'json') {
            return await res.json();
        }
        if (type === 'text') {
            return await res.text();
        }

    }

    let images = document.querySelector('.canvas-result');

    let outerCanvas = new EasyC(document.querySelector('#fasad'));
    let innerCanvas = new EasyC(document.querySelector('#inner'));
    let canvasTrigger = document.querySelectorAll('.canvas-trigger');
    let count = 0

    let formData = new FormData

    formData.append('getImg', '')
    postData('/zed/modules/canvas/canvas_load.php', formData, 'json')
        .then((res) => {


            Render(res)

            canvasTrigger.forEach(canvasTriggerItem => {
                canvasTriggerItem.addEventListener('click', () => {
                    // postData('/zed/modules/canvas/canvas_load.php')
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

        console.log(imgObject)
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
                    x: 430,
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
                    z: 2,
                    x: 296
                },
                section2: {
                    z: 2,
                    x: 435
                },
                section3: {
                    z: 2,
                    x: 600
                },
                section4: {
                    z: 2,
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

            let parameterWidth = document.querySelector('#width-calc')
            let parameterHeight = document.querySelector('#height-calc')
            let parameterDeep = document.querySelector('#deep-calc')
            let parameterAmountDoors = searchCheckedElem('input[name="section-amount"]'); // получаем id выбраного параметра отвечающего за количество дверей
            let parameterLocation = searchCheckedElem('input[name="location"]'); // получаем id выбраного параметра отвечающего за расположение шкафа
            let parameterZakladnaia = searchCheckedElem('#zakladnaia');
            let parameterCeiling = searchCheckedElem('#stretch-ceiling');
            let parameterOtbonik = searchCheckedElem('#otboinik');
            let parameterDovodchik = searchCheckedElem('#dovodchiki');
            let parameterFrame = searchCheckedElem('input[name="frame-type"]')
            let parameterFrameType1 = searchCheckedElem('input[name="type-frame-1"]')
            let parameterFrameType2 = searchCheckedElem('input[name="type-frame-2"]')
            let parameterDoorBackground1 = searchCheckedElem('input[name="texture-doors1"]')
            let parameterDoorBackground2 = searchCheckedElem('input[name="texture-doors2"]')
            let parameterDoorCenter1 = searchCheckedElem('input[name="texture-center-doors1"]')
            let parameterDoorCenter2 = searchCheckedElem('input[name="texture-center-doors2"]');
            let parameterСabinet = searchCheckedElem('input[name="cabinet-parameters"]')
            let parameterSectionColor = searchCheckedElem('input[name="texture-doors"]')
            let parameterSection1 = searchCheckedElem('input[name="section-modal1"]');
            let parameterSection2 = searchCheckedElem('input[name="section-modal2"]');
            let parameterSection3 = searchCheckedElem('input[name="section-modal3"]');
            let parameterSection4 = searchCheckedElem('input[name="section-modal4"]');


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
                canvasRender(background, innerCanvas, parameterLocation)

            }());
            (function doorsRender() {

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
                        if (item.article === parameterDoorBackground2) {
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
            })();
            (function sectionRender() {
                function hideButtons() {

                    let blockOpenModal3 = document.querySelector('.section3').closest('.calculator__select-wrapper')
                    let blockOpenModal4 = document.querySelector('.section4').closest('.calculator__select-wrapper')
                    let plusOpenModal3 = document.querySelector('.plus-3')
                    let plusOpenModal4 = document.querySelector('.plus-4')

                    if (parameterAmountDoors === '2') {
                        plusOpenModal3.style.display = 'none'
                        plusOpenModal4.style.display = 'none'
                        blockOpenModal3.style.display = 'none'
                        blockOpenModal4.style.display = 'none'

                    }
                    if (parameterAmountDoors === '3') {
                        plusOpenModal3.style.display = 'block'
                        plusOpenModal4.style.display = 'none'
                        blockOpenModal3.style.display = 'block'
                        blockOpenModal4.style.display = 'none'
                    }
                    if (parameterAmountDoors === '4') {
                        plusOpenModal3.style.display = 'block'
                        blockOpenModal3.style.display = 'block'
                        plusOpenModal4.style.display = 'block'
                        blockOpenModal4.style.display = 'block'
                    }
                }

                function buttonNameChange() {
                    let buttonOpenModal1 = document.querySelector('.section1')
                    buttonOpenModal1.textContent = document.querySelector('input[name="section-modal1"]:checked').dataset.name
                    let buttonOpenModal2 = document.querySelector('.section2')
                    buttonOpenModal2.textContent = document.querySelector('input[name="section-modal2"]:checked').dataset.name
                    if (parameterAmountDoors === '3' || parameterAmountDoors === '4') {
                        let buttonOpenModal3 = document.querySelector('.section3')
                        buttonOpenModal3.textContent = document.querySelector('input[name="section-modal3"]:checked').dataset.name
                    }
                    if (parameterAmountDoors === '4') {
                        let buttonOpenModal4 = document.querySelector('.section4')
                        buttonOpenModal4.textContent = document.querySelector('input[name="section-modal4"]:checked').dataset.name
                    }

                }

                function renderTextureModals(obj, modal) {
                    let modalInput = document.querySelectorAll(modal)


                    obj.forEach(objItem => {
                        modalInput.forEach(modalInput => {

                            if (objItem.article === modalInput.dataset.article && objItem.color === parameterSectionColor) {

                                let imgDiv = modalInput.nextElementSibling.querySelector('.modal__section-img')

                                imgDiv.style.backgroundImage = `url('${objItem.src}')`
                            }
                        })
                    })

                }

                buttonNameChange()
                hideButtons()
                renderTextureModals(imgObject.textureCarcase, 'input[name="section-modal1"]')
                renderTextureModals(imgObject.textureCarcase, 'input[name="section-modal2"]')
                renderTextureModals(imgObject.textureCarcase, 'input[name="section-modal3"]')
                renderTextureModals(imgObject.textureCarcase, 'input[name="section-modal4"]')


                let section1 = imgObject.textureCarcase.filter(item => {
                    if (item.article === parameterSection1 && item.color === parameterSectionColor) {
                        return item
                    }
                })
                canvasRender(section1, innerCanvas, parameterLocation)
                let section2 = imgObject.textureCarcase.filter(item => {
                    if (item.article === parameterSection2 && item.color === parameterSectionColor) {
                        return item
                    }
                })
                canvasRender(section2, innerCanvas, parameterLocation)
                if (parameterAmountDoors === '3' || parameterAmountDoors === '4') {
                    let section3 = imgObject.textureCarcase.filter(item => {
                        if (item.article === parameterSection3 && item.color === parameterSectionColor) {
                            return item
                        }
                    })
                    canvasRender(section3, innerCanvas, parameterLocation)

                }
                if (parameterAmountDoors === '4') {
                    let section4 = imgObject.textureCarcase.filter(item => {
                        if (item.article === parameterSection4 && item.color === parameterSectionColor) {
                            return item
                        }
                    })
                    canvasRender(section4, innerCanvas, parameterLocation)
                }


            })();

            (function calcPrice() {
                let doorsObject = {}
                let sectionObject = {}
                doorsObject.koldors = parameterAmountDoors
                doorsObject.width = parameterWidth.value
                doorsObject.height = parameterHeight.value
                doorsObject.deep = parameterDeep.value
                doorsObject.location = parameterLocation
                doorsObject.colorframe = parameterFrame
                doorsObject.typed1 = parameterFrameType1
                doorsObject.d1fon = parameterDoorBackground1
                doorsObject.d1center = parameterDoorCenter1
                doorsObject['stretch-ceiling'] = parameterCeiling
                doorsObject.otbonik = parameterOtbonik
                doorsObject.dovdhik = parameterDovodchik
                doorsObject.typed2 = parameterFrameType2
                doorsObject.d2fon = parameterDoorBackground2
                doorsObject.d2center = parameterDoorCenter2


                sectionObject.width = parameterWidth.value
                sectionObject.height = parameterHeight.value
                sectionObject.deep = parameterDeep.value
                sectionObject.location = parameterLocation
                sectionObject.zakladnaia = parameterZakladnaia
                sectionObject['stretch-ceiling'] = parameterCeiling
                sectionObject.koldors = parameterAmountDoors
                sectionObject.section1 = parameterSection1
                sectionObject.section2 = parameterSection2
                sectionObject.section3 = parameterSection3
                sectionObject.section4 = parameterSection4


                let formData = new FormData()
                console.log(parameterСabinet)
                formData.append('fasad', JSON.stringify(doorsObject))
                formData.append('section', JSON.stringify(sectionObject))
                postData('/zed/modules/canvas/canvas_load.php', formData, 'text')
                    .then((res) => {
                        let priceResult = document.querySelector('.canvas-price')
                        let arr = res.split('-')
                        let result = 0
                        arr.forEach(item => {
                            if (!isNaN(parseFloat(item))) {
                                // console.log(+item)
                                result = result + +item
                            }
                        })
                        priceResult.textContent = result + 'руб.'

                    })
                    .then(

                    )


            })();


        }


        innerCanvas.draw()
        outerCanvas.draw()
        getImgCanvas()
        console.log(outerCanvas.objects)




    }


    // canvasLoadingImages(jsonSkafella)


}
constructor()


const inputLimitation = () => {
    let btn = document.querySelector('.calculator__size-check')
    let widthInput = document.querySelector('#width-calc')
    let heightInput = document.querySelector('#height-calc')
    let deepInput = document.querySelector('#deep-calc')
    let amountDoorsRadio = document.querySelectorAll('input[name="section-amount"]')
    let typeDoors1 = document.querySelectorAll('input[name="type-frame-1"]')
    let typeDoors2 = document.querySelectorAll('input[name="type-frame-2"]')
    let obj = {
        width: false,
        height: false,
        deep: false,
        amountDoors: false
    }
    if (document.querySelector('input[name="section-amount"]:checked') !== null) {
        obj.amountDoors = true
    }

    function disabledCenterFon(typeDoorsSelector, textureSelector) {
        let checkedTypeDoors = document.querySelector(typeDoorsSelector)
        let textureCenter = document.querySelectorAll(textureSelector)
            textureCenter.forEach(texture => {

                if (checkedTypeDoors.dataset.article === '1') {
                    texture.disabled = true
                } else texture.disabled = false

            })




    }

    typeDoors1.forEach(item => {
        item.addEventListener('click', function () {
            disabledCenterFon('input[name="type-frame-1"]:checked', 'input[name="texture-center-doors1"]')
        })
    })
    typeDoors2.forEach(item => {
        item.addEventListener('click', function () {
            disabledCenterFon('input[name="type-frame-2"]:checked', 'input[name="texture-center-doors2"]')
        })
    })


    disabledCenterFon('input[name="type-frame-1"]:checked', 'input[name="texture-center-doors1"]')
    disabledCenterFon('input[name="type-frame-2"]:checked', 'input[name="texture-center-doors2"]')

    function checkWidth() {
        let value = +widthInput.value
        amountDoorsRadio.forEach(item => {
            let min = +item.dataset.min
            let max = +item.dataset.max

            if (value >= min && value <= max) {
                item.disabled = false
            } else item.disabled = true

        })
    }

    checkWidth()
    widthInput.addEventListener('input', () => {
        checkWidth()


    })

    heightInput.addEventListener('input', function () {
        let value = +this.value
        let min = this.dataset.min
        let max = this.dataset.max
        if (value >= min && value <= max) {
            obj.height = true
        } else obj.height = false
        disabledButton()


    })
    deepInput.addEventListener('input', function () {
        let value = +this.value
        let min = this.dataset.min
        let max = this.dataset.max
        if (value >= min && value <= max) {
            obj.deep = true
        } else obj.deep = false
        disabledButton()


    })
    widthInput.addEventListener('input', function () {
        let value = +this.value
        for (const item of amountDoorsRadio) {
            let min = +item.dataset.min
            let max = +item.dataset.max

            console.log(min)
            console.log(max)
            console.log(value)
            if (value >= min && value <= max) {
                obj.width = true
                console.log(item)
                break;


            } else obj.width = false
        }


        disabledButton()


    })
    amountDoorsRadio.forEach(item => {
        item.addEventListener('click', function () {
            obj.amountDoors = true
        })
    })

    function disabledButton() {
        let tabHeader = document.querySelectorAll('.calculator-header__item')
        for (let objKey in obj) {

            if (obj[objKey] === false) {
                tabHeader.forEach(item => {
                    if (!item.classList.contains('calculator__item-active')) {
                        item.style.pointerEvents = 'none'
                        item.style.opacity = '0.5'
                    }

                })
                btn.disabled = true
                return ''
            }
        }
        btn.disabled = false
        tabHeader.forEach(item => {
            item.style.pointerEvents = 'auto'
            item.style.opacity = '1'
        })
    }

    disabledButton()

}
// inputLimitation()


