// скролл хедера
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

    })




















