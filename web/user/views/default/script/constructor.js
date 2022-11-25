window.addEventListener("DOMContentLoaded", ()=>{

    const constructor = document.querySelector('[data-constructor]'),
    type = document.querySelector("[ data-type_cake]"),
    
    osnovaBisquit = document.querySelector('[data-osnova_bisquit]'),
    osnovaMuss = document.querySelector('[data-osnova_muss]'),
    cremeBisquit = document.querySelector('[data-creme_bisquit]'),
    cremeMuss = document.querySelector('[data-creme_muss]'),
    weight = document.querySelector('[ data-weight]'),
    weightBento = document.querySelector('[data-bento_weight]'),
        subtitle = document.querySelector('.constructor_subtitile');


     let description = {
        bisquit: 'Классический бисквитный торт',
        muss: 'Муссовый торт для ценителей',
        bento: 'Такой же, как и бисквитный, только маленький'
    }
    subtitle.innerText = description.bisquit;

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



    let bisqChecked = document.querySelector('#type__white__bisquit');
    let mussChecked = document.querySelector('#osnova__tri_choko__muss');
    let mussCremeChecked = document.querySelector('#napolnitel__caramel__muss');
    let bisqCremeChecked = document.querySelector('#krem__maslyanny__bisquit');


    bisqChecked.setAttribute('checked', '');
    bisqCremeChecked.setAttribute('checked', '');

const labels = document.querySelectorAll('input');
constructor.addEventListener("change", (event)=>{
  for(item of labels){
    if(event.target.closest('[data-bisquit_cake]')){
        showBisquitMenu()
        hideMussMenu()
        showWeight()
        hideBentoWeight()
            mussChecked.removeAttribute('checked');
            mussCremeChecked.removeAttribute('checked');
            bisqChecked.setAttribute('checked', '');
            bisqCremeChecked.setAttribute('checked', '');

            subtitle.innerText = description.bisquit;



    } else if(event.target.closest('[data-muss_cake]')){
        hideBisquitMenu()
        showMussMenu()
        showWeight()
        hideBentoWeight()
        bisqChecked.removeAttribute('checked');
        bisqCremeChecked.removeAttribute('checked');
        mussChecked.setAttribute('checked', '');
        mussCremeChecked.setAttribute('checked', '');

        subtitle.innerText = description.muss;

    } else if(event.target.closest('[data-bento_cake]')){
        hideWeight()
        showBentoWeight()
        hideMussMenu()
        showBisquitMenu()
        mussChecked.removeAttribute('checked');
        mussCremeChecked.removeAttribute('checked');
        bisqChecked.setAttribute('checked', '');
        bisqCremeChecked.setAttribute('checked', '');

        subtitle.innerText = description.bento;
    }
  }
})


}); // слушатель DOMContentLoaded
