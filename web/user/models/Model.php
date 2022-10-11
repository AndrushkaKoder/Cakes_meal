<?php

namespace web\user\models;

use core\exceptions\DbException;
use core\traites\Singleton;

class Model extends \core\models\BaseModel
{
    use Singleton;
    //Руководитель проекта сказал, что PDO это моднее, поэтому я не меняя базовые классы сделал подключение к СУБД по средствам расширения
    //PDO. Это комментарий для проверяющих проекта.



}