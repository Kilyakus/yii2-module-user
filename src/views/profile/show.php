<?php
use bin\admin\components\API;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use bin\admin\models\MapsCountry;
use bin\admin\models\MapsRegion;
use bin\admin\models\MapsCity;
use app\assets\UserAsset;
use bin\admin\helpers\Image;
use bin\admin\modules\chat\api\Chat;
use bin\admin\modules\catalog\api\Catalog;

use \kilyakus\web\widgets as Widget;

$asset = UserAsset::register($this)->baseUrl;

$this->title = empty($profile->name) ? Html::encode($profile->user->username) : Html::encode($profile->name . ' ' . $profile->second_name);
$this->params['breadcrumbs'][] = Yii::t('user','Profile');
?>

<?php $this->beginContent('@bin/user/views/layouts/main.php') ?>
    <div class="panel panel-default border">
        <div class="row h-align-md h-align-lg">
            <div class="col-xs-12 col-md-1" style="min-width:300px;">
                <div class="h-12 panel-body border-right border-default">
                    <?php if($profile->user->id == Yii::$app->user->identity->id) : ?>
                        <?php Pjax::begin([
                            'enablePushState' => false,
                        ]);?>
                            <?php $form = ActiveForm::begin([
                                'method' => 'POST',
                                // 'action' => Url::toRoute('/user/settings/profile'),
                                'options' => ['enctype' => 'multipart/form-data', 'data-pjax' => true, 'data-pjax-problem' => true, 'class' => 'model-form', 'type' => 'cropper'],
                                'enableAjaxValidation' => true,
                                'enableClientValidation' => false,
                                'validateOnBlur' => false,
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
                        <div class="mt-10 h-align">
                            <a href="<?= Url::toRoute(['/user/settings/profile']) ?>" class="btn btn-lg btn-block btn-primary"><?= Yii::t('easyii','Edit') ?></a>
                            <a href="<?= Url::toRoute(['/user/settings/account']) ?>" class="btn btn-lg btn-default ml-10 ico-key"></a>
                        </div>
                    <?php else : ?>
                        <?= Html::img(Image::thumb(API::getAvatarById($profile->user->id),300,300),['class' => 'img-rounded']) ?>
                        <div class="mt-10 h-align">
                            <?= Html::a('Сообщение',['#'], ['class' => 'btn-block btn btn-lg btn-primary','data-toggle' => 'modal', 'data-target' => '#modal-'.$profile->user->id]) ?>
                            <div class="btn-group ml-10">
                                <a class="btn btn-lg btn-default dropdown-toggle ico-options" data-toggle="dropdown" href="#"></a>
                                <ul class="dropdown-menu pull-right">
                                    <li class="item-lang">
                                        <?= Chat::checkMyFriend($profile->user->id,'/user/connection/set-friend') ?>
                                        <?= Chat::checkMyFavorite($profile->user->id,'/user/connection/set-favorite') ?>
                                        <?= Chat::checkMyBlackList($profile->user->id,'/user/connection/add-bl') ?>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <?php $form = ActiveForm::begin(['action'=>['/user/connection/chat','id' => $profile->user->id],'id'=>'message-form-'.$profile->user->id]);$message = Chat::message(); ?>
                        <div class="modal fade" id="modal-<?= $profile->user->id ?>">
                            <div class="modal-dialog modal-md">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <p>Новое сообщение</p>
                                        <p>Введите ваше сообщение пользователю <?= API::getName($profile->user->id) ?></p>
                                        <?= Html::beginForm(['/user/connection/to-user','id'=>'message-form-'.$profile->user->id]).
                                            $form->field($message, 'recipientId')->hiddenInput(['value'=>$profile->user->id])->label(false).
                                            $form->field($message, 'text')->textarea()->label(false).
                                            Html::submitButton('Отправить', ['class' => 'btn btn-lg btn-block btn-primary']).
                                        Html::endForm() ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php ActiveForm::end(); ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-xs-12 col-md-12">
                <div class="panel-body">
					<div class="username-block">
						<div class="username"><?= $this->title ?> <img src="<?= $asset ?>/img/user/icons/icon-men.svg" /></div>
						<span><?= Yii::t('user', 'Joined on {0, date}', $profile->user->created_at) ?></span>
					</div>
					<div class="raiting-block">
						<div class="h-align">
							3,5 <img src="<?= $asset ?>/img/user/icons/icon-star.svg" />
						</div>
					</div>
                    <?php $interests = explode(',',$profile->interests); ?>
                    <?php if(count($interests)) : ?>
					   <div class="group-block">
                            <?php foreach($interests as $interest) : ?>
                                <?php $category = Catalog::cat($interest); ?>
                                <div class="group-block-item">
                                    <div class="v-align"><img src="<?= Image::thumb($category->icon,32,32) ?>" /> <?= $category->title ?></div>
                                </div>
                            <?php endforeach; ?>
					   </div>
                    <?php endif; ?>
                    <hr>
					<!-- <div class="row block-metr opacity-5">
						<div class="col-xs-12 col-md-4 walk">
							<img src="<?= $asset ?>/img/user/icons/icon-walk.svg" />
							Шагомер
							<span>10 000 шагов</span>
						</div>
						<div class="col-xs-12 col-md-4 mountain">
							<img src="<?= $asset ?>/img/user/icons/icon-mountain.svg" />
							Высотомер
							<span>2 000 метров</span>
						</div>
					</div>
					<hr> -->
                    <div class="row">
                        <div class="col-xs-12 col-md-6 col-lg-4">
                            <label><?= Yii::t('user','Email') ?></label>
                            <p class="email-open"><?= $profile->public_email ? $profile->public_email : '<span class="text-gray">(не указано)</span>' ?></p>
							<p class="email-show">Показать</p>
                        </div>
                        <div class="col-xs-12 col-md-6 col-lg-4">
                            <label><?= Yii::t('user','Phone') ?></label>
                            <p><?= $profile->phone ? implode(', ',explode(',',$profile->phone)) : '<span class="text-gray">(не указано)</span>' ?></p>
                        </div>
                        <div class="col-xs-12 col-md-6 col-lg-4">
                            <label><?= Yii::t('user','Language') ?></label>
                            <p><?= json_decode($profile->location)->country_id ? MapsCountry::findOne(json_decode($profile->location)->country_id)->code : '<span class="text-gray">(не указано)</span>' ?></p>
                        </div>
                        <div class="col-xs-12 col-md-6 col-lg-4">
                            <label><?= Yii::t('easyii','Country') ?></label>
                            <p><?= json_decode($profile->location)->country_id ? MapsCountry::findOne(json_decode($profile->location)->country_id)->name_ru : '<span class="text-gray">(не указано)</span>' ?></p>
                        </div>
                        <div class="col-xs-12 col-md-6 col-lg-4">
                            <label><?= Yii::t('user','Nearest town') ?></label>
                            <p><?= json_decode($profile->location)->city_id ? MapsCity::findOne(json_decode($profile->location)->city_id)->name_ru : '<span class="text-gray">(не указано)</span>' ?></p>
                        </div>
                        <div class="col-xs-12 col-md-6 col-lg-4">
                            <label><?= Yii::t('user','Purpose of stay') ?></label>
                            <p class="text-gray">(не указано)</p>
                        </div>
                    </div>
                    <br>
                    <label><?= Yii::t('user','Обо мне') ?></label>
                    <?php if (!empty($profile->bio)): ?>
                        <p><?= Html::encode($profile->bio) ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<script>
$('.email-show').click(function() {
	$(this).hide();
	$('.email-open').show();
});
</script>
<?php $this->endContent() ?>