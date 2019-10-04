<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kilyakus\module\user\helpers\Timezone;
use bin\admin\models\MapsCountry;
use bin\admin\models\MapsRegion;
use bin\admin\models\MapsCity;
use bin\admin\widgets\TagsInput;
use kilyakus\web\widgets as Widget;
use kartik\daterange\DateRangePicker;
?>

<?php $this->beginContent('@bin/user/views/admin/update.php', ['user' => $user]) ?>

<div class="row">
    <?php $form = ActiveForm::begin([
        // 'layout' => 'horizontal',
        'options' => ['data-pjax' => true, 'data-pjax-problem' => true,'enctype' => 'multipart/form-data', 'class' => 'form-horizontal col-xs-12 col-sm-12 col-md-9'],
        'enableAjaxValidation' => true,
        'enableClientValidation' => false,
        'fieldConfig' => [
            'horizontalCssClasses' => [
                'wrapper' => 'col-xs-12 col-md-9',
            ],
            'template' => "{label}\n<div class=\"col-xs-12 col-md-9\">{input}</div>\n<div class=\"col-sm-offset-3 col-md-9\">{error}\n{hint}</div>",
            'labelOptions' => ['class' => 'col-xs-12 col-md-3 control-label'],
        ],

    ]); ?>

    <?= $form->field($profile, 'name') ?>
    <?= $form->field($profile, 'second_name') ?>
    <?= $form->field($profile, 'generic_name') ?>

    <?php $profile->birthdate = $profile->birthdate ? date('d-m-Y h:i', $profile->birthdate) : date('d-m-Y h:i'); ?>
    <?= $form->field($profile, 'birthdate')->widget(DateRangePicker::classname(), [
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

    <?= $form->field($profile, 'timezone')->widget(Widget\Select2::classname(), [
        'data' => ArrayHelper::map(
            Timezone::getAll(),
            'timezone',
            'name'
        ),
        'showToggleAll' => false,
        'options'       => ['placeholder' => Yii::t('podium/view', 'Select your time zone for proper dates display...')],
        'pluginOptions' => ['allowClear' => true],
    ])->label(Yii::t('podium/view', 'Time Zone'))
    ->hint(Html::a(Yii::t('podium/view', 'What is my time zone?'), 'http://www.timezoneconverter.com/cgi-bin/findzone', ['target' => '_blank'])); ?>

    <?= $form->field($profile, 'public_email') ?>

    <?= $form->field($profile, 'phone')->widget(TagsInput::className(), [
        'name' => 'phone[]',
        'value' => is_array($profile->phone) ? implode(',', $profile->phone) : $profile->phone,
        'options' => [
            'placeholder' => Yii::t('easyii/catalog', 'Type options with `comma` as delimiter')
        ]
    ]) ?>

    <div class="well well-sm">
    <?= $form->field($profile, 'messengers[skype]')->widget(TagsInput::className(), [])->textInput(['value' => json_decode($profile->messengers)->skype])->label('Skype') ?>
    <?= $form->field($profile, 'messengers[icq]')->widget(TagsInput::className(), [])->textInput(['value' => json_decode($profile->messengers)->icq])->label('ICQ') ?>
    <?= $form->field($profile, 'messengers[aim]')->widget(TagsInput::className(), [])->textInput(['value' => json_decode($profile->messengers)->aim])->label('AIM') ?>
    <?= $form->field($profile, 'messengers[yahoo]')->widget(TagsInput::className(), [])->textInput(['value' => json_decode($profile->messengers)->yahoo])->label('Yahoo') ?>
    <?= $form->field($profile, 'messengers[msn]')->widget(TagsInput::className(), [])->textInput(['value' => json_decode($profile->messengers)->msn])->label('MSN') ?>
    </div>

    <?php $form->field($profile, 'location') ?>

    <?= $form->field(new MapsCountry(), 'name_ru')->widget(Widget\Select2::classname(), [
        'id' => 'item-country_id',
        'data' => ArrayHelper::map(MapsCountry::find()->orderBy(['name_ru' => SORT_ASC])->all(), 'id', 'name_ru'),
        'options' => [
            'value' => json_decode($profile->location)->country_id,
            'placeholder' => Yii::t('easyii','Search for a country ...')
        ],
    ])->label(Yii::t('easyii/catalog','Country')); ?>

    <?= $form->field(new MapsRegion(), 'name_ru')->widget(Widget\DepDrop::classname(), [
        'id' => 'item-region_id',
        'data' => ArrayHelper::map(MapsRegion::find()->where(['country_id' => json_decode($profile->location)->country_id])->orderBy(['name_ru' => SORT_ASC])->all(), 'id', 'name_ru'),
        'options' => ['value' => json_decode($profile->location)->region_id,'placeholder' => Yii::t('easyii','Search for a state ...')],
        'type' => Widget\DepDrop::TYPE_SELECT2,
        'select2Options' => [
            'pluginOptions' => ['allowClear' => true,'multiple' => false,]
        ],
        'pluginOptions' => [
            'depends' => ['mapscountry-name_ru'],
            'url' => Url::to(['/site/get-region']),
            'loadingText' => 'Loading ...',
        ]
    ])->label(Yii::t('easyii/catalog','Region')); ?>

    <?= $form->field(new MapsCity(), 'name_ru')->widget(Widget\DepDrop::classname(), [
        'id' => 'item-city_id',
        'data' => ArrayHelper::map(MapsCity::find()->where(['country_id' => json_decode($profile->location)->country_id,'region_id' => json_decode($profile->location)->region_id])->orderBy(['name_ru' => SORT_ASC])->all(), 'id', 'name_ru'),
        'options' => ['value' => json_decode($profile->location)->city_id,'placeholder' => Yii::t('easyii','Search for a city ...')],
        'type' => Widget\DepDrop::TYPE_SELECT2,
        'select2Options' => [
            'pluginOptions' => ['allowClear' => true,'multiple' => false,]
        ],
        'pluginOptions' => [
            'depends' => ['mapsregion-name_ru'],
            'url' => Url::to(['/site/get-city']),
            'loadingText' => 'Loading ...',
        ]
    ])->label(Yii::t('easyii/catalog','City')); ?>

    <?= $form->field($profile, 'avatar')->widget(Widget\Cutter::className(), ['cropperOptions' => ['aspectRatio' => '4/4','aspectRatioHidden' => true,'positionsHidden' => true,'sizeHidden' => true,'rotateHidden' => true]]) ?>
    <?= $form->field($profile, 'gravatar_email')->hint(Html::a(Yii::t('user', 'Change your avatar at Gravatar.com'), 'http://gravatar.com')) ?>

    <?= $form->field($profile, 'website') ?>
    <?= $form->field($profile, 'bio')->textarea() ?>
    <?= $form->field($profile, 'signature')->label(Yii::t('podium/view', 'Signature'))->textarea()->hint(Yii::t('podium/view', 'Signature under each post')) ?>

    <div class="form-group">
        <div class="col-lg-offset-3 col-lg-9">
            <?= Html::submitButton(Yii::t('user', 'Update'), ['class' => 'btn btn-block btn-success']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
<?php $this->endContent() ?>
