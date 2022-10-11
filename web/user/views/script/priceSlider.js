
let rangeSlider = document.getElementById('slider');

if(rangeSlider){
    noUiSlider.create(rangeSlider, {
        start: [500, 10000],
        connect: true,
        step:100,
        range: {
            'min': [500],
            'max': [10000]
        }
    });

const minValue = document.querySelector('#min_value'),//инпут меньшего значения
    maxValue = document.querySelector('#max_value');//инпут большего значения

 const valuesArr = [minValue, maxValue];

rangeSlider.noUiSlider.on('update', function (values, handle){
  valuesArr[handle].value = Math.round(values[handle])
})
}
//values выдаёт два массива со значениями
//handle выдаёт 0 или 1. Приравнивая индексы массива к handle получаем меньшее и большее значение



