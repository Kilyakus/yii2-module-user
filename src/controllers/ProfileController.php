<?php
namespace kilyakus\module\user\controllers;

use dektrium\user\Finder;
use kilyakus\module\user\models\Profile;
use kilyakus\module\user\models\SettingsForm;
use kilyakus\module\user\models\PasswordForm;
use kilyakus\module\user\models\User;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use kilyakus\module\user\traits\AjaxValidationTrait;
use kilyakus\module\user\traits\EventTrait;

use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\helpers\Json;
use bin\admin\modules\catalog\api\Catalog;
use bin\admin\modules\catalog\models\ItemUpdate as CatalogUpdates;
use bin\admin\modules\events\api\Events;
use bin\admin\modules\events\models\ItemUpdate as EventsUpdates;
use bin\admin\models\Comment;

use bin\admin\modules\chat\api\Chat;
use bin\admin\modules\chat\models\Message;

class ProfileController extends \bin\admin\components\AppController
{
    use AjaxValidationTrait;
    use EventTrait;

    const EVENT_BEFORE_PROFILE_UPDATE = 'beforeProfileUpdate';
    const EVENT_AFTER_PROFILE_UPDATE = 'afterProfileUpdate';
    const EVENT_BEFORE_ACCOUNT_UPDATE = 'beforeAccountUpdate';
    const EVENT_AFTER_ACCOUNT_UPDATE = 'afterAccountUpdate';
    const EVENT_BEFORE_CONFIRM = 'beforeConfirm';
    const EVENT_AFTER_CONFIRM = 'afterConfirm';
    const EVENT_BEFORE_DISCONNECT = 'beforeDisconnect';
    const EVENT_AFTER_DISCONNECT = 'afterDisconnect';
    const EVENT_BEFORE_DELETE = 'beforeDelete';
    const EVENT_AFTER_DELETE = 'afterDelete';

    public $defaultAction = 'profile';

    protected $finder;

