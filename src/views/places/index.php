<?php
use kartik\tabs\TabsX;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

$this->title = Yii::t('easyii/catalog','Мои места');
$this->params['breadcrumbs'][] = ['url' => Url::toRoute(['/user/profile/show','id' => $profile->user->id]), 'label' => Yii::t('user','Profile')];
$this->params['breadcrumbs'][] = $this->title;

$items = [
    [
        'label' => Yii::t('user','Мои места'),
        'content' => $this->render('_provider',compact('items')),
        'active' => true,
        // 'linkOptions' => ['data-url' => Url::toRoute(['/user/places/tab','tab' => 1])]
    ],
    [
        'label' => Yii::t('user','Мой архив'),
        // 'content' => $this->render('_provider',compact('items')),
        'active' => false,
        'linkOptions' => ['data-url' => Url::toRoute(['/user/places/tab','tab' => 3])]
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
            'items' => $items,
            'position' => TabsX::POS_ABOVE,
            'encodeLabels' => false,
            'pluginOptions' => [
                'enableCache' => true
            ]
        ]); ?>
    </div>
<?php $this->endContent() ?>