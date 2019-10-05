<?php
namespace kilyakus\module\user\clients;

use Yii;
use yii\authclient\clients\Yandex as BaseYandex;

class Yandex extends BaseYandex implements ClientInterface
{
    public function getEmail()
    {
        $emails = isset($this->getUserAttributes()['emails'])
            ? $this->getUserAttributes()['emails']
            : null;

        if ($emails !== null && isset($emails[0])) {
            return $emails[0];
        } else {
            return null;
        }
    }

    public function getUsername()
    {
        return isset($this->getUserAttributes()['login'])
            ? $this->getUserAttributes()['login']
            : null;
    }

    protected function defaultTitle()
    {
        return Yii::t('user', 'Yandex');
    }
}
