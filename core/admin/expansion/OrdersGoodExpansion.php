<?php


namespace webQAdmin\expansion;


class OrdersGoodExpansion extends Expansion
{


    public function expansion($args = [], $obj = false)
    {
        parent::expansion($args, $obj);

        if(\WqH::isPost()){

            $orders_id = $this->model->get('orders', [
                'fields' => ['id'],
                'where' => ['id' => 'SELECT orders_id FROM orders_goods WHERE id = ' . $_POST['id']]
            ])[0]['id'];

            if($orders_id)
                \WqH::redirect($this->alias([$this->adminPath => 'edit', 'orders' => $orders_id]));

        }

    }

}