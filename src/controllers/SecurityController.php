<?php
namespace kilyakus\module\user\controllers;

use Yii;
use dektrium\user\Finder;
use kilyakus\module\user\models\Account;
use kilyakus\module\user\models\LoginForm;
use kilyakus\module\user\models\User;
use kilyakus\module\user\models\Profile;
use kilyakus\module\user\Module;
use kilyakus\module\user\traits\AjaxValidationTrait;
use kilyakus\module\user\traits\EventTrait;
use kilyakus\module\user\filters\AccessRule;
use yii\authclient\AuthAction;
use yii\authclient\ClientInterface;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;

class SecurityController extends \bin\admin\components\AppController
{
    use AjaxValidationTrait;
    use EventTrait;

    const EVENT_BEFORE_LOGIN = 'beforeLogin';
    const EVENT_AFTER_LOGIN = 'afterLogin';
    const EVENT_BEFORE_LOGOUT = 'beforeLogout';
    const EVENT_AFTER_LOGOUT = 'afterLogout';
    const EVENT_BEFORE_AUTHENTICATE = 'beforeAuthenticate';
    const EVENT_AFTER_AUTHENTICATE = 'afterAuthenticate';
    const EVENT_BEFORE_CONNECT = 'beforeConnect';
    const EVENT_AFTER_CONNECT = 'afterConnect';

    protected $finder;

    public function __construct($id, $module, Finder $finder, $config = [])
    {
        $this->finder = $finder;
        parent::__construct($id, $module, $config);
    }

    public $enableCsrfValidation = false;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => AccessRule::className(),
                ],
                'rules' => [
                    ['allow' => true, 'actions' => ['login', 'auth','networks'], 'roles' => ['?']],
                    ['allow' => true, 'actions' => ['login', 'auth','networks', 'logout'], 'roles' => ['@']],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                    'networks' => ['post'],
                ],
            ],

        ];
    }

    public function actions()
    {
        return [
            'auth' => [
                'class' => AuthAction::className(),
                // if user is not logged in, will try to log him in, otherwise
                // will try to connect social account to user.
                'successCallback' => Yii::$app->user->isGuest
                    ? [$this, 'authenticate']
                    : [$this, 'connect'],
            ],
        ];
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            $this->goHome();
        }

        $model = Yii::createObject(LoginForm::className());
        $event = $this->getFormEvent($model);

        $this->sendAjaxValidation($model);

        $this->trigger(self::EVENT_BEFORE_LOGIN, $event);

        if ($model->load(Yii::$app->getRequest()->post()) && $model->login()) {
            $this->trigger(self::EVENT_AFTER_LOGIN, $event);
            return $this->redirect((Yii::$app->user->returnUrl && Yii::$app->user->returnUrl != '/') ? Yii::$app->user->returnUrl : Yii::$app->request->referrer);
        }

        return $this->render('login', [
            'model'  => $model,
            'module' => $this->module,
        ]);
    }

    public function actionLogout()
    {
        $event = $this->getUserEvent(Yii::$app->user->identity);

        $this->trigger(self::EVENT_BEFORE_LOGOUT, $event);

        Yii::$app->getUser()->logout();

        $this->trigger(self::EVENT_AFTER_LOGOUT, $event);

        return $this->goHome();
    }

    public function actionNetworks()
    {
        $response = json_decode(file_get_contents('http://ulogin.ru/token.php?token=' . $_POST['token'] . '&host=' . $_SERVER['HTTP_HOST']), true);

        if(Yii::$app->user->isGuest){
            $identityUser = User::find()->where(['email' => $response['email']])->one();
            $identityAccount = User::findOne(Account::find()->where(['client_id' => $response['uid']])->one()->user_id);
        }else{
            $identityUser = User::findOne(\Yii::$app->user->getId());
            $identityAccount = User::findOne(Account::find()->where(['client_id' => $response['uid']])->one()->user_id);
        }

        if($identityAccount){

            Yii::$app->user->login($identityAccount);

        }else{

            if(!$identityUser){
                $user = new User();
                $user->username = $response['email'];
                $user->email = $response['email'];
                $user->password = $response['uid'];
                $user->save(false);

                $profile = Profile::find()->where(['user_id' => $user->id])->one();
                $profile->name = $response['first_name'];
                $profile->gravatar_email = $response['email'];
                $profile->save(false);

                $currentUser = $user->id;
            }

            $currentUser =  $user->id ? $user->id : $identityUser->id;

            $account = new Account();
            $account->user_id = $currentUser;
            $account->provider = $response['network'];
            $account->client_id = $response['uid'];
            $account->email = $response['email'];
            $account->save(false);

            $identityAccount = User::find()->where(['id'=>$currentUser])->one();
            Yii::$app->user->login($identityAccount);
        }

        return $this->redirect(Yii::$app->request->referrer);
        
    }

    public function authenticate(ClientInterface $client)
    {
        $account = $this->finder->findAccount()->byClient($client)->one();

        if (!$this->module->enableRegistration && ($account === null || $account->user === null)) {
            Yii::$app->session->setFlash('danger', Yii::t('user', 'Registration on this website is disabled'));
            $this->action->successUrl = Url::to(['/user/security/login']);
            return;
        }

        if ($account === null) {
            $accountObj = Yii::createObject(Account::className());
            $account = $accountObj::create($client);
        }

        $event = $this->getAuthEvent($account, $client);

        $this->trigger(self::EVENT_BEFORE_AUTHENTICATE, $event);

        if ($account->user instanceof User) {
            if ($account->user->isBlocked) {
                Yii::$app->session->setFlash('danger', Yii::t('user', 'Your account has been blocked.'));
                $this->action->successUrl = Url::to(['/user/security/login']);
            } else {
                $account->user->updateAttributes(['last_login_at' => time()]);
                Yii::$app->user->login($account->user, $this->module->rememberFor);
                $this->action->successUrl = Yii::$app->getUser()->getReturnUrl();
            }
        } else {
            $this->action->successUrl = $account->getConnectUrl();
        }

        $this->trigger(self::EVENT_AFTER_AUTHENTICATE, $event);
    }

    public function connect(ClientInterface $client)
    {
        $account = Yii::createObject(Account::className());
        $event   = $this->getAuthEvent($account, $client);

        $this->trigger(self::EVENT_BEFORE_CONNECT, $event);

        $account->connectWithUser($client);

        $this->trigger(self::EVENT_AFTER_CONNECT, $event);

        $this->action->successUrl = Url::to(['/user/settings/networks']);
    }
}
