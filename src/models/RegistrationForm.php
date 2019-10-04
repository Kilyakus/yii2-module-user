<?php
namespace kilyakus\module\user\models;

use kilyakus\module\user\traits\ModuleTrait;
use bin\rbac\models\Agreements;
use Yii;
use yii\base\Model;

class RegistrationForm extends Model
{
    use ModuleTrait;

    public $email;
    public $username;
    public $password;
    public $role;
    public $agreement;

    public function rules()
    {
        $user = $this->module->modelMap['User'];

        return [
            // username rules
            'usernameTrim'     => ['username', 'trim'],
            'usernameLength'   => ['username', 'string', 'min' => 3, 'max' => 255],
            'usernamePattern'  => ['username', 'match', 'pattern' => $user::$usernameRegexp],
            'usernameRequired' => ['username', 'required'],
            'usernameUnique'   => [
                'username',
                'unique',
                'targetClass' => $user,
                'message' => Yii::t('user', 'This username has already been taken')
            ],
            // email rules
            'emailTrim'     => ['email', 'trim'],
            'emailRequired' => ['email', 'required'],
            'emailPattern'  => ['email', 'email'],
            'emailUnique'   => [
                'email',
                'unique',
                'targetClass' => $user,
                'message' => Yii::t('user', 'This email address has already been taken')
            ],

            // password rules
            'passwordRequired' => ['password', 'required', 'skipOnEmpty' => $this->module->enableGeneratingPassword],
            'passwordLength'   => ['password', 'string', 'min' => 6, 'max' => 72],

            // roles rules
            'roleRequired' => ['role', 'required'],
            'roleLength'   => ['role', 'string'],

            // roles rules
            'agreementRequired' => ['agreement', 'required','message' => Yii::t('user', 'Необходимо дать согласие на обработку персональных данных!')],
            'agreementLength'   => ['agreement', 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'email'     => Yii::t('user', 'Email'),
            'username'  => Yii::t('user', 'Username'),
            'password'  => Yii::t('user', 'Password'),
            'role'      => Yii::t('user', 'Roles'),
            'agreement' => Yii::t('user', 'Terms of use'),
        ];
    }

    public function formName()
    {
        return 'register-form';
    }

    public function register()
    {
        $user = Yii::createObject(User::className());
        
        $user->setScenario('register');
        $this->loadAttributes($user);

        if (!$user->register() || !$this->agreement) {
            return false;
        }else{
            
            // if($this->role){

            //     foreach (Yii::$app->authManager->getPermissions() as $key => $permission) {
            //         if($this->role == $permission->name){
            //             Yii::$app->authManager->assign(Yii::$app->authManager->getPermission($this->role), $user->id);
            //         }
            //     }

            // }else{
                Yii::$app->authManager->assign(Yii::$app->authManager->getPermission('users'), $user->id);

                if(Yii::$app->getModule('forum')){
                    if(!Yii::$app->getModule('forum')->rbac->checkAccess($user->id, 'forumUser')){
                        Yii::$app->getModule('forum')->rbac->assign(Yii::$app->getModule('forum')->rbac->getRole('forumUser'), $user->id);
                    }
                }
            // }

        }

        if($this->module->enableConfirmation){

            Yii::$app->session->setFlash(
                'success',
                Yii::t(
                    'user',
                    'Your account has been created and a message with further instructions has been sent to your email'
                )
            );
            
        }else{
            Yii::$app->user->login($user);

            Yii::$app->session->setFlash('success',Yii::t('user','Your account has been created'));
        }

        return true;
    }

    protected function loadAttributes(User $user)
    {
        $user->setAttributes($this->attributes);
    }
}
