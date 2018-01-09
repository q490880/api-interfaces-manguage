<?php

/* @var $this yii\web\View */

$this->title = '接口列表页';
$this->registerJs("
    $('.interface_x').on('click', function(){layer.open({type: 2,title:'接口详情', fix:false,shadeClose:true,maxmin:true,area: ['900px', '800px'],skin: 'layui-layer-rim', content: ['". \yii\helpers\Url::to(['interface-view'])."&id='+$(this).attr('id'), 'yes'] });});
")
?>
<div class="site-index">
    <?if(isset($interfaceList['page'])):?>
<!--    <table class="table table-striped table-bordered detail-view">-->
<!--        <tr>-->
<!--            <td class="child_title">按页面名称检索</td>-->
<!--        </tr>-->
<!--        <tr>-->
<!--            <td>-->
<!--                <table class="table table-striped table-bordered detail-view">-->
<!--                    <tr>-->
<!--                        --><?//foreach($interfaceList['page'] as $k=>$page):?>
<!--                            <td class="interface_td">--><?//=\yii\helpers\Html::a($page['page_name'],"#{$page['page_id']}")?><!--</td>-->
<!--                            --><?//$k++;
//                            if($k % 6 == 0){
//                                echo '</tr><tr>';
//                            }
//                        endforeach?>
<!--                    </tr>-->
<!--                </table>-->
<!--            </td>-->
<!--        </tr>-->
<!--    </table>-->
        <table class="table table-striped table-bordered detail-view">
            <tr>
                <td class="child_title">版本信息</td>
            </tr>
            <tr>
                <td>
                    <?=$interfaceList['version_desc']?>
                </td>
            </tr>
        </table>
    <?foreach($interfaceList['page'] as $page):?>
        <table class="table table-striped table-bordered detail-view">
            <tr>
                <td class="child_title" id="<?=$page['page_id']?>">页面名称：<?=$page['page_name']?></td>
            </tr>
            <tr>
                <td>
                    <table class="table-striped table-bordered detail-view interface-list">
                        <tr>
                            <?foreach($page['interface'] as $k=>$interface):?>
                                <td><?=\yii\helpers\Html::a($interface['interface_name'],'javascript:',['class'=>'interface_x','id'=>$interface['interface_id']])?></td>
                                <?$k++;
                                if($k % 6 == 0){
                                    echo '</tr><tr>';
                                }
                            endforeach?>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    <?endforeach?>
    <?endif?>
</div>
