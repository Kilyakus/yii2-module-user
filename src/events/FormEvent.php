<?php
namespace kilyakus\module\user\events;

use yii\base\Event;
use yii\base\Model;

class FormEvent extends Event
{
    private $_form;

    public function getForm()
    {
        return $this->_form;
    }

    public function setForm(Model $form)
    {
        $this->_form = $form;
    }
}
