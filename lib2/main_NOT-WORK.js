function synchronizationCheckbox(name) {
    let checkbox = document.querySelectorAll(name)

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
} // функция для синхронизаций нажатий на checkbox (при нажатий на один становятся выбраны все )
function getNameSection(selectorElem) {
    let nodeList = document.querySelectorAll(selectorElem)
    let name = ''
    nodeList.forEach(item => {
        if (item.checked) {
            name = item.dataset.descr
        }

    })
    return name
}

function sendSale() {
    let modal = document.querySelector('.modal-calc')
    let finalModal = document.querySelector('.modal-feedback')
    let buttonsTriggerSend = document.querySelectorAll('.btn-send-trigger')
    let form = document.querySelector('.send-sale')
    let formInputs = form.querySelectorAll('input')
    let dataBaseId = '-1'
    let formOfPayment = document.querySelector('input[ name="calculator-type-payment"]:checked').id

    function collectionOfParameters() {
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
        let parameterSectionColor = searchCheckedElem('input[name="texture-doors"]')
        let parameterCalculation = document.querySelector('input[name="cabinet-parameters"]:checked').id
        let parameterSection1 = getNameSection('input[name="section-modal1"]');
        let parameterSection2 = getNameSection('input[name="section-modal2"]');
        let parameterSection3 = getNameSection('input[name="section-modal3"]');
        let parameterSection4 = getNameSection('input[name="section-modal4"]');


        let doorCenter1 = document.querySelector('input[name="texture-center-doors1"]:disabled')
        let doorCenter2 = document.querySelector('input[name="texture-center-doors2"]:disabled')
        //console.log(doorCenter1)
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

        if (doorCenter1 === null) {
            doorsObject.d1center = parameterDoorCenter1
        } else doorsObject.d1center = 'false'

        doorsObject['stretch-ceiling'] = parameterCeiling
        doorsObject.otbonik = parameterOtbonik
        doorsObject.dovdhik = parameterDovodchik
        doorsObject.typed2 = parameterFrameType2
        doorsObject.d2fon = parameterDoorBackground2

        doorsObject.d2center = parameterDoorCenter2
        if (doorCenter2 === null) {
            doorsObject.d2center = parameterDoorCenter2
        } else doorsObject.d2center = 'false'


        sectionObject.width = parameterWidth.value
        sectionObject.height = parameterHeight.value
        sectionObject.deep = parameterDeep.value
        sectionObject.location = parameterLocation
        sectionObject.zakladnaia = parameterZakladnaia
        sectionObject['stretch-ceiling'] = parameterCeiling
        sectionObject.koldors = parameterAmountDoors

        sectionObject.section1 = parameterSection1
        sectionObject.section2 = parameterSection2
        if (parameterAmountDoors === '3' || parameterAmountDoors === '4') {
            sectionObject.section3 = parameterSection3
        }
        if (parameterAmountDoors === '4') {
            sectionObject.section4 = parameterSection4
        }

        sectionObject.colorframe = parameterSectionColor
        //console.log(sectionObject)

        let formData = new FormData()

        if (parameterCalculation === 'coupe') {
            let coupeObject = Object.assign(doorsObject, sectionObject, {id: dataBaseId})
            formData.append('price', JSON.stringify(coupeObject))
            console.log(dataBaseId)


        }

        postData('/zed/modules/price/price_load.php', formData, 'text')
            .then(res => {

                dataBaseId = res
            })


    }


    const clearInputs = () => {
        formInputs.forEach(item => {
            item.value = '';
        });
    };

    buttonsTriggerSend.forEach(item => {
        item.addEventListener('click', function () {
            collectionOfParameters()

        })
    })

    form.addEventListener('submit', (e) => {
        e.preventDefault();
        let imgFasad = document.querySelector('.fasad-img-canvas')
        let imgSection = document.querySelector('.inner-img-canvas')
        let parameterCalculation = document.querySelector('input[name="cabinet-parameters"]:checked').id
        let objectOrder = {}
        objectOrder.id = dataBaseId
        const formData = new FormData();


        if (parameterCalculation === 'coupe') {
            objectOrder.imgFasad = imgFasad.src
            objectOrder.imgSection = imgFasad.src

        }

        formData.append('add', JSON.stringify(objectOrder))
        formData.append('Payment', formOfPayment)
        postData('/zed/modules/sale/sale_load.php', formData, 'text')
            .then(res => {
                console.log(res);
            })
            .catch((res) => console.log(res))
            .finally(() => {
                // modal.style.display = 'none'
                // finalModal.style.display = 'block'
                // clearInputs();

            });
    });


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
} // функция для поиска выбранного элемента
function resetSection(obj) {
    function resetRadioButton(arr) {
        arr.forEach(radioBtn => {
            radioBtn.checked = false
        })
    }

    let previousState = {}

    Object.entries(obj).forEach((item, index, arr) => {
        let [typeSection, nameSection] = item
        let number = index + 1
        let checkedInput = document.querySelector(`input[data-section-number="${number}"]:checked`)

        let inputsNode = document.querySelectorAll(`input[data-section-number="${number}"]`)
        resetRadioButton(inputsNode) // сброс checked с input
        inputsNode.forEach(input => {
            let inputName = input.dataset.descr
            if (inputName === nameSection) {
                input.checked = true
            }
        })


    })
} // функция которая принудительно сбрасывает сецкию

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

