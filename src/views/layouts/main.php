<?php
use bin\admin\components\API;
use app\assets\UserAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use kilyakus\module\user\widgets\UserMenu;
use bin\admin\helpers\Image;
use bin\admin\modules\chat\api\Chat;

$asset = UserAsset::register($this)->baseUrl;
$moduleName = $this->context->module->id;
?>
<?php $this->beginContent('@app/views/layouts/base.php',compact('config')); ?>
    <header>
        <?= $this->render('@app/views/elements/_menu') ?>
    </header>
    <div id="user-body" class="<?= (Yii::$app->controller->id == 'settings') ? 'settings' : ''?>">
        <div class="main">
            <?= $this->render('@app/views/elements/_sidebar-left') ?>
            <div class="box content">
                <div class="container-fluid">
                    <?= $this->render('@app/views/_alert') ?>
                    <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] :[],]); ?>
                    <?= $content ?>
                </div>
            </div>
            <?php if(IS_USER) : ?>
                <div class="box menu-right border-left border-default collapse" aria-expanded="false" id="sidebar-menu-right">
                    <div class="hidden-md hidden-lg p-20">
                        <button type="button" class="close" data-toggle="collapse" data-target="#sidebar-menu-right"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class=" p-20 text-center">
                        <?= Html::img(Image::thumb(Yii::$app->user->identity->profile->avatar,100,100),['class' => 'img-circle mb-15']); ?>
                        <p class="welcome">Добро пожаловать,<br><strong><?= Yii::$app->user->identity->profile->name ?></strong></p>
                        <div class="h-align">
                            <a href="<?= Url::toRoute(['/user/settings/profile']) ?>" class="btn btn-lg btn-block btn-primary"><?= Yii::t('easyii','Edit') ?></a>
                            <a href="<?= Url::toRoute(['/user/settings/account']) ?>" class="btn btn-lg btn-default ml-10 ico-key"></a>
                        </div>
                    </div>
                    <div class="menu-item">
                        <a href="<?= Url::to(['/user']) ?>">
                            <div class="right-menu-icon home"></div>
                            <?= Yii::t('user','Мой профиль') ?>
                        </a>
    					<a href="<?= Url::to('/user/connection/friends') ?>">
                            <div class="v-align">
                                <div class="right-menu-icon friends"></div>
                                <?= Yii::t('user','Друзья') ?> 
                                <?php if(count(Chat::friendsRequests(Yii::$app->user->id)['dataProvider']->query->all())) : ?>
                                    <span class="badge"><?= count(Chat::friendsRequests(Yii::$app->user->id)['dataProvider']->query->all()) ?></span>
                                <?php endif; ?>
                            </div>
                        </a>
    					<a href="<?= Url::to('/user/connection/chat') ?>">
    						<div class="v-align">
                                <div class="right-menu-icon messages"></div>
                                <?= Yii::t('user','Сообщения') ?> 
                                <?php if(count(Chat::noAnswer()) != 0) : ?>
                                    <span class="badge"><?= Chat::noAnswer() ?></span>
                                <?php endif; ?>
                            </div>
                        </a>
    					<a href="<?= Url::to('/forum/profile/subscriptions') ?>">
                            <div class="right-menu-icon favorite"></div>
                            <?= Yii::t('user','Избранное') ?>
                        </a>
    					<a href="<?= Url::to('/user/connection/black-list') ?>">
                            <div class="right-menu-icon blist"></div>
                            <?= Yii::t('user','Черный список') ?>
                        </a>
    					<a href="<?= Url::to('/user/places/index') ?>">
                            <div class="right-menu-icon place"></div>
                            <?= Yii::t('user','Места') ?>
                        </a>
    					<a href="<?= Url::to('/user/market/index') ?>">
                            <div class="right-menu-icon basket"></div>
                            <?= Yii::t('user','Товары') ?>
                        </a>
    					<!-- <a href="javascript://" class="opacity-5">
                            <div class="right-menu-icon list"></div>
                            <?= Yii::t('user','История продаж/покупки') ?>
                        </a> -->
    					<!-- <a href="javascript://" class="opacity-5">
                            <div class="right-menu-icon gallery"></div>
                            <?= Yii::t('user','Галерея') ?>
                        </a> -->
    					<!-- <a href="javascript://" class="opacity-5">
                            <div class="right-menu-icon config"></div>
                            <?= Yii::t('user','Настройки') ?>
                        </a> -->
    					<!-- <a href="javascript://" class="opacity-5">
                            <div class="right-menu-icon file"></div>
                            <?= Yii::t('user','Платежные реквизиты') ?>
                        </a> -->
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
	<?= $this->render('@app/views/elements/_footer') ?>
<?php $this->endContent(); ?>
