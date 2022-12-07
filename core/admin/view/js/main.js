//================================================================
//Кастомный Select
//================================================================
const forms = document.querySelectorAll('.wq-form-select');

if (forms.length) {

    const selects = document.querySelectorAll('.wq-select');

    const filters = () => {

        for (let i = 0; i < selects.length; i++) {

            if(!selects[i].hasAttribute('data-exclude-custom-select')){

                new Choices(selects[i], {

                    noResultsText: 'Ничего не найдено',
                    shouldSort: false,

                });

            }
        }
    };

    filters();
}
//================================================================
//color picker
//================================================================
const cpBlocks = document.querySelectorAll('[data-cp]');

if (cpBlocks.length) {

    jsColorPicker('input.color', {

        customBG: '#222',

        readOnly: true,

        init: function(elm, colors){

            elm.style.backgroundColor = elm.value;

            elm.style.color = colors.rgbaMixCustom.luminance > 0.22 ? '#222' : '#ddd';

        },

    });

    //Функция вывода цвета
    const colorShow = function(){

        for(let c = 0; c < cpBlocks.length; c++){

            let cpBlock = cpBlocks[c];

            let cpColorBlock = cpBlock.querySelector('.wq-block__cp-wrap');

            let cpColorStr = cpBlock.querySelector('.wq-block__input').value;

            if(cpColorStr !== 0 ){   

                cpColorBlock.innerHTML = ``;

                let cpColorArr = cpColorStr.split(';');   

                for(let a = 0; a < cpColorArr.length; a++){

                    if(cpColorArr[a]){

                        let cpColor = cpColorArr[a];

                        cpColorBlock.innerHTML +=

                            `<div class="wq-block__cp-color" style = "background-color:` +  cpColor +`"></div>`;                        
                    }
                }             
            }            
        }
    }
    colorShow();

    //Изменение input

    document.querySelectorAll('[data-cp]').forEach(cpInputParent => {

        if(cpInputParent.querySelector('.wq-block__input')){

            cpInputParent.querySelector('.wq-block__input').addEventListener("change", function(e){

                e.preventDefault();
            
                colorShow();
    
            });

        }

    });

    //color picker popup

    document.querySelectorAll('[data-cp-btn]').forEach(cpButtonAdd => {

        cpButtonAdd.addEventListener("click", function(e){

            e.preventDefault();

            let parentBlock = cpButtonAdd.parentElement.closest('.wq-block__wrap');            

            if(parentBlock){

                parentBlock.querySelector('.wq-block__cp-popup').classList.toggle('_open');

                //Радиокнопки переключение типа цвета

                const radioButtons = parentBlock.querySelectorAll('.wq-block__radio');

                if(radioButtons.length){

                    for(let j = 0; j < radioButtons.length; j++){

                        let radioButton = radioButtons[j];

                        let radioParent = radioButton.parentElement.closest('.wq-block__cp-type');

                        radioParent.classList.remove('_active');

                        if(radioButton.checked){

                            radioParent.classList.add('_active');

                        }

                        radioButton.addEventListener("click", function(){

                            for(let r = 0; r < radioButtons.length; r++){

                                let radioButtonClick = radioButtons[r];

                                radioButtonClick.parentElement.closest('.wq-block__cp-type').classList.remove('_active');

                                radioButtonClick.checked = false

                            }

                            radioButton.checked = true;

                            radioButton.parentElement.closest('.wq-block__cp-type').classList.add('_active');

                        });
                    }
                }

            }
            
        });

    });

    //Кнопка "Сохранить"

    document.querySelectorAll('.wq-button__cp-save').forEach(cpButtonSave => {

        cpButtonSave.addEventListener("click", function(e){

            e.preventDefault();

            let parentBlock = cpButtonSave.parentElement.closest('.wq-block__wrap');

            if(parentBlock){

                parentBlock.querySelector('.wq-block__cp-popup').classList.remove('_open');

                let inputParent = parentBlock.querySelector('.wq-block__cp-type._active');

                let cpColor = inputParent.querySelector('.wq-block__input_type').value;

                let inputColors = parentBlock.querySelector('.wq-block__input');

                if(inputColors.value){

                    inputColors.value = inputColors.value + ';' + cpColor;

                }

                else{

                    inputColors.value = inputColors.value + cpColor;

                }

                colorShow();

            }

        });

    });

    //Кнопка "Отмена"

    document.querySelectorAll('.wq-button__cp-reset').forEach(cpButtonReset => {

        cpButtonReset.addEventListener("click", function(e){

            e.preventDefault();

            let parentBlock = cpButtonReset.parentElement.closest('.wq-block__wrap');

            if(parentBlock){

                parentBlock.querySelector('.wq-block__cp-popup').classList.remove('_open');

            }

        });

    });

    //Удаление цвета

    document.querySelectorAll('.wq-block__cp-wrap').forEach(item => {        

        item.addEventListener('click', (e) => {

            let target = e.target.matches('.wq-block__cp-color') ? e.target : e.target.closest('.wq-block__cp-color');            

            if(target){

                let index = [...target.parentElement.children].indexOf(target);

                if(index !== -1){

                    let cpColorParent = target.parentElement.closest('.wq-block__wrap');

                    if(cpColorParent){

                        let cpColorInputStr = cpColorParent.querySelector('.wq-block__input');

                        if(cpColorInputStr){

                            let cpColorInputArr = cpColorInputStr.value.split(';');

                            if(typeof cpColorInputArr[index] !== 'undefined'){

                                cpColorInputArr.splice(index, 1);

                                cpColorInputStr.value = cpColorInputArr.join(';');

                                colorShow();

                            }

                        }

                    }

                }

            }

        });

    });
}
//================================================================
//Блок "Вывод категорий"
//================================================================
document.querySelectorAll('.wq-goods__items').forEach(goodsBlock => {

    goodsBlock.addEventListener("click", function(e){          

        const wqPage = document.querySelector('.wq-page');

        let target = e.target.matches('.wq-goods__item') ? e.target : e.target.closest('.wq-goods__item'); 
        
        const moveTargetChild = function(){            

            let coordsParent = target.parentElement.getBoundingClientRect();               
        
            let paddingParent = parseInt(window.getComputedStyle(target.parentElement, null).getPropertyValue('padding-left'));

            let widthChild = coordsParent.width - paddingParent * 2;

            let targetChild = [...target.children][1]; //не забыть            

            let targetChildCoords = targetChild.getBoundingClientRect();               

            let moveLeft = 0;  
            
            moveLeft = targetChildCoords.left - coordsParent.left - paddingParent;
            
            if(moveLeft != 0){

                targetChild.setAttribute("style", "width:" + widthChild + "px; left:-" + moveLeft + "px;");

            }

            else{

                targetChild.setAttribute("style", "width:" + widthChild + "px;");

            }
            
        }

        const stileAttr = function(){

            if([...target.children][1].getAttribute("style")){

                [...target.children][1].removeAttribute("style");

            }

        }

        if(target){
            
            if(target.closest('.wq-goods__item._active')){

                stileAttr();

                target.classList.remove('_active');   
                
                wqPage.classList.remove('_open');

            }

            else{
                
                document.querySelectorAll('.wq-goods__item').forEach(goodsClick => {

                    if(goodsClick.closest('._active')){

                        stileAttr();
    
                        goodsClick.classList.remove('_active');

                        wqPage.classList.remove('_open');
    
                    }
    
                });

                target.classList.add('_active');                  
                                
                if([...target.children].length > 1){

                    wqPage.classList.add('_open');

                    moveTargetChild();                     
                    
                }

            }

        }
    });

    window.onresize = function() {

        let openBlock = document.querySelector('.wq-goods__item._active');

        if(openBlock){

            let sizeBlock = goodsBlock.getBoundingClientRect();

            let padding = parseInt(window.getComputedStyle(goodsBlock, null).getPropertyValue('padding-left'));

            let widthOpenBlock = sizeBlock.width - padding * 2;

            let openChild = [...openBlock.children][1]; //не забыть

            let openBlockCoords = openBlock.getBoundingClientRect();

            let moveLeft = 0;

            moveLeft = openBlockCoords.left - sizeBlock.left - padding;

            openChild.style.width = widthOpenBlock + "px";

            openChild.style.left = "-" + moveLeft + "px";

        }

    };

});

