/*Подключение визуального редактора*/

//import tinymce from "./tinymce";

function MCEInit(element, height = 400){

    tinymce.init({
        language : "ru",
        mode:'exact',
        elements:element,
        gecko_spellcheck:true,
        /*forced_root_block : false,
        force_p_newlines : false,
        force_br_newlines : true,*/
        /*extended_valid_elements : "script[src|async|defer|type|charset], p[class,style], p/div, p/pre, span, i, strong, em, b, ul, li, ol, img",*/
        height: height,
        plugins: [
            "advlist autolink lists link image charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars code fullscreen",
            "insertdatetime media nonbreaking save table directionality",
            "emoticons template paste textpattern media imagetools"
        ],
        toolbar: "insertfile undo redo | styleselect | bold italic | forecolor backcolor emoticons | " +
            "alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | " +
            "formatselect fontsizeselect | code media emoticons ",

        relative_urls: false,
        image_advtab: true,
        image_title: true,

        automatic_uploads: true,

        images_reuse_filename: true,
        imagetools_toolbar: 'editimage imageoptions',

        file_picker_types: 'image',

        images_upload_handler: function(file, success, fail){

            let formData = new FormData();

            formData.append('file', file.blob(), file.filename());

            formData.append('ajax', 'wyswyg_file')

            formData.append('table', document.querySelector('input[name="table"]').value)

            let id = document.querySelector('input#tableId')

            if(id){

                formData.append('tableId', id.value)

            }

            Ajax({
                url: document.querySelector('#main-form').getAttribute('action'),
                data:formData,
                type: 'post',
                contentType:false,
                processData:false,
            }).then(res => {
                success(JSON.parse(res).location);
            })

        },

        /* and here's our custom image picker*/
        file_picker_callback: function (cb, value, meta) {

            let input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');

            input.onchange = function () {

                let file = this.files[0];

                let reader = new FileReader();

                reader.onload = function () {

                    let blobCache =  tinymce.activeEditor.editorUpload.blobCache;
                    let base64 = reader.result.split(',')[1];
                    let blobInfo = blobCache.create(file.name, file, base64);
                    blobCache.add(blobInfo);

                    cb(blobInfo.blobUri(), { title: file.name });
                };

                reader.readAsDataURL(file);
            };

            input.click();
        }

    });

}

enableMCE()

function enableMCE(element){

    let mceElements = []

    if(typeof element === 'undefined' || !element){

        mceElements = document.querySelectorAll('.tinyMceInit')

    }else{

        mceElements.push(element)

    }

    mceElements.forEach(item => {

        if(item.checked){

            let blockContent = item.closest('.wq-main-form__full')

            let textarea = item.closest('[data-tiny-wrapper]').querySelector('textarea')

            let textareaName = textarea.getAttribute('name')

            MCEInit(textareaName, blockContent ? 400 : 300)

        }

        item.onchange = () => {

            let blockContent = item.closest('.wq-main-form__full')

            let textarea = item.closest('[data-tiny-wrapper]').querySelector('textarea')

            let textareaName = textarea.getAttribute('name')

            if(textareaName){

                console.log(item.checked);

                if(item.checked){

                    MCEInit(textareaName, blockContent ? 400 : 300)

                }else{

                    tinymce.remove(`[name="${textareaName}"]`)

                    if(!blockContent) textarea.value = textarea.value.replace(/<\/?[^>]+(>|$)/g, "");

                }

            }

        }

    })

}




/*Подключение визуального редактора*/
