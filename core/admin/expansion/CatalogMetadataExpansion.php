<?php


namespace webQAdmin\expansion;

use webQTraits\Singleton;

class CatalogMetadataExpansion extends Expansion
{

    public function expansion($args = [], $obj = false)
    {
        parent::expansion($args, $obj);

        if(\WqH::isPost() && $_POST['id']){

            $name = preg_replace('/^.*?\/?([^\/]+)\/?$/', '$1', $_POST['name']);

            $this->model->edit('catalog_metadata', [
                'fields' => ['name' => $name],
                'where' => ['id' => $_POST['id']]
            ]);

        }

    }

}