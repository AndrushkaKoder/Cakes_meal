<?php
/**
 * Created by PhpStorm.
 * User: developer
 * Date: 28.02.2019
 * Time: 15:03
 */

namespace webQAdmin\controller;

class AddController extends BaseAdmin
{

    protected $action = 'add';

    protected function inputData(){

        if(!$this->userData) $this->execBase();

        $this->checkPost();

        $this->createTableData();

        $this->createForeignData();

        $this->createMenuPosition();

        $this->createRadio();

        $this->createOutputData();

        $this->createManyToMany();

        return $this->expansion();

    }

}