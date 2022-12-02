let forms = document.querySelectorAll('form');

if(forms.length){

    forms.forEach(form => {

        form.addEventListener('submit', e => {

            if(e.isTrusted){

                e.preventDefault();

                Ajax({

                    data: {ajax: 'token'}

                }).then(res => {

                    if(res){

                        let input = document.createElement('input')

                        input.type = 'hidden'

                        input.name = 'token'

                        input.value = res

                        form.append(input)

                        if(typeof e.submitter !== 'undefined' && e.submitter.name){

                            form.insertAdjacentHTML('beforeend', `<input type="hidden" name="${e.submitter.name}" value="${(e.submitter.value || e.submitter.innerHTML)}">`)

                        }

                    }

                    form.submit();

                })

            }
        })

    })

}