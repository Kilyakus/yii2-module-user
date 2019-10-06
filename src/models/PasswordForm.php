<?php
namespace kilyakus\module\user\models;

use Yii;
use kilyakus\module\user\helpers\Password;
use yii\base\Model;

class PasswordForm extends Model
{
    protected $user;

    public $current_password;
    public $new_password;

    public function setUser(User $user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function rules()
    {
        return [
            'passwordTrim' => ['current_password', 'trim'],
            'requiredFields' => [['current_password','new_password'], 'required'],
            'passwordValidate' => [
                'current_password',
                function ($attribute) {
                    if ($this->user === null || !Password::validate($this->current_password, $this->user->password_hash)) {
                        $this->addError($attribute, Yii::t('user', 'Invalid password'));
                    }
                }
            ],

            'passwordLength'   => ['new_password', 'string', 'min' => 6],
        ];
    }

    public function attributeLabels()
    {
        //@todo: add profile attrs
        return [
            'current_password'    => Yii::t('user', 'Current password'),
            'new_password' => Yii::t('user', 'New password'),
        ];
    }

    /**
     * Updates password
     *
     * @return bool
     */
    public function save()
    {
        if (!$this->validate()) {
            return false;
        }

        $this->user->scenario = 'update';
        $this->user->password = $this->new_password;

        if (!$this->user->save()) {
            $this->addError('new_password', Yii::t('user', 'Invalid password'));
            return false;
        }

        return Yii::$app->session->setFlash('info', Yii::t('user', 'Your password had been updated'));
    }

}
