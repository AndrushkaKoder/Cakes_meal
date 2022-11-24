window.addEventListener("DOMContentLoaded", ()=>{

    const cross = document.querySelector('[data-cross]'); // крестик закрытия
    const burger = document.querySelector('[data-burger_button]');// бургер кнопка
    const burgerMenu = document.querySelector('[data-burger_menu]'); // бургер меню
    const nawWrapper = document.querySelector('[data-nav_wrapper]'); //контейнер навигации


    //---------- появление кнопки бургер при разрешении меньше 767рх -------------
    function showOrHideBurgerButton(){

        window.innerWidth <= 767 ? burger.classList.add("showBurger") : burger.classList.remove("showBurger");

        window.addEventListener('resize', ()=>{
            window.innerWidth >= 767 ?  burger.classList.remove("showBurger") : burger.classList.add("showBurger")

        })
    }


    //----------------- показ-скрытие бургер-меню ------------------

    function hideOrShowMobileMenu(){
        nawWrapper.addEventListener("click", (event)=>{

            if(event.target.closest('.burger_nav_button')){
                event.preventDefault()
                event.stopPropagation()
                burgerMenu.classList.add("showBurgerMenu");
                window.scrollBy(0,-100);
                document.body.style.overflow = 'hidden'

                document.addEventListener('click', e => {

                    if(e.target.closest('.cross_button') || !(e.target.closest('.showBurgerMenu'))){

                        burgerMenu.classList.remove("showBurgerMenu");

                        document.body.style.overflow = 'visible'

                        document.removeEventListener('click', arguments.callee)

                    }

                })

            }
        })

    }


    showOrHideBurgerButton()
    hideOrShowMobileMenu()

})
