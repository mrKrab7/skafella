const tabs = (headerSelector, tabSelector, contentSelector, activeClass) => {
    const header = document.querySelector(headerSelector),
        tab = document.querySelectorAll(tabSelector),
        content = document.querySelectorAll(contentSelector)

    function hideTabContent() {
        content.forEach((item) => {
            item.style.display = 'none'
        });
        tab.forEach((item) => {
            item.classList.remove((activeClass))
        })
    }

    function showTabContent(i = 0) {
        content[i].style.display = 'flex';
        tab[i].classList.add(activeClass);

    }

    header.addEventListener('click', (e) => {

        const target = e.target

        tab.forEach((item, i) => {
            if (target === item || target.parentNode === item) {

                hideTabContent();
                showTabContent(i)
            }
        })

    })
    hideTabContent()
    showTabContent();

}
tabs('.tabs-block', '.tabs', '.tabContent', 'after-click')
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
    let count = 0
    let jsonSkafella = {
        typeDoor2: {
            backgroundFon: [{
                src: 'photo/fon-2d.png',
                type: 'center',
                zakladnaia: false,
                locationOnCanvas: 'background'
            },
                {
                    src: 'photo/fon-2d-left.png',
                    type: 'left',
                    zakladnaia: false,
                    locationOnCanvas: 'background'
                }, {
                    src: 'http',
                    type: 'right'
                }, {src: 'http', type: 'center', zakladnaia: false}],
            // фасад (как выглядит снаруже)
            frameFacade: [{
                type: 'd',
                src: 'http',
                color: 'gray',
                doorPosition: 'door1',
                DoorHandle: 'handle1',
                article: '&we432ds'
            }],
            textureFacade: [{
                src: 'http',
                doorPosition: 'door1',
                article: '&we432ds'
            }],
            textureCenterFacade: [{
                type: 'd',
                src: 'http',
                doorPosition: 'door1',
                article: '&we432ds'
            }],

            //*********************************
            // внутрение наполнение

            textureCarcase: [
                {
                    typeSection: 'полки',
                    src: 'http',
                    sectionPosition: 'section1',
                    article: '&we432ds',
                    color: 'gray'

                }
            ],


        },
        typeDoor3: {
            backgroundFon: [{src: 'photo/fon-4d.png', type: 'center'}, {src: 'http', type: 'angular'}, {
                src: 'http',
                type: 'center-z'
            }, {src: 'http', type: 'angular-z'}],
            // фасад (как выглядит снаруже)
            frameFacade: [{
                type: 'd',
                src: 'http',
                color: 'gray',
                doorPosition: 'door1',
                DoorHandle: 'handle1',
                article: '&we432ds'
            }],
            textureFacade: [{
                src: 'http',
                doorPosition: 'door1',
                article: '&we432ds'
            }],
            textureCenterFacade: [{
                type: 'd',
                src: 'http',
                doorPosition: 'door1',
                article: '&we432ds'
            }],

            //*********************************
            // внутрение наполнение

            textureCarcase: [
                {
                    typeSection: 'полки',
                    src: 'http',
                    sectionPosition: 'section1',
                    article: '&we432ds',
                    color: 'gray'

                }
            ],


        },
        typeDoor4: {
            backgroundFon: [{
                src: 'photo/fon-4d.png',
                type: 'center',
                zakladnaia: false,
                locationOnCanvas: 'background'
            },],
            // фасад (как выглядит снаруже)
            frameFacade: [{
                type: 'd1',
                src: 'photo/type4_door1_r_d1.png',
                locationOnCanvas: 'frameType4Door1',
                DoorHandle: 'handle1',
                article: 'metallic'
            },
                {
                    type: 'd1',
                    src: 'photo/type4_door2_r_d1.png',
                    locationOnCanvas: 'frameType4Door2',
                    DoorHandle: 'handle1',
                    article: 'metallic'
                },
                {
                    type: 'd1',
                    src: 'photo/type4_door3_r_d1.png',
                    locationOnCanvas: 'frameType4Door3',
                    DoorHandle: 'handle1',
                    article: 'metallic'
                },
                {
                    type: 'd1',
                    src: 'photo/type4_door4_r_d1.png',
                    locationOnCanvas: 'frameType4Door4',
                    DoorHandle: 'handle1',
                    article: 'metallic'
                },
                {
                    type: 'd2',
                    src: 'photo/type4_door1_r_d2.png',
                    locationOnCanvas: 'frameType4Door1',
                    DoorHandle: 'handle1',
                    article: 'metallic'
                },
                {
                    type: 'd2',
                    src: 'photo/type4_door2_r_d2.png',
                    locationOnCanvas: 'frameType4Door2',
                    DoorHandle: 'handle1',
                    article: 'metallic'
                },
                {
                    type: 'd2',
                    src: 'photo/type4_door3_r_d2.png',
                    locationOnCanvas: 'frameType4Door3',
                    DoorHandle: 'handle1',
                    article: 'metallic'
                },
                {
                    type: 'd2',
                    src: 'photo/type4_door4_r_d2.png',
                    locationOnCanvas: 'frameType4Door4',
                    DoorHandle: 'handle1',
                    article: 'metallic'
                },
                {
                    type: 'd3',
                    src: 'photo/type4_door1_r_d3.png',
                    locationOnCanvas: 'frameType4Door1',
                    DoorHandle: 'handle1',
                    article: 'metallic'
                },
                {
                    type: 'd3',
                    src: 'photo/type4_door2_r_d3.png',
                    locationOnCanvas: 'frameType4Door2',
                    DoorHandle: 'handle1',
                    article: 'metallic'
                },
                {
                    type: 'd3',
                    src: 'photo/type4_door3_r_d3.png',
                    locationOnCanvas: 'frameType4Door3',
                    DoorHandle: 'handle1',
                    article: 'metallic'
                },
                {
                    type: 'd3',
                    src: 'photo/type4_door4_r_d3.png',
                    locationOnCanvas: 'frameType4Door4',
                    DoorHandle: 'handle1',
                    article: 'metallic'
                },
                {
                    type: 'd4',
                    src: 'photo/type4_door1_r_d4.png',
                    locationOnCanvas: 'frameType4Door1',
                    DoorHandle: 'handle1',
                    article: 'metallic'
                },
                {
                    type: 'd4',
                    src: 'photo/type4_door2_r_d4.png',
                    locationOnCanvas: 'frameType4Door2',
                    DoorHandle: 'handle1',
                    article: 'metallic'
                },
                {
                    type: 'd4',
                    src: 'photo/type4_door3_r_d4.png',
                    locationOnCanvas: 'frameType4Door3',
                    DoorHandle: 'handle1',
                    article: 'metallic'
                },
                {
                    type: 'd4',
                    src: 'photo/type4_door4_r_d4.png',
                    locationOnCanvas: 'frameType4Door4',
                    DoorHandle: 'handle1',
                    article: 'metallic'
                },
                {
                    type: 'd5',
                    src: 'photo/type4_door1_r_d5.png',
                    locationOnCanvas: 'frameType4Door1',
                    DoorHandle: 'handle1',
                    article: 'metallic'
                },
                {
                    type: 'd5',
                    src: 'photo/type4_door2_r_d5.png',
                    locationOnCanvas: 'frameType4Door2',
                    DoorHandle: 'handle1',
                    article: 'metallic'
                },
                {
                    type: 'd5',
                    src: 'photo/type4_door3_r_d5.png',
                    locationOnCanvas: 'frameType4Door3',
                    DoorHandle: 'handle1',
                    article: 'metallic'
                },
                {
                    type: 'd5',
                    src: 'photo/type4_door4_r_d5.png',
                    locationOnCanvas: 'frameType4Door4',
                    DoorHandle: 'handle1',
                    article: 'metallic'
                },

            ],

            textureFacade: [
                {
                    src: 'photo/type4_door1_fon_sosna.png',
                    locationOnCanvas: 'textureType4Door1',
                    article: 'sosna'
                },
                {
                    src: 'photo/type4_door2_fon_sosna.png',
                    locationOnCanvas: 'textureType4Door2',
                    article: 'sosna'
                },
                {
                    src: 'photo/type4_door3_fon_sosna.png',
                    locationOnCanvas: 'textureType4Door3',
                    article: 'sosna'
                },
                {
                    src: 'photo/type4_door4_fon_sosna.png',
                    locationOnCanvas: 'textureType4Door4',
                    article: 'sosna'
                },
                {
                    src: 'photo/type4_door4_fon_sosna.png',
                    locationOnCanvas: 'textureType4Door4',
                    article: 'sosna'
                },
                {
                    src: 'photo/type4_door2_fon_z.png',
                    locationOnCanvas: 'textureType4Door2',
                    article: 'glass'
                },
                {
                    src: 'photo/type4_door3_fon_z.png',
                    locationOnCanvas: 'textureType4Door3',
                    article: 'glass'
                },

            ],
            textureCenterFacade: [{
                type: 'd',
                src: 'http',
                doorPosition: 'door1',
                article: '&we432ds'
            }],

            //*********************************
            // внутрение наполнение

            textureCarcase: [
                {
                    typeSection: 'полки',
                    src: 'http',
                    sectionPosition: 'section1',
                    article: '&we432ds',
                    color: 'gray'

                }
            ],


        }


    }

    function canvasLoadingImages(resObject) {
        let cashAray = [];

        function loadSrc() {

            for (const key in resObject) {
                for (const keyKey in resObject[key]) {
                    resObject[key][keyKey].forEach(item => {
                        cashAray.push(item.src)

                    })

                }

            }
        }

        loadSrc()
        console.log(cashAray)

        function loadImg(url) {
            return new Promise(function (resolve, reject) {
                    let img = new Image()
                    img.onload = function () {
                        resolve(url)
                    }
                    img.onerror = function () {
                        reject(url);
                    };
                    img.src = url;
                }
            )
        }

        cashAray.forEach(async (item, i) => {
            // console.log(item)
            await loadImg(item)
            console.log(i +'    '+ item)

        })



        let canvasTrigger = document.querySelectorAll('.canvas-trigger');


        Render(resObject)


        canvasTrigger.forEach(canvasTriggerItem => {
            canvasTriggerItem.addEventListener('click', function () {
                Render(resObject)
            })
        })


    }

    function Render(imgObject) {

        if (outerCanvas.objects.length >= 1 || innerCanvas.objects.length >= 1) {
            outerCanvas.objects = [];
            innerCanvas.objects = [];
            // outerCanvas.clean()
            // innerCanvas.clean()
            count += 10
        }

        function canvasRender(renderItems, canvas) {
            const settingsCanvas = {

                background: {
                    x: 500,
                    z: 10
                },
                frameType4Door1: {
                    x: 296,
                    z: 6
                },
                frameType4Door2: {
                    x: 432,
                    z: 6
                },
                frameType4Door3: {
                    x: 583,
                    z: 6
                },
                frameType4Door4: {
                    x: 747,
                    z: 6
                },
                textureType4Door1: {
                    x: 295,
                    z: 3
                },
                textureType4Door2: {
                    x: 434,
                    z: 3
                },
                textureType4Door3: {
                    x: 580,
                    z: 3
                },
                textureType4Door4: {
                    x: 745,
                    z: 3
                },
                textureCenter1Type4: {
                    x: 306,
                    z: 4
                },
                textureCenter2Type4: {
                    x: 440,
                    y: 375,
                    z: 4
                },
                textureCenter3Type4: {
                    x: 590,
                    y: 375,
                    z: 4
                },
                textureCenter4Type4: {
                    x: 750,
                    y: 375,
                    z: 4
                },
                frameType2Door1: {
                    x: 303,
                    z: 5
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


                let currentObjImg = Object.assign({}, settingsCanvas[itemImg.locationOnCanvas])

                currentObjImg.type = 'image'
                currentObjImg.src = itemImg.src
                currentObjImg.locationOnCanvas = itemImg.locationOnCanvas
                currentObjImg.z = currentObjImg.z + count
                currentObjImg.y = 325
                // console.log(currentObjImg)
                canvas.objects.push(currentObjImg)


            })


            // console.log(sheet.objects)

        }

        function searchCheckedElem(selectorElem) {
            let nodeList = document.querySelectorAll(selectorElem)
            let checkedElement
            nodeList.forEach(item => {
                if (item.checked) {
                    checkedElement = item.id
                }
            })
            return checkedElement
        }

        if (typeof imgObject === 'object' && imgObject !== null) {
            let parameterNumberOfDoors = document.querySelector('input[name="col-doors"]:checked').id; // получаем id выбраного параметра отвечающего за количество дверей
            let parameterLocation = document.querySelector('input[name="location"]:checked').id; // получаем id выбраного параметра отвечающего за расположение шкафа
            let parameterFrameType = document.querySelector('input[name="door-left-type"]:checked').id
            let parameterFrameColor = document.querySelector('input[name="color-frame"]:checked').id;
            let parameterTextureColor = document.querySelector('input[name="door-left-texture-bg"]:checked').id;
            let parameterDoorCenterType = document.querySelector('input[name="door-center-type"]:checked').id;
            let parameterSectionsColor = document.querySelector('input[name="section-color"]:checked').id;
            let parameterTextureCenterColor = searchCheckedElem('input[name="door-left-texture-center"]');


            (function backgroundRender() {  // фунция отвечающия за рендер фона
                let arrayBackgroundImages = imgObject[parameterNumberOfDoors].backgroundFon  // сохроняем в переменую все доступные варианты по выбору фона
                let background = arrayBackgroundImages.filter(item => {
                    if (item.type === parameterLocation) {
                        return item
                    }

                }) // производим поиск нужного фона по выбраным параметрам
                // console.log('фон', background)

                canvasRender(background, outerCanvas)
                canvasRender(background, innerCanvas)

            }());
            (function frameRender() {  // фунция отвечающия за рендер рамок

                let arrayFrameImages = imgObject[parameterNumberOfDoors].frameFacade

                // console.log(arrayFrameImages)
                let frameSearchResults = arrayFrameImages.filter(item => {
                    if (item.article === parameterFrameColor && item.type === parameterFrameType) {
                        return item
                    }

                })
                if (parameterDoorCenterType !== 'as-everyone' && parameterNumberOfDoors === 'typeDoor4') {
                    let searchFrameForMirror = arrayFrameImages.filter((item) => {
                        if (item.article === parameterFrameColor
                            && item.type === 'd1'
                            && item.locationOnCanvas !== 'frameType4Door1'
                            && item.locationOnCanvas !== 'frameType4Door4') {
                            return item
                        }
                        if (item.article === parameterFrameColor
                            && item.type === parameterFrameType
                            && item.locationOnCanvas !== 'frameType4Door2'
                            && item.locationOnCanvas !== 'frameType4Door3') {
                            return item
                        }

                    }) // поиск профяля для зеркала в центр шкафа
                    canvasRender(searchFrameForMirror, outerCanvas)
                    return

                }
                // console.log(frameSearchResults)
                canvasRender(frameSearchResults, outerCanvas)


            }());
            (function textureBackgroundRender() {  // фунция отвечающия за рендер фоновых текстур дверей

                let arrayTextureImages = imgObject[parameterNumberOfDoors].textureFacade
                let textureSearchResults = arrayTextureImages.filter(item => {
                    if (item.article === parameterTextureColor) {
                        return item
                    }
                })
                canvasRender(textureSearchResults, outerCanvas)
            }());
            (function textureMirrorsRender() {  // фунция отвечающия за рендер фоновых текстур дверей

                if (parameterDoorCenterType !== 'as-everyone' && parameterNumberOfDoors === 'typeDoor4') {
                    let swapItem = document.querySelector('input[name="door-center-type"]:checked').id
                    let arrayTextureImages = imgObject[parameterNumberOfDoors].textureFacade
                    let resultGlassTexture = arrayTextureImages.filter(item => {

                        if (item.swap === swapItem) {
                            return item
                        }
                    })

                    canvasRender(resultGlassTexture, outerCanvas)

                }
            }());
            (function textureCenterRender() {  // фунция отвечающия за рендер фоновых текстур дверей
                if (parameterTextureCenterColor !== 'undefined') {
                    let arrayTextureImages = imgObject[parameterNumberOfDoors].textureCenterFacade

                    if (parameterDoorCenterType !== 'as-everyone' && parameterNumberOfDoors === 'typeDoor4') {
                        let searchTextureForMirror = arrayTextureImages.filter((item) => {
                            if (item.article === parameterTextureCenterColor
                                && item.type === parameterFrameType
                                && item.locationOnCanvas !== 'textureCenter2Type4'
                                && item.locationOnCanvas !== 'textureCenter3Type4') {
                                return item
                            }


                        })

                        canvasRender(searchTextureForMirror, outerCanvas)
                        return


                    } // делаем исключение на убирание цетровых вставок при наличий включеного зеркала у 2 и 3 двери
                    let textureSearchResults = arrayTextureImages.filter(item => {
                        if (item.article === parameterTextureCenterColor && item.type === parameterFrameType) {
                            return item
                        }
                    })


                    canvasRender(textureSearchResults, outerCanvas)
                }


            }());
            (function sectionRender() {
                console.log('sectionColor', parameterSectionsColor)
                let arraySectionImages = imgObject[parameterNumberOfDoors].textureCarcase
                let section1Id = searchCheckedElem('input[name="section-1"]')
                let section2Id = searchCheckedElem('input[name="section-2"]')

                let section1Result = arraySectionImages.filter(item => {

                    if (item.article === section1Id && item.color === parameterSectionsColor) {
                        return item
                    }
                })
                console.log(arraySectionImages)
                console.log(section1Result)
                canvasRender(section1Result, innerCanvas)
                let section2Result = arraySectionImages.filter(item => {
                    if (item.article === section2Id && item.color === parameterSectionsColor) {
                        return item
                    }
                })

                canvasRender(section2Result, innerCanvas)


                if (parameterNumberOfDoors === 'typeDoor3' || parameterNumberOfDoors === 'typeDoor4') { /// поменять потом условие на количество секций
                    let section3Id = searchCheckedElem('input[name="section-3"]')
                    let section3Result = arraySectionImages.filter(item => {
                        if (item.article === section3Id && item.color === parameterSectionsColor) {
                            return item
                        }
                    })
                    canvasRender(section3Result, innerCanvas)
                }
                if (parameterNumberOfDoors === 'typeDoor4') { /// поменять потом условие на количество секций
                    let section4Id = searchCheckedElem('input[name="section-4"]')
                    let section4Result = arraySectionImages.filter(item => {
                        if (item.article === section4Id && item.color === parameterSectionsColor) {
                            return item
                        }
                    })
                    canvasRender(section4Result, innerCanvas)
                }
                // if (parameterNumberOfDoors === 'typeDoor3') { /// поменять потом условие на количество секций
                //     let section3Id = searchCheckedElem('input[name="section-3"]')
                //     let section3Result = arraySectionImages.filter(item => {
                //         if (item.article === section3Id) {
                //             return item
                //         }
                //     })
                //     canvasRender(section3Result, innerCanvas)
                // }


                // canvasRender(textureSearchResults, outerCanvas)
            }());
        }

        innerCanvas.draw()
        outerCanvas.draw()
        console.log(innerCanvas.objects)

    }

    let formData = new FormData
    formData.append('getImg', '')
    postData('/zed/modules/canvas/canvas.php', formData)
        .then((res) => {

            canvasLoadingImages(res)
        })

    // canvasLoadingImages(jsonSkafella)

    let settingsShkaf = {
        //фасад (параметры)
        selectHandle: [{
            src: 'http',
            dataArticle: '&ew34lx',
            checked: true,
            name: '...',
            description: ''

        }],
        selectСarcass: [{
            src: 'http',
            dataArticle: '&ew34lx',
            checked: true,
            name: '...',
            description: ''
        }],
        selectTextureFacade: [{
            src: 'http',
            dataArticle: '&ew34lx',
            checked: true,
            id: '1',
            name: '...',
            description: ''
        }],
        selectTextureCenterFacade: [{
            src: 'http',
            dataArticle: '&ew34lx',
            checked: true,
            id: '1',
            name: '...',
            description: ''
        }],
        //внутрение наполнение (параметры)
        selectTextureCarcase: [{
            src: 'http',
            dataArticle: '&ew34lx',
            checked: true,
            id: '1',
            name: '...',
            description: '',


        }],
        selectTextureSection1: [{
            src: 'http',
            dataArticle: '&ew34lx',
            checked: true,
            id: '1',
            name: '...',
            description: '',
            sectionType: '',
        }],
        selectTextureSection2: [{
            src: 'http',
            dataArticle: '&ew34lx',
            checked: true,
            id: '1',
            name: '...',
            description: '',
            sectionType: ''
        }],
        selectTextureSection3: [{
            src: 'http',
            dataArticle: '&ew34lx',
            checked: true,
            id: '1',
            name: '...',
            description: '',
            sectionType: ''
        }],
        selectTextureSection4: [{
            src: 'http',
            dataArticle: '&ew34lx',
            checked: true,
            id: '1',
            name: '...',
            description: '',
            sectionType: ''
        }],
    }
}
constructor()

