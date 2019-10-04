<?php
use yii\helpers\Url;
use yii\helpers\Html;
use bin\admin\helpers\Image;
use kilyakus\web\widgets\PortletTabs;
use yii\bootstrap\Modal;
use bin\admin\modules\geo\api\Geo;
use yii\widgets\ActiveForm;

if ($model->name{strlen($model->name)-1} == ' ') {
$model->name = substr($model->name,0,-2);
}
$permissions = [];
foreach (Yii::$app->authManager->getPermissionsByUser($model->id) as $name => $data) {
	$permissions[] = $data->description;
}
$location = json_decode($model->profile->location);
$locations = [];
!$location->country_id ?: $locations[] = Geo::country($location->country_id)->name;
!$location->region_id ?: $locations[] = Geo::region($location->region_id)->name;
!$location->city_id ?: $locations[] = Geo::city($location->city_id)->name;
$locations = implode(',',$locations);
?>


<div class="kt-portlet">
    <div class="kt-portlet__body">
        <div class="kt-widget kt-widget--user-profile-3">
            <div class="kt-widget__top">
                <div class="kt-widget__media kt-hidden-">
                    <img src="<?= Image::thumb($model->profile->avatar,110,110) ?>" alt="image">
                </div>
                <div class="kt-widget__content">

                    <div class="kt-widget__head">
                        <?= PortletTabs::widget([
    'items' => [
        [
            'label' => 'qwe',
            'content' => 'qwrqw'
        ],
        [
            'label' => 'qwe',
            'content' => 'qwrqw'
        ]
    ]
]) ?>
                    	<div class="kt-widget__username">
	                        <a href="<?= Url::toRoute(['/user/admin/update','id' => $model->id]) ?>">
	                            <?php if($model->username != $model->name && $model->name) : ?><?= $model->name ?> (<?= $model->username ?>)<?php else: ?><?= $model->username ?><?php endif; ?>
	                        </a>
                            <?php if ($model->isConfirmed) : ?>
                            	<i class="flaticon2-correct kt-font-success" data-toggle="kt-tooltip" data-placement="right" data-original-title="<?= Yii::t('user', 'Confirmed') ?>"></i>
			                <?php else: ?>
			                    <?= Html::a('<i class="flaticon2-correct"></i>', ['confirm', 'id' => $model->id], [
			                        'class' => 'btn btn-outline-hover-danger btn-elevate btn-circle btn-icon',
			                        'data-original-title' => Yii::t('user', 'Confirm'),
			                        'data-method' => 'post',
			                        'data-confirm' => Yii::t('user', 'Are you sure you want to confirm this user?'),
			                    ]); ?>
			                <?php endif; ?>
                        </div>
                        <div class="kt-widget__action">
                            <a href="<?= Url::toRoute(['/admin/chat/message/chat','id' => $model->id]) ?>" data-pjax="0" class="btn btn-label-success btn-sm btn-upper">Написать сообщение</a>
                        </div>
                    </div>

                    <div class="kt-widget__subhead">
                        <a href="#"><i class="flaticon2-new-email"></i><?= $model->email ?></a> 
                        <a href="#"><i class="flaticon2-calendar-3"></i>
                        	<?= $permissions[0] ?>
	                    </a> 
                        <?php if($locations): ?><a href="#"><i class="flaticon2-placeholder"></i><?= $locations ?></a><?php endif; ?>
                    </div> 

                    <div class="kt-widget__info">
                        <div class="kt-widget__desc">
                            <?= Yii::t('user','Bio') ?>: <?= $model->profile->bio ? $model->profile->bio : Yii::t('user', '(not set)') ?>
                        </div> 
                        <div class="kt-datatable kt-datatable--default kt-datatable--brand kt-datatable--loaded">
                            <table class="kt-datatable__table">
                                <tbody class="kt-datatable__body">
                                    <tr data-row="30" class="kt-datatable__row">
                                        <td data-field="ShipDate" class="kt-datatable__cell"><?= $model->registration_ip ?></td>

                                        <td data-field="Status" class="kt-datatable__cell">
                                            <span style="width: 100px;">
                                                <span class="btn btn-bold btn-sm btn-font-sm  btn-label-danger">Processing</span>
                                            </span>
                                        </td>
                                        <td data-field="Type" data-autohide-disabled="false" class="kt-datatable__cell">
                                            <span style="width: 110px;"><span class="kt-badge kt-badge--danger kt-badge--dot"></span>&nbsp;<span class="kt-font-bold kt-font-danger">Online</span></span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>    
            </div>
        </div>
    </div>
</div>