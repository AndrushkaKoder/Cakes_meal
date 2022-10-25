<?php

namespace web\user\controllers;

class TestController extends BaseUser
{
    protected function actionInput(){

        \AppH::clearStr('fsdfsdfs');
        \AppH::clearStr('pdfsd');
    }
}