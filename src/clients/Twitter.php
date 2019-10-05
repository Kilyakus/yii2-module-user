<?php
namespace kilyakus\module\user\clients;

use yii\authclient\clients\Twitter as BaseTwitter;
use yii\helpers\ArrayHelper;

class Twitter extends BaseTwitter implements ClientInterface
{
    public function getUsername()
    {
        return ArrayHelper::getValue($this->getUserAttributes(), 'screen_name');
    }

    public function getEmail()
    {
        return ArrayHelper::getValue($this->getUserAttributes(), 'email');
    }
}
