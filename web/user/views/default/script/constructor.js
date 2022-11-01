window.addEventListener("DOMContentLoaded", ()=>{

    const constructor = document.querySelector('[data-constructor]'),
    type = document.querySelector("[ data-type_cake]"),
    
    osnovaBisquit = document.querySelector('[data-osnova_bisquit]'),
    osnovaMuss = document.querySelector('[data-osnova_muss]'),
    cremeBisquit = document.querySelector('[data-creme_bisquit]'),
    cremeMuss = document.querySelector('[data-creme_muss]'),
    weight = document.querySelector('[ data-weight]'),
    weightBento = document.querySelector('[data-bento_weight]'),
    rightForm = document.querySelector('[data-right_form]');


    // показ и скрытие бисквита
    function showBisquitMenu(){
        osnovaBisquit.classList.add('show');
        cremeBisquit.classList.add('show');
        osnovaBisquit.classList.remove('hide')
        cremeBisquit.classList.remove('hide');
    }
    function hideBisquitMenu(){
        osnovaBisquit.classList.remove('show')
        cremeBisquit.classList.remove('show')
        osnovaBisquit.classList.add("hide")
        cremeBisquit.classList.add('hide')
    }

    // показ и скрытие мусса
    function showMussMenu(){
        osnovaMuss.classList.add('show');
        cremeMuss.classList.add('show');
        osnovaMuss.classList.remove('hide')
        cremeMuss.classList.remove('hide');
    }
    function hideMussMenu(){
        osnovaMuss.classList.remove('show')
        cremeMuss.classList.remove('show')
        osnovaMuss.classList.add("hide")
        cremeMuss.classList.add('hide')
    }

    // показ и скрытие веса торта для большого и бенто

    function showBentoWeight(){
        weightBento.classList.add('show')
        weightBento.classList.remove('hide')
    }

    function hideBentoWeight(){
        weightBento.classList.remove('show')
        weightBento.classList.add('hide')
    }

    function showWeight(){
        weight.classList.add('show')
        weight.classList.remove('hide')
    }
    function hideWeight(){
        weight.classList.remove('show')
        weight.classList.add('hide')
    }


    showBisquitMenu()
    hideMussMenu()
    hideBentoWeight()

//    type.addEventListener("change", (e)=>{
//     if(e.target.closest('[data-bisquit_cake]')){
//         showBisquitMenu()
//         hideMussMenu()
//         showWeight()
//         hideBentoWeight()
//         // console.log(e.target.value)
//     } else if(e.target.closest('[data-muss_cake]')){
//         hideBisquitMenu()
//         showMussMenu()
//         showWeight()
//         hideBentoWeight()
//         // console.log(e.target.value)
//     } else if(e.target.closest('[data-bento_cake]')){
//         hideWeight()
//         showBentoWeight()
//         hideMussMenu()
//         showBisquitMenu()
//     }
//    })



//    ВАРИАНТ 2

const labels = document.querySelectorAll('input');
constructor.addEventListener("change", (event)=>{
  for(item of labels){
    if(event.target.closest('[data-bisquit_cake]')){
        showBisquitMenu()
        hideMussMenu()
        showWeight()
        hideBentoWeight()
    } else if(event.target.closest('[data-muss_cake]')){
        hideBisquitMenu()
        showMussMenu()
        showWeight()
        hideBentoWeight()
    } else if(event.target.closest('[data-bento_cake]')){
        hideWeight()
        showBentoWeight()
        hideMussMenu()
        showBisquitMenu()
    }
  }
})


 
  




}); // слушатель DOMContentLoaded



 // document.querySelectorAll('form input')
    // .forEach((el) => {
    //     let typeTort = {
    //         biscvit: el.id === 'type__bisquit' ? el.value : '',
    //         muss: el.id === 'type__muss' ? el.value : '',
    //         bento: el.id === 'type__bento' ? el.value : '',
    //     }

    //     console.log(typeTort.bento);
    // })