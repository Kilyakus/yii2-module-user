<?php
namespace kilyakus\module\user\clients;

use Yii;
use yii\authclient\clients\VKontakte as BaseVKontakte;

class VKontakte extends BaseVKontakte implements ClientInterface
{
    public $scope = 'email';

    public function getEmail()
    {
        return $this->getAccessToken()->getParam('email');
    }

    public function getUsername()
    {
        return isset($this->getUserAttributes()['screen_name'])
            ? $this->getUserAttributes()['screen_name']
            : null;
    }

    protected function defaultTitle()
    {
        return Yii::t('user', 'VKontakte');
    }
}
