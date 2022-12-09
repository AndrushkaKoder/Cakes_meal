<?php
/**
 * Created by PhpStorm.
 * User: developer
 * Date: 28.02.2019
 * Time: 15:03
 */

namespace webQAdmin\controller;

use webQExceptions\RouteException;
use webQAdminSettings\Settings;

class EditController extends BaseAdmin
{

    protected $action = 'edit';

    protected function inputData(){

        if(!$this->userData) $this->execBase();

        $this->checkPost();

        $this->createTableData();

        $this->createData();

        $this->createForeignData();

        $this->createMenuPosition();

        $this->createRadio();

        $this->createOutputData();

        $this->createManyToMany();

        $this->template = $this->getViewsPath() . 'add';

        return $this->expansion();

    }

    /**
     * @throws RouteException
     */
    protected function createData(){

        $id = \WqH::clearStr($this->parameters[$this->table]);

        if(!$id) throw new RouteException('Не корректный идентификатор - ' . $id .
                                                    'при редактировании - ' . $this->table);

        $this->data = $this->model->get($this->table, [
            'where' => [$this->columns['id_row'] => $id],
            'single' => true
        ]);

    }


}