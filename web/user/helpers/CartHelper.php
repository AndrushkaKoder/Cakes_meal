<?php

namespace web\user\helpers;

use core\models\Crypt;

trait CartHelper
{
    protected $cart = [];

    private $emptyCartValue = 'Купить';

    private $fullCartValue = 'В корзине';

    private $cartStrictMode = true;

    private $lastCartElement = [];

    private function checkCartStrictMode(){

        return $this->cartStrictMode ?? ($this->cartStrictMode = $this->set['cart_strict_mode'] ?? false);

    }

    protected function getElementId($data, $table = ''){

        $resultId = null;

        if($data){

            !$table && $table = $this->model->goodsTable;

            if(!is_array($data)){

                if(is_numeric($data)){

                    $resultId = \AppH::clearNum($data);

                }else{

                    $resultId = \AppH::clearStr($data);

                }

            }elseif($table && in_array($table, $this->model->showTables())){

                $resultId = $data[$this->model->showColumns($this->model->goodsTable)['id_row']] ?? null;

            }

        }

        return $resultId;

    }

    protected function wishList($data, $action = 'wishList'){

        $id = $this->getElementId($data);

        if($id){

            $str = 'data-' . $action . '="' . $id . '"';

            if(!empty($_SESSION[$action][$id])){

                $str .= ' data-' . $action . '-added';

            }

            echo $str;

        }

    }

    protected function headerWishList($action = 'wishList'){

        $str = 'data-header-' . $action;

        if(!empty($_SESSION[$action])){

            $str .= ' data-' . $action . '-added';

        }

        echo $str;

    }

    protected function delayed($data){

        $this->wishList($data, 'delayed');

    }

    protected function headerDelayed(){

        $this->headerWishList('delayed');

    }

    protected function addToWishList($id, $action){

        !$id && $id = \AppH::clearNum($this->ajaxData['id'] ?? 0);

        !$action && $action = \AppH::clearStr($this->ajaxData['ajax'] ?? '') ?: 'delayed';

        if(!$id){

            return ['error' => 1, 'message' => $this->translateEl('Не балуйтесь')];

        }

        $where = [$this->model->showColumns($this->model->goodsTable)['id_row'] => $id];

        if(!empty($this->model->showColumns($this->model->goodsTable)['visible'])){

            $where['visible'] = 1;

        }

        $goods = $this->model->get($this->model->goodsTable, [
            'fields' => [$this->model->showColumns($this->model->goodsTable)['id_row']],
            'where' => $where,
            'single' => true
        ]);

        if(!$goods){

            return ['error' => 1, 'message' => $this->translateEl('Данного товара не найдено')];

        }

        $res['success'] = 1;

        if(!empty($_SESSION[$action][$id])){

            unset($_SESSION[$action][$id]);

            $res['success'] = 0;

        }else{

            $_SESSION[$action][$id] = true;

        }

        if(empty($_SESSION[$action])){

            $res['empty'] = 1;

        }

        return $res;

    }

    protected function initCartValues($emptyCartValue = '', $fullCartValue = ''){

        $this->emptyCartValue = $emptyCartValue;

        $this->fullCartValue = $fullCartValue;

    }

    protected function setAddToCart($data, $emptyCartValue = false, $fullCartValue = false){

        $id = $this->getElementId($data);

        if($id){

            $emptyCartValue !== false && $this->emptyCartValue = $emptyCartValue;

            $fullCartValue !== false && $this->fullCartValue = $fullCartValue;

            $data && $this->lastCartElement = $data;

            return 'data-addToCart="' . $id . '"' .
                ' data-notInCartValue="' . $this->emptyCartValue . '"' .
                ' data-inCartValue="' . $this->fullCartValue . '"' .
                (!empty($this->cart[$this->model->goodsTable][$id]) ? ' data-toCartAdded' : '') .
                (!empty($data[$this->model->offersTable]) ? ' data-exists-offers' : '') .
                ($this->checkCartStrictMode() && !empty($data[$this->model->offersTable]) ? ' data-strict-mode' : '');

        }

    }

    protected function setAddToCartOneClick($data, $emptyCartValue = false, $fullCartValue = false){

        return $this->setAddToCart($data, $emptyCartValue, $fullCartValue) . ' data-one-click';

    }

    protected function setCartQuantity($data)
    {

        $id = $this->getElementId($data);

        if($id){

            $value = !empty($this->cart[$this->model->goodsTable][$id]['qty']) &&
                empty($this->cart[$this->model->goodsTable][$id][$this->model->offersTable]) ?
                $this->cart[$this->model->goodsTable][$id]['qty'] : 1;

            return 'data-quantity value="' . $value . '"' .
                (!empty($this->cart[$this->model->goodsTable][$id]) ? ' data-toCartAdded="' . ($this->cart[$this->model->goodsTable][$id]['qty'] ?? '') . '"' : '');

        }

    }

