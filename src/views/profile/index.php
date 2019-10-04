<?php
use bin\admin\components\API;
use app\assets\AppAsset;
use yii\helpers\Url;
use yii\helpers\Html;
use bin\admin\helpers\Image;
use bin\admin\models\Comment;
use bin\admin\modules\catalog\api\Catalog;
use bin\admin\modules\events\api\Events;
$asset = AppAsset::register($this);
$this->title = 'Мой аккаунт';
$this->params['breadcrumbs'][] = $this->title;
$config['title'] = $this->title;

?>
<?php $this->beginContent('@app/views/layouts/main.php',compact('config')); ?>
<div class="container">
    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
            <div class="card mb-25 p-20 text-center">
                <a href="<?= Url::to('/user/profile/edit') ?>" class="text-gray position-absolute" style="top:10px;right:25px;font-size:20px;"><i class="glyphicon glyphicon-edit"></i></a>
                <?= Html::img(API::getAvatar(112,112), ['class' => 'img-circle','alt' => $profile->user->username,]) ?>
                <p class="mt-10">Добро пожаловать в Ваш аккаунт,</p>
                <h3 class="mt-0"><?= API::getName() ?></h3>
                <?php foreach (Yii::$app->authManager->getRolesByUser(Yii::$app->user->id) as $status) : ?>
                    <i class="text-gray"><?= $status->description ?></i>
                <?php endforeach; ?>
            </div>
            <?php if(\Yii::$app->user->can('business')) : ?>
                <div class="card mb-10 pt-20 pb-20 pr-25 pl-25">
                    <a class="row v-align-lg mr-0 ml-0 pb-15 border-bottom border-light" href="<?= Url::toRoute('/profile/business'); ?>">
                        <div class="col-lg-2 pr-0 pl-0">
                            <img src="<?= $asset->baseUrl ?>/img/ico-briefcase.png">
                        </div>
                        <div class="col-lg-10">
                            <h4 class="mb-0">Мой бизнес</h4>
                            <p class="text-muted">В данном разделе, вы можете изменить информацию о бизнесе</p>
                        </div>
                    </a>
                    <a class="row v-align-lg mr-0 ml-0 pt-15 pb-15 border-bottom border-light" href="<?= Url::toRoute('/profile/events'); ?>">
                        <div class="col-lg-2 pr-0 pl-0">
                            <img src="<?= $asset->baseUrl ?>/img/ico-calendar.png">
                        </div>
                        <div class="col-lg-10">
                            <h4 class="mb-0">Мои мероприятия</h4>
                            <p class="text-muted">Вы создали <strong><?= count($events); ?></strong> мероприятия</p>
                        </div>
                    </a>
                    <a class="row v-align-lg mr-0 ml-0 pt-15" href="<?= Url::toRoute('/profile/reviews'); ?>">
                        <div class="col-lg-2 pr-0 pl-0">
                            <img src="<?= $asset->baseUrl ?>/img/ico-reviews.png">
                        </div>
                        <div class="col-lg-10">
                            <h4 class="mb-0">Отзывы обо мне</h4>
                            <p class="text-muted">Пользователи оставили <strong><?= count($model->comments) ?></strong> <?= Yii::$app->i18n->format('{n, plural, =0{нет отзывов} =1{отзыв} one{отзыва} few{отзыва} many{отзывов} other{отзывов}}', ['n' => substr(count($model->comments),-1)], 'ru_RU'); ?> о вашем бизнесе и мероприятиях</p>
                        </div>
                    </a>
                </div>
            <?php else: ?>
                <div class="card mb-10 pt-20 pb-20 pr-25 pl-25">
                    <a class="row v-align-lg mr-0 ml-0 pb-15 border-bottom border-light" href="<?= Url::toRoute('/profile/catalog'); ?>">
                        <div class="col-lg-2 pr-0 pl-0">
                            <img src="<?= $asset->baseUrl ?>/img/ico-briefcase.png">
                        </div>
                        <div class="col-lg-10">
                            <h4 class="mb-0">Бизнесы</h4>
                            <p class="text-muted">Вы следите за обновлениями <strong><?= count($catalog_favorite); ?></strong> бизнес-аккаунтов</p>
                        </div>
                    </a>
                    <a class="row v-align-lg mr-0 ml-0 pt-15 pb-15 border-bottom border-light" href="<?= Url::toRoute('/profile/events'); ?>">
                        <div class="col-lg-2 pr-0 pl-0">
                            <img src="<?= $asset->baseUrl ?>/img/ico-calendar.png">
                        </div>
                        <div class="col-lg-10">
                            <h4 class="mb-0">Мероприятия</h4>
                            <p class="text-muted">Вы следите за обновлениями <strong><?= count($events_favorite); ?></strong> мероприятий</p>
                        </div>
                    </a>
                    <a class="row v-align-lg mr-0 ml-0 pt-15" href="<?= Url::toRoute('/profile/reviews'); ?>">
                        <div class="col-lg-2 pr-0 pl-0">
                            <img src="<?= $asset->baseUrl ?>/img/ico-reviews.png">
                        </div>
                        <div class="col-lg-10">
                            <h4 class="mb-0">Мои отзывы</h4>
                            <p class="text-muted">
                                <?php if(count(Comment::my(Yii::$app->user->id))) : ?>
                                    Вы оставили <strong><?= count(Comment::my(Yii::$app->user->id)) ?></strong> <?= Yii::$app->i18n->format('{n, plural, =0{нет отзывов} =1{отзыв} one{отзыва} few{отзыва} many{отзывов} other{отзывов}}', ['n' => substr(count(Comment::my(Yii::$app->user->id)),-1)], 'ru_RU'); ?>
                                <?php else: ?>
                                    Вы еще не оставляли отзывов
                                <?php endif; ?>
                            </p>
                        </div>
                    </a>
                </div>
            <?php endif; ?>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
            <?php if(count($update)) : ?>
                <?= $this->render('wall',compact('update','dataProvider')) ?>
            <?php else : ?>
                <p>Записей не найдено</p>
            <?php endif; ?>
            <?= \yii\widgets\LinkPager::widget(['pagination' => $dataProvider->pagination]); ?>
        </div>
    </div>
</div>
<?php $this->endContent(); ?>