function insertImagesInModal() {
    let btn = document.querySelectorAll('.calculator__next')

    function getImgCanvas(canvasSelector, imgSelector) {
        let canvas = document.querySelector(canvasSelector)
        let img = document.querySelector(imgSelector)
        img.src = canvas.toDataURL('image/jpeg')
    }

    btn.forEach(item => {
        item.addEventListener('click', function () {
            getImgCanvas('#fasad', '.fasad-img-canvas')
            getImgCanvas('#inner', '.inner-img-canvas')
        })
    })

} // вставка картинок из canvas в модальное окно "оформление заказа"

function hideSectionInput() {
    let inputTrigger = document.querySelectorAll('.send-info-section')
    let doorAmount = document.querySelector('input[name="section-amount"]:checked').dataset.article
    let location = document.querySelector('input[name="location"]:checked').dataset.article


    function formationObj() {
        let obj = {}
        obj.section1 = getNameSection('input[name="section-modal1"]')
        obj.section2 = getNameSection('input[name="section-modal2"]')
        obj.location = location
        obj.koldors = doorAmount
        if (doorAmount === '3' || doorAmount === '4') {

            obj.section3 = getNameSection('input[name="section-modal3"]')
        }
        if (doorAmount === '4') {
            obj.section4 = getNameSection('input[name="section-modal4"]')
        }
        // console.log(obj)
        return JSON.stringify(obj)
    }

    function sendInfo() {

        let amountDoorsId = searchCheckedElem('input[name="section-amount"]')
        let parameterLocationId = searchCheckedElem('input[name="location"]')
        let formData = new FormData()
        // let obj = {}
        // obj.kolDor = amountDoorsId
        // obj.loc = parameterLocationId
        // formData.append('displaySection', '')
        //         // formData.append('koldors', amountDoorsId)
        //         // formData.append('location', parameterLocationId)
        formData.append('sectionMap', formationObj())
        postData('/zed/modules/canvas/canvas_load.php', formData, 'json')
            .then((res) => {

                // displayInput('.modal-section-1', res.section1)
                // displayInput('.modal-section-2', res.section2)
                // displayInput('.modal-section-3', res.section3)
                // displayInput('.modal-section-4', res.section4)
                displayInput(res)
            })
    }

    sendInfo()

    function displayInput(obj) {

        Object.entries(obj)
            .sort()
            .forEach((item, index) => {
                let [sectionName, sectionArray] = item
                let modal = document.querySelector(`.modal-section-${index + 1}`)
                let inputs = modal.querySelectorAll('input')
                // console.log(sectionName, sectionArray)
                // console.log(modal)
                inputs.forEach(input => {
                    let parentElem = input.closest('.col')
                    let inputName = input.dataset.name

                    for (const arrItem of sectionArray) {
                        // console.log(inputName, '===', arrItem)

                        if (arrItem === inputName) {
                            // console.log(true)
                            parentElem.classList.remove('hide-section')
                            // console.log(input.closest('.col'))
                            break;
                        } else parentElem.classList.add('hide-section')
                    }
                })
            })


    }

    inputTrigger.forEach(item => {
        item.addEventListener('click', function () {

            sendInfo()

        })
    })


}   // получаю  от сервера к каким секциям нужно применить display block