document.addEventListener('DOMContentLoaded', () => {

    /*Фильтры в шаблоне show*/

    document.querySelectorAll('[data-filters] select').forEach(item => {

        item.addEventListener('change', () => {

            let name = item.getAttribute('name')

            let value = item.value

            let str = location.search.replaceGetParameters(`filter[${name}]`, value)

            str = str.replace(/(\?|&)\s*$/g, '')

            if(!str){

                let href = location.href.split(/\?/);

                location.href = href ? href[0] : location.href;

            }else{

                location.search = str

            }

        })

    })

    /*Фильтры в шаблоне show*/


    /*Валидатор ввода типов данных*/

    document.querySelectorAll('[data-type]').forEach(item => {

        item.addEventListener('input', () => {

            let type = item.getAttribute('data-type')

            if(type){

                if(/int/.test(type)){

                    item.value = item.value.replace(/[^\d]/g, '');

                }else if(/float/.test(type) || /double/.test(type)){

                    item.value = item.value.replace(/[^\d.,]/g, '');

                    item.value = item.value.replace(/,/g, '.');

                }

                let res = type.match(/\((\d+)\)/)

                if(res && typeof res[1] !== 'undefined'){

                    let counterSpan = item.closest('.wq-block__wrap').querySelector('.wq-block__caption span')

                    let maxLength = +res[1]

                    if (item.value.length > maxLength){

                        item.value = item.value.substr(0, maxLength)

                    }

                    if(counterSpan){

                        counterSpan.innerHTML = item.value.length

                    }

                }

            }

        })


    })

    /*Валидатор ввода типов данных*/

    /*ajax поиск*/

    let searchResultHover = (() => {

        let searchRes = document.querySelector('.search_res')

        let searchInput = document.querySelector('input[name=search]')

        let defaultInputValue = null

        function searchKeydown(e){

            if(!searchInput.classList.contains('active-search-input') ||
                (e.key !== 'ArrowUp' && e.key !== 'ArrowDown')) return;

            let children = [...searchRes.children]

            if(children.length){

                e.preventDefault()

                let activeItem = searchRes.querySelector('.search_act')

                let activeIndex = activeItem ? children.indexOf(activeItem) : -1

                if(e.key === 'ArrowUp')
                    activeIndex = activeIndex <= 0 ? children.length - 1 : --activeIndex
                else
                    activeIndex = activeIndex === children.length - 1 ? 0 : ++activeIndex

                children.forEach(item => item.classList.remove('search_act'))

                children[activeIndex].classList.add('search_act')

                searchInput.value = children[activeIndex].innerText.replace(/\s*\(.+?\)\s*$/, '')

            }

        }

        function setDefaultValue(){

            searchInput.value = defaultInputValue

        }

        if(searchRes){

            searchRes.addEventListener('mouseleave', setDefaultValue)

            window.addEventListener('keydown', searchKeydown)

        }

        return () => {

            defaultInputValue = searchInput.value

            if(searchRes.children.length){

                let children = [...searchRes.children]

                children.forEach(item => {

                    item.addEventListener('mouseover', () => {

                        children.forEach(el => {
                            el.classList.remove('search_act')
                        })

                        item.classList.add('search_act')

                        searchInput.value = item.innerText

                    })

                })

            }

        }

    })()

    search()

    function search(){

        let searchInput = document.querySelector('input[name=search]')

        if(searchInput){

            searchInput.addEventListener('focus', () => {
                searchInput.classList.add('active-search-input')
            })

            searchInput.addEventListener('blur', (e) => {

                if(!e.relatedTarget || e.relatedTarget.tagName !== 'A'){

                    searchInput.classList.remove('active-search-input')

                    searchInput.closest('.wq-header__search').querySelector('.search_res').innerHTML = ''

                }


            })

            searchInput.oninput = () => {

                if(searchInput.value.length > 1){

                    Ajax(
                        {
                            data:{
                                data:searchInput.value,
                                table: document.querySelector('input[name="search_table"]').value,
                                ajax: 'search'
                            }
                        }
                    ).then(res => {

                        console.log(res)

                        try{

                            res = JSON.parse(res)

                            let resBlok = document.querySelector('.search_res')

                            let counter = res.length > 20 ? 20 : res.length

                            if(resBlok){

                                resBlok.innerHTML = '';

                                for(let i = 0; i < counter; i++){

                                    resBlok.insertAdjacentHTML('beforeend', `<a href="${res[i]['alias']}">${res[i]['name']}</a>`)

                                }

                                searchResultHover();

                            }

                        }catch (e){

                            console.log(e)
                            alert('Ошибка в системе поиска по административной панели')

                        }

                    })

                }

            }

        }

    }

    /*ajax поиск*/

    /*Показ загружаемых изображений*/

    let createFile = (function(){

        let fileStore = [];

        let form = document.querySelector('#main-form')

        if(form){

            form.onsubmit = function(e){

                createJsSortable(form)

                if(!isEmpty(fileStore)){

                    e.preventDefault()

                    if(typeof tinymce !== 'undefined'){

                        document.querySelectorAll('.tinyMceInit:checked').forEach(item => {

                            let textArea = item.closest('[data-tiny-wrapper]').querySelector('textarea')

                            if(textArea && textArea.name){

                                tinymce.remove(`[name="${textArea.name}"]`)

                            }

                        })

                    }

                    let forData = new FormData(this)

                    for(let i in fileStore){

                        if(fileStore.hasOwnProperty(i)){

                            forData.delete(i)

                            let rowName = i.replace(/[\[\]]/g, '')

                            fileStore[i].forEach((item, index) => {

                                forData.append(`${rowName}[${index}]`, item)

                            })

                        }

                    }

                    forData.append('ajax', 'editData')

                    if(typeof e.submitter.name !== 'undefined' && e.submitter.name)
                        forData.append(e.submitter.name, 'save')

                    let loader = document.querySelector('[data-loader]')

                    if(!loader){

                        loader = document.createElement('div')

                        loader.setAttribute('data-loader', true)

                        document.body.append(loader)

                        loader.insertAdjacentHTML('beforeend', '<div class="wq-loader"></div>')

                    }

                    loader.classList.add('_active-loader')

                    Ajax({
                        url: this.getAttribute('action'),
                        type: 'post',
                        data:forData,
                        processData: false,
                        contentType: false
                    }).then(res => {

                        if(!res){

                            location.reload()

                            return false;

                        }

                        try{

                            res = JSON.parse(res)

                            if(!res.success) throw new Error()

                            location.href = res.success

                        }catch (e){

                            console.error(e)

                            alert('Произошла внутренняя ошибка')

                        }

                    }).catch(res => {

                        console.error(res)

                    })

                }

            }

        }

        function deleteNewFiles(elId, fileName, attributeName, container){

            container.addEventListener('click', function(e){

                if(e.target === container){

                    this.remove()

                    delete fileStore[fileName][elId]

                }

            })

        }

        function showImage(item, container, calcback){

            let reader = new FileReader()

            reader.readAsDataURL(item)

            reader.onload = e => {

                console.log(e)

                if(container){

                    container.innerHTML = ''

                    let type = e.target.result.match(/data:([^\/]+)/)

                    type = (type[1] || '').toLowerCase().trim()

                    container.classList.remove('wq-block__img-view')

                    if(type === 'image'){

                        container.innerHTML = `<img class="img_item" src="${e.target.result}">`

                        container.classList.add('wq-block__img-view')

                    }else if(type === 'video'){

                        container.innerHTML = `<video src="${e.target.result}" controls="controls"></video>`

                    }else{

                        container.innerHTML = `<a class="img_item">${item.name}</a>`

                    }

                    container.classList.remove('empty_container')

                }

                calcback && calcback()

            }

        }

        function dragAndDrop(area, input){

            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach((eventName, index) => {

                area.addEventListener(eventName, e => {

                    if(e.dataTransfer.items.length){

                        e.stopPropagation()

                        e.preventDefault()

                        if(index < 2){

                            area.style.background = 'lightblue'

                        }else{

                            area.style.background = '#fff'

                            if(index === 3 && e.dataTransfer.files.length && e.dataTransfer.files[0].type){

                                input.files = e.dataTransfer.files

                                console.log(input.files);

                                input.dispatchEvent(new Event('change'))

                            }

                        }

                    }

                })

            })

        }

        return function (files){

            if(!files)
                files = document.querySelectorAll('input[type=file]')

            if(files.length){

                files.forEach(item => {

                    item.onchange = function() {

                        let multiple = false

                        let parentContainer

                        let container

                        if(item.hasAttribute('multiple')){

                            multiple = true

                            parentContainer = this.closest('.gallery_container')

                            if(!parentContainer) return false;

                            container = parentContainer.querySelectorAll('.empty_container')

                            if(container.length < this.files.length){

                                for(let index = 0; index < this.files.length - container.length; index++){

                                    let el = document.createElement('div')

                                    el.classList.add('wq-block__img-gallery', '_ibg', 'empty_container')

                                    parentContainer.append(el)

                                }

                                container = parentContainer.querySelectorAll('.empty_container')

                            }

                        }

                        let fileName = item.name

                        let attributeName = fileName.replace(/[\[\]]/g, '')

                        for(let i in this.files){

                            if(this.files.hasOwnProperty(i)){

                                if(multiple){

                                    if(typeof fileStore[fileName] === 'undefined') fileStore[fileName] = []

                                    let elId = fileStore[fileName].push(this.files[i]) - 1

                                    container[i].setAttribute(`data-deleteFileId-${attributeName}`, elId)

                                    showImage(this.files[i], container[i], function(){

                                        parentContainer.sortable({excludedElements: '.empty_container, .wq-button__wrapper'})

                                    })

                                    deleteNewFiles(elId, fileName, attributeName, container[i])

                                }else{

                                    container = this.closest('.img_container')

                                    if(container){

                                        container = container.querySelector('.img_show')

                                        showImage(this.files[i], container)

                                    }


                                }

                            }

                        }

                    }

                    let area = item.closest('.img_wrapper')

                    if(area){

                        dragAndDrop(area, item)

                    }

                })

            }

        }

    })()

    createFile()

    /*Показ загружаемых изображений*/


    let galleries = document.querySelectorAll('.gallery_container')

    if(galleries.length){

        galleries.forEach((area, index) => {

            area.sortable({
                excludedElements: '.empty_container, .wq-button__wrapper',
                stop(item){
                    createJsSortable(area.closest('form'))
                }
            })

        });

    }


    /*Сортировка изображений*/
    function createJsSortable(form){

        if(form){

            let sortable = form.querySelectorAll('input[type=file][multiple]')

            if(sortable.length){

                sortable.forEach(item => {

                    let container = item.closest('.gallery_container')

                    let name = item.getAttribute('name')

                    if(name && container){

                        name = name.replace(/\[\]/g, '')

                        let inputSorting = form.querySelector(`input[name="js-sorting[${name}]"]`)

                        if(!inputSorting){

                            inputSorting = document.createElement('input')

                            inputSorting.name = `js-sorting[${name}]`

                            inputSorting.type = 'hidden'

                            form.append(inputSorting)

                        }

                        let res = []

                        for(let i in container.children){

                            if(container.children.hasOwnProperty(i)){

                                if(!container.children[i].matches('.wq-button__wrapper') && !container.children[i].matches('.empty_container')){

                                    if(container.children[i].tagName === 'A'){

                                        res.push(container.children[i].querySelector('img').getAttribute('src').split('?')[0])

                                    }else{

                                        res.push(container.children[i].getAttribute(`data-deletefileid-${name}`))

                                    }

                                }

                            }

                        }

                        inputSorting.value = JSON.stringify(res)

                    }

                })

            }

        }

    }
    /*Сортировка изображений*/

    /*Сортировка блоков админки*/

    let blocksSortingActive = false

    document.querySelectorAll('.wq-block__move, .wq-block__title').forEach(el => {

        el.addEventListener('mousedown', () => {

            if(!blocksSortingActive){

                blocksSortingActive = true

                document.querySelectorAll('.sort_panel').forEach(item => {

                    item.sortable({
                        targetElement: '.wq-block__move, .wq-block__title',
                        stop(element){

                            let data = [];

                            data['current'] = element.querySelector('[name]')

                            data['current'] = data['current'] ? data['current'].getAttribute('name').replace(/\[.*/, '') : null

                            data['previous'] = element.previousElementSibling

                            data['previous'] = data['previous'] ? data['previous'].querySelector('[name]').getAttribute('name').replace(/\[.*/, '') : null

                            data['table'] = item.closest('form').querySelector('input[name="table"]').value

                            data['ajax'] = 'sort_table'

                            Ajax({type: 'post', data:data}).then(res => {
                                if(!res){
                                    console.log('Ошибка сортировки полей')
                                }
                            })

                        }

                    })

                })

            }

        })

    })
    /*Сортировка блоков админки*/

    /*addedlist template*/
    document.querySelectorAll('.addedlist-add').forEach(item => {

        item.addEventListener('click', () => {

            let container = item.closest('.addedlist-container')

            if(container){

                let wrap = container.querySelector('.addedlist-wrap')

                let counter = wrap.children.length

                let name = container.querySelector('[name]').getAttribute('name').replace(/\[.*/, '')

                let template = wrap.children[counter - 1].outerHTML

                template = template.replace((new RegExp(`${name}(\\[[^\\]*]\\])`, 'g')), `${name}[${counter}]`)

                template = template.replace((new RegExp(`${name}-\\d+`, 'g')), `${name}-${counter}`)

                wrap.insertAdjacentHTML('beforeend', template)

                wrap.children[counter].querySelectorAll('.main_img_show img, .wq-delete, .tox-tinymce').forEach(item => {

                    item.remove()

                })

                wrap.children[counter].querySelectorAll('input, textarea').forEach(item => {

                    item.removeAttribute('style')

                    item.value = ''

                })

                let files = wrap.children[counter].querySelectorAll('input[type="file"]')

                if(files.length){
                    createFile(files)
                }

                enableMCE(wrap.children[counter].querySelector('.tinyMceInit'))

            }

        })

    })
    /*addedlist template*/

    /*Поиск по фильтрам*/

    document.querySelectorAll('[data-filter-search]').forEach(input => {

        let optionWrap = input.closest('[data-filter-wrap]')

        if(optionWrap){

            let labels = optionWrap.querySelectorAll('[data-filter-item]')

            input.addEventListener('click', e => {

                if(optionWrap.style.display === 'block')
                    e.stopPropagation()
            })

            input.addEventListener('input', () => {

                if(!input.value.length){

                    labels.forEach(item => item.style.display = 'flex')

                }else{

                    let value = input.value.replace(/([\\\.\+\*\?\[\^\]\$\(\)\{\}\=\!\<\>\|\:])/g, "\\$1")

                    let namesArr = value.split(/\s+/)

                    labels.forEach(item => {

                        let spanName = item.querySelector('[data-filter-name]')

                        if(spanName){

                            let flag = false

                            for(let i of namesArr){

                                let regExp = new RegExp(i, 'i')

                                if(regExp.test(spanName.innerText)){

                                    flag = true

                                    break

                                }

                            }

                            if(!flag)
                                item.style.display = 'none'
                            else
                                item.style.display = 'flex'

                        }

                    })

                }

            })

        }

    })

    /*Поиск по фильтрам*/

    blockParameters()

    function blockParameters(){

        document.querySelectorAll('[data-filter-select-all]').forEach(item => {

            item.addEventListener('click', () => {

                let wrap = item.closest('[data-filter-wrap]')

                if(wrap){

                    let checked = !wrap.querySelector('input[type=checkbox]:checked');

                    wrap.querySelectorAll('input[type=checkbox]').forEach(el => el.checked = checked)

                }

            })

        })

        document.querySelectorAll('[data-filter-block]').forEach(block => {

            let btns = block.querySelectorAll('[data-show]')

            btns.forEach(btn => {

                btn.addEventListener('click', e => {

                    e.preventDefault();

                    block.querySelectorAll('[data-filter-wrap]').forEach(wrap => {
                        if(!wrap.querySelector('input:checked')){

                            if(btn.getAttribute('data-show') === 'none')
                                wrap.removeAttribute('style')

                            wrap.style.display = btn.getAttribute('data-show')
                        }
                    })

                    btns.forEach(el => {
                        el.classList.remove('_active')
                    })

                    btn.classList.add('_active')

                })

            })

        })

    }

    /*Смена позиций в меню при наличии родительской категории*/

    let changeMenuPosition = (function (){

        let form = document.querySelector('#main-form')

        if(form){

            let selectParent = form.querySelector('select[name=parent_id]')

            let selectPosition = form.querySelector('select[name=menu_position]')

            let defaultParent, defaultPosition

            if(selectPosition && selectParent){

                defaultParent = selectParent.value

                defaultPosition = +selectPosition.value

                selectParent.addEventListener('change', function(){
                    changeMenuPosition()
                })

            }

            return function (){

                if(selectPosition && selectParent){

                    let defaultChoose = false

                    if(selectParent.value === defaultParent) defaultChoose = true

                    Ajax({
                        data:{
                            table: form.querySelector('input[name=table]').value,
                            'parent_id': selectParent.value,
                            ajax: 'change_parent',
                            iteration: !form.querySelector('#tableId') ? 1 : +!defaultChoose
                        }
                    }).then(res => {

                        res = +res;

                        if(!res) return errorAlert();

                        let newSelect = document.createElement('select')

                        newSelect.setAttribute('name', 'menu_position')

                        newSelect.classList.add('wq-select')

                        if(res) {

                            for(var i = 1; i <= res; i++) {

                                let option = document.createElement('option')

                                option.value = option.innerText = i

                                if(defaultChoose && i == defaultPosition) option.selected = true

                                newSelect.append(option)

                            }

                        } else {

                            let option = document.createElement('option')

                            option.value = option.innerText = 1

                        }

                        let selectWrap = selectPosition.closest('[data-select-wrap]')

                        if(selectWrap){

                            selectPosition = newSelect

                            selectWrap.innerHTML = ''

                            selectWrap.append(selectPosition)

                            if(typeof Choices !== 'undefined'){

                                new Choices(newSelect, {

                                    noResultsText: 'Ничего не найдено',
                                    shouldSort: false,

                                });

                            }

                        }

                    })

                }

            }

        }

    })()

    if(typeof changeMenuPosition === 'function'){

        //changeMenuPosition()

    }
    /*Смена позиций в меню при наличии родительской категории*/

    /*Сохранение родителя в сессии*/

    let parentIdSelect = document.querySelector('[name="parent_id"]')

    if(parentIdSelect){

        let table = document.querySelector('input[name="table"]')

        if(table){

            table = table.value.trim()

            if(table){

                let loadFlag = false

                parentIdSelect.addEventListener('change', e => {

                    if(!loadFlag){

                        Ajax({
                            data:{
                                ajax: 'set_parent_id',
                                table: table,
                                id: parentIdSelect.value
                            }
                        })

                    }

                })

                let checkedParents = document.querySelector('#data-checked-parents')

                if(checkedParents && !document.querySelector('#tableId')){

                    parentIdSelect.value = checkedParents.value

                    loadFlag = true

                    parentIdSelect.dispatchEvent(new Event('change'))

                    loadFlag = false

                }

            }

        }

        new Choices(parentIdSelect, {

            noResultsText: 'Ничего не найдено',
            shouldSort: false,

        });

    }

    /*Сохранение родителя в сессии*/

    /*uploadFiles*/

    let importForm = document.querySelector('form.file-import')

    if(importForm){

        importForm.addEventListener('submit', function(e){

            e.preventDefault();

            let formData = new FormData(this)

            formData.append('ajax', '1c_import')

            let submitBtn = importForm.querySelector('[type="submit"]')

            if(!submitBtn.nextElementSibling){

                submitBtn.insertAdjacentHTML('afterend', '<span style="margin-left: 20px"></span>')

            }

            Ajax({
                type: 'POST',
                processData: false,
                contentType: false,
                data: formData,
                onprogress: function (event){
                    submitBtn.nextElementSibling.innerHTML = (100 / (event.total / event.loaded)).toFixed(2) + '%'
                },
                onload: function (event){
                    submitBtn.nextElementSibling.innerHTML = 'Данные загружены, начат процесс импорта'
                }
            }).then(res => {

                console.log('then')

                Ajax({
                    data: {ajax: 'after_1c_import'}}
                ).then(res => {
                    location.reload();
                }).catch(res => {
                    console.log(res)
                })

            }).catch(res => {
                console.log(res)
            })

        })

    }

    /*uploadFiles*/

    /*Удаление элементов*/

    document.querySelectorAll('.wq-delete').forEach(item => {

        item.addEventListener('click', e => {

            if(e.target.tagName === "IMG" || !confirm('Подтвердить удаление')){

                e.preventDefault()

                return false;

            }

        })

    })

    /*Удаление элементов*/

    /*Ревизия позиций вывода*/

    document.querySelectorAll('.revision-menu_position').forEach(item => {

        item.addEventListener('click', e => {

            if(!confirm('Подтвердить ревизию позиций сортировки')){

                e.preventDefault()

                return false;

            }
        })

    })

    /*Ревизия позиций вывода*/

})

/*COOKIE*/

function getCookie(name) {
    let matches = document.cookie.match(new RegExp(
        "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
    ));
    return matches ? decodeURIComponent(matches[1]) : null;
}

function setCookie(name, value, options = {}) {

    options = {
        path: '/',
        // при необходимости добавьте другие значения по умолчанию
        ...options
    };

    if (options.expires instanceof Date) {
        options.expires = options.expires.toUTCString();
    }

    let updatedCookie = encodeURIComponent(name) + "=" + encodeURIComponent(value);

    for (let optionKey in options) {
        updatedCookie += "; " + optionKey;
        let optionValue = options[optionKey];
        if (optionValue !== true) {
            updatedCookie += "=" + optionValue;
        }
    }

    document.cookie = updatedCookie;
}

function deleteCookie(name) {
    setCookie(name, "", {
        'max-age': -1
    })
}

/*COOKIE*/

