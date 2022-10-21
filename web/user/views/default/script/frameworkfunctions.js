const Ajax = (set) => {

    if(typeof set === 'undefined') set = {};

    if(typeof set.url === 'undefined' || !set.url){
        set.url = typeof PATH !== 'undefined' ? PATH : '/';
    }

    if(typeof set.ajax === 'undefined') set.ajax = true

    if(typeof set.type === 'undefined' || !set.type) set.type = 'GET';

    set.type = set.type.toUpperCase();

    let body = '';

    if(typeof set.data !== 'undefined' && set.data){

        if(typeof set.processData !== 'undefined' && !set.processData){

            body = set.data

        }else{

            for(let i in set.data){

                if(set.data.hasOwnProperty(i))
                    body += '&' + i + '=' + set.data[i];

            }

            body = body.substr(1);

            if(typeof ADMIN_MODE !== 'undefined'){

                body += body ? '&' : '';
                body += 'ADMIN_MODE=' + ADMIN_MODE;

            }

        }

    }

    if(set.type === 'GET' && body){

        set.url += '?' + body;
        body = null;

    }

    return new Promise((resolve, reject) => {

        let xhr = new XMLHttpRequest();

        xhr.open(set.type, set.url, true);

        let contentType = false;

        if(typeof set.headers !== 'undefined' && set.headers){

            for (let i in set.headers){

                if(set.headers.hasOwnProperty(i)){

                    xhr.setRequestHeader(i, set.headers[i]);

                    if(i.toLowerCase() === 'content-type') contentType = true;

                }

            }

        }

        if(!contentType && (typeof set.contentType === 'undefined' || set.contentType))
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');

        if(set.ajax)
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');


        if(typeof set.onprogress === 'function'){

            xhr.upload.onprogress = set.onprogress

        }

        if(typeof set.onload === 'function'){

            xhr.upload.onload = set.onload

        }

        xhr.onload = function(){

            if(this.status >= 200 && this.status < 300){

                if(/fatal\s+?error/ui.test(this.response)){

                    reject(this.response);

                }

                resolve(this.response);

            }

            reject(this.response);

        }

        xhr.onerror = function () {
            reject(this.response)
        }

        xhr.send(body);

    });

}

function isEmpty(arr){

    for(let i in arr){

        return false;

    }

    return true;

}

function errorAlert(){

    alert('Произошла внутренняя ошибка')

    return false

}

Element.prototype.slideToggle = function(time, callback){

    let _time = typeof time === 'number' ? time : 400

    callback = typeof time === 'function' ? time : callback

    if(getComputedStyle(this)['display'] === 'none'){

        this.existsProperties = {
            transition: null,
            overflow: null,
            maxHeight: null,
            minHeight: null,
        }

        for(let i in this.existsProperties){

            this.existsProperties[i] = this.style[i]

        }

        this.style.overflow = 'hidden';

        this.style.display = 'block'

        this.style.minHeight = 0

        this.style.maxHeight = 0;

        this.style.transition = _time + 'ms'

        this.style.maxHeight = this.scrollHeight + 'px'

        setTimeout(() => {

            _setDefaultStyles.call(this)

            callback && callback.call(this)

        }, _time)

    }else{

        this.style.transition = _time + 'ms'

        this.style.maxHeight = 0;

        setTimeout(() => {

            this.style.display = 'none'

            _setDefaultStyles.call(this)

            callback && callback.call(this)

        }, _time)

    }

    function _setDefaultStyles(){


        if(typeof this.existsProperties !== 'undefined'){

            for(let i in this.existsProperties){

                this.style[i] = this.existsProperties[i]

            }

            delete this.existsProperties

        }


    }

}

