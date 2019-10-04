<?php
use bin\admin\components\API;
use yii\helpers\Url;
use yii\helpers\Html;
use kilyakus\module\user\helpers\Timezone;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Tabs;

use \kilyakus\web\widgets as Widget;

$this->title = Yii::t('user', 'Profile settings');
$this->params['breadcrumbs'][] = ['label' => Yii::t('user','Profile'), 'url' => Url::toRoute(['/user/profile/index'])];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $this->render('/_alert', ['module' => Yii::$app->getModule('user')]) ?>

<style>
.form-control {border-color:#E7E9EB;}
.input-lg {height:48px;}
.cropper-view-box,
.cropper-face.cropper-move {border-radius:100%;}
.cropper-view-box {outline:none;outline-color:transparent;}
.cropper-line {background-color:transparent;}
.cropper-dashed.dashed-h,
.cropper-dashed.dashed-v {border:0;}
.cropper-point.point-ne,
.cropper-point.point-nw,
.cropper-point.point-sw,
.cropper-point.point-se {display:none;}
.cropper-point {background-color:#FFF;}
</style>
<div class="container">
    <div class="card mb-25">
        <div class="row">
            <div class="col-md-3">
                <?php Pjax::begin([
                    'enablePushState' => false,
                    'options' => ['class' => 'p-25']
                ]);?>
                <?php $this->registerJs("$(document).ready(function(){var el = $('#profile-avatar-css');var st = el.get(0).style;st.height = el.css('width');st.borderRadius = '100%';})", yii\web\View::POS_END); ?>
                    <?php $form = ActiveForm::begin([
                        'options' => ['enctype' => 'multipart/form-data', 'data-pjax' => true, 'data-pjax-problem' => true, 'class' => 'model-form', 'type' => 'cropper'],
                        'enableAjaxValidation' => true,
                        'enableClientValidation' => false,
                        'validateOnBlur' => true,
                    ]); ?>
                        <?= $form->field($profile, 'avatar')->widget(Widget\Cutter::className(), [
                            'cropperOptions' => [
                                'aspectRatio' => '4/4',
                                'aspectRatioHidden' => true,
                                'positionsHidden' => true,
                                'sizeHidden' => true,
                                'rotateHidden' => true
                            ]
                        ])->label(false) ?>
                    <?php ActiveForm::end(); ?>
                <?php Pjax::end(); ?>
            </div>
            <div class="col-md-9">
                <?= $this->render('edit/form_profile',compact('user','profile','account')) ?>

                <?= Tabs::widget(['options' => ['class' => 'nav-tabs',],
                    'items' => [
                        [
                            'label'   => Yii::t('easyii', 'Change password'),
                            'content' => $this->render('edit/form_account',compact('account')),
                        ],
                    ],
                ]) ?>
            </div>
        </div>
    </div>
</div>