<?php


namespace core\admin\expansion;


class OrdersGoodExpansion extends Expansion
{


    public function expansion($args = [], $obj = false)
    {
        parent::expansion($args, $obj);

        if(\AppH::isPost()){

            $orders_id = $this->model->get('orders', [
                'fields' => ['id'],
                'where' => ['id' => 'SELECT orders_id FROM orders_goods WHERE id = ' . $_POST['id']]
            ])[0]['id'];

            if($orders_id)
                \AppH::redirect($this->alias([$this->adminPath => 'edit', 'orders' => $orders_id]));

        }

    }

}