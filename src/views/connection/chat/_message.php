<?php
use bin\admin\components\API;
use yii\helpers\Html;
use bin\admin\helpers\Image;
use kilyakus\module\user\models\User;
$user = User::findOne($model->authorId);
?>
<div class="messages-item">
	<div class="col-xs-12 text-center hidden"><?= date('d.m.Y',$model->created)  ?></div>
	<table class="table alert">
		<!--  <?= ($model->authorId == Yii::$app->user->id) ? 'bg-info text-info' : 'bg-warning text-warning' ?> -->
		<tbody>
			<tr>
				<td rowspan="2" width="60" style="vertical-align:top;">
					<?= Html::img(Image::thumb(API::getAvatarById($user->id),41,41),['class' => 'img-circle']) ?>
				</td>
				<td><?= API::getName($user->id) ?></td>
				<td class="text-right"><?= date('H:i',$model->created)  ?></td>
			</tr>
			<tr>
				<td colspan="2" style="border-top:0px;padding-top:0px;">
					<pre><?= $model->text ?></pre>
				</td>
			</tr>
		</tbody>
	</table>
</div>