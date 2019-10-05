<?php
namespace kilyakus\module\user\models;

use kilyakus\module\user\Finder;
use kilyakus\module\user\helpers\Password;
use kilyakus\module\user\traits\ModuleTrait;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use Yii;
use yii\base\Model;

class LoginForm extends Model
{
    use ModuleTrait;

    public $login;
    public $password;
    public $rememberMe = false;

    protected $user;
    protected $finder;

    public function __construct(Finder $finder, $config = [])
    {
        $this->finder = $finder;
        parent::__construct($config);
    }

    public static function loginList()
    {
        /** @var \kilyakus\module\user\Module $module */
        $module = \Yii::$app->getModule('user');

        $userModel = $module->modelMap['User'];

        return ArrayHelper::map($userModel::find()->where(['blocked_at' => null])->all(), 'username', function ($user) {
            return sprintf('%s (%s)', Html::encode($user->username), Html::encode($user->email));
        });
    }

    public function attributeLabels()
    {
        return [
            'login'      => Yii::t('user', 'Login'),
            'password'   => Yii::t('user', 'Password'),
            'rememberMe' => Yii::t('user', 'Remember me next time'),
        ];
    }

    public function rules()
    {
        $rules = [
            'loginTrim' => ['login', 'trim'],
            'requiredFields' => [['login'], 'required'],
            'confirmationValidate' => [
                'login',
                function ($attribute) {
                    if ($this->user !== null) {
                        $confirmationRequired = $this->module->enableConfirmation
                            && !$this->module->enableUnconfirmedLogin;
                        if ($confirmationRequired && !$this->user->getIsConfirmed()) {
                            $this->addError($attribute, Yii::t('user', 'You need to confirm your email address'));
                        }
                        if ($this->user->getIsBlocked()) {
                            $this->addError($attribute, Yii::t('user', 'Your account has been blocked'));
                        }
                    }
                }
            ],
            'rememberMe' => ['rememberMe', 'boolean'],
        ];

        if (!$this->module->debug) {
            $rules = array_merge($rules, [
                'requiredFields' => [['login', 'password'], 'required'],
                'passwordValidate' => [
                    'password',
                    function ($attribute) {
                        if ($this->user === null || !Password::validate($this->password, $this->user->password_hash)) {
                            $this->addError($attribute, Yii::t('user', 'Invalid login or password'));
                        }
                    }
                ]
            ]);
        }

        return $rules;
    }

    public function validatePassword($attribute, $params)
    {
      if ($this->user === null || !Password::validate($this->password, $this->user->password_hash))
        $this->addError($attribute, Yii::t('user', 'Invalid login or password'));
    }

    public function login()
    {
        if ($this->validate() && $this->user) {
            $isLogged = Yii::$app->getUser()->login($this->user, $this->rememberMe ? $this->module->rememberFor : 0);

            if ($isLogged) {
                $this->user->updateAttributes(['last_login_at' => time()]);
            }

            return $isLogged;
        }

        return false;
    }

    public function formName()
    {
        return 'login-form';
    }

    public function beforeValidate()
    {
        if (parent::beforeValidate()) {
            $this->user = $this->finder->findUserByUsernameOrEmail(trim($this->login));

            return true;
        } else {
            return false;
        }
    }
}
