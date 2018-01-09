<?php

namespace app\models;

use app\core\ApiActiveRecord;
use Elasticsearch\Common\Exceptions\Unauthorized401Exception;
use Yii;
use yii\db\Exception;
use yii\web\UnauthorizedHttpException;

/**
 * This is the model class for table "{{%project}}".
 *
 * @property integer $project_id
 * @property string $project_name
 * @property string $project_desc
 * @property string $create_at
 */
class Project extends ApiActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%project}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['project_name','project_url'], 'required'],
            [['create_at'], 'safe'],
            [['project_name', 'project_desc','project_url'], 'string', 'max' => 255],
        ];
    }
    public function beforeSave($insert){
        if($insert){
            $this->create_user_id = Yii::$app->user->id;
        }
        return parent::beforeSave($insert);
    }
    public function save($runValidation = true, $attributeNames = null){
        $dbTrans = Yii::$app->db->beginTransaction();
        try{
            if($this->isNewRecord){
                parent::save();
                $userProjectModel = new UserProject();
                $userProjectModel->user_id = Yii::$app->user->id;
                $userProjectModel->project_id = $this->project_id;
                $userProjectModel->role = 1;
                $userProjectModel->save();
            }else{
                parent::save();
            }
            $dbTrans->commit();
            return true;
        }catch (Exception $e){
            $dbTrans->rollBack();
            return false;
        }
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'project_id' => '项目ID',
            'project_name' => '项目名称',
            'project_desc' => '项目描述',
            'project_url'=>'项目地址',
            'create_at' => '创建时间',
        ];
    }
    /*
     * 获取项目
     * */
    public function getProject(){
        $project_id = UserProject::find()->where(['user_id'=>Yii::$app->user->id])->select('project_id')->asArray()->all();
        if(empty($project_id)){
            return [];
        }
        $project_id = array_column($project_id,'project_id');
        $projectModel = self::find()->where(['project_id'=>$project_id])->select('project_id,project_name,project_desc,project_url')->asArray()->all();
        if(empty($projectModel)){
            return [];
        }
        foreach($projectModel as $k=>$project){
            $projectModel[$k]['version'] = ProjectVersion::find()->where(['project_id'=>$project['project_id']])->select('version_id,version,create_at')->asArray()->all();
        }
        return $projectModel;
    }
    /*
     * 获取接口
     * */
    public function getInterfaceByVersionID($version_id){
        $versionModel = ProjectVersion::find()->where(['version_id'=>$version_id])->select('version_id,version_desc')->asArray()->one();
        if(empty($versionModel)){
            return [];
        }
        $pageModel = ProjectPage::find()->where(['version_id'=>$versionModel['version_id']])->select('page_id,page_name,page_desc')->orderBy('sort desc,page_id asc')->asArray()->all();
        if(empty($pageModel)){
            return $versionModel;
        }
        foreach($pageModel as $k=>$page){
            $pageModel[$k]['interface'] = ProjectVersionInterface::find()->where(['page_id'=>$page['page_id'],'status'=>1])->select('interface_id,interface_name')->asArray()->all();
        }
        $versionModel['page'] = $pageModel;
        return $versionModel;
    }
    /*
     * 验证用户是否有管理该项目成员的权限
     * */
    public function verifyMemberManage(){
        if($this->create_user_id != Yii::$app->user->id){
            throw new UnauthorizedHttpException("您没有管理该项目成员的权限");
        }
    }
}
