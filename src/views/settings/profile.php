<?php
use bin\admin\components\API;
use app\assets\ScrollbarAsset;
use yii\helpers\Url;
use yii\helpers\Html;
use kilyakus\module\user\helpers\Timezone;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use kartik\daterange\DateRangePicker;
use kartik\select2\Select2;
use bin\admin\widgets\TagsInput;

use kartik\depdrop\DepDrop;
use bin\admin\models\MapsCountry;
use bin\admin\models\MapsRegion;
use bin\admin\models\MapsCity;
use yii\web\JsExpression;

ScrollbarAsset::register($this);

$this->title = Yii::t('user', 'Profile settings');
$this->params['breadcrumbs'][] = ['label' => Yii::t('user','Profile'), 'url' => Url::toRoute(['/user/profile/show'])];
$this->params['breadcrumbs'][] = $this->title;
?>
<style type="text/css">

.selectize-input {height:55px;border:1px solid #dbe3f0;overflow-x:hidden;overflow-y:auto;}

/* .box.content input удалить это из elements.css */
.box.content input {
    margin-bottom: 0px;
}
.box.content .form-group {margin-bottom:40px;}

.btn-group .btn-lg {height:56px;overflow:hidden;border:1px solid #DBE3F0;background:#FFF;}
.btn-group label {height:100%;}
.btn-group input:checked + label {background-color:#F0F5FD!important;} 
.btn-group input {display:none;width:0px;height:0px;position:fixed;}
</style>
<?php $this->render('/_alert', ['module' => Yii::$app->getModule('user')]) ?>
<?php
$country = MapsCountry::find()->orderBy(['name_ru' => SORT_ASC])->all();
$region = MapsRegion::find()->orderBy(['name_ru' => SORT_ASC])->all();
$city = MapsCity::find()->orderBy(['name_ru' => SORT_ASC])->all();
?>

<?php Pjax::begin([
    'enablePushState' => false,
]);?>
    <?php $form = ActiveForm::begin([
        'options' => ['data-pjax' => true, 'data-pjax-problem' => true,'enctype' => 'multipart/form-data', 'class' => 'model-form'],
        'enableAjaxValidation' => true,
        'enableClientValidation' => false,

    ]); ?>
	<div class="row form-group">
		<div class="col-md-12">
			<p class="edit-title">Редактировать инфу</p>
			<p class="edit-desc">Разнообразный и богатый опыт укрепление и развитие структуры в значительной степени обуславливает создание существенных финансовых и административных условий.</p>
		</div>
	</div>
    <div class="row form-group">
        <?= $form->field($model, 'name',['options' => ['class' => 'col-xs-12 col-md-6']])->textInput(['class' => 'input-lg form-control']) ?>
        <?= $form->field($model, 'second_name',['options' => ['class' => 'col-xs-12 col-md-6']])->textInput(['class' => 'input-lg form-control']) ?>
    </div>
    <div class="row form-group">
        <?= $form->field($model, 'public_email',['options' => ['class' => 'col-xs-12 col-md-6']])->textInput(['class' => 'input-lg form-control']) ?>
        <?=  $form->field($model, 'phone',['options' => ['class' => 'col-xs-12 col-md-6']])->widget(TagsInput::className(), [
        'name' => 'phone[]',
        'value' => is_array($model->phone) ? implode(',', $model->phone) : $model->phone,
        'options' => [
            'class' => 'input-lg form-control',
            'placeholder' => Yii::t('easyii/catalog', 'Type options with `comma` as delimiter')
        ],
    ]) ?>
    </div>

    <div class="row form-group">
        <?= $form->field(new MapsCountry(), 'name_ru',['options' => ['class' => 'col-xs-12 col-md-4']])->widget(Select2::classname(), [
            'id' => 'item-country_id',
            'theme' => 'default',
            'data' => ArrayHelper::map(MapsCountry::find()->orderBy(['name_ru' => SORT_ASC])->all(), 'id', 'name_ru'),
            'options' => ['value' => json_decode($model->location)->country_id,'placeholder' => Yii::t('easyii','Search for a country ...')],
            'pluginOptions' => [
                'allowClear' => false,
                'minimumInputLength' => 3,
                'ajax' => [
                    'url' => \yii\helpers\Url::to(['/maps/country-list']),
                    'dataType' => 'json',
                    'data' => new JsExpression('function(params) { return {q:params.term}; }')
                ],
                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                'templateResult' => new JsExpression('function(city) { return city.text; }'),
                'templateSelection' => new JsExpression('function (city) { return city.text; }'),
            ],
        ])->label(Yii::t('easyii/catalog','Country')); ?>

        <?= $form->field(new MapsRegion(), 'name_ru',['options' => ['class' => 'col-xs-12 col-md-4']])->widget(DepDrop::classname(), [
            'id' => 'item-region_id',
            'data' => ArrayHelper::map(MapsRegion::find()->where(['country_id' => json_decode($model->location)->country_id])->orderBy(['name_ru' => SORT_ASC])->all(), 'id', 'name_ru'),
            'options' => ['value' => json_decode($model->location)->region_id,'placeholder' => Yii::t('easyii','Search for a state ...')],
            'type' => DepDrop::TYPE_SELECT2,
            'select2Options' => [
                'theme' => 'default',
                'pluginOptions' => [
                    'allowClear' => true,'multiple' => false,'disabled' => false,
                    // 'ajax' => [
                    //     'url' => \yii\helpers\Url::to(['/maps/region-list']),
                    //     'dataType' => 'json',
                    //     'data' => new JsExpression('function(params) { return {q:params.term}; }')
                    // ],
                    // 'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    // 'templateResult' => new JsExpression('function(city) { return city.text; }'),
                    // 'templateSelection' => new JsExpression('function (city) { return city.text; }'),
                ],
            ],
            'pluginOptions' => [
                'depends' => ['mapscountry-name_ru'],
                'url' => Url::to(['/site/get-region']),
                'loadingText' => 'Loading ...',
            ]
        ])->label(Yii::t('easyii/catalog','Region')); ?>

        <?= $form->field(new MapsCity(), 'name_ru',['options' => ['class' => 'col-xs-12 col-md-4']])->widget(DepDrop::classname(), [
            'id' => 'item-city_id',
            'data' => ArrayHelper::map(MapsCity::find()->where(['country_id' => json_decode($model->location)->country_id,'region_id' => json_decode($model->location)->region_id])->orderBy(['name_ru' => SORT_ASC])->all(), 'id', 'name_ru'),
            'options' => ['value' => json_decode($model->location)->city_id,'placeholder' => Yii::t('easyii','Search for a city ...')],
            'type' => DepDrop::TYPE_SELECT2,
            'select2Options' => [
                'theme' => 'default',
                'pluginOptions' => ['allowClear' => true,'multiple' => false,'disabled' => false]
            ],
            'pluginOptions' => [
                'depends' => ['mapsregion-name_ru'],
                'url' => Url::to(['/site/get-city']),
                'loadingText' => 'Loading ...',
            ]
        ])->label(Yii::t('easyii/catalog','City')); ?>
    </div>
    <?= $form->field($model, 'gender')->radioList([0 => Yii::t('user','male'), 1 => Yii::t('user','female')],['class' => 'w-12 btn-group','item' => function ($index, $label, $name, $checked, $value) {
        $check = $checked ? ' checked="checked"' : '';
        return '<input id="gender-'.$value.'" type="radio" name="'.$name.'" value="'.$value.'"'.$check.' class="hidden"><label class="btn btn-lg btn-default" for="gender-'.$value.'"><img src="'.\app\assets\UserAsset::register($this)->baseUrl.'/img/user/icons/icon-gender-'.$value.'.svg"> '.$label.'</label>';
    },]); ?>
    <div class="row">
        <?= $form->field($model, 'body_height',['options' => ['class' => 'col-xs-12 col-md-4']])->textInput(['class' => 'input-lg form-control']) ?>
        <?= $form->field($model, 'body_weight',['options' => ['class' => 'col-xs-12 col-md-4']])->textInput(['class' => 'input-lg form-control']) ?>
        <?php $model->birthdate = $model->birthdate ? date('d-m-Y h:i', $model->birthdate) : date('d-m-Y h:i'); ?>
<?= $form->field($model, 'birthdate',['options' => ['class' => 'col-xs-12 col-md-4']])->widget(DateRangePicker::classname(), [
    'useWithAddon'=>true,
    'convertFormat'=>true,
    'startAttribute' => 'birthdate',
    'pluginOptions'=>[
        'singleDatePicker' => true,
        'timePicker'=>true,
        'timePickerIncrement'=>15,
        'showDropdowns'=>true,
        'minYear'=> 1950,
        'locale'=>['format'=>'d-m-Y h:i']
    ],
    'presetDropdown'=>false,
    'hideInput'=>true
]); ?>
    </div>

    <?php $form->field($model, 'timezone')->widget(Select2::classname(), [
        'data'          => ArrayHelper::map(
            Timezone::getAll(),
            'timezone',
            'name'
        ),
        // 'theme'         => Select2::THEME_BOOTSTRAP,
        'showToggleAll' => false,
        'options'       => ['placeholder' => Yii::t('podium/view', 'Select your time zone for proper dates display...')],
        'pluginOptions' => ['allowClear' => true],
    ])->label(Yii::t('podium/view', 'Time Zone'))
    ->hint(Html::a(Yii::t('podium/view', 'What is my time zone?'), 'http://www.timezoneconverter.com/cgi-bin/findzone', ['target' => '_blank'])); ?>

    <?php $form->field($model, 'gravatar_email')->hint(Html::a(Yii::t('user', 'Change your avatar at Gravatar.com'), 'http://gravatar.com')) ?>

    <?= $form->field($model, 'bio')->textarea(['class' => 'form-control input-lg']) ?>
    <div class="row form-group">
        <?= $form->field($model, 'website',['options' => ['class' => 'col-xs-12 col-md-4']])
            ->textInput(['class' => 'form-control input-lg']) ?>
        <?= $form->field($model, 'messengers[skype]',['options' => ['class' => 'col-xs-12 col-md-4']])
            ->widget(TagsInput::className(), [])
            ->textInput(['value' => $model->messengers ? json_decode($model->messengers)->skype : ''])
            ->label('Skype') ?>
        <?= $form->field($model, 'messengers[icq]',['options' => ['class' => 'col-xs-12 col-md-4']])
            ->widget(TagsInput::className(), [])
            ->textInput(['value' => $model->messengers ? json_decode($model->messengers)->icq : ''])
            ->label('ICQ') ?>
        <?= $form->field($model, 'messengers[aim]',['options' => ['class' => 'col-xs-12 col-md-4']])
            ->widget(TagsInput::className(), [])
            ->textInput(['value' => $model->messengers ? json_decode($model->messengers)->aim : ''])
            ->label('AIM') ?>
        <?= $form->field($model, 'messengers[yahoo]',['options' => ['class' => 'col-xs-12 col-md-4']])
            ->widget(TagsInput::className(), [])
            ->textInput(['value' => $model->messengers ? json_decode($model->messengers)->yahoo : ''])
            ->label('Yahoo') ?>
        <?= $form->field($model, 'messengers[msn]',['options' => ['class' => 'col-xs-12 col-md-4']])
            ->widget(TagsInput::className(), [])
            ->textInput(['value' => $model->messengers ? json_decode($model->messengers)->msn : ''])
            ->label('MSN') ?>
    </div>
    
    <div class="row form-group">
        <?= $form->field($model, 'signature',['options' => ['class' => 'col-xs-12 col-md-12']])->label(Yii::t('podium/view', 'Signature'))->textarea()->hint(Yii::t('podium/view', 'Signature under each post')) ?>

        <?= $form->field($model, 'interests',['options' => ['class' => 'col-xs-12 col-md-12']])->widget(Select2::classname(), [
            'id' => 'item-interests',
            'theme' => 'default',
            'data' => ArrayHelper::map(\bin\admin\modules\catalog\api\Catalog::cats(), 'category_id', 'title'),
            'options' => ['value' => explode(',',$model->interests),'multiple' => true],
        ])->label(Yii::t('easyii','Interests')); ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('user', 'Update'), ['class' => 'btn btn-lg btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

<?php $js = <<< JS
$("select").on('select2:open', function(e) {
    var options = $(".select2-results");
    if(!$("#select-"+this.id).length){
        setTimeout(function(){
            $(".select2-results ul").wrapInner('<div class="mCustomScrollbar" id="select-\'+this.id+\'" style="max-height:200px;"></div>');
            $("#select-"+this.id).mCustomScrollbar({theme:"default"});
        },1)
    }else{
        $("#select-"+this.id).mCustomScrollbar("update");
    }
})

$('#country .list-group-item').on('click',function(){
    $('#maps-search-input').val($($(this).find('strong')).text());
    $('#maps-search').click();
});

$('#profile-phone-selectized').on('input', function (e) {
  var x = e.target.value.replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,4})/);
  e.target.value = !x[2] ? x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : '');
});


JS;
$this->registerJs($js, yii\web\View::POS_READY); ?>

<script>

</script>

<?php Pjax::end(); ?>
