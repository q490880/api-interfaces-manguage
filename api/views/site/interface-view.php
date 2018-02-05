<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\ActiveForm;
\api\assets\AppAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<head>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody(); ?>
<div class="members-role-index" style="padding: 10px">
    <table class="table table-striped table-bordered detail-view">
        <tr>
            <td class="child_title">接口详情:</td>
        </tr>
        <tr>
            <td>
                <table class="table table-striped table-bordered detail-view">
                    <tr>
                       <td class="interface_td">接口名称：</td>
                       <td><?=$model['interface_name']?></td>
                    </tr>
                    <tr>
                        <td class="interface_td">负责人：</td>
                        <td><?=$model['create_user_name']?></td>
                    </tr>
                    <tr>
                        <td class="interface_td">Url地址：</td>
                        <td><?=$interface_url = $model->reformUrl()?></td>
                    </tr>
                    <tr>
                        <td class="interface_td">访问方式：</td>
                        <td><?=$model['method']?></td>
                    </tr>
                    <tr>
                        <td class="interface_td">接口描述：</td>
                        <td><?=$model['interface_desc']?></td>
                    </tr>
                    <tr>
                        <td class="interface_td">创建时间：</td>
                        <td><?=$model['create_at']?></td>
                    </tr>
                    <tr>
                        <td class="interface_td">最后更新时间：</td>
                        <td><?=$model['update_at']?></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table class="table table-striped table-bordered detail-view">
        <tr>
            <td class="child_title">入参:</td>
        </tr>
        <tr>
            <td>
                <?$params = $model->getParams()?>
                <table class="table table-striped table-bordered detail-view">
                    <tr>
                        <td class="interface_td">字段名称</td>
                        <td class="interface_td">字段类型</td>
                        <td class="interface_td">字段描述</td>
                    </tr>
                    <?foreach($params as $param):?>
                        <tr>
                            <td class="interface_td"><?=$model->getArrayValue($param,0)?>：</td>
                            <td class="interface_td"><?=$model->getArrayValue($param,1)?></td>
                            <td class="interface_td"><?=$model->getArrayValue($param,2)?></td>
                        </tr>
                    <?endforeach?>
                </table>
            </td>
        </tr>
    </table>
    <table class="table table-striped table-bordered detail-view">
        <tr>
            <td class="child_title">返回值:</td>
        </tr>
        <tr>
            <td>
                <table class="table table-striped table-bordered detail-view">
                    <tr>
                        <td class="interface_td">字段名称</td>
                        <td class="interface_td">字段类型</td>
                        <td class="interface_td">字段描述</td>
                    </tr>
                    <?foreach($model->getResult() as $result):?>
                    <tr>
                        <td class="interface_td"><?=$model->getArrayValue($result,0)?></td>
                        <td class="interface_td"><?=$model->getArrayValue($result,1)?></td>
                        <td class="interface_td"><?=$model->getArrayValue($result,2)?></td>
                    </tr>
                    <?endforeach?>
                </table>
            </td>
        </tr>
    </table>
    <table class="table table-striped table-bordered detail-view">
        <tr>
            <td class="child_title">调试:</td>
        </tr>
        <tr>
            <td>
                <table class="table table-striped table-bordered detail-view">
                    <tr>
                        <td class="interface_td">Url地址：</td>
                        <td><?=Html::textInput('url',$interface_url,['class'=>'interface-input','id'=>'url'])?></td>
                    </tr>
                    <tr>
                        <td class="interface_td">访问方式：</td>
                        <td><?=Html::dropDownList('method',$model['method'],['GET'=>'GET','POST'=>'POST','PUT'=>'PUT','DELETE'=>'DELETE','OPTIONS'=>'OPTIONS'],['id'=>'method'])?></td>
                    </tr>
                    <?foreach($params as $param):?>
                        <tr>
                            <td class="interface_td"><?=$model->getArrayValue($param,2)?>：</td>
                            <td><?=Html::textInput($model->getArrayValue($param,0),$model->getArrayValue($param,3),['class'=>'interface-input params-field'])?></td>
                        </tr>
                    <?endforeach?>
                    <tr>
                        <td class="interface_td">返回值：</td>
                        <td><?=Html::textarea('result','',['class'=>'interface-input','rows'=>5,'id'=>'result'])?></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: center">
                            <button name="提交" id="submitApi">提交</button>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>
<?php $this->endBody() ?>
<script>
    $('#submitApi').click(function(){
        var url = $('#url').val();
        var method = $('#method').val();
        var params = $('.params-field');
        var post_data = {};
        $.each(params,function(i){
            post_data[params[i].name] = params[i].value
        });
        $.ajax({
            url:url,
            type:method,
            data:post_data,
            crossDomain:true,
            success:function(data){
                layer.msg('请求成功', {icon: 1});
                data = JSON.stringify(data)
                $('#result').val(data)
            },
            error:function(data){
                layer.msg('请求失败', {icon: 2});
            },
            beforeSend:function(data){
                layer.msg('执行中', {
                    icon: 16
                    ,shade: 0.01
                });
            }
        })
    });
</script>
</body>
</html>
<?php $this->endPage() ?>
