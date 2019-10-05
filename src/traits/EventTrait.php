<?php
namespace kilyakus\module\user\traits;

use kilyakus\module\user\events\AuthEvent;
use kilyakus\module\user\events\ConnectEvent;
use kilyakus\module\user\events\FormEvent;
use kilyakus\module\user\events\ProfileEvent;
use kilyakus\module\user\events\ResetPasswordEvent;
use kilyakus\module\user\events\UserEvent;
use kilyakus\module\user\models\Account;
use kilyakus\module\user\models\Profile;
use kilyakus\module\user\models\RecoveryForm;
use kilyakus\module\user\models\Token;
use kilyakus\module\user\models\User;
use yii\authclient\ClientInterface;
use yii\base\Model;

trait EventTrait
{
    protected function getFormEvent(Model $form)
    {
        return \Yii::createObject(['class' => FormEvent::className(), 'form' => $form]);
    }

    protected function getUserEvent(User $user)
    {
        return \Yii::createObject(['class' => UserEvent::className(), 'user' => $user]);
    }

    protected function getProfileEvent(Profile $profile)
    {
        return \Yii::createObject(['class' => ProfileEvent::className(), 'profile' => $profile]);
    }

    protected function getConnectEvent(Account $account, User $user)
    {
        return \Yii::createObject(['class' => ConnectEvent::className(), 'account' => $account, 'user' => $user]);
    }

    protected function getAuthEvent(Account $account, ClientInterface $client)
    {
        return \Yii::createObject(['class' => AuthEvent::className(), 'account' => $account, 'client' => $client]);
    }

    protected function getResetPasswordEvent(Token $token = null, RecoveryForm $form = null)
    {
        return \Yii::createObject(['class' => ResetPasswordEvent::className(), 'token' => $token, 'form' => $form]);
    }
}
