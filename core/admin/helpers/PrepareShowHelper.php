<?php

namespace core\admin\helpers;

use core\exceptions\RouteException;
use settings\Settings;

trait PrepareShowHelper
{

    protected function createCustomSortingTable(){

        $tableParameters = Settings::get('tableParameters');

        if($tableParameters && in_array($tableParameters, $this->model->showTables()) &&
            !empty($this->model->showColumns($tableParameters)['sorting'])){

            $res = $this->model->get($tableParameters, [
                'fields' => ['sorting'],
                'where' => ['users_id' => $this->userData['id'], 'table_name' => $this->table],
                'single' => true
            ]);

            if(!empty($res['sorting'])){

                $sortingData = json_decode($res['sorting'], true);

                if($sortingData && !empty($this->blocks)){

                    $prevCounter = count($sortingData);

                    $blocks = [];

                    $prevSortingBlocks = $this->blocks;

                    foreach ($sortingData as $key => $item){

                        foreach ($prevSortingBlocks as $k => $arr){

                            if(!isset($blocks[$k])){

                                $blocks[$k] = [];

                            }

                            if(($arrKey = array_search($item, $arr)) !== false){

                                $blocks[$k][] = $item;

                                unset($prevSortingBlocks[$k][$arrKey]);

                                continue 2;

                            }

                        }

                        unset($sortingData[$key]);

                    }

                    if($blocks){

                        foreach ($prevSortingBlocks as $key => $item){

                            if($item){

                                $blocks[$key] = array_merge($blocks[$key], $item);

                            }

                        }

                        $this->blocks = $blocks;

                    }

                    if($prevCounter !== count($sortingData)){

                        $this->model->edit($tableParameters, [
                            'fields' => ['sorting' => $sortingData],
                            'where' => ['users_id' => $this->userData['id'], 'table_name' => $this->table],
                        ]);

                    }

                }

            }

        }

    }

    protected function createTableData($settings = false){

        if(!$this->table){
            if($this->parameters)
                $this->table = array_keys($this->parameters)[0];
            else{
                if(!$settings)
                    $settings = Settings::instance();

                if(!($this->table = $settings::get('defaultTable'))){

                    throw new RouteException('Отсутствует имя таблицы в контроллере ' . $this->getController());

                }

            }

        }

        $this->columns = $this->model->showColumns($this->table);

        if(!$this->columns) new RouteException('Не найдены поля в таблице - ' . $this->table, 2);

        if(method_exists($this, 'prepareTranslate'))
            $this->prepareTranslate();

    }

    protected function createOutputData($settings = false){

        if(!$settings) $settings = Settings::instance();

        $blocks = $this->blockNeedle ?: $settings::get('blockNeedle');
        //$this->translate = $settings::get('translate');

        if(!$blocks || !is_array($blocks)){

            foreach($this->columns as $name => $item){
                if($name === 'id_row' || $name === 'multi_id_row') continue;

                if(!$this->translate[$name]) $this->translate[$name][] = $name;
                $this->blocks[0][] = $name;
            }

            return;

        }

        $default = array_keys($blocks)[0];

        foreach($this->columns as $name => $item){

            if($name === 'id_row' || $name === 'multi_id_row') continue;

            $insert = false;

            foreach ($blocks as $block => $value){

                if(!array_key_exists($block, $this->blocks)) $this->blocks[$block] = [];

                if(in_array($name, $value)){
                    $this->blocks[$block][] = $name;
                    $insert = true;
                    break;
                }

            }

            if(!$insert) $this->blocks[$default][] = $name;
            if(empty($this->translate[$name])) $this->translate[$name][] = $name;

        }

        return;
    }

    protected function createRadio($settings = false){

        if(!$settings) $settings = Settings::instance();

        $radio = $settings::get('radio');

        if($radio){
            foreach ($this->columns as $name => $item){
                if(!empty($radio[$name])){
                    $this->foreignData[$name] = $radio[$name];
                }
            }
        }

    }

    protected function clearParents(&$foreign, $id = false){

        !$id && $id = $this->data[$this->columns['id_row']];

        foreach ($foreign as $key => $item){

            if($item['parent_id'] === $id){

                unset($foreign[$key]);

                $this->clearParents($foreign, $item['id']);

            }

        }

        return $foreign;

    }

    protected function showSelectParents($arr, $row, $data = null, $str = '', $deepLevel = -1, $deep = -1){

        $deep++;

        !$data && $data = $this->filteringData ?: $this->data;

        if($deepLevel === -1){

            $settings = $this->settings ?: Settings::instance();

            $deepLevel = $settings::get('deepLevel');

            $deepLevel = !empty($deepLevel[$this->table][$row]) ? $deepLevel[$this->table][$row] : 0;

        }

        foreach($arr as $key => $item){

            if(is_array($item)){

                $id = $item['id'];

                $name = $item['name'];

            }else{

                if(!is_numeric($key))
                    continue;

                $id = $key;

                $name = $item;

            }

            $name = $str . $name;

            echo '<option value="' . $id .'" ' . (is_array($data) && array_key_exists($row, $data) && $data[$row] == $id ? 'selected' : '') . ' >' .
                $name . '</option>';

            if(isset($item['sub'])){

                if($deepLevel && $deep >= $deepLevel) continue;

                $dop = '';

                !$str && $dop .= '&nbsp;&nbsp;';

                $this->showSelectParents($item['sub'], $row, $data, $dop . $name . '->', $deepLevel, $deep);

            }

        }

    }

}