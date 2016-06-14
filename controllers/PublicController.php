<?php
namespace app\controllers;
class PublicController extends CoController
{
    public $enableCsrfValidation = false;
    public function actionAdd(){
            return $this->renderPartial("add");
    }
    public function actionAddall(){
       /*
            Array
        (
            [publicname] => 对对对
            [publicid] => 啊啊啊
            [publicselect] => 顶顶顶顶顶
        )
        */
        $publicName = \Yii::$app->request->post('publicName');
        $publicId = \Yii::$app->request->post('publicId');
        $publicSelect = \Yii::$app->request->post('publicSelect');

    }
    public function actionList(){
        return $this->renderPartial('list');
    }

}
