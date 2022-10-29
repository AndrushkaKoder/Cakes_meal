//должен быть подключен font awesome


function createUpArrow(){
    const arrowStyle = {
        width: '40px',
        height: '40px',
        background: 'black',
        color: 'white',
        // display: 'flex',
        justifyContent: 'center',
        alignItems: 'center',
        borderRadius: '100%',
        position: 'fixed',
        top: '50%',
        right: '5px',
        zIndex: 9999,
        cursor: 'pointer',
        display: 'none'
    };


    let arrowUp = document.createElement('div')
    arrowUp.classList.add('arrowUp')
    arrowUp.innerHTML = '&#8593;'
    document.body.append(arrowUp)

    for (let i in arrowStyle) {
        arrowUp.style[i] = arrowStyle[i]
    }

    arrowUp.addEventListener('click', ()=>{
        window.scrollTo(0,0)

    })

   window.addEventListener('scroll', ()=>{
       if(window.innerWidth < 991 && window.scrollY > 1000){
           arrowUp.style.display = 'flex'
       } else{
           arrowUp.style.display = 'none'
       }
   })

}

createUpArrow()

