

function imgPopUp(element, callback){
    const containerStyles = {
        width: '100vw',
        height: '100vh',
        background: 'rgba(0,0,0,0.8)',
        position: 'fixed',
        top: 0,
        left: 0,
        zIndex: 9999,
        display: 'flex',
        justifyContent: 'center',
        alignItems: 'center'
    }
    const wrapperStyles = {
        display: 'flex',
        overflow: 'hidden',
        borderRadius: '10px'
    }
    const imgStyles = {
        maxWidth: '80vw',
        maxHeight: '80vh',
        objectFit: 'contain',

    }

    element.addEventListener('click', ()=>{
        // console.log(document.documentElement.clientWidth, window.innerWidth)
        let bodyPadding = window.innerWidth - document.documentElement.clientWidth + 'px'
        document.body.style.overflow = 'hidden'
        document.body.style.paddingRight = bodyPadding
        if(typeof (callback === 'function')){
            callback(true)
        }

        document.body.insertAdjacentHTML('beforeend', `<div data-imgPopUpContainer> <div data-imgPopUpWrapper> <img src="${element.src}"> </div> </div>`)

        let container = document.querySelector('[data-imgPopUpContainer]')

        container.children[0].insertAdjacentHTML('beforeend', ``)

        for(let i in containerStyles){
            if(containerStyles.hasOwnProperty(i)){
                container.style[i] = containerStyles[i]
            }
        }

        for(let i in wrapperStyles){
            if(wrapperStyles.hasOwnProperty(i)){
                container.children[0].style[i] = wrapperStyles[i]
            }
        }

        for (let i in imgStyles){
            if(imgStyles.hasOwnProperty(i)){
                container.children[0].children[0].style[i] = imgStyles[i]
            }
        }

        container.addEventListener('click', (e)=>{
            if(e.target === container){
                container.remove()
                document.body.style.overflow = 'visible'
                document.body.style.paddingRight = 0;
                if(typeof (callback === 'function')){
                    callback(false)
                }
            }
        })
    })
}

document.addEventListener('DOMContentLoaded', ()=>{

    document.querySelectorAll('.basket_item_img').forEach(item=>{
        imgPopUp(item, function (show){
            let header = document.querySelector('header')
            if(header){
                header.style.position = show ? 'static' : 'absolute'
            }
        })
    })

})

