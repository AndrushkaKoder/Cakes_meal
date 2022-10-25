//показать хедер при скролле
window.addEventListener("DOMContentLoaded", ()=>{

    const header = document.querySelector('[data-header]')

    window.addEventListener("scroll", ()=>{
        if(window.scrollY > 500){
            header.classList.add("header_fixed")
        } else {
            header.classList.remove("header_fixed")
        }

        if(window.innerWidth < 991){
            header.classList.remove("header_fixed")
        }
    })
//показать хедер при скролле

//показ поисковика
    const searchBtn = document.querySelector('.nav_item > .search_button');
    const searchWrapper = document.querySelector('.search_wrapper');


    function hideSearch(e){
        if(!e.target.closest('.search_area')){
            searchWrapper.classList.remove('showSearch');
            searchWrapper.classList.add('hideSearch');
            document.removeEventListener('click', hideSearch)
        }
    }

    searchBtn.addEventListener('click', (e)=>{
        e.preventDefault();
        e.stopPropagation()
        if(searchWrapper.classList.contains('hideSearch')){
            searchWrapper.classList.remove('hideSearch');
            searchWrapper.classList.add('showSearch');
            document.addEventListener('click', hideSearch)
        }

    })



    //показ меню в хедере
    const dropdown = document.querySelector('.dropdownWrapper');

    if(dropdown){
        ['mouseenter', 'mouseleave'].forEach( event => {
            dropdown.addEventListener(event, ()=>{
                let action = event === 'mouseenter' ? 'add' : 'remove';
                dropdown.children[1].classList[action]('show');
                //Если в action лежит add, dropdorwn.children[i].classlist.add('show')
                //иначе dropdorwn.children[i].classlist.remove('show')
            })


        })


    }

    const slidesContentText = document.querySelectorAll('.slide_content');

    function showSlideTextAnimate(){
        slidesContentText.forEach((slide, i)=>{
            slide.classList.add('bounceInUp')
            setTimeout(()=>{
                slide.classList.remove('bounceInUp')
            },1000)


        })
    }

    showSlideTextAnimate()





})




