const inputLimitation = () => {
    let allInput = document.querySelectorAll('.measurement-input');
    let withInput = document.querySelector('#width');
    allInput.forEach(item => {
        item.addEventListener('input', function () {

            this.value = this.value.replace(/[^0-9]/g, '')
            if (this.value.length > 4) {
                this.value = this.value.slice(0, 4)
            }


        })
    })
    withInput.addEventListener('input', function () {
        let activeRadioButton = document.querySelector('input[name="col-section"]:checked')
        let maxWidth = +activeRadioButton.dataset.maxWidth
        let minWidth = +activeRadioButton.dataset.minWidth

        let currentValue = this.value
        if (+this.value > maxWidth) {
            this.value = maxWidth

        } else if (+this.value < minWidth && this.value.length > 3) {

            this.value = minWidth


        } else this.value = currentValue
    })

}
inputLimitation()
const radioButtonLimitation = () => {
    inputLimitation()
    let allRadioButtonDoors = document.querySelectorAll('input[name="col-doors"]')
    let allRadioButtonSections = document.querySelectorAll('input[name="col-section"]')

    function checkColDoors() {
        let checkedRadioButtonDoorsId = document.querySelector('input[name="col-doors"]:checked').id

        allRadioButtonSections.forEach(item => {
            if (checkedRadioButtonDoorsId !== 'typeDoor0') {
                item.setAttribute('disabled', '')
                item.removeAttribute('checked', '')

            } else {
                item.removeAttribute('disabled', '')
                item.removeAttribute('checked', '')
            }

            if (item.dataset.sections === checkedRadioButtonDoorsId) {


                item.removeAttribute('disabled', '')
                item.checked = true

            }
        })
    }


    allRadioButtonDoors.forEach(itemButtonDo0rs => {
        itemButtonDo0rs.addEventListener('click', checkColDoors)
    })
}
radioButtonLimitation()

let fasad = {
    width: '',
    height: '',
    doorCol: '', // количество дверей
    handle: '', // ручка
    colorFrame: '', // цвет профиля
    doorСloser: '', // доводчик для двери
    colorFasad: '', // фоновый цвет двери
    colorCenter: '', // центровая часть двери (если есть то отправляется)
    centerDoor: true, // двери которые находятся по середине  такие как все или зеркальные
}

let section = {
    width: '',
    height: '',
    sectionCol: '', // количество секций
    fenders: '', // отбойники
    colorFrame: '', // цвет профиля
    stretchСeiling: '', // натяжной потолок
    zakladnaia: false, // наличие закладной
    section1: '',
    section2: '',
    section3: '',
    section4: '',
}