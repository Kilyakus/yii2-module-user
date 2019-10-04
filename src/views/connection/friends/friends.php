<?php
use kartik\tabs\TabsX;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use bin\admin\modules\chat\api\Chat;

$this->title = Yii::t('user','Friends');
$this->params['breadcrumbs'][] = ['url' => Url::toRoute(['/user/profile/show','id' => $profile->user->id]), 'label' => Yii::t('user','Profile')];
$this->params['breadcrumbs'][] = $this->title;

$items = [
    [
        'label' => Yii::t('user','All friends'),
        'content'=>$this->render('_provider',compact('dataProvider','searchModel','message')),
        'active'=>true,
        'linkOptions' => ['data-url' => Url::toRoute(['/user/connection/friends-all','tab' => 1])]
    ],
    [
        'label' => Yii::t('user','Friends online'),
        'linkOptions' => ['data-url' => Url::toRoute(['/user/connection/friends-online','tab' => 2])]
    ],
    [
        'label' => Yii::t('user','Friend requests') . ' <span class="badge">' . count(Chat::friendsRequests(Yii::$app->user->id)['dataProvider']->query->all()) . '</span>',
        'linkOptions' => ['data-url' => Url::toRoute(['/user/connection/friends-requests','tab' => 3])]
    ],
];
?>
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