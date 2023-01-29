document.addEventListener('DOMContentLoaded', () => {

    /*Показ сообщений*/

    let messageWrap = document.querySelector('.wq-message__wrap')

    if(messageWrap){

        let styles = {
            position: 'fixed',
            width: '50%',
            textAlign: 'center',
            top: '30%',
            left: '50%',
            transform: 'translateX(-50%)',
            display: 'block',
            zIndex: 999
        }


        let successStyles = {
            background: 'radial-gradient(circle, rgba(126,241,85,1) 27%, rgba(21,254,188,1) 100%)',
            color: 'white',
            marginBottom: '10px',
            padding: '25px 30px',
            borderRadius: '20px'
        }

        let errorStyles = {
            background: 'linear-gradient(0deg, rgba(236,0,148,1) 27%, rgba(253,45,45,1) 100%)',
            color: 'white',
            marginBottom: '10px',
            padding: '25px 30px',
            borderRadius: '20px'
        }

        if(messageWrap.innerHTML.trim()){

            for(let i in styles){

                messageWrap.style[i] = styles[i]

            }

            if(messageWrap.children.length){

                for (let i in messageWrap.children){

                    if(messageWrap.children.hasOwnProperty(i)){

                        let typeStyles = /success/.test(messageWrap.children[i].classList.value) ? successStyles : errorStyles

                        for(let j in typeStyles){

                            messageWrap.children[i].style[j] = typeStyles[j]

                        }

                    }


                }

            }

            document.addEventListener('click', hideMessages)

            window.addEventListener('scroll', hideMessages)

        }else{

            messageWrap.remove()

        }

    }

    function hideMessages(){

        let messageWrap = document.querySelector('.wq-message__wrap')

        if(messageWrap){

            messageWrap.remove()

        }

        document.removeEventListener('click', hideMessages)

        window.removeEventListener('scroll', hideMessages)

    }


    /*Показ сообщений*/

})