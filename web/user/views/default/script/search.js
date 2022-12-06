document.addEventListener('DOMContentLoaded', () => {

    document.querySelectorAll('[data-form-search]').forEach(formSearch => {

        let searchResultsBlock = formSearch.querySelector('.search_res')

        if(!searchResultsBlock){

            formSearch.insertAdjacentHTML('beforeend', '<div class="search_res"></div>')

        }

        let inputSearch = formSearch.querySelector('input[type="text"]')

        if(inputSearch && inputSearch.name !== 'search'){

            inputSearch.name = 'search'

        }

        formSearch.style.position = 'relative'

    })

    /*ajax поиск*/

    let searchResultHover = (() => {

        let searchRes = document.querySelector('.search_res')

        let searchInput = document.querySelector('input[name=search]')

        let defaultInputValue = null

        function searchKeydown(e){

            if(e.key !== 'ArrowUp' && e.key !== 'ArrowDown') return;

            let children = [...searchRes.children]

            if(children.length){

                e.preventDefault()

                let activeItem = searchRes.querySelector('.search_act')

                let activeIndex = activeItem ? children.indexOf(activeItem) : -1

                if(e.key === 'ArrowUp')
                    activeIndex = activeIndex <= 0 ? children.length - 1 : --activeIndex
                else
                    activeIndex = activeIndex === children.length - 1 ? 0 : ++activeIndex

                children.forEach(item => item.classList.remove('search_act'))

                children[activeIndex].classList.add('search_act')

                searchInput.value = children[activeIndex].innerText.replace(/\s*\(.+?\)\s*$/, '')

            }

        }

        function setDefaultValue(){

            searchInput.value = defaultInputValue

        }

        searchRes.addEventListener('mouseleave', setDefaultValue)

        window.addEventListener('keydown', searchKeydown)

        return () => {

            defaultInputValue = searchInput.value

            if(searchRes.children.length){

                let children = [...searchRes.children]

                children.forEach(item => {

                    item.addEventListener('mouseover', () => {

                        children.forEach(el => {
                            el.classList.remove('search_act')
                        })

                        item.classList.add('search_act')

                        searchInput.value = item.innerText

                    })

                })

            }

        }

    })()

    search()

    function search(){

        document.addEventListener('click', e => {

            if(!e.target.closest('[data-form-search]')){

                document.querySelectorAll('.search_res').forEach(item => {

                    item.innerHTML = ''

                    item.style.display = 'none'

                })

            }

        })

        document.querySelectorAll('input[name="search"]').forEach(searchInput => {

            let form = searchInput.closest('[data-form-search]')

            if(form){

                searchInput.oninput = () => {

                    if(searchInput.value.length > 1){

                        let data = {
                            search:searchInput.value,
                            ajax: form.getAttribute('data-form-search') || 'site_search'
                        }

                        form.querySelectorAll('input[name]').forEach(item => {

                            if(typeof data[item.getAttribute('name')] === 'undefined'){

                                data[item.getAttribute('name')] = item.value

                            }

                        })

                        if(form.getAttribute('action') && typeof data.action === 'undefined'){

                            data.action = form.getAttribute('action')

                        }

                        Ajax(
                            {
                                data: data
                            }
                        ).then(res => {

                            console.log(res)

                            try{

                                res = JSON.parse(res)

                                let resBlok = document.querySelector('.search_res')

                                if(resBlok){

                                    resBlok.innerHTML = '';

                                    for(let i in res){

                                        if(res.hasOwnProperty(i)){

                                            let template = res[i]['template'] || `<a href="${res[i]['alias']}">${res[i]['name']}</a>`

                                            resBlok.insertAdjacentHTML('beforeend', template)

                                        }

                                    }

                                    resBlok.style.display = 'block'

                                    searchResultHover();

                                }

                            }catch (e){

                                console.log(e)

                            }

                        })

                    }

                }

            }

        })



    }

    /*ajax поиск*/

})