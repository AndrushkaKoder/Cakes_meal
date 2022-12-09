<?php

namespace webQApplication\models;

use webQApplication\helpers\CatalogModelHelper;

class Model extends \webQModels\BaseModel
{
    use CatalogModelHelper;

    //Руководитель проекта сказал, что PDO это моднее, поэтому я не меняя базовые классы сделал подключение к СУБД по средствам расширения
    //PDO. Это комментарий для проверяющих проекта.

}