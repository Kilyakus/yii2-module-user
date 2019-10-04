<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use bin\rbac\models\Agreements;
?>
<?php $form = ActiveForm::begin([
    'id' => 'registration-form',
    'method' => 'POST', 
    // 'options' => ['enctype' => 'multipart/form-data'],
    'action' => ['/user/registration/register'],
    'enableAjaxValidation' => true,
    'enableClientValidation' => false,
]); ?>
    <?php $permissions=[];foreach (Yii::$app->authManager->getPermissions() as $permission => $data) {$permissions[$permission] = $data->description;} ?>
    <?php $form->field($model, 'role')->radioList($permissions,['class' => 'row','item' => function ($index, $label, $name, $checked, $value) {
        $check = $checked ? ' checked="checked"' : '';
        return '<div class="col-xs-6"><label class="btn btn-block img-rounded border border-light pt-15 pb-15" for="role-'.$value.'"><img src="/bin/media/img/role/'.$value.'.png"><input id="role-'.$value.'" type="radio" name="'.$name.'" value="'.$value.'"'.$check.' class="hidden"> '.$label.'</label></div>';
    },]); ?>

    <?= $form->field($model, 'email', [
    // 'options' => ['class' => 'form-group input-group col-lg-12'],
    // 'template' => '{label}{input}',
    // 'labelOptions' => ['class' => 'control-label input-group-addon','style' => 'width:30%;text-align:left;'],
    // 'inputOptions' => ['autofocus' => 'autofocus', 'class' => 'form-control', 'tabindex' => '1'],
    ])->input('text',[
        'class' => 'form-control input-lg', 
        'tabindex' => '1',
        // 'placeholder' => Yii::t('easyii','Enter your') . ' ' . Yii::t('user','Email'),
    ]); ?>

    <?= $form->field($model, 'username', [
    // 'options' => ['class' => 'form-group input-group col-lg-12'],
    // 'template' => '{label}{input}',
    // 'labelOptions' => ['class' => 'control-label input-group-addon','style' => 'width:30%;text-align:left;'],
    // 'inputOptions' => ['autofocus' => 'autofocus', 'class' => 'form-control', 'tabindex' => '1'],
    ])->input('text',[
        'class' => 'form-control input-lg',
        'tabindex' => '2',
        // 'placeholder' => Yii::t('easyii','Enter your') . ' ' . Yii::t('user','Name'),
    ]); ?>

    <?php if ($module->enableGeneratingPassword == false): ?>
        <?= $form->field($model, 'password', [
            'template' => '{label}<div class="v-align position-relative">{input}<i class="v-align position-absolute inside-right h-12 pr-15 pl-15 glyphicon glyphicon-eye-close cursor-pointer eye__btn cursor_pointer" data-show-password="true"></i></div>',
            'inputOptions' => [
                'class' => 'form-control input-lg',
                'tabindex' => '3',
                'placeholder' => Yii::t('easyii','Enter your') . ' ' . Yii::t('user','Password'),
            ],
        ])->passwordInput(); ?>
    <?php endif ?>

    <?php if(!empty(Agreements::get('user-agreement')) && !empty(Agreements::get('politics')) && !empty(Agreements::get('rules'))) : ?>
        <?= $form->field($model, 'agreement',[
            'template' => '<div class="col-xs-12"><label class="switcher pull-left" for="agreement">{input}<div><svg viewBox="0 0 44 44"><path d="m 15 24 l 7 7 l 24 -32 c -50 0 1 0 -50 0 c 0 50 0 0 0 50 c 50 0 0 0 50 0 c 0 0 0 -50 0 -50" transform="translate(-2.000000, -2.000000)"></path></svg></div>' .
                    '<span>Регистрируясь на сайте, я принимаю условия ' .
'<u>' . Html::a(Agreements::get('user-agreement')['title'],Agreements::get('user-agreement')['description'] ? Url::toRoute(['/agreements/' . Agreements::get('user-agreement')['name']]) : Agreements::get('user-agreement')['file'],['target' => '_blank']) . '</u>, '.
'<u>' . Html::a(Agreements::get('personal-data')['title'],Agreements::get('personal-data')['description'] ? Url::toRoute(['/agreements/' . Agreements::get('personal-data')['name']]) : Agreements::get('personal-data')['file'],['target' => '_blank']) . '</u>, ' .
'даю ' . 
'<u>' . Html::a(Agreements::get('politics')['title'],Agreements::get('politics')['description'] ? Url::toRoute(['/agreements/' . Agreements::get('politics')['name']]) : Agreements::get('politics')['file'],['target' => '_blank']) . '</u>, ' .
' и обязуюсь соблюдать ' . 
'<u>' . Html::a(Agreements::get('rules')['title'],Agreements::get('rules')['description'] ? Url::toRoute(['/agreements/' . Agreements::get('rules')['name']]) : Agreements::get('rules')['file'],['target' => '_blank']) . '</u>.' . 
                    '</span></label><div class="help-block"></div></div>',
            'options' => ['class' => 'row mb-10']
        ])->input('checkbox',['id' => 'agreement'])->label(false) ?>
    <?php else: ?>
        <?= $form->field($model, 'agreement',[
            'template' => '<div class="col-xs-12"><label class="switcher pull-left" for="agreement">{input}<div class="position-relative"><svg viewBox="0 0 44 44"><path d="m 15 24 l 7 7 l 24 -32 c -50 0 1 0 -50 0 c 0 50 0 0 0 50 c 50 0 0 0 50 0 c 0 0 0 -50 0 -50" transform="translate(-2.000000, -2.000000)"></path></svg></div>' .
                    '<span>Согласен на обработку персональных данных</span></label><div class="help-block"></div></div>',
            'options' => ['class' => 'row mb-10']
        ])->input('checkbox',['id' => 'agreement'])->label(false) ?>
    <?php endif; ?>

    <div class="row h-align-lg">
        <div class="col-lg-7 col-sm-5 col-xs-12">
            <?= Html::submitButton(Yii::t('user', 'Sign up'), ['class' => 'btn btn-lg btn-primary btn-block']) ?>
        </div>
        <div class="col-lg-5 col-sm-7 col-xs-12 h-align-xs pt-xs-20">
            <?= $this->render('@app/views/layouts/social-networks') ?>
        </div>
    </div>

<?php ActiveForm::end(); ?>


<?php $js = <<< JS
$(document).ready(function(){
    var eyeBtn = $('.eye__btn'),
        EyeBtnClose = 'glyphicon-eye-close',
        EyeBtnOpen = 'glyphicon-eye-open',
        passwordInput = $('#register-form-password');

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