function getObjectAccordance() {
    let amountDoorsId = searchCheckedElem('input[name="section-amount"]')
    let parameterLocationId = searchCheckedElem('input[name="location"]')
    // let doorAmount = document.querySelector('input[name="section-amount"]:checked').dataset.article
    // let location = document.querySelector('input[name="location"]:checked').dataset.article
    // let sectionAll = document.querySelectorAll('.section-input')
    let sectionAll = document.querySelectorAll('.section-input')

    function disabledInputSection(obj) {
        // console.log(obj)
        Object.entries(obj)
            .sort()
            .forEach((item, index) => {

            let [nameSection, sectionArray] = item

            let modal = document.querySelector(`.modal-section-${index + 1}`)
            let inputs = modal.querySelectorAll('input')

            inputs.forEach(input => {

                let inputName = input.dataset.name

                for (const arrItem of sectionArray) {

                    if (arrItem === inputName) {

                        input.disabled = false
                        break;
                    } else input.disabled = true
                }
            })

        })
        // function disabled(modalSelector, section) {
        //
        //     let modal = document.querySelector(modalSelector)
        //     let sectionArray = obj[section]
        //
        //
        // }

        // disabled('.modal-section-1', 'section1')
        // disabled('.modal-section-2', 'section2')
        // disabled('.modal-section-3', 'section3')
        // disabled('.modal-section-4', 'section4')
    }

    function sendInfo() {
        let formData = new FormData

        formData.append('displaySection', '')
        formData.append('koldors', amountDoorsId)
        formData.append('location', parameterLocationId)
        postData('/zed/modules/canvas/canvas_load.php', formData, 'json')
            .then((res) => {
                console.log('displaySection' ,res)
                disabledInputSection(res)

            })
    }



    sectionAll.forEach(item => {
        item.addEventListener('click', function () {
            sendInfo()



        })
    })
    sendInfo()
} // получаю  от сервера к каким секциям надо применить атрибут disabled


function createDescrOrder() {
    function getDescription(elementSelector) {
        let inputSelector = document.querySelectorAll(elementSelector)
        let result = false

        inputSelector.forEach(item => {
            if (item.checked) {

                result = item.dataset.descr
            }
        })
        return result

    }

    let btn = document.querySelectorAll('.calculator__next')


    function descriptionFormation() {

        let amountDoors = document.querySelector('input[name="section-amount"]:checked').dataset.article
        let frameType1 = document.querySelector('input[name="type-frame-1"]:checked').dataset.article
        let frameType2 = document.querySelector('input[name="type-frame-2"]:checked').dataset.article
        let resultListDoor = document.querySelector('#result-list-doors')
        let resultListSection = document.querySelector('#result-list-section')
        let doorsFon1 = getDescription('input[name="texture-doors1"]')
        let doorsFon2 = getDescription('input[name="texture-doors2"]')
        let doorsCenter1 = getDescription('input[name="texture-doors2"]')
        let doorsCenter2 = getDescription('input[name="texture-center-doors2"]')
        let colorCorpus = getDescription('input[name="texture-doors"]')
        let section1 = getDescription('input[name="section-modal1"]')
        let section2 = getDescription('input[name="section-modal2"]')
        let section3 = getDescription('input[name="section-modal3"]')
        let section4 = getDescription('input[name="section-modal4"]')
        let objDoors = {}
        let objSection = {}

        objDoors.stretchCeiling = getDescription('#stretch-ceiling')

        if (amountDoors === '2') {
            if (frameType1 !== '1') {
                objDoors.door1 = `Левая дверь: ${doorsFon1}  вставка ${doorsCenter1}`

            } else objDoors.door1 = 'левая дверь: ' + doorsFon1


            if (frameType2 !== '1') {
                objDoors.door2 = `Правая дверь: ${doorsFon2}  вставка ${doorsCenter2}`
            } else objDoors.door2 = 'правая дверь: ' + doorsFon2

        }
        if (amountDoors === '3' || amountDoors === '4') {
            if (frameType1 !== '1') {
                objDoors.door1 = `Крайняя дверь : ${doorsFon1}  вставка ${doorsCenter1}`

            } else objDoors.door1 = `Крайняя дверь : ${doorsFon1}`


            if (frameType2 !== '1') {
                objDoors.door2 = `Центральная дверь: ${doorsFon2}  вставка ${doorsCenter2}`
            } else objDoors.door2 = `Центральная дверь: ${doorsFon2}`


        }
        objDoors.frame = 'Профиль: ' + getDescription('input[name="frame-type"]')
        objDoors.doorClosers = getDescription('#dovodchiki')

        // descrSection ---------------------------------------------------


        objSection.section1 = 'Секция №1: ' + section1
        objSection.section2 = 'Секция №2: ' + section2
        if (amountDoors === '3' || amountDoors === '4') {
            objSection.section3 = 'Секция №3: ' + section3

        }
        if (amountDoors === '4') {
            objSection.section4 = 'Секция №4: ' + section4

        }
        objSection.doorClosers = 'Цвет корпуса: ' + colorCorpus
        objSection.encumbrance = getDescription('#zakladnaia')
        objSection.fenders = getDescription('#otboinik')


        function insertObjectInHtml(obj, ulParent) {
            ////console.log(resultListSection)
            let string = ''
            ////console.log(obj)
            for (const key in obj) {
                ////console.log(obj[key])
                if (obj[key] !== false) {

                    string += `<li class="calculator__list-item"> ${obj[key]} </li>`
                }
            }
            //console.log(string)
            ulParent.innerHTML = string
        }

        insertObjectInHtml(objDoors, resultListDoor)
        insertObjectInHtml(objSection, resultListSection)


    }

    btn.forEach(item => {
        item.addEventListener('click', descriptionFormation)
    })


}  // формирование описания для модального окна "оформление заказа"


