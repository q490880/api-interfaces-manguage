<?php

/* @var $this yii\web\View */

$this->title = '首页';
$this->registerJs("
    $('.interface_x').on('click', function(){layer.open({type: 2,title:'接口详情', fix:false,shadeClose:true,maxmin:true,area: ['900px', '800px'],skin: 'layui-layer-rim', content: ['". \yii\helpers\Url::to(['interface-view'])."&id='+$(this).attr('id'), 'yes'] });});
")
?>
<div class="site-index">

    <?foreach($projectList as $project):?>
    <table class="table table-striped table-bordered detail-view">
        <tr>
            <td class="child_title">项目名称:<?=$project['project_name']?> 项目地址:<?=\yii\bootstrap\Html::a($project['project_url'],$project['project_url'],['class'=>'create_interface','target'=>'_bank'])?></td>
        </tr>
        <tr>
            <td>
                <?foreach($project['version'] as $version):?>
                <table class="table table-striped table-bordered detail-view">
                    <tr>
                        <td class="interface_td">版本号:</td>
                        <td><?=\yii\helpers\Html::a($version['version'],\yii\helpers\Url::to(['interface','version_id'=>$version['version_id']]))?></td>
                    </tr>
                    <tr>
                        <td class="interface_td">创建时间:</td>
                        <td><?=$version['create_at']?></td>
                    </tr>
                </table>
                <?endforeach?>
            </td>
        </tr>
    </table>
    <?endforeach?>
</div>