    protected function setCartPriceCorrector($data){

        $id = $this->getElementId($data);

        if($id){

            $value = !empty($this->cart[$this->model->goodsTable][$id]['corrector']) ? $this->cart[$this->model->goodsTable][$id]['corrector'] : '';

            return 'data-priceCorrector="' . $value . '" value="' . $value . '"';

        }

    }

    protected function cartBtnText($data = [], $emptyCartValue = false, $fullCartValue = false){

        !$data && $data = $this->lastCartElement;

        if($data){

            $id = $this->getElementId($data);

            if($id){

                $emptyCartValue !== false && $this->emptyCartValue = $emptyCartValue;

                $fullCartValue !== false && $this->fullCartValue = $fullCartValue;

                return empty($this->cart[$this->model->goodsTable][$id]) ? $this->emptyCartValue : $this->fullCartValue;

            }

        }

    }

    protected function setGoodsPrice($data, $column = 'price'){

        if(isset($data[$column])){

            $attribute = preg_replace_callback('/_+(\w)/', function($matches){

                return strtoupper($matches[1]);

            }, $column) ?? $column;

            $id = $this->getElementId($data);

            $str = 'data-' . $attribute . '="' . $data[$column] . '"';

            if(!empty($this->cart[$this->model->goodsTable][$id]['corrector'])){

                $str .= ' data-corrector="' . $this->cart[$this->model->goodsTable][$id]['corrector'] . '"';

            }

            return $str;

        }

    }

    protected function setGoodsOldPrice($data, $column = 'old_price'){

        return $this->setGoodsPrice($data, $column);

    }

    protected function setOffers($goods, $data){

        if($goods){

            $goodsId = $this->getElementId($goods);

            $id = $this->getElementId($data, $this->model->offersTable);

            if($goodsId && $id && is_array($data)){

                $offersDataStr = 'data-offers="' . $id . '"';

                !empty($data['old_price']) && $offersDataStr .= ' data-offers-oldPrice="' . $data['old_price'] . '"';

                !empty($data['price']) && $offersDataStr .= ' data-offers-price="' . $data['price'] . '"';

                !empty($this->cart[$this->model->goodsTable][$goodsId][$this->model->offersTable][$id]['qty']) &&
                    $offersDataStr .= ' data-offers-quantity="' . $this->cart[$this->model->goodsTable][$goodsId][$this->model->offersTable][$id]['qty'] . '"';

                !empty($this->cart[$this->model->goodsTable][$goodsId][$this->model->offersTable][$id]['corrector']) &&
                    $offersDataStr .= ' data-corrector="' . $this->cart[$this->model->goodsTable][$goodsId][$this->model->offersTable][$id]['corrector'] . '"';


                return $offersDataStr;

            }

        }

    }

    protected function getGoodsPrice($data, $column = 'price'){

        if(array_key_exists($column, $data)){

            $id = $this->getElementId($data);

            $price = \AppH::clearNum($data[$column]);

            return !empty($this->cart[$this->model->goodsTable][$id]['corrector']) ?
                round($price * $this->cart[$this->model->goodsTable][$id]['corrector']) : $price;


        }

    }

    protected function getGoodsOldPrice($data){

        return $this->getGoodsPrice($data, 'old_price');

    }


    protected function addToCart($id = null, $qty = 0, $offersId = null, $cartData = []){

        !$id && $id = \AppH::clearNum($this->ajaxData['id'] ?? null);

        !$qty && $qty = \AppH::clearNum($this->ajaxData['qty'] ?? 1) ?: 1;

        !$offersId && $offersId = \AppH::clearNum($this->ajaxData['offersId'] ?? null);

        !$cartData && !empty($this->ajaxData['cartData']) && $cartData = $this->ajaxData['cartData'];

        !is_array($cartData) && $cartData = json_decode($cartData, true);

        if($cartData && is_array($cartData)){

            foreach ($cartData as $name => $item){

                $item = is_numeric($item) ? \AppH::clearNum($item) : \AppH::clearStr($item);

                if(empty($item)){

                    unset($cartData[$name]);

                    continue;

                }

                $cartData[$name] = $item;

            }

        }else{

            $cartData = [];

        }

        if(!$id){

            return ['success' => 0, 'message' => $this->translateEl('Не балуйтесь')];

        }

        $where = [$this->model->showColumns($this->model->goodsTable)['id_row'] => $id];

        if(!empty($this->model->showColumns($this->model->goodsTable)['visible'])){

            $where['visible'] = 1;

        }

        $data = $this->model->getGoods([
            'where' => $where,
            'single' => true
        ], ...[false, false]);

        if(method_exists($this, 'beforeAddToCart')){

            $message = null;

            if($this->beforeAddToCart($data, $id, $qty, $offersId, $message) === false){

                return ['success' => 0, 'message' => $message];

            }

        }

        if(empty($data)){

            return ['success' => 0, 'message' => $this->translateEl('Не найден товар при добавлении в корзину')];

        }

        if($this->checkCartStrictMode()){

            if(!empty($data[$this->model->offersTable]) && (empty($offersId) || empty($data[$this->model->offersTable][$offersId]))){

                return ['success' => 0, 'message' => $this->translateEl('Нельзя добавить товар без торгового предложения')];

            }

        }

        $res = $this->setCartData($data, $qty, $offersId, $cartData);

        if(empty($res) || empty($res[$this->model->goodsTable][$id])){

            return ['success' => 0, 'message' => $this->translateEl('Ошибка добавления товара в корзину')];

        }

        $res['current'] = $res[$this->model->goodsTable][$id];

        unset($res['current'][$this->model->offersTable]);

        if(!empty($offersId) && !empty($res[$this->model->goodsTable][$id][$this->model->offersTable][$offersId])){

            $res['current']['offers'] = $res[$this->model->goodsTable][$id][$this->model->offersTable][$offersId];

        }

        return ['success' => 1, 'message' => $this->translateEl('Товар добавлен в корзину'), 'data' => $res];

    }

    protected function setCartData($data, $qty = 1, $offersId = null, $cartData = []){

        $id = $this->getElementId($data);

        $cart = &$this->getCart();

        if(!isset($cart[$this->model->goodsTable][$id])){

            $cart[$this->model->goodsTable][$id] = [];

        }

        if(!empty($offersId) && !empty($data[$this->model->offersTable][$offersId])){

            $cart[$this->model->goodsTable][$id][$this->model->offersTable][$offersId] = [];

            $cart[$this->model->goodsTable][$id][$this->model->offersTable][$offersId]['qty'] = $qty;

            if($cartData){

                $cart[$this->model->goodsTable][$id][$this->model->offersTable][$offersId] = array_merge($cart[$this->model->goodsTable][$id][$this->model->offersTable][$offersId], $cartData);

            }

        }else{

            $cart[$this->model->goodsTable][$id]['qty'] = $qty;

            if($cartData){

                $cart[$this->model->goodsTable][$id] = array_merge($cart[$this->model->goodsTable][$id], $cartData);

            }else{

                foreach ($cart[$this->model->goodsTable][$id] as $key => $item){

                    if($key !== 'qty' && $key !== $this->model->offersTable){

                        unset($cart[$this->model->goodsTable][$id][$key]);

                    }

                }

            }

        }

        $this->updateCart();

        return $this->getCartData(true);

    }

    protected function deleteCartData($id, $offersId = null){

        $id = \AppH::clearNum($id);

        if($offersId){

            $offersId = \AppH::clearNum($offersId);

        }

        if($id){

            $cart = &$this->getCart();

            if(empty($cart[$this->model->goodsTable])){

                $this->clearCart();

            }

            if(!empty($offersId)){

                if(!empty($cart[$this->model->goodsTable][$id][$this->model->offersTable][$offersId])){

                    unset($cart[$this->model->goodsTable][$id][$this->model->offersTable][$offersId]);

                    if(empty($cart[$this->model->goodsTable][$id][$this->model->offersTable])){

                        unset($cart[$this->model->goodsTable][$id]);

                    }

                }

            }else{

                unset($cart[$this->model->goodsTable][$id]);

            }

            $this->updateCart();

            $this->getCartData(true);

        }

    }

    public function getCartData($cartChanged = false){

        if(!empty($this->cart) && !$cartChanged){

            return $this->cart;

        }

        $cart = &$this->getCart();

        if(empty($cart[$this->model->goodsTable])){

            $this->clearCart();

            return false;

        }

        $where = [
            $this->model->showColumns($this->model->goodsTable)['id_row'] => array_keys($cart[$this->model->goodsTable])
        ];

        if(!empty($this->model->showColumns($this->model->goodsTable)['id_row']['visible'])){

            $where['visible'] = 1;
        }

        $goods = $this->model->getGoods([
            'where' => $where
        ], ...[false, false]);

        $this->cart = [
            $this->model->goodsTable => []
        ];

        if($goods){

            $changeCart = false;

            foreach ($cart[$this->model->goodsTable] as $key => $item){

                if(empty($goods[$key])){

                    unset($cart[$this->model->goodsTable][$key]);

                    $changeCart = true;

                    continue;

                }

                $this->cart[$this->model->goodsTable][$key] = $this->setElement($goods[$key], $item);

                if(!empty($item[$this->model->offersTable])){

                    if(empty($goods[$key][$this->model->offersTable])){

                        unset($cart[$this->model->goodsTable][$key]);

                        $changeCart = true;

                        continue;

                    }

                    foreach ($item[$this->model->offersTable] as $k => $offer){

                        if(empty($goods[$key][$this->model->offersTable][$k])){

                            unset($cart[$this->model->goodsTable][$key][$this->model->offersTable][$k]);

                            $changeCart = true;

                            continue;

                        }

                        $this->cart[$this->model->goodsTable][$key][$this->model->offersTable][$k] = $this->setElement($goods[$key][$this->model->offersTable][$k], $offer);

                    }

                }

            }

            if($changeCart){

                $this->updateCart();

            }

        }else{

            $this->clearCart();

        }

        return $this->totalSum();

    }

