<?php
namespace app\controllers;
use Yii;
//核心控制器
class LoginController extends \yii\web\Controller
{
    public $layout='';
    public $enableCsrfValidation = false;
    public function actionIndex()
    {
        $session = \Yii::$app->session;
        $session->open();
        $name = $session->get('name')?$session->get('name'):'';
        $pwd = $session->get('pwd')?$session->get('pwd'):'';
        if($name && $pwd){
            return $this->renderPartial('login',['name'=>$name,'pwd'=>$pwd]);
        }else{
            return $this->renderPartial('login');
        }
    }
    //展示登录页面
    public function actionLogin(){
        $session = \Yii::$app->session;
        $session->open();
        if($session->get('id')==''){
            echo "<script>alert('请先登录');location.href='index.php?r=login/index'</script>";
        }else{
            return $this->renderPartial('index');
        }
    }
    public function actionForget(){
        echo "忘记密码";die;
    }
    //注册
    public function actionRegister(){
        $request=Yii::$app->request;
        $u_name=$request->post('u_name');
        $u_pwd=$request->post('u_pwd');
        $u_email=$request->post('u_email');
        $re=Yii::$app->db->createCommand()->insert('we_user',['u_name'=>"$u_name",'u_pwd'=>"$u_pwd",'u_email'=>"$u_email"])->execute();
        if($re){
            return $this->redirectPartial(['computer/index']);
        }else{
            return $this->redirectPartial(['computer/register']);
        }
    }
    /*用于登录验证*/
    public function actionProving(){
        $username = \Yii::$app->request->post('username');
        $pwd = \Yii::$app->request->post('pwd');
        $remember = \Yii::$app->request->post('remember');
        $connection = \Yii::$app->db->createCommand("SELECT * FROM we_user WHERE u_name='$username' and u_pwd='$pwd' limit 1");
        $titles = $connection->queryAll();
        if($titles){
            $session = \Yii::$app->session;
            $session->open();
            $session->set('id', $titles[0]['u_id']);
            $session->set('name', $titles[0]['u_name']);
            if($remember){
                $session->set('pwd', $titles[0]['u_pwd']);
                echo true;
            }
        }else{
            echo false;
        }
    }
}