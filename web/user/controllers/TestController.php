<?php

namespace webQApplication\controllers;

class TestController extends BaseUser
{
    protected function actionInput(){

        \WqH::clearStr('fsdfsdfs');
        \WqH::clearStr('pdfsd');
    }
}