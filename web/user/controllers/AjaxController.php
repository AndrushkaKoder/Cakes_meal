<<<<<<< HEAD
<?php

namespace web\user\controllers;

class AjaxController extends \core\system\Controller
{

    protected function actionInput(){

        $this->skipRenderingTemplates = true;

        if(empty($_COOKIE['visitor']) || empty($_GET)){

            return['success' => 0, 'message' => 'Не балуйтесь'];

        }

        $method = 'add';

        foreach (['add', 'edit', 'delete'] as $item){

            if(!empty($_GET[$item])){

                $method = $item;

                break;

            }

        }

        $_GET['comment'] = \AppH::clearStr($_GET['comment'] ?? '');

        if(empty($_GET['comment'])){

            if($method !== 'delete'){

                return['success' => 0, 'message' => 'Заполните текст комментария'];

            }

        }

        $method .= 'Comment';

        return \web\user\models\Model::instance()->$method();

    }

=======
<?php

namespace web\user\controllers;

class AjaxController extends BaseUser
{
    protected $ajaxData = [];

    protected function actionInput(){

        $this->skipRenderingTemplates = true;

        if(!empty($_REQUEST)){
            $this->ajaxData = $_REQUEST;
        }

        if(!empty($_GET['ajax']) && $_GET['ajax'] === 'add_to_cart'){

            return $this->addToCart();
        }

    }

>>>>>>> 2e2162608b52d77abe9c5daf01b432e99b9bf943
}