Element.prototype.moving = function(target, width, options = {}){

    let callback = null

    if(typeof options === 'function'){

        callback = options

    }

    if(typeof options !== 'object'){

        options = {}

    }

    if(typeof options.target === 'undefined' || !options.target){

        options.target = target

    }

    _setTargets()

    options.targetPosition = options.targetPosition || 'beforeend'

    options.returnPosition = options.returnPosition || 'beforeend'

    let moved = options.moved = false

    if(options.target){

        if(typeof this.defaultParent === 'undefined' && (options.returnTarget === 'undefined' || !options.returnTarget)){

            this.defaultParent = true

            options.returnTarget = this.parentElement

        }

        _moved.call(this)

        window.addEventListener('resize', _moved.bind(this))

    }

    function _moved(e){

        let target = null

        let targetPosition = null

        if(!moved && window.innerWidth < width){

            moved = options.moved = true

            if(!options.returnTarget){

                options.returnTarget = this.parentElement

            }

            target = options.target

            targetPosition = options.targetPosition


        }else if(moved && window.innerWidth >= width){

            moved = options.moved = false

            target = options.returnTarget

            targetPosition = options.returnPosition

        }

        if(target){

            if(typeof options.onBeforeMoving === 'function'){

                options.onBeforeMoving.call(this, options, e || null)

                _setTargets()

            }

            target.insertAdjacentElement(targetPosition, this)

            if(typeof options.onAfterMoving === 'function'){

                options.onAfterMoving.call(this, options, e || null)

                _setTargets()

            }

            if(typeof callback === 'function'){

                callback.call(this, options)

                _setTargets()

            }

        }

    }

    function _setTargets(){

        if(typeof options.target === 'string'){

            options.target = document.querySelector(options.target)

        }

        if(typeof options.returnTarget === 'string'){

            options.returnTarget = document.querySelector(options.returnTarget)

        }

    }

}

Element.prototype.sortable = (function(){

    let dragEl, nextEl;

    function _unDraggable(elements){

        if(elements && elements.length){

            for(let i = 0; i < elements.length; i++){

                if(!elements[i].hasAttribute('draggable')){

                    elements[i].draggable = false

                    _unDraggable(elements[i].children)

                }

            }

        }

    }

    function _onDragStart(e){

        this._dragging = null

        e.stopPropagation()

        this.tempTarget = null

        dragEl = e.target

        nextEl = dragEl.nextSibling

        e.dataTransfer.dropEffect = 'move'

        this.addEventListener('dragover', _onDragOver, false)

        this.addEventListener('dragend', _onDragEnd, false)

    }

    function _onDragOver(e){

        if(this._dragging === false) return false

        if(this._dragging === null && this.targetElementSelector){

            let targetElements = this.targetElementSelector.split(/,*\s+/)

            let exists = false

            for(let i in targetElements){

                if(e.target.matches(targetElements[i]) || e.target.closest(targetElements[i])){

                    exists = true

                    break

                }

            }

            if(!exists){

                return this._dragging = false

            }

        }

        this._dragging = true

        e.preventDefault()

        e.stopPropagation()

        e.dataTransfer.dropEffect = 'move'

        let target

        if(e.target !== this.tempTarget){

            this.tempTarget = e.target

            target = e.target.closest('[draggable=true]')

        }

        if(target && target !== dragEl && target.parentElement === this){

            let rect = target.getBoundingClientRect()

            let next = (e.clientY - rect.top)/(rect.bottom - rect.top) > .5;

            this.insertBefore(dragEl, next && target.nextSibling || target)

        }

    }

    function _onDragEnd(e){

        if(!this._dragging){

            return false;

        }

        e.preventDefault()

        this.removeEventListener('dragover', _onDragOver, false)

        this.removeEventListener('dragend', _onDragEnd, false)

        if(nextEl !== dragEl.nextSibling){

            this.onUpdate && this.onUpdate(dragEl)

        }

    }

    return function (options){

        options = options || {}

        this.onUpdate = options.stop || null

        this.targetElementSelector = options.targetElement || null

        let excludedElements = options.excludedElements && options.excludedElements.split(/,*\s+/) || null;

        [...this.children].forEach(item => {

            let draggable = true

            if(excludedElements){

                for(let i in excludedElements){

                    if(excludedElements.hasOwnProperty(i) && item.matches(excludedElements[i])){

                        draggable = false

                        break

                    }

                }

            }

            item.draggable = draggable

            _unDraggable(item.children)

        })

        this.removeEventListener('dragstart', _onDragStart, false)

        this.addEventListener('dragstart', _onDragStart, false)

    }

})()

String.prototype.replaceGetParameters = function(key, value, char){

    key += ''

    value += ''

    let str = decodeURIComponent(this.trim().replace(/^\?/, ''))

    if(/^\s*null\s*$/i.test(value)){

        value = ''

    }

    let regExpKey = key.replace(/[-[\]{}()*+?.,\\^$|#\s]/g, "\\$&")

    let regExpStr = (regExpKey + '=' + (/\[\]/.test(key) ? value.replace(/[-[\]{}()*+?.,\\^$|#\s]/g, "\\$&") : '[^&]*'))

    let regExp = new RegExp(`&?${regExpStr}`, 'i')

    if(regExp.test(str)){

        str = /\[\]/.test(key) || !value ? str.replace(regExp, '') : str.replace(regExp, `&${key}=${value}`)

    }else {

        str += '&' + `${key}=${value}`

    }

    return (char || '') + str.replace(/^\s*&/, '')

}