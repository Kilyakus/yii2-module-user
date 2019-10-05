<?php
namespace kilyakus\module\user\clients;

use yii\authclient\clients\LinkedIn as BaseLinkedIn;

class LinkedIn extends BaseLinkedIn implements ClientInterface
{
    public function getEmail()
    {
        return isset($this->getUserAttributes()['email-address'])
            ? $this->getUserAttributes()['email-address']
            : null;
    }

    public function getUsername()
    {
        return;
    }
}
