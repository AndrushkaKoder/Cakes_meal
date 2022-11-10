    // nnerWidth - ширина окна
window.addEventListener("DOMContentLoaded", ()=>{
    const cross = document.querySelector('[data-cross]'); // крестик закрытия
    const burger = document.querySelector('[data-burger_button]');// бургер кнопка
    const burgerMenu = document.querySelector('[data-burger_menu]'); // бургер меню
    const navList = document.querySelectorAll(".nav_item");
    const nawWrapper = document.querySelector('[data-nav_wrapper]');



    //---------- появление кнопки бургер при разрешении меньше 767рх -------------

    function showBurger(){
        burger.classList.remove("hideBurger")
        burger.classList.add("showBurger")
    }
    function hideBurger(){
        burger.classList.remove("showBurger")
        burger.classList.add("hideBurger")
    }

    if(window.innerWidth >= 767){
        hideBurger()
    } else{
    showBurger()
    }
    window.addEventListener('resize', ()=>{
        if(window.innerWidth >= 767){
            burger.classList.remove("showBurger")
            burger.classList.add("hideBurger")
        } else{
            burger.classList.remove("hideBurger")
            burger.classList.add("showBurger")
        }
    })


    //----------------- показ-скрытие бургер-меню ------------------

    function showBurgerMenu(){
        burgerMenu.classList.add("showBurgerMenu");
        burgerMenu.classList.remove("hideBurgerMenu");
    }
    function hideBurgerMenu(){
        burgerMenu.classList.remove("showBurgerMenu");
        burgerMenu.classList.add("hideBurgerMenu");
    }

    nawWrapper.addEventListener("click", (event)=>{

        if(event.target.closest('.burger_nav_button')){
            event.preventDefault()
            showBurgerMenu();
            document.body.style.overflow = 'hidden'
        } 

    })
    burgerMenu.addEventListener("click", (event)=>{

        if(event.target.closest('.cross_button')){

            setTimeout(hideBurgerMenu, 200)
            document.body.style.overflow = 'visible'

        }
    })
    window.addEventListener("resize", ()=>{
        if(window.innerWidth >= 767){
            hideBurgerMenu()
        }
    })



})
