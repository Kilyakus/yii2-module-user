<?php

namespace kilyakus\module\user\models;

use yii\helpers\Url;
use kilyakus\module\user\helpers\Password;
use kilyakus\module\user\models\User;
use Yii;
use yii\base\Model;
use bin\admin\helpers\Mail;
use bin\admin\models\Setting;


/**
 * Registration form collects user input on registration process, validates it and creates new User model.
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class PasswordForm extends Model
{
    /** @var \bin\admin\models\user\User */
    protected $user;

    /**
     * @var string Password
     */
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

    /**
     * @inheritdoc
     */
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
            // password rules
            'passwordLength'   => ['new_password', 'string', 'min' => 6],
        ];
    }

    /**
     * @inheritdoc
     */
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

        Yii::$app->session->setFlash(
            'info',
            Yii::t('user', 'Your password had been updated')
        );
        
        return Mail::send(
            'ak@elgrow.ru',
            'Change password',
            '@app/mail/layouts/change_password',
            ['feedback' => $this, 'link' => Url::toRoute(['/user/profile/edit'], true)]
        );
        // return true;
    }

}
