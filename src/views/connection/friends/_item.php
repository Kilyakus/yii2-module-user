<?php
use bin\admin\components\API;
use app\assets\UserAsset;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kilyakus\module\user\models\User;
use bin\admin\models\MapsCountry;
use bin\admin\models\MapsRegion;
use bin\admin\models\MapsCity;
use bin\admin\modules\chat\api\Chat;

$baseUrl = UserAsset::register($this)->baseUrl;
?>

<tr>
	<td width="120" class="text-center"><?= Html::img(API::getAvatarById($model->id,120,120),['class' => 'img-circle mt-15 mb-15']) ?></td>
	<td width="50%">
		<table class="table clear">
			<tbody>
				<tr>
					<td colspan="2">
						<p><u style="font-size:18px;"><a href="<?= Url::toRoute(['/user/profile/show','id' => $model->id]) ?>" target="_blank"><?= API::getName($model->id) ?></a></u></p>
					</td>
				</tr>
				<tr>
					<td><p class="text-gray"><?= Yii::t('user','Language') ?></p></td>
					<td>
						<p class="text-black"><?= json_decode($model->profile->location)->country_id ? MapsCountry::findOne(json_decode($model->profile->location)->country_id)->code : '<span class="text-gray">(не указано)</span>' ?></p>
					</td>
				</tr>
				<tr>
					<td><p class="text-gray"><?= Yii::t('user','Nearest town') ?></p></td>
					<td>
						<p class="text-black"><?= json_decode($model->profile->location)->city_id ? MapsCity::findOne(json_decode($model->profile->location)->city_id)->name_ru : '<span class="text-gray">(не указано)</span>' ?></p>
					</td>
				</tr>
				<tr>
					<td><p class="text-gray"><?= Yii::t('user','Purpose of stay') ?></p></td>
					<td>
						<p class="text-black"><?= '<span class="text-gray">(не указано)</span>' ?></p>
					</td>
				</tr>
			</tbody>
		</table>
	</td>
	<td width="220px" style="vertical-align:top;">
		<?= Html::a('Написать сообщение',['#'], ['class' => 'btn-block btn btn-lg btn-primary','data-toggle' => 'modal', 'data-target' => '#modal-'.$model->id]) ?>
		<?php $form = ActiveForm::begin(['action'=>['/user/connection/chat'],'id'=>$model->id]);$message = Chat::message(); ?>
		<div class="modal fade" id="modal-<?= $model->id ?>">
			<div class="modal-dialog modal-md">
				<div class="modal-content">
					<div class="modal-body modal-send-message">
						<p class="new-message-img"></p>
						<div class="close" data-dismiss="modal"></div>
						<p class="new-message-title">Новое сообщение</p>
						<p class="new-message-to">Введите ваше сообщение пользователю <span class="new-message-to-user"><?= $model->username ?></span></p>
						<?= $form->field($message, 'recipientId')->hiddenInput(['value'=>$model->id])->label(false) ?>
						<?= $form->field($message, 'text')->textarea()->label(false) ?>
						<div class="row">
							<div class="col-md-6">
								<button type="submit" class="btn btn-lg btn-block btn-primary">Отправить</button>
							</div>
							<div class="col-md-6">
								<button class="btn btn-lg btn-block btn-secondary" data-dismiss="modal">Отменить</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php ActiveForm::end(); ?>
	</td>
	<td width="65px" style="vertical-align:top;">
		<?= Chat::buttonMyFavorite($model->id,'/user/connection/set-favorite','favorite-check') ?>
	</td>
</tr>