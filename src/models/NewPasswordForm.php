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
class NewPasswordForm extends Model
{
    /** @var \bin\admin\models\user\User */
    protected $user;

    /**
     * @var string Password
     */
    public $oldPassword;
    public $newPassword;

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
            'passwordValidate' => [
                'oldPassword',
                function ($attribute) {
                    if ($this->user === null || !Password::validate($this->oldPassword, $this->user->password_hash)) {
                        $this->addError($attribute, Yii::t('user', 'Invalid password'));
                    }
                }
            ],
            // password rules
            'passwordRequired' => ['newPassword', 'required'],
            'passwordLength'   => ['newPassword', 'string', 'min' => 6],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        //@todo: add profile attrs
        return [
            'oldPassword'    => Yii::t('easyii', 'Old password'),
            'newPassword' => Yii::t('easyii', 'New password'),
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
        $this->user->password = $this->newPassword;

        if (!$this->user->save()) {
            $this->addError('newPassword', Yii::t('user', 'Invalid login or password'));
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
            ['feedback' => $this, 'link' => Url::to(['/profile/edit'], true)]
        );
        // return true;
    }

}