    protected function setElement($data, $item){


        if(isset($data) && isset($item)){

            unset($data[$this->model->offersTable], $item[$this->model->offersTable]);

            return array_merge($data, $item);

        }

        return [];

    }

    private function totalSum(){

        if(empty($this->cart) || empty($this->cart[$this->model->goodsTable])){

            $this->clearCart();

            return false;

        }

        $this->cart['total_sum'] = $this->cart['total_old_sum'] = $this->cart['total_qty'] = 0;

        foreach ($this->cart[$this->model->goodsTable] as $key => $item){

            $this->cart[$this->model->goodsTable][$key]['total_qty'] = $this->cart[$this->model->goodsTable][$key]['total_sum'] = $this->cart[$this->model->goodsTable][$key]['total_old_sum'] = 0;

            if(!empty($item['qty'])){

                $this->cart[$this->model->goodsTable][$key]['total_qty'] = $item['qty'];

                $this->cart[$this->model->goodsTable][$key]['total_sum'] = round($item['qty'] * $item['price'], 2);

                $this->cart[$this->model->goodsTable][$key]['total_old_sum'] = round($item['qty'] * ($item['old_price'] ?? 0), 2);

            }

            if(!empty($item[$this->model->offersTable])){

                foreach ($item[$this->model->offersTable] as $offerKey => $offer){

                    $totalOfferSum = round($offer['qty'] * $offer['price'], 2);

                    $totalOfferOldSum = round($offer['qty'] * ($offer['old_price'] ?? 0), 2);

                    $this->cart[$this->model->goodsTable][$key]['total_qty'] += $offer['qty'];

                    $this->cart[$this->model->goodsTable][$key]['total_sum'] += $totalOfferSum;

                    $this->cart[$this->model->goodsTable][$key]['total_old_sum'] += $totalOfferOldSum;

                    $this->cart[$this->model->goodsTable][$key][$this->model->offersTable][$offerKey]['total_sum'] = $totalOfferSum;

                    $this->cart[$this->model->goodsTable][$key][$this->model->offersTable][$offerKey]['total_old_sum'] = $totalOfferOldSum;

                }

            }

            $this->cart['total_qty'] += $this->cart[$this->model->goodsTable][$key]['total_qty'];

            $this->cart['total_sum'] += $this->cart[$this->model->goodsTable][$key]['total_sum'];

            $this->cart['total_old_sum'] += $this->cart[$this->model->goodsTable][$key]['total_old_sum'];

            if(in_array('gifts', $this->model->showTables())){

                $this->cart['gifts'] = $this->model->get('gifts', [
                    'where' => ['<=price' => $this->cart['total_sum']],
                    'order' => 'price DESC',
                    'limit' => 1,
                    'single' => true
                ]);
            }


        }

        return $this->cart;

    }

    protected function updateCart(){

        $cart = &$this->getCart();

        if(empty($cart[$this->model->goodsTable])){

            $this->clearCart();

            return false;

        }

        if(defined('CART') && strtolower(CART) === 'cookie'){

            setcookie('cart', Crypt::instance()->encrypt(json_encode($cart)), time()+60*60*24*CART_COOKIE_TIME, PATH);

        }

        return true;

    }

    public function clearCart(){

        unset($_SESSION['cart'], $_COOKIE['cart']);

        if(defined('CART') && strtolower(CART) === 'cookie'){

            setcookie('cart', '', 1, PATH);

        }

        $this->cart = [];

    }

    public function &getCart(){

        if(!defined('CART') || strtolower(CART) !== 'cookie'){

            if(!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

            return $_SESSION['cart'];

        }else{

            if(!isset($_COOKIE['cart'])){

                $_COOKIE['cart'] = [];

            }else{

                $_COOKIE['cart'] = is_string($_COOKIE['cart']) ? json_decode(Crypt::instance()->decrypt($_COOKIE['cart']), true) : $_COOKIE['cart'];

            }

            return $_COOKIE['cart'];

        }

    }

}