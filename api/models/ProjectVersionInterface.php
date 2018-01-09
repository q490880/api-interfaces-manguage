<?php

namespace app\models;

use app\core\ApiActiveRecord;
use Yii;

/**
 * This is the model class for table "{{%project_version_interface}}".
 *
 * @property integer $interface_id
 * @property integer $page_id
 * @property string $interface_name
 * @property string $interface_url
 * @property string $interface_desc
 * @property string $method
 * @property string $param
 * @property string $result
 */
class ProjectVersionInterface extends ApiActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%project_version_interface}}';
    }
    public function beforeSave($insert){
        if($insert){
            $this->create_at = date('Y-m-d H:i:s');
            $this->create_user_id = Yii::$app->user->id;
            $this->create_user_name = Yii::$app->user->identity->username;
        }
        return parent::beforeSave($insert);
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['page_id', 'interface_name', 'interface_url','status'], 'required'],
            [['page_id','create_user_id','project_id','status'], 'integer'],
            [['param', 'result','create_at','update_at','create_user_name'], 'string'],
            [['interface_name', 'interface_url','interface_desc'], 'string', 'max' => 255],
            [['method'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'interface_id' => 'Interface ID',
            'page_id' => 'Page ID',
            'interface_name' => '接口名称',
            'interface_url' => '接口访问地址',
            'interface_desc' => '接口描述',
            'method' => '提交方法',
            'param' => '传入参数',
            'result' => '返回结果',
            'create_at' => '生成时间',
            'update_at' => '修改时间',
            'status' => '状态'
        ];
    }
    /*
     * 获取数组的内容
     * */
    public function getArrayValue($array,$key){
        if(isset($array[$key])){
            return $array[$key];
        }
    }
    /*
     * 拼凑完整URL
     * */
    public function reformUrl(){
        if(strncmp($this->interface_url,'http',4)){
            $projectModel = Project::find()->where(['project_id'=>$this->project_id])->select('project_url')->asArray()->one();
            return $projectModel['project_url'].'/'.$this->interface_url;
        }else{
            return $this->interface_url;
        }
    }
    /*
     * 获取入参
     * */
    public function getParams(){
        if(empty($this->param)){
            return [];
        }
        if(strstr($this->param,"\r\n")){
            $params = explode("\r\n",$this->param);
        }else{
            $params = [$this->param];
        }
        $resultParam = [];
        foreach($params as $param){
            $resultParam[] = explode(',',$param);
        }
        return $resultParam;
    }
    /*
     * 获取返回值
     * */
    public function getResult(){
        if(empty($this->result)){
            return [];
        }
        if(strstr($this->result,"\r\n")){
            $results = explode("\r\n",$this->result);
        }else{
            $results = [$this->result];
        }
        $resultArray = [];
        foreach($results as $result){
            $iResult = explode(',',$result);
            if(count($iResult) > 2){
                $reformResult = [];
                $reformResult[] = $iResult[0];
                $reformResult[] = $iResult[1];
                unset($iResult[0]);
                unset($iResult[1]);
                $reformResult[] = implode(",",$iResult);
                $resultArray[] = $reformResult;
            }else{
                $resultArray = $iResult;
            }
        }

        return $resultArray;
    }
}
