<?php
namespace kilyakus\module\user\events;

use kilyakus\module\user\models\Profile;
use yii\base\Event;

class ProfileEvent extends Event
{
    private $_profile;

    public function getProfile()
    {
        return $this->_profile;
    }

    public function setProfile(Profile $form)
    {
        $this->_profile = $form;
    }
}
