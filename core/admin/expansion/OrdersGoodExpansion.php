<?php


namespace core\admin\expansion;


use core\base\controller\Singleton;

class OrdersGoodExpansion extends Expansion
{

    use Singleton;

    public function expansion($args = [], $obj = false)
    {
        parent::expansion($args, $obj);

        if($this->isPost()){

            $orders_id = $this->model->get('orders', [
                'fields' => ['id'],
                'where' => ['id' => 'SELECT orders_id FROM orders_goods WHERE id = ' . $_POST['id']]
            ])[0]['id'];

            if($orders_id) $this->redirect($this->adminPath . 'edit/orders/' . $orders_id);

        }

    }

}