<?php


namespace core\admin\expansion;


use core\base\controller\BaseMethods;
use core\base\controller\Singleton;

class CatalogMetadataExpansion extends Expansion
{

    use Singleton;
    use BaseMethods;

    public function expansion($args = [], $obj = false)
    {
        parent::expansion($args, $obj);

        if($this->isPost() && $_POST['id']){

            $name = preg_replace('/^.*?\/?([^\/]+)\/?$/', '$1', $_POST['name']);

            $this->model->edit('catalog_metadata', [
                'fields' => ['name' => $name],
                'where' => ['id' => $_POST['id']]
            ]);

        }

    }

}