<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var dektrium\user\models\User $model
 * @var dektrium\user\models\Account $account
 */

$this->title = Yii::t('user', 'Sign up');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
    </div>
    <div class="panel-body">
        <div class="alert alert-info">
            <p>
                <?= Yii::t(
                    'user',
                    'In order to finish your registration, we need you to enter following fields'
                ) ?>:
            </p>
        </div>
        <?php $form = ActiveForm::begin([
            'id' => 'connect-account-form',
            'enableAjaxValidation' => false,
            'enableClientValidation' => false
        ]); ?>

        <?= $form->field($model, 'email')->textInput([
            'class' => 'form-control input-lg',
            'placeholder' => Yii::t('easyii','Enter your') . ' ' . Yii::t('user','Email'),
        ]) ?>

        <?= $form->field($model, 'username')->textInput([
            'class' => 'form-control input-lg',
            'placeholder' => Yii::t('easyii','Enter your') . ' ' . Yii::t('user','Login'),
        ]) ?>

        <?= $form->field($model, 'password', [
            'template' => '{label}<div class="v-align position-relative">{input}<i class="v-align position-absolute inside-right h-12 pr-15 pl-15 glyphicon glyphicon-eye-close cursor-pointer eye__btn cursor_pointer" data-show-password="true"></i></div>',
            'inputOptions' => [
                'class' => 'form-control input-lg',
                'tabindex' => '3',
                'placeholder' => Yii::t('easyii','Enter your') . ' ' . Yii::t('user','Password'),
            ],
        ])->passwordInput(); ?>

        <?= Html::submitButton(Yii::t('user', 'Continue'), ['class' => 'btn btn-lg btn-primary btn-block']) ?>

        <?php ActiveForm::end(); ?>
    </div>
</div>
<p class="text-center">
    <?= Html::a(
        Yii::t(
            'user',
            'If you already registered, sign in and connect this account on settings page'
        ),
        ['/user/settings/networks']
    ) ?>.
</p>

<?php $js = <<< JS
$(document).ready(function(){
    var eyeBtn = $('.eye__btn'),
        EyeBtnClose = 'glyphicon-eye-close',
        EyeBtnOpen = 'glyphicon-eye-open',
        passwordInput = $('#user-password');

    eyeBtn.on('click', function() {
        if($(this).hasClass(EyeBtnClose)){
            $(this).removeClass(EyeBtnClose).addClass(EyeBtnOpen);
            passwordInput.attr('type', 'text');
        } else {
            $(this).removeClass(EyeBtnOpen).addClass(EyeBtnClose);
            passwordInput.attr('type', 'password');
        }
    })
})
JS;
$this->registerJs($js, yii\web\View::POS_END); ?>