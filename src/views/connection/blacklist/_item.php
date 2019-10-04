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
		<a href="<?= Url::toRoute(['/user/connection/add-bl','id' => $model->id]) ?>" class="btn btn-lg btn-primary mt-md-15 mt-lg-15">Из ч/с</a>
	</td>
</tr>