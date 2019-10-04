<?php
namespace kilyakus\module\user;

use yii\base\Module as BaseModule;
use dektrium\user\Module as BaseUser;

class UserModule extends BaseUser
{
    const VERSION = '0.9.12';

    const STRATEGY_INSECURE = 0;
    const STRATEGY_DEFAULT = 1;
    const STRATEGY_SECURE = 2;
    public $enableFlashMessages = true;
    public $enableRegistration = true;
    public $enableGeneratingPassword = false;
    public $enableConfirmation = true;
    public $enableUnconfirmedLogin = false;
    public $enablePasswordRecovery = true;
    public $enableAccountDelete = false;
    public $enableImpersonateUser = true;
    public $emailChangeStrategy = self::STRATEGY_DEFAULT;
    public $rememberFor = 1209600; // two weeks
    public $confirmWithin = 86400; // 24 hours
    public $recoverWithin = 21600; // 6 hours
    public $cost = 10;
    public $admins = [];
    public $adminPermission;
    public $mailer = [];
    public $modelMap = [];
    public $urlPrefix = 'user';
    public $debug = false;
    public $dbConnection = 'db';
    public $urlRules = [
        '/'                               => 'profile/show',
        '<id:\d+>'                               => 'profile/show',
        '<action:(login|logout|auth)>'           => 'security/<action>',
        '<action:(register|resend)>'             => 'registration/<action>',
        'confirm/<id:\d+>/<code:[A-Za-z0-9_-]+>' => 'registration/confirm',
        'forgot'                                 => 'recovery/request',
        'recover/<id:\d+>/<code:[A-Za-z0-9_-]+>' => 'recovery/reset',
        'settings/<action:\w+>'                  => 'settings/<action>'
    ];

    public function getDb()
    {
        return \Yii::$app->get($this->dbConnection);
    }
}
