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

class PlacesController extends \bin\admin\components\AppController
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
                    ['allow' => true, 'actions' => ['show','tab'], 'roles' => ['?', '@']],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $profile = $this->finder->findProfileById(\Yii::$app->user->getId());

        $items = Catalog::items(['where' => ['and',['created_by' => Yii::$app->user->identity->id],['status' => [0,1]]],'pagination' => ['pageSize' => 10]]);

        return $this->renderPartial('index', [
            'profile' => $profile,
            'items' => $items,
        ]);
    }

    public function actionTab($tab = null)
    {
        $items = Catalog::items(['where' => ['and',['created_by' => Yii::$app->user->identity->id],['status' => 2]],'pagination' => ['pageSize' => 10]]);

        $html = $this->renderPartial('_provider',[
            'items' => $items,
        ]);

        return Json::encode($html);
    }
}
