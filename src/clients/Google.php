<?php
namespace kilyakus\module\user\clients;

use yii\authclient\clients\Google as BaseGoogle;

class Google extends BaseGoogle implements ClientInterface
{
    public $hostedDomain;

    public function buildAuthUrl(array $params = [])
    {
        if ($this->hostedDomain) {
            $params['hd'] = $this->hostedDomain;
        }

        return parent::buildAuthUrl($params);
    }
    
    public function getEmail()
    {
        return isset($this->getUserAttributes()['emails'][0]['value'])
            ? $this->getUserAttributes()['emails'][0]['value']
            : null;
    }

    public function getUsername()
    {
        return;
    }
}
