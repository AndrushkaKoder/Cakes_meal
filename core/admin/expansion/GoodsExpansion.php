<?php
/**
 * Created by PhpStorm.
 * User: developer
 * Date: 20.02.2019
 * Time: 17:45
 */

namespace core\admin\expansion;

use core\base\controller\BaseMethods;
use core\base\controller\Singleton;
use core\base\exceptions\RouteException;
use core\base\settings\Settings;
use libraries\TextModify;
use morphos\Russian\GeographicalNamesInflection;
use morphos\Russian\NounDeclension;

class GoodsExpansion extends Expansion
{
    use Singleton;
    use BaseMethods;


    public function expansion($args = [], $obj = false){

        parent::expansion($args, $obj);

    }

}