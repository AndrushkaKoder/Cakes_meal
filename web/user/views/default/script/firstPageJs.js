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
        } 

    })
    burgerMenu.addEventListener("click", (event)=>{
        // event.preventDefault()
        if(event.target.closest('.cross_button')){
            hideBurgerMenu()
        }
    })
    window.addEventListener("resize", ()=>{
        if(window.innerWidth >= 767){
            hideBurgerMenu()
        }
    })



})

const slideContent = document.querySelectorAll(".slide_content");
const slides = document.querySelectorAll('.swiper-slide');
document.addEventListener("scroll", ()=>{
    for(el of slides){
        if(el.classList.add('swiper-slide-active')){
           for(item of slideContent){
            item.classList.add('bounceInRight')
           }
        }
    }
})