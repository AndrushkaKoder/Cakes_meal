<?php


namespace webQAdmin\expansion;

use webQAdminSettings\Settings;

class OrdersExpansion extends Expansion
{

    protected $tables = [
        'visitors' => [
            'inner_field' => 'visitors_id',
            'single' => true
        ],
        'orders_goods' => [
            'outer_field' => 'orders_id',
            'total_sum' => ['price', 'qty']
        ]
    ];

    protected $tableTranslate = [
        'visitors' => [
            'name' => ['ФИО']
        ],
    ];

    public function expansion($args = [], $obj = false){

        parent::expansion($args, $obj);

        $no_add = true;
        $no_delete = true;

        if($this->className === 'Edit'){

            $this->translate['address'] = ['Адрес доставки'];

            if(\WqH::isPost()){

                $this->editOrderData($args);

            }else{

                $this->createOrderData();

            }

        }

        return compact('no_add', 'no_delete');

    }

    protected function editOrderData($args = []){

        $data = $this->model->get('orders', [
            'where' => [$this->columns['id_row'] => $_POST[$this->columns['id_row']]]
        ])[0];

        if(array_key_exists('order_status', $data) &&
            !empty($args['oldData']) &&
            !empty($this->columns['order_status']) &&
            !empty($this->columns['history_statuses']) &&
            $args['oldData']['order_status'] != $_POST['order_status']){

            $history = json_decode($data['history_statuses'], true);

            !$history && $history = [];

            $history[(new \DateTime())->format('Y-m-d H:i:s')] = $_POST['order_status'];

            $this->model->edit('orders', [
                'fields' => ['history_statuses' => $history],
                'where' => [$this->columns['id_row'] => $_POST[$this->columns['id_row']]]
            ]);

        }

        foreach ($this->tables as $table => $item){

            $columns = $this->model->showColumns($table);

            if(isset($item['inner_field'])){

                $res = $this->model->get($table, [
                    'where' => [$columns['id_row'] => $data[$item['inner_field']]]
                ]);

            }

            if(isset($item['outer_field'])){

                $res = $this->model->get($table, [
                    'where' => [$item['outer_field'] => $data[$this->columns['id_row']]]
                ]);

            }

            if(isset($item['single']) && $item['single'] && $res){

                $arr = [];

                $res = $res[0];

                foreach ($columns as $key => $value){

                    if(array_key_exists($table . '__' . $key, $_POST)){

                        $arr[$key] = $_POST[$table . '__' . $key];

                    }

                }

                if($arr){

                    $this->model->edit($table, [
                        'fields' => $arr,
                        'where' => [$columns['id_row'] => $res[$columns['id_row']]]
                    ]);

                }

            }

        }

    }

    protected function createOrderData(){

        if(!$this->tables) return;

        $blocks = Settings::get('blockNeedle');
        $radio = Settings::get('radio');

        $goods = $this->model->get('orders_goods', [
            'where' => ['orders_id' => $this->data['id']]
        ]);

        $this->blocks['vg-img'][] = 'orders_goods';

        $this->foreignData['orders_goods']['orders_goods']['name'] = 'Заказанные товары';

        $this->foreignData['orders_goods']['orders_goods']['sub'] = [];

        if($goods){

            foreach ($goods as $item){

                $price = $item['old_price'] ? 'цена без скидки - ' . $item['old_price'] . ' со скидкой - ' . $item['price']: 'цена - ' . $item['price'];

                $item['name'] = $item['name'] . ', количество - ' . $item['qty'] . ', ' . $price;

                $this->foreignData['orders_goods']['orders_goods']['sub'][$item['id']] = $item;

                $this->data['orders_goods']['orders_goods'][$item['id']] = true;

            }

        }

        return;

    }

}