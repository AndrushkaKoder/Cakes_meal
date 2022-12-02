<?php if(!empty($row)):?>
    <!-- Блок "GPS координаты" -->
    <div class="wq-block">

        <?=$this->render(ADMIN_TEMPLATE . 'include/sorting_block')?>

        <div class="wq-block__wrap">
            <div>
                <h3 class="wq-block__title <?=!empty($this->userData['ROOT']) ? 'sorting-title' : ''?>"><?=$this->translate[$row][0] ?? $row?></h3>
                <p class="wq-block__caption"><?=$this->translate[$row][1] ?? ''?></p>

                <?php

                $value = isset($_SESSION['res'][$row]) ? $_SESSION['res'][$row] : json_decode($this->data[$row], true);

                foreach ($value as &$v){

                    $v = htmlspecialchars($v);

                }
                ?>

                <h2 class="wq-block__title-h2">GPS</h2>
                <input type="text" name="<?=$row?>[gps]" value="<?=$value['gps'] ?? ''?>" class="wq-block__input wq-block_mb-small wq-block__input_middle">
                <h2 class="wq-block__title-h2">CENTER</h2>
                <input type="text" name="<?=$row?>[center]" value="<?=$value['center'] ?? ''?>" class="wq-block__input wq-block_mb-small wq-block__input_middle">
                <h2 class="wq-block__title-h2">ZOOM</h2>
                <input type="text" name="<?=$row?>[zoom]" value="<?=$value['zoom'] ?? ''?>" class="wq-block__input wq-block__input_small wq-block_mb-small">
            </div>

            <div class="wq-block__maps" id="map_<?=$row?>" style="max-width: 100%">

            </div>
        </div>
        <script>

            document.addEventListener('DOMContentLoaded', () => {

                let center = '<?=$value['center']?>' || '<?=\core\base\settings\Settings::get('GPSCoordinates')?>'

                let readyCoords = '<?=$value['gps']?>'

                let zoom = '<?=$value['zoom']?>' || 9

                center = center.split(/\s*,\s*/)

                ymaps.ready(init);

                function init() {

                    let blockInputGps = document.querySelector('[name="<?=$row?>[gps]"]')
                    let blockInputCenter = document.querySelector('[name="<?=$row?>[center]"]')
                    let blockInputZoom = document.querySelector('[name="<?=$row?>[zoom]"]')

                    var myPlacemark,
                        myMap = new ymaps.Map('map_<?=$row?>', {
                            center: [+center[0], +center[1]],
                            zoom: zoom
                        });

                    if(readyCoords){

                        readyCoords = readyCoords.split(/\s*,\s*/)

                        readyCoords[0] = +readyCoords[0]
                        readyCoords[1] = +readyCoords[1]

                        myPlacemark = createPlacemark(readyCoords);
                        myMap.geoObjects.add(myPlacemark);
                        setGPSValue(myPlacemark.geometry.getCoordinates());
                        // Слушаем событие окончания перетаскивания на метке.
                        myPlacemark.events.add('dragend', function () {
                            setGPSValue(myPlacemark.geometry.getCoordinates());
                        });

                    }

                    myMap.events.add('boundschange', function(){

                        blockInputCenter.value = myMap.getCenter().join(', ')
                        blockInputZoom.value = myMap.getZoom()

                    })

                    // Слушаем клик на карте.
                    myMap.events.add('click', function (e) {

                        let coords = e.get('coords');

                        // Если метка уже создана – просто передвигаем ее.
                        if (myPlacemark) {
                            myPlacemark.geometry.setCoordinates(coords);
                            setGPSValue(myPlacemark.geometry.getCoordinates());
                        }
                        // Если нет – создаем.
                        else {
                            myPlacemark = createPlacemark(coords);
                            myMap.geoObjects.add(myPlacemark);
                            setGPSValue(myPlacemark.geometry.getCoordinates());
                            // Слушаем событие окончания перетаскивания на метке.
                            myPlacemark.events.add('dragend', function () {
                                setGPSValue(myPlacemark.geometry.getCoordinates());
                            });
                        }
                        //getAddress(coords);
                    });

                    // Создание метки.
                    function createPlacemark(coords) {
                        return new ymaps.Placemark(coords, {
                            iconCaption: coords.join(', ')
                        }, {
                            preset: 'islands#violetDotIconWithCaption',
                            draggable: true
                        });
                    }

                    function setGPSValue(coords){

                        blockInputGps.value = coords.join(', ')

                    }

                    blockInputCenter.addEventListener('change', () => {

                        readyCoords = blockInputCenter.value

                        readyCoords = readyCoords.split(/\s*,\s*/)

                        readyCoords[0] = +readyCoords[0]
                        readyCoords[1] = +readyCoords[1]

                        myMap.panTo(readyCoords, {
                            flying: 1
                        });

                    })

                    blockInputZoom.addEventListener('change', () => {
                        myMap.options.set('maxAnimationZoomDifference', Infinity);
                        myMap.setZoom(+blockInputZoom.value)
                    })

                    blockInputGps.addEventListener('change', () => {

                        readyCoords = blockInputGps.value

                        readyCoords = readyCoords.split(/\s*,\s*/)

                        readyCoords[0] = +readyCoords[0]
                        readyCoords[1] = +readyCoords[1]

                        myMap.panTo(readyCoords, {
                            flying: 1
                        });

                        if (myPlacemark) {
                            myPlacemark.geometry.setCoordinates(readyCoords);
                        }
                        // Если нет – создаем.
                        else {
                            myPlacemark = createPlacemark(readyCoords);
                            myMap.geoObjects.add(myPlacemark);
                            // Слушаем событие окончания перетаскивания на метке.
                            myPlacemark.events.add('dragend', function () {
                                setGPSValue(myPlacemark.geometry.getCoordinates());
                            });
                        }

                    })

                }

            })

        </script>
    </div>


    <!-- Конец блока "GPS координаты" -->
<?php endif;?>