<?php
namespace kilyakus\module\user\clients;

use yii\authclient\clients\Facebook as BaseFacebook;

class Facebook extends BaseFacebook implements ClientInterface
{
    public function getEmail()
    {
        return isset($this->getUserAttributes()['email'])
            ? $this->getUserAttributes()['email']
            : null;
    }

    public function getUsername()
    {
        return;
    }
}
