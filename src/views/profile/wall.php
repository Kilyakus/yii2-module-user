<?php
use bin\admin\components\API;
use yii\helpers\Url;
use yii\helpers\Html;
use bin\admin\helpers\Image;
use bin\admin\modules\catalog\api\Catalog;
use bin\admin\modules\events\api\Events;
?>
<?php foreach ($update as $key => $item) : ?>
    <?php if(isset($update[$key-1])) : ?>
        <?php if(date('d.m.Y', $item->time) != date('d.m.Y', $update[$key-1]->time)) : ?>
            <p class="text-muted"><strong><?= date('d.m.Y', $item->time) ?></strong></p>
        <?php endif; ?>
    <?php else: ?>
        <p class="text-muted"><strong><?= date('d.m.Y', $item->time) ?></strong></p>
    <?php endif; ?>
    <div class="card mb-25">
        <?php if($item->class == 'bin\admin\modules\catalog\models\Item' && !isset($item->created_by)) : ?>
            <?php
            $model = Catalog::get($item->item_id);
            $account = \bin\admin\modules\catalog\models\Item::find()->where(['created_by' => $model->model->created_by])->one();
            ?>
            <div class="v-align">
                <div class="p-15" style="min-width:78px;max-width:78px;">
                    <?= Html::img(Image::thumb($account->image,48,48), ['class' => 'img-circle mr-15']) ?>
                </div>
                <div class="pl-0 p-15 m-0">
                    <span>Бизнес-аккаунт</span> 
                    <strong>
                        <a href="<?= Url::toRoute(['/catalog/view/','slug' => $account->slug]) ?>" target="_blank"><?= $account->title ?></a>
                    </strong>
                    <span>обновил свой профиль.</span>
                </div>
            </div>
            <?php if($model->tags) : ?>
                <div class="p-15 border-top border-light">
                    <p class="m-0 text-muted" style="font-size:13px;">Вы можете ознакомиться с информацией о данном аккаунте, посмотреть галерею и видеогалерею</p>
                    <div class="nav-tags hidden" id="nav-<?= Yii::$app->controller->id.'-'.$item->id ?>">
                        <ul class="visible-links pl-0">
                            <?php foreach($model->tags as $tag) :?>
                                <li class="label label-default"><a href="<?= Url::toRoute(['/search/', 'text' => $tag]) ?>"><?= $tag ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                        <ul class="hidden-links hidden"></ul>
                        <button class="label label-default">...</button>
                    </div>
                </div>
            <?php endif; ?>
        <?php elseif($item->class == 'bin\admin\modules\events\models\Item' && !isset($item->created_by)) : ?>
            <?php
            $model = Events::get($item->item_id);
            $account = \bin\admin\modules\catalog\models\Item::find()->where(['created_by' => $model->model->created_by])->one();
            ?>
            <div class="v-align">
                <div class="p-15" style="min-width:78px;max-width:78px;">
                    <?= Html::img(Image::thumb($account->image,48,48), ['class' => 'img-circle mr-15']) ?>
                </div>
                <div class="pl-0 p-15 m-0">
                    <span>Бизнес-аккаунт</span> 
                    <strong>
                        <a href="<?= Url::toRoute(['/catalog/view/','slug' => $account->slug]) ?>" target="_blank"><?= $account->title ?></a>
                    </strong>
                    <span>обновил данные по мероприятию "<strong><a href="<?= Url::toRoute(['/events/view/','slug' => $model->slug]) ?>" class="text-orange" target="_blank"><?= $model->title ?></a></strong>".</span>
                </div>
            </div>
        <?php else: ?>
            <?php
            $user = \bin\admin\models\User::findOne($item->created_by);
            $account = \bin\admin\models\Comment::find()->where(['created_by' => $user->id])->one();
            if($item->class == 'bin\admin\modules\catalog\models\Item'){
                $url = '/catalog/view/';
            }elseif($item->class == 'bin\admin\modules\events\models\Item'){
                $url = '/events/view/';
            }
            ?>
            <div class="v-align">
                <div class="p-15 pr-0 m-0">
                    <?= Html::img(API::getAvatarById($user,48,48), ['class' => 'img-circle mr-15']) ?>
                </div>
                <div class="p-15 m-0">
                    <strong>
                        <a href="<?= Url::toRoute([$url,'slug' => $item->item_id]) ?>#reviews<?= $item->id ?>" target="_blank"><?= API::getName($user->id) ?></a>
                    </strong>
                    <span>ответил на ваш отзыв. Вы можете ознакомиться с ответом, а также дать обратную связь.</span>
                </div>
            </div>
        <?php endif; ?>
    </div>
<?php endforeach; ?>