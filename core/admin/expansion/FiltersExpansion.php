<?php


namespace core\admin\expansion;


use core\traites\Singleton;

class FiltersExpansion extends Expansion
{

    public function expansion($args = [], $obj = false)
    {
        parent::expansion($args, $obj);

        if($this->className === 'Add' || $this->className === 'Edit'){

            if(!$this->data['parent_id'])
                unset($this->templateArr['checkboxlist'][array_search('goods', $this->templateArr['checkboxlist'])]);

            //$this->onlyRootParents($this->foreignData['parent_id']);

        }
    }

}