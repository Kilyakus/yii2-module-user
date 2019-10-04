<?php
namespace kilyakus\module\user\controllers;

use Yii;
use dektrium\user\Finder;
use kilyakus\module\user\models\RecoveryForm;
use kilyakus\module\user\models\Token;
use kilyakus\module\user\traits\AjaxValidationTrait;
use kilyakus\module\user\traits\EventTrait;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class RecoveryController extends Controller
{
    use AjaxValidationTrait;
    use EventTrait;

    const EVENT_BEFORE_REQUEST = 'beforeRequest';
    const EVENT_AFTER_REQUEST = 'afterRequest';
    const EVENT_BEFORE_TOKEN_VALIDATE = 'beforeTokenValidate';
    const EVENT_AFTER_TOKEN_VALIDATE = 'afterTokenValidate';
    const EVENT_BEFORE_RESET = 'beforeReset';
    const EVENT_AFTER_RESET = 'afterReset';

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
                    ['allow' => true, 'actions' => ['request', 'reset'], 'roles' => ['?']],
                ],
            ],
        ];
    }

    public function actionRequest()
    {
        if (!$this->module->enablePasswordRecovery) {
            throw new NotFoundHttpException();
        }

        $model = \Yii::createObject([
            'class'    => RecoveryForm::className(),
            'scenario' => RecoveryForm::SCENARIO_REQUEST,
        ]);
        $event = $this->getFormEvent($model);

        $user = $this->finder->findUserByEmail(Yii::$app->request->post('recovery-form')['email']);
        
        $this->sendAjaxValidation($model);
        if($user){
            $this->trigger(self::EVENT_BEFORE_REQUEST, $event);
        
            if ($model->load(\Yii::$app->request->post()) && $model->sendRecoveryMessage()) {
                $this->trigger(self::EVENT_AFTER_REQUEST, $event);
                return $this->redirect(\Yii::$app->request->referrer);
            }
        }else{
            \Yii::$app->session->setFlash('danger', \Yii::t('user', 'User not found'));
            return $this->redirect(\Yii::$app->request->referrer);
        }

        return $this->render('request', [
            'model' => $model,
        ]);
    }

    public function actionReset($id, $code)
    {
        if (!$this->module->enablePasswordRecovery) {
            throw new NotFoundHttpException();
        }

        $token = $this->finder->findToken(['user_id' => $id, 'code' => $code, 'type' => Token::TYPE_RECOVERY])->one();
        if (empty($token) || ! $token instanceof Token) {
            throw new NotFoundHttpException();
        }
        $event = $this->getResetPasswordEvent($token);

        $this->trigger(self::EVENT_BEFORE_TOKEN_VALIDATE, $event);

        if ($token === null || $token->isExpired || $token->user === null) {
            $this->trigger(self::EVENT_AFTER_TOKEN_VALIDATE, $event);
            \Yii::$app->session->setFlash(
                'danger',
                \Yii::t('user', 'Recovery link is invalid or expired. Please try requesting a new one.')
            );
            return $this->render('/message', [
                'title'  => \Yii::t('user', 'Invalid or expired link'),
                'module' => $this->module,
            ]);
        }

        $model = \Yii::createObject([
            'class'    => RecoveryForm::className(),
            'scenario' => RecoveryForm::SCENARIO_RESET,
        ]);
        $event->setForm($model);

        $this->sendAjaxValidation($model);
        $this->trigger(self::EVENT_BEFORE_RESET, $event);

        if ($model->load(\Yii::$app->getRequest()->post()) && $model->resetPassword($token)) {
            $this->trigger(self::EVENT_AFTER_RESET, $event);
            return $this->render('/message', [
                'title'  => \Yii::t('user', 'Password has been changed'),
                'module' => $this->module,
            ]);
        }

        return $this->render('reset', [
            'model' => $model,
        ]);
    }
}
