window.addEventListener('DOMContentLoaded', ()=>{



    let betaAlert = document.querySelector('.beta_alert');
    let closeBetaAlert = document.querySelector('.close_beta');

    setTimeout(()=>{
        betaAlert.classList.add('show')
    }, 3000)

    closeBetaAlert.addEventListener('click', ()=>{
        betaAlert.classList.remove('show')
    })
})