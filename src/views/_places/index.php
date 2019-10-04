<?php
use kartik\tabs\TabsX;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

$this->title = Yii::t('user','Friends');
$this->params['breadcrumbs'][] = ['url' => Url::toRoute(['/user/profile/show','id' => $profile->user->id]), 'label' => Yii::t('user','Profile')];
$this->params['breadcrumbs'][] = $this->title;

$items = [
    [
        'label' => Yii::t('user','Мои места'),
        'content' => $this->render('_provider',compact('dataProvider')),
        'active'=>true,
        'linkOptions' => ['data-url' => Url::toRoute(['/user/places/my','tab' => 1])]
    ],
    [
        'label' => Yii::t('user','Сохраненные фильтры'),
        'linkOptions' => ['data-url' => Url::toRoute(['/user/places/filters','tab' => 2])]
    ],
];
?>
<style>
.panel-default .tab-content {padding:0px;}
.panel-default .tab-content .table > tbody > tr:first-child > td {border-top:0px;}
</style>
<?php $this->beginContent('@bin/user/views/layouts/main.php') ?>
    <div class="panel panel-default border">
        <?= TabsX::widget([
            'items'=>$items,
            'position'=>TabsX::POS_ABOVE,
            'encodeLabels'=>false,
            'pluginOptions' => [
                'enableCache' => false
            ]
        ]); ?>
    </div>
<?php $this->endContent() ?>