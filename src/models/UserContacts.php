<?php
namespace kilyakus\module\user\models;

class UserContacts extends \kilyakus\modules\components\ActiveRecord
{
    public static function tableName()
    {
        return 'user_contacts';
    }

    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['phone_number',], 'string'],
            [['latitude','longitude',], 'string', 'max' => 100],
            [['country_id','region_id','city_id','street_id','street_number_id','status',], 'integer'],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public static function find()
    {
        return new \kilyakus\modules\components\ActiveQuery(get_called_class());
    }
}