    public function __construct($id, $module, Finder $finder, $config = [])
    {
        $this->finder = $finder;
        parent::__construct($id, $module, $config);
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    ['allow' => true, 'actions' => ['index','edit'], 'roles' => ['@']],
                    ['allow' => true, 'actions' => ['show'], 'roles' => ['?', '@']],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $profile = $this->finder->findProfileById(\Yii::$app->user->getId());

        if ($profile === null) {
            throw new NotFoundHttpException();
        }

        if(\Yii::$app->user->can('business')){

            if(!$slug){
                $slug = Catalog::items(['where' => ['created_by' => \Yii::$app->user->getId()]])[0]->slug;
            }

            $catalog = Catalog::get($slug);

            $events = Events::items(['where' => ['created_by' => \Yii::$app->user->getId()]]);

        }
        $catalog_update = CatalogUpdates::find()->from(['item_id' => CatalogUpdates::find()->where(['item_id' => Catalog::favorites()->all()])->orderBy(['time' => SORT_DESC])])->groupBy('item_id');

        $events_update = EventsUpdates::find()->from(['item_id' => EventsUpdates::find()->where(['item_id' => Events::favorites()->all()])->orderBy(['time' => SORT_DESC])])->groupBy('item_id');


        $comments = [];
        foreach (Comment::cats() as $comment) {
            if($comment->parent){
                $parent = Comment::findOne($comment->parent);
                if($parent->created_by == Yii::$app->user->identity->id){
                    $comments[] = $comment->id;
                }
            }
        }
        $comments = Comment::find()->where(['and',['id' => $comments],['!=','created_by',\Yii::$app->user->getId()]])->all();

        $query = $catalog_update->union($events_update);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['time' => SORT_DESC]],
            'pagination' => [ 'pageSize' => 100, ],
        ]);

        $update = array_merge($dataProvider->getModels(),$comments);

        uasort($update, function($a, $b){
            return ($a['time'] < $b['time']);
        });

        $return = array();
        foreach ($update as $v){
          $return []= $v;
        }
        $update = $return;

        if ($profile === null) {
            throw new NotFoundHttpException();
        }

        return $this->renderPartial('index', [
            'profile' => $profile,
            'model' => $catalog,
            'events' => $events,
            'catalog_favorite' => Catalog::favorites()->all(),
            'events_favorite' => Events::favorites()->all(),
            'update' => $update,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionEdit($id = null)
    {
        $id = $id ? $id : \Yii::$app->user->identity->getId();

        $user = $this->finder->findUserById($id);
        $profile = $this->finder->findProfileById($id);

        if ($profile == null) {
            $profile = \Yii::createObject(Profile::className());
            $profile->link('user', \Yii::$app->user->identity);
        }

        $request = \Yii::$app->request;

        if($request->post('Profile')){

            if($request->post('Profile')['avatar']){
                $this->performAjaxValidation($profile,false);
            }else{
                $this->sendAjaxValidation($profile);
            }

            $this->trigger(self::EVENT_BEFORE_PROFILE_UPDATE, $profile_event);

            if ($profile->load(\Yii::$app->request->post()) && $profile->save()) {

                \Yii::$app->getSession()->setFlash('success', \Yii::t('user', 'Your profile has been updated'));
                $this->trigger(self::EVENT_AFTER_PROFILE_UPDATE, $profile_event);
                
                
            }

        }

        if($request->post('User')){

            $this->performAjaxValidation($user);

            if ($user->load(\Yii::$app->request->post())) {

                $user->save();
                
            }

        }
        
        $account = $this->getResetPassword($user);

        if($request->post('PasswordForm')){

            $this->sendAjaxValidation($account);

            $this->trigger(self::EVENT_BEFORE_ACCOUNT_UPDATE, $account_event);

            if ($account->load(\Yii::$app->request->post()) && $account->save()) {
                \Yii::$app->session->setFlash('success', \Yii::t('user', 'Your account details have been updated'));
                $this->trigger(self::EVENT_AFTER_ACCOUNT_UPDATE, $account_event);

            }

        }
        if($request->post()){

            if (\Yii::$app->request->isAjax) {
                return $this->renderAjax('edit', [
                    'user'    => $user,
                    'profile' => $profile,
                    'account' => $account,
                ]);
            }else{
                return $this->refresh();
            }

        }

        return $this->render('edit', [
            'user'    => $user,
            'profile' => $profile,
            'account' => $account,
            'passModel' => $passModel,
        ]);
    }

    public function actionShow($id = null)
    {
        $this->layout = 'main';
        $id = $id ? $id : \Yii::$app->user->getId();

        $model = $this->finder->findProfileById($id);

        if ($model === null) {
            if($id){
                return $this->redirect(Url::to(['/user/profile/show']));
            }else{
                return $this->redirect(Url::to(['/']));
            }
        }

        $event = $this->getProfileEvent($model);
        
        if(\Yii::$app->request->post('Profile')['avatar']){
            $this->performAjaxValidation($model);
        }else{
            $this->performAjaxValidation($model);
            // $this->sendAjaxValidation($model);
        }

        $this->trigger(self::EVENT_BEFORE_PROFILE_UPDATE, $event);
        if ($model->load(\Yii::$app->request->post()) && $model->save()) {

            \Yii::$app->getSession()->setFlash('success', \Yii::t('user', 'Your profile has been updated'));
            $this->trigger(self::EVENT_AFTER_PROFILE_UPDATE, $event);
            
            if (\Yii::$app->request->isAjax) {
                return $this->renderAjax('show', [
                    'profile' => $model,
                ]);
            }else{
                return $this->refresh();
            }
        }

        return $this->renderPartial('show', [
            'profile' => $model,
        ]);
    }

    public function getResetPassword(User $user)
    {
        if (!$user) {
            throw new InvalidParamException(Yii::t('user', 'User doesn\'t exist'));
        }

        $model = new PasswordForm();
        
        $model->setUser($user);

        $this->performAjaxValidation($model);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('user', 'Account details have been updated'));
        }

        return $model;
    }
}
