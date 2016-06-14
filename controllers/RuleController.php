<?php

namespace app\controllers;

class RuleController extends CoController
{
    public function actionAdd(){
        return $this->renderPartial("add");
    }

    public function actionList(){
        return $this->renderPartial('list');
    }

}
