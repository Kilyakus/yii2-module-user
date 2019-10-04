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
use yii\helpers\Html;
use yii\helpers\Json;

use bin\admin\modules\chat\api\Chat;
use bin\admin\modules\chat\models\Group;
use bin\admin\modules\chat\models\Relative;
use bin\admin\modules\chat\models\Message;

use yii\db\Query;

class ConnectionController extends \bin\admin\components\AppController
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

    // public function behaviors()
    // {
    //     return [
    //         'access' => [
    //             'class' => AccessControl::className(),
    //             'rules' => [
    //                 // ['allow' => true, 'actions' => ['check-messages'], 'roles' => ['@']],
    //                 ['allow' => true, 'actions' => ['check','friends','friends-all','friends-online','chat','to-user','black-list','add-bl','set-friend','set-favorite'], 'roles' => ['?', '@']],
    //             ],
    //         ],
    //     ];
    // }

    public function actionUserList($q = null) {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => ''],'parents' => []];
        if (!is_null($q)) {
            $query = new Query;
            $query = $query->select('id, username, name AS text')->from('user');

            $query->where(['or',['like', 'username', $q], ['like', 'name', $q]]);

            $query = $query->createCommand()->queryAll();

            $out['results'] = array_values($query);
            foreach ($query as $key => $result) {
                $user = User::findOne($result['id']);
                $avatar = Html::img($user->avatar,['width' => 25, 'height' => 25, 'class' => 'img-circle']);

                $out['results'][$key]['text'] = $avatar . '&nbsp; ' . ($result['text'] ? trim($result['text']) . ($result['username'] != trim($result['text']) ? ' (' . $result['username'] . ')' : '') : $result['username']);
            }
        }
        return $out;
    }

    public function actionGroups($id=0,$expand = true)
    {
        $message = new Message();
        $newone = new Relative();

        $list = User::find()->select(['id as id','username as value','username as label'])
            ->where(['!=','id',Yii::$app->user->id])->asArray()->all();

        if ($id > 0) {
            if (Yii::$app->user->id != Group::findOne($id)->adminId) {
                if (!Relative::find()->where(['userId'=>Yii::$app->user->id,'groupId'=>$id])->exists()) {
                    // Yii::$app->session->setFlash('danger','Вы не являетесь участником этой комнаты');
                    $error = 'Вы не являетесь участником этой комнаты';
                    // return $this->redirect(['message/groups']);
                    return $this->back();
                }

                if (Relative::find()->where(['userId'=>Yii::$app->user->id,'blackListGroup'=>$id])->exists()) {
                    // $error = Yii::$app->session->setFlash('danger','Ваш доступ к данной группе ограничен');
                    $error = 'Ваш доступ к данной группе ограничен';
                    // return $this->redirect(['message/groups']);
                    return $this->back();
                }
            }
            $dataProvider = new ActiveDataProvider([
                'query' => $query = Message::find()->where(['groupId'=>$id])->orderBy('created DESC'),
            ]);
        } else $dataProvider == '';

        if ($newone->load(Yii::$app->request->post())) {
            if (Relative::find()->where(['userId'=>$newone->userId,'groupId'=>$id])->exists()) {
                Yii::$app->session->setFlash('danger','Участник уже в группе');
                return $this->refresh();
            } else {
                if ($newone->save()) {
                    Yii::$app->session->setFlash('success','Участник успешно добавлен');
                    // return $this->refresh();
                }
            }
        }

        if(Yii::$app->request->isAjax){
            return $this->renderAjax('chat/groups', [
                'path' => Url::toRoute(['/user/connection']),
                'expand' => $expand,
                'dataProvider' => $dataProvider,
                'id' => $id,
                'list' => $list,
                'newone' => $newone,
                'message' => $message,
                'error' => $error,
                'admin' => Group::findOne($id)->adminId,
            ]);
        }else{
            return $this->renderPartial('chat/groups', [
                'path' => Url::toRoute(['/user/connection']),
                'expand' => $expand,
                'dataProvider' => $dataProvider,
                'id' => $id,
                'list' => $list,
                'newone' => $newone,
                'message' => $message,
                'error' => $error,
                'admin' => Group::findOne($id)->adminId,
            ]);
        }
    }

    public function actionChat($id=0,$expand = true)
    {
        $message = new Message();
        if ($id > 0) {
            $dataProvider = new ActiveDataProvider([
                'query' => $query = Message::find()->where(['authorId'=>Yii::$app->user->id,'recipientId'=>$id])
                    ->orWhere(['recipientId'=>Yii::$app->user->id,'authorId'=>$id])->orderBy('id DESC'),
            ]);
        } else $dataProvider == '';

        if(Yii::$app->request->isAjax){
            return $this->renderAjax('chat/chat', [
                'path' => Url::toRoute(['/user/connection']),
                'expand' => $expand,
                'dataProvider' => $dataProvider,
                'id' => $id,
                'user' => User::findOne($id),
                'message' => $message,
            ]);
        }else{
            return $this->renderPartial('chat/chat', [
                'path' => Url::toRoute(['/user/connection']),
                'expand' => $expand,
                'dataProvider' => $dataProvider,
                'id' => $id,
                'user' => User::findOne($id),
                'message' => $message,
            ]);
        }
    }

    public function actionFriends($id = null)
    {
        $this->layout = 'main';
        $id = $id ? $id : \Yii::$app->user->getId();

        $profile = $this->finder->findProfileById($id);

        if ($profile === null) {
            if($id){
                return $this->redirect(Url::to(['/user/profile/show']));
            }else{
                return $this->redirect(Url::to(['/']));
            }
        }

        return $this->renderPartial('friends/friends', [
            'profile' => $profile,
            'dataProvider' => Chat::friends($id)['dataProvider'],
            'message' => Chat::friends($id)['message'],
        ]);
    }

    public function actionFriendsAll($id = null)
    {
        $this->layout = 'main';
        $id = $id ? $id : \Yii::$app->user->getId();

        $profile = $this->finder->findProfileById($id);

        if ($profile === null) {
            if($id){
                return $this->redirect(Url::to(['/user/profile/show']));
            }else{
                return $this->redirect(Url::to(['/']));
            }
        }

        $html = $this->renderPartial('friends/_provider', [
            'profile' => $profile,
            'dataProvider' => Chat::friends($id)['dataProvider'],
            'message' => Chat::friends($id)['message'],
        ]);
        return Json::encode($html);
    }

    public function actionFriendsOnline($id = null)
    {
        $this->layout = 'main';
        $id = $id ? $id : \Yii::$app->user->getId();

        $profile = $this->finder->findProfileById($id);

        if ($profile === null) {
            if($id){
                return $this->redirect(Url::to(['/user/profile/show']));
            }else{
                return $this->redirect(Url::to(['/']));
            }
        }
        $html = $this->renderPartial('friends/_provider', [
            'profile' => $profile,
            'dataProvider' => Chat::friendsOnline($id)['dataProvider'],
            'message' => Chat::friendsOnline($id)['message'],
        ]);
        return Json::encode($html);
    }

    public function actionFriendsRequests($id = null)
    {
        $this->layout = 'main';
        $id = $id ? $id : \Yii::$app->user->getId();

        $profile = $this->finder->findProfileById($id);

        if ($profile === null) {
            if($id){
                return $this->redirect(Url::to(['/user/profile/show']));
            }else{
                return $this->redirect(Url::to(['/']));
            }
        }

        $html = $this->renderPartial('friends/_provider', [
            'profile' => $profile,
            'dataProvider' => Chat::friendsRequests($id)['dataProvider'],
            'message' => Chat::friendsRequests($id)['message'],
        ]);
        return Json::encode($html);
    }

    public function actionBlackList($id = null)
    {
        $this->layout = 'main';
        $id = $id ? $id : \Yii::$app->user->getId();

        $profile = $this->finder->findProfileById($id);

        if ($profile === null) {
            if($id){
                return $this->redirect(Url::to(['/user/profile/show']));
            }else{
                return $this->redirect(Url::to(['/']));
            }
        }

        return $this->renderPartial('blacklist/index', [
            'profile' => $profile,
            'dataProvider' => Chat::blackList($id)['dataProvider'],
            'message' => Chat::blackList($id)['message'],
        ]);
    }

    public function actionToUser($expand)
    {
        $model = new Message();
        if ($model->load(Yii::$app->request->post())) {
            if ($blackList = Relative::find()->where(['userId'=>$model->recipientId,'blackList'=>Yii::$app->user->id])->one()) {
                Yii::$app->session->setFlash('danger','Данный пользователь ограничил доступ');
                return $this->redirect(Yii::$app->request->referrer);
            }
            $model->authorId = Yii::$app->user->id;
            $model->created = time();
            if ($model->save()) {
                if(Yii::$app->request->isAjax){
                    if($model->groupId){
                        self::actionGroups($model->groupId,$expand);
                    }elseif($model->recipientId){
                        self::actionChat($model->recipientId,$expand);
                    }
                }else{
                    Yii::$app->session->setFlash('success','Сообщение доставлено');
                    // return $this->redirect(Yii::$app->request->referrer);
                    return $this->redirect(Url::to(['/user/connection/chat', 'id' => $model->recipientId, 'expand' => $expand]));
                }
            }
        }
    }

    public function actionToGroup($expand)
    {
        $model = new Message();
        if ($model->load(Yii::$app->request->post())) {
            if (Relative::find()->where(['userId'=>Yii::$app->user->id,'blackListGroup'=>$model->groupId])->exists()) {
                // Yii::$app->session->setFlash('danger','Ваш доступ к данной группе ограничен');
                return $this->redirect(Yii::$app->request->referrer);
            } else {
                if (Yii::$app->user->id != Group::findOne($model->groupId)->adminId) {
                    if (!Relative::find()->where(['userId'=>Yii::$app->user->id,'groupId'=>$model->groupId])->exists()) {
                        $relat = new Relative();
                        $relat->userId = Yii::$app->user->id;
                        $relat->groupId = $model->groupId;
                        $relat->save();
                    }
                }
                $model->authorId = Yii::$app->user->id;
                $model->created = time();
                if ($model->save()) {
                    if(Yii::$app->request->isAjax){
                        self::actionGroups($model->groupId,$expand);
                    }else{
                        Yii::$app->session->setFlash('success','Сообщение доставлено');
                        // return $this->redirect(Yii::$app->request->referrer);
                        return $this->redirect(Yii::$app->request->referrer);
                    }
                }
            }
        }
    }

    // public function actionCheckMessages($id = 0)
    // {
    //     if(Yii::$app->request->isAjax){
    //         return $this->readMessages($id,true);
    //     }
    //     return false;
    // }

    public function actionCheck($id = 0)
    {
        if($id > 0 && Yii::$app->request->isAjax){

            $html = Message::getPrivateMessages($id,true);

            return $this->pushMessages($id,$html);
        }
        return false;
    }

    public function actionCheckGroup($id = 0)
    {
        if($id > 0 && Yii::$app->request->isAjax){

            $html = Message::getGroupMessages($id,true);

            return $this->pushMessages($id,$html);
        }
        return false;
    }

    public function pushMessages($id = 0, $html)
    {
        if($html){
            return $html;
        }else{
            return false;
        }

    }

    public function actionSetFriend($id)
    {
        Chat::setFriend($id);
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionSetFavorite($id)
    {
        Chat::setFavorite($id);
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionAddBl($id)
    {
        Chat::addBl($id);
        return $this->redirect(Yii::$app->request->referrer);
    }
}
