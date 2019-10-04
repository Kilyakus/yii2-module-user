<?php
use yii\helpers\Url;
use yii\helpers\Html;
use bin\rbac\widgets\Assignments;
use bin\rbac\widgets\ForumAssignments;
use bin\admin\modules\chat\models\Relative;
use kilyakus\web\widgets as Widget;

use kilyakus\module\user\widgets\UserDetails;
use bin\admin\modules\geo\api\Geo;
$user = UserDetails::get($user->id);
$location = $user->profile->location ? json_decode($user->profile->location) : [];
$location = $location->city_id ? Geo::city($location->city_id)->name . ' (' . Geo::country($location->country_id)->code . ')' : '';

$this->title = Yii::t('user', 'Update user account');
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_menu') ?>

<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
    <?php Widget\Portlet::begin([
        // 'options' => ['class' => 'kt-portlet--tabs', 'id' => 'kt_page_portlet'],
        'title' => $user->getName() . ' ' . $user->getOnline(),
        'actions' => (
            $user->id > 0 ? [
                Widget\Dropdown::widget([
                    'button' => [
                        'icon' => 'flaticon-more-1',
                        'type' => 'clean',
                        'size' => Widget\Button::SIZE_SMALL,
                        'options' => ['class' => 'btn-icon btn-icon-md']
                    ],
                    'items' => [
                        Relative::isFriend($user->id),
                        Relative::isFavorite($user->id),
                        Relative::isBlock($user->id),
                    ]
                ])
            ] : []
        ),

        // 'scroller' => [
        //     'max-height' => 50,
        //     'format' => 'vh',
        // ],
        // 'bodyOptions' => [
        //     'class' => 'kt-portlet__body--fit',
        // ],
    ]); ?>
        <div class="kt-widget kt-widget--user-profile-2" style="height:auto;">
            <div class="kt-widget__head" style="margin-top:0px;">
                <div class="kt-widget__media">
                    <?= Html::img($user->getAvatar(90),['class' => 'kt-widget__img kt-hidden-']) ?>
                    <div class="kt-widget__pic kt-widget__pic--danger kt-font-danger kt-font-boldest kt-font-light kt-hidden">
                        MP
                    </div>
                </div>
                <div class="kt-widget__info" style="width: 100%;">
                    <span class="kt-widget__desc">
                        <?= Assignments::widget(['userId' => $user->id]) ?>
                        <?= ForumAssignments::widget(['userId' => $user->id]) ?>
                    </span>
                </div>
            </div>

            <div class="kt-widget__body">
                <div class="kt-widget__section">
                    <?= $user->profile->bio ?>
                </div>

                <div class="kt-widget__item">
                    <div class="kt-widget__contact">
                        <span class="kt-widget__label">Email:</span>
                        <a href="#" class="kt-widget__data"><?= $user->email ?></a>
                    </div>
                    <div class="kt-widget__contact">
                        <span class="kt-widget__label">Phone:</span>
                        <a href="#" class="kt-widget__data"><?= $user->profile->phone ?></a>
                    </div>
                    <div class="kt-widget__contact">
                        <span class="kt-widget__label">Location:</span>
                        <span class="kt-widget__data"><?= $location ?></span>
                    </div>
                </div>
            </div>

            <div class="kt-widget__footer">
                <?= Widget\Button::widget([
                    'tagName' => 'a',
                    'title' => 'Написать сообщение',
                    'type' => Widget\Button::TYPE_SUCCESS,
                    'disabled' => false,
                    'block' => true,
                    'outline' => false,
                    'hover' => false,
                    'circle' => false,
                    'label' => true,
                    'upper' => true,
                    'options' => ['href' => Url::toRoute(['/admin/chat/message/chat','id' => $user->id])],
                ]) ?>
            </div>
        </div>
    <?php Widget\Portlet::end(); ?>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-8 col-lg-9">
        <?php Widget\Portlet::begin([
            'options' => ['class' => 'kt-portlet--tabs', 'id' => 'kt_page_portlet'],
            'headerContent' => $this->render('_submenu',compact('user')),
            // 'scroller' => [
            //     'max-height' => 50,
            //     'format' => 'vh',
            // ],
            // 'bodyOptions' => [
            //     'class' => 'kt-portlet__body--fit',
            // ],
        ]); ?>
            <?= $content ?>
        <?php Widget\Portlet::end(); ?>
    </div>
</div>