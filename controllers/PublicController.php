<?php
namespace app\controllers;
class PublicController extends CoController
{
    public $enableCsrfValidation = false;
    public function actionAdd(){
            return $this->renderPartial("add");
    }
    public function actionAddall(){
        header('content-type:text/html;charset=utf8');
       /*
            Array
        (
            [publicname] => 对对对
            [publicid] => 啊啊啊
            [publicselect] => 顶顶顶顶顶
        )
        */
        $session = \Yii::$app->session;
        $session->open();
        $id=$session->get('id');
        $publicName = \Yii::$app->request->post('publicName');
        $publicId = \Yii::$app->request->post('publicId');
        $publicSelect = \Yii::$app->request->post('publicSelect');
        $connection = \Yii::$app->db;
        $command = $connection->createCommand("SELECT p_id FROM we_public WHERE p_name='$publicName'");
        $post = $command->queryOne();
        if($post){
            echo "该公众号已经存在";
            die;
        }
        //生成tokey
        $tokey=$this->actionTokey();
        //生成url参数值
        $urlget=$this->actionUget();
        //生成微信通信页面
        $url=substr('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],0,strpos('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],'?'))."?r=weixin/url&st=".$urlget;
        $connection->createCommand()->update('we_public', ['p_state' => 0], 'p_state = 1')->execute();
        $state=$connection->createCommand()->insert('we_public', [
            'p_name' => $publicName,
            'p_type'=>'微信公众号',
            'p_AppID' =>$publicId,
            'p_AppSecret'=>$publicSelect,
            'u_id'=>$id,
            'p_state'=>1,
            'p_token'=>$tokey,
            'p_url'=> $url,
            'p_urlget'=> $urlget
        ])->execute();
        if($state){
            $this->redirect(array('/public/addselect','id'=>\Yii::$app->db->getLastInsertID()));
        }
    }
    //生成随机tokey
    protected function actionTokey($pw_length=8){
        $randpwd = '';
        for ($i = 0; $i < $pw_length; $i++)
        {
            $randpwd .= chr(mt_rand(33, 126));
        }
        return $randpwd;
    }
    public function actionList(){
        return $this->renderPartial('list');
    }
    //生成URl随机给值
    public function actionUget($len=50, $chars=null){
        if (is_null($chars)){
            $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        }
        mt_srand(10000000*(double)microtime());
        for ($i = 0, $str = '', $lc = strlen($chars)-1; $i < $len; $i++){
            $str .= $chars[mt_rand(0, $lc)];
        }
        return $str;
    }
    public function actionAddselect(){
        $id =$_GET['id'];
        if($id==''){
            return false;
        }
        $connection = \Yii::$app->db;
        $command = $connection->createCommand("SELECT * FROM we_public WHERE p_id=$id");
        $post = $command->queryOne();
        if(!$post){
            return false;
        }
        return $this->renderPartial("addselect");
    }
}