const constructor = () => {


    let images = document.querySelector('.canvas-result');

    let outerCanvas = new EasyC(document.querySelector('#fasad'));
    let innerCanvas = new EasyC(document.querySelector('#inner'));
    let canvasTrigger = document.querySelectorAll('.canvas-trigger');
    let resetButtonTrigger = document.querySelectorAll('.reset-section')
    let count = 0

    let formData = new FormData

    formData.append('getImg', '')
    postData('/zed/modules/canvas/canvas_load.php', formData, 'json')
        .then((res) => {
            Render(res)

            canvasTrigger.forEach(canvasTriggerItem => {
                canvasTriggerItem.addEventListener('click', () => {

                    Render(res)
                })
            })

            resetButtonTrigger.forEach(resetTrigger => {
                resetTrigger.addEventListener('click', function () {
                    let numberSection = this.dataset.section
                    let nameSection = this.dataset.name
                    let doorAmount = document.querySelector('input[name="section-amount"]:checked').dataset.article
                    let location = document.querySelector('input[name="location"]:checked').dataset.article
                    let section1Id = getNameSection('input[name="section-modal1"]')
                    let section2Id = getNameSection('input[name="section-modal2"]')

                    let obj = {
                        section1: section1Id,
                        section2: section2Id,
                        reset: numberSection,

                        location: location,
                        koldors: doorAmount

                    }
                    if (doorAmount === '3' || doorAmount === '4') {

                        obj.section3 = getNameSection('input[name="section-modal3"]')
                    }
                    if (doorAmount === '4') {
                        obj.section4 = getNameSection('input[name="section-modal4"]')
                    }
                    obj[`section${numberSection}`] = nameSection

                    let form = new FormData()
                    form.append('reset', JSON.stringify(obj))

                    postData('/zed/modules/canvas/canvas_load.php', form, 'json')
                        .then(resetObject => {

                            resetSection(resetObject)
                            getObjectAccordance()
                            Render(res)

                        })


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
    //     //console.log(cashAray)
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
    //         // //console.log(item)
    //         await loadImg(item)
    //         //console.log(i + '    ' + item)
    //
    //     })
    //
    //


    //


    function Render(imgObject) {

        //console.log(imgObject)
        if (outerCanvas.objects.length >= 1 || innerCanvas.objects.length >= 1) {
            outerCanvas.objects = [];
            innerCanvas.objects = [];
            // outerCanvas.clean()
            // innerCanvas.clean()
            count += 10
        }

        // фунция для рендора canvas
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
                    x: 784
                },
            }


            renderItems.forEach(itemImg => {

                // //console.log(itemImg)
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

                // //console.log(currentObjImg)
                canvas.objects.push(currentObjImg)


            })


            // //console.log(sheet.objects)

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
                //console.log(background)
                // //console.log('фон', background)
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
                    // //console.log(textureFacade1)
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


                    // //console.log(parameterDoorBackground1)
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

                    // //console.log(textureFacade1)
                    let textureCenter1 = imgObject.textureCenterFacade.filter(item => {
                        if (item.article === parameterDoorCenter1 && item.type === parameterFrameType1) {
                            return item
                        }
                    })
                    //console.log(textureCenter1)
                    sortLastNumber(textureCenter1)
                    //console.log(textureCenter1)
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
                    //console.log(parameterDoorCenter2)
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


                // //console.log(resultArray)
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
                let parameterCalculation = document.querySelector('input[name="cabinet-parameters"]:checked').id
                let doorCenter1 = document.querySelector('input[name="texture-center-doors1"]:disabled')
                let doorCenter2 = document.querySelector('input[name="texture-center-doors2"]:disabled')
                //console.log(doorCenter1)
                let doorsObject = {}
                let sectionObject = {}

                function getNameSection(selectorElem) {
                    let nodeList = document.querySelectorAll(selectorElem)
                    let name = ''
                    nodeList.forEach(item => {
                        if (item.checked) {
                            name = item.dataset.descr
                        }

                    })
                    return name
                }

                doorsObject.koldors = parameterAmountDoors
                doorsObject.width = parameterWidth.value
                doorsObject.height = parameterHeight.value
                doorsObject.deep = parameterDeep.value
                doorsObject.location = parameterLocation
                doorsObject.colorframe = parameterFrame
                doorsObject.typed1 = parameterFrameType1
                doorsObject.d1fon = parameterDoorBackground1

                if (doorCenter1 === null) {
                    doorsObject.d1center = parameterDoorCenter1
                } else doorsObject.d1center = 'false'

                doorsObject['stretch-ceiling'] = parameterCeiling
                doorsObject.otbonik = parameterOtbonik
                doorsObject.dovdhik = parameterDovodchik
                doorsObject.typed2 = parameterFrameType2
                doorsObject.d2fon = parameterDoorBackground2

                doorsObject.d2center = parameterDoorCenter2
                if (doorCenter2 === null) {
                    doorsObject.d2center = parameterDoorCenter2
                } else doorsObject.d2center = 'false'


                sectionObject.width = parameterWidth.value
                sectionObject.height = parameterHeight.value
                sectionObject.deep = parameterDeep.value
                sectionObject.location = parameterLocation
                sectionObject.zakladnaia = parameterZakladnaia
                sectionObject['stretch-ceiling'] = parameterCeiling
                sectionObject.koldors = parameterAmountDoors

                sectionObject.section1 = getNameSection('input[name="section-modal1"]')
                sectionObject.section2 = getNameSection('input[name="section-modal2"]')
                if (parameterAmountDoors === '3' || parameterAmountDoors === '4') {
                    sectionObject.section3 = getNameSection('input[name="section-modal3"]')
                }
                if (parameterAmountDoors === '4') {
                    sectionObject.section4 = getNameSection('input[name="section-modal4"]')
                }

                sectionObject.colorframe = parameterSectionColor
                //console.log(sectionObject)

                let formData = new FormData()
                if (parameterCalculation === 'coupe') {
                    formData.append('fasad', JSON.stringify(doorsObject))
                    formData.append('section', JSON.stringify(sectionObject))

                }
                if (parameterCalculation === 'coupe1') {
                    formData.append('fasad', JSON.stringify(doorsObject))

                }
                if (parameterCalculation === 'coupe2') {
                    formData.append('section', JSON.stringify(sectionObject))

                }


                postData('/zed/modules/canvas/canvas_load.php', formData, 'text')
                    .then((res) => {
                        let priceResult = document.querySelector('.canvas-price')
                        let arr = res.split('-')
                        let result = 0
                        arr.forEach(item => {
                            if (!isNaN(parseFloat(item))) {
                                //console.log(+item)
                                result = result + +item
                            }
                        })
                        priceResult.textContent = result + 'руб.'

                    })
                    .then(

                    )


            })();


        }


        innerCanvas.draw() // вызов рендора для канваса
        outerCanvas.draw()

        //console.log(outerCanvas.objects)
        // console.log(innerCanvas.objects)


    }


    // canvasLoadingImages(jsonSkafella)


} // функция для канваса


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

    function disabledMirrorTypeDoor1() {
        function disabledTexture(textureSelector, typeDoorSelector) {
            let typeDoor = document.querySelector(typeDoorSelector)
            let hideArray = ['7', '8', '9', '10']
            let texture = document.querySelectorAll(textureSelector)
            let maxHeightMirror = +heightInput.dataset.maxHeightMirror

            texture.forEach(item => {
                item.disabled = false
            })
            texture.forEach(item => {
                let id = item.dataset.article

                hideArray.forEach(hideItem => {
                    if (id === hideItem && typeDoor.dataset.article === '1' && +heightInput.value >= maxHeightMirror) {
                        item.disabled = true
                    }
                })

            })
        }

        heightInput.addEventListener('input', () => {
            disabledTexture('input[name="texture-doors1"]', 'input[name="type-frame-1"]:checked')
            disabledTexture('input[name="texture-doors2"]', 'input[name="type-frame-2"]:checked')
        })
        typeDoors1.forEach(item => {
            item.addEventListener('click', () => {
                disabledTexture('input[name="texture-doors1"]', 'input[name="type-frame-1"]:checked')
                disabledTexture('input[name="texture-doors2"]', 'input[name="type-frame-2"]:checked')
            })
        })
        typeDoors2.forEach(item => {
            item.addEventListener('click', () => {
                disabledTexture('input[name="texture-doors1"]', 'input[name="type-frame-1"]:checked')
                disabledTexture('input[name="texture-doors2"]', 'input[name="type-frame-2"]:checked')
            })
        })


    }

    disabledMirrorTypeDoor1()

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

    function buttonNameChange() {
        let amount = document.querySelector('input[name="section-amount"]:checked').dataset.article
        //console.log(amount)
        let btn = document.querySelectorAll('.calc__doors-tab')
        if (amount === '2') {
            btn[0].textContent = 'Левая'
            btn[1].textContent = 'Правая'
        } else {
            btn[0].textContent = 'Крайняя'
            btn[1].textContent = 'Центральная'
        }
    }

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

            //console.log(min)
            //console.log(max)
            //console.log(value)
            if (value >= min && value <= max) {
                obj.width = true
                //console.log(item)
                break;


            } else obj.width = false
        }


        disabledButton()


    })
    amountDoorsRadio.forEach(item => {
        item.addEventListener('click', function () {
            obj.amountDoors = true
            buttonNameChange()

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

}  // функция для ограничения переходов между табами если не введены нужные параметры
const tabsCalculator = (headerSelector, tabSelector, contentSelector, activeClass, nextButton, prevButton) => {
    const header = document.querySelector(headerSelector),
        tab = document.querySelectorAll(tabSelector),
        content = document.querySelectorAll(contentSelector),
        prevBtn = document.querySelectorAll(prevButton),
        nextBtn = document.querySelectorAll(nextButton)
    let previewTabActive = +header.dataset.tabActive - 1
    let cabinetParameters = document.querySelectorAll('input[name="cabinet-parameters"]')
    let cabinetParametersChecked = document.querySelector('input[name="cabinet-parameters"]:checked').id
    let currentTab = 0
    let modal = document.querySelector('.modal-calc')
    let headerItem = document.querySelectorAll('.calculator-header__item')

    function coupePlus() {

        hideTabContent()
        showTabContent(currentTab)
        if (currentTab < 2) {
            currentTab++
            hideTabContent()
            showTabContent(currentTab)
        } else {
            document.body.style.overflow = 'hidden'
            modal.style.display = 'block'

        }
        //console.log(currentTab)
    }  // фунция для шкафа купе

    function coupeMinus() {
        currentTab--
        hideTabContent()
        showTabContent(currentTab)
        //console.log(currentTab)
    } // фунция для шкафа купе

    function coupe1Plus() {

        if (currentTab === 0) {
            currentTab = 1
        } else {
            document.body.style.overflow = 'hidden'
            modal.style.display = 'block'

        }
        hideTabContent()
        showTabContent(currentTab)
        //console.log(currentTab)
    } //фунция для  Только двери

    function coupe1Minus() {
        if (currentTab === 0) {
            currentTab = 2
            hideTabContent()
            showTabContent(currentTab)
        } else {
            document.body.style.overflow = 'hidden'
            modal.style.display = 'block'
        }


    } //фунция для  Только двери

    function coupe2Plus() {

        if (currentTab === 0) {
            currentTab = 2
        } else {
            document.body.style.overflow = 'hidden'
            modal.style.display = 'block'
        }
        hideTabContent()
        showTabContent(currentTab)
        //console.log(currentTab)
    } //фунция для  Только внутреннее наполнение

    function coupe2Minus() {
        if (currentTab === 2) {
            currentTab = 0
            hideTabContent()
            showTabContent(currentTab)
        }


    }// фунция для  Только внутреннее наполнение
    //console.log(cabinetParametersChecked)
    if (cabinetParametersChecked !== null && cabinetParametersChecked === 'coupe') {

        nextBtn.forEach(item => {
            item.addEventListener('click', coupePlus)
        })
        prevBtn.forEach(item => {
            item.addEventListener('click', coupeMinus)
        })
    }
    if (cabinetParametersChecked !== null && cabinetParametersChecked === 'coupe1') {

        nextBtn.forEach(item => {
            item.addEventListener('click', coupe1Plus)
        })

        prevBtn.forEach(item => {
            item.addEventListener('click', coupe1Minus)
        })

    }
    if (cabinetParametersChecked !== null && cabinetParametersChecked === 'coupe2') {

        nextBtn.forEach(item => {
            item.addEventListener('click', coupe2Plus)
        })

        prevBtn.forEach(item => {
            item.addEventListener('click', coupe2Minus)
        })

    }

    cabinetParameters.forEach(item => {

        item.addEventListener('click', function () {
            headerItem.forEach(item => {
                item.style.pointerEvents = 'auto'
                item.style.opacity = '1'
            })
            currentTab = 0
            nextBtn.forEach(item => {
                item.removeEventListener('click', coupePlus)
                item.removeEventListener('click', coupe1Plus)
                item.removeEventListener('click', coupe2Plus)
            })
            prevBtn.forEach(item => {
                item.removeEventListener('click', coupeMinus)
                item.removeEventListener('click', coupe1Minus)
                item.removeEventListener('click', coupe2Minus)
            })
            //console.log(this)

            if (this.id === 'coupe') {
                //console.log(1)
                nextBtn.forEach(item => {
                    item.addEventListener('click', coupePlus)
                })
                prevBtn.forEach(item => {
                    item.addEventListener('click', coupeMinus)
                })
            }
            if (this.id === 'coupe1') {
                headerItem[2].style.pointerEvents = 'none'
                headerItem[2].style.opacity = '.5'
                nextBtn.forEach(item => {
                    item.addEventListener('click', coupe1Plus)
                })

                prevBtn.forEach(item => {
                    item.addEventListener('click', coupe1Minus)
                })


            }

            if (this.id === 'coupe2') {
                headerItem[1].style.pointerEvents = 'none'
                headerItem[1].style.opacity = '.5'
                nextBtn.forEach(item => {
                    item.addEventListener('click', coupe2Plus)
                })

                prevBtn.forEach(item => {
                    item.addEventListener('click', coupe2Minus)
                })

            }
        })

    })


    function hideTabContent() {
        content.forEach((item) => {
            item.style.display = 'none'
        });
        tab.forEach((item) => {
            item.classList.remove((activeClass))
        })
    }

    function showTabContent(i = 0) {
        if (i > content.length - 1) {
            content[content.length - 1].style.display = 'block';
            tab[content.length - 1].classList.add(activeClass);

        } else {
            content[i].style.display = 'block';
            tab[i].classList.add(activeClass);
        }

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
    hideTabContent()

    if (isNaN(previewTabActive) || previewTabActive === -1) {
        showTabContent(0)
    } else showTabContent(previewTabActive)


} // табы для калькулятора

getObjectAccordance()
// inputLimitation()
insertImagesInModal()
createDescrOrder()
tabsCalculator('.calculator-header', '.calculator-header__item', '.calculator-content', 'calculator__item-active', '.calculator__next', '.calculator__prev')
synchronizationCheckbox('input[name="zakladnaia"]')
synchronizationCheckbox('input[name="stretch-ceiling"]')
constructor()
createDescrOrder()
hideSectionInput()
sendSale()
