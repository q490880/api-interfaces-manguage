<?php
namespace api\controllers;

use api\core\ApiController;
use app\models\LoginForm;
use app\models\Project;
use app\models\ProjectVersionInterface;
use app\models\SignupForm;
use Yii;
use yii\base\InvalidParamException;
use yii\bootstrap\Html;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\User;

class SiteController extends ApiController
{
    public function init(){
        $this->guestActions = ['login','signup'];
        $this->loginActions = ['index','interface','logout','interface-view'];
        return parent::init();
    }

    /*
     * 首页
     * */
    public function actionIndex()
    {
        $projectModel = new Project();
        if($projectList = $projectModel->getProject()){
            return $this->render('index',[
                'projectList'=>$projectList
            ]);
        }else{
            return $this->render('empty',[
                'message'=>'您还没有任何项目。['.Html::a('新建项目',['project/create']).']'
            ]);
        }
    }

    /*
     * 接口列表页
     * */
    public function actionInterface($version_id){
        $projectModel = new Project();
        $interfaceList = $projectModel->getInterfaceByVersionID($version_id);
        if(isset($interfaceList['page'])){
            return $this->render('interface',[
                'interfaceList'=>$interfaceList
            ]);
        }else{
            return $this->render('empty',[
                'message'=>'该版本下还没有任何页面和接口。['.Html::a('返回首页',['/']).']'
            ]);
        }
    }

    //登陆
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goHome();
        }
        $this->layout = false;
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    //注册
    public function actionSignup(){
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            return $this->goHome();
        }
        $this->layout = false;
        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return string
     * 退出
     */
    public function actionLogout(){
        Yii::$app->user->logout();
        return $this->goHome();
    }

    /*
     * 接口详情页
     * */
    public function actionInterfaceView($id){
        $this->layout = false;
        $model = ProjectVersionInterface::findOne($id);
        return $this->render('interface-view',[
            'model'=>$model
        ]);
    }

}
