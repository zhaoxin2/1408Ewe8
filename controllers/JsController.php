<?php
namespace app\controllers;
//js公共控制器
class JsController extends \yii\web\Controller{
    //得到当前登录人的姓名
    public $enableCsrfValidation = false;
    public function actionUser(){
        $session = \Yii::$app->session;
        $session->open();
        return $session->get('name');
    }
}