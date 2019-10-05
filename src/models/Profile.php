<?php
namespace kilyakus\module\user\models;

use Yii;
use yii\db\ActiveRecord;
use kilyakus\module\user\traits\ModuleTrait;
use kilyakus\imageprocessor\Avatar;
use kilyakus\imageprocessor\Image;
use kilyakus\cutter\behaviors\CutterBehavior;


class Profile extends ActiveRecord
{
    use ModuleTrait;

    protected $module;

    public function init()
    {
        $this->module = \Yii::$app->getModule('user');
    }

    public function getAvatar($x = 300, $y = null)
    {
        if(!$y){
            $y = $x;
        }
        $avatar = isset($this->avatar) ? $this->avatar : $this->getAvatarUrl();
        $avatar = Image::thumb($avatar, $x, $y);
        return $avatar;
    }

    public function getAvatarUrl($size = 300)
    {
        $gravatar = Yii::getAlias('@webroot') . '/uploads/avatars/gravatar_'.$this->gravatar_id.'.jpg';

        if(empty($this->avatar) && $this->gravatar_id && is_file($gravatar)){
            $avatar = $gravatar;
        }else{
            $avatar = Avatar::get($this->avatar,$this->user->name ? $this->user->name : $this->user->username);
        }
        return $avatar;
    }

    public function getUser()
    {
        return $this->hasOne($this->module->modelMap['User'], ['id' => 'user_id']);
    }

    public function rules()
    {
        return [
            'bioString'            => ['bio', 'string'],
            'phoneString'          => ['phone', 'string'],
            'signatureString'      => ['signature', 'string'],
            'timeZoneValidation'   => ['timezone', 'validateTimeZone'],
            'publicEmailPattern'   => ['public_email', 'email'],
            'gravatarEmailPattern' => ['gravatar_email', 'email'],
            'websiteUrl'           => ['website', 'url'],
            // 'nameLength'           => ['name', 'string', 'max' => 255],
            'publicEmailLength'    => ['public_email', 'string', 'max' => 255],
            'gravatarEmailLength'  => ['gravatar_email', 'string', 'max' => 255],
            'locationLength'       => ['location', 'string', 'max' => 255],
            'websiteLength'        => ['website', 'string', 'max' => 255],
            'avatarImage'          => ['avatar', 'image'],
            ['birthdate', 'default', 'value'=> time()],
            ['interests', 'default', 'value' => null],
            [['anonymous','gender','spouse'],'integer'],
            [['name','second_name','generic_name','body_height','body_weight'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'public_email'   => \Yii::t('user', 'Email (public)'),
            'gravatar_email' => \Yii::t('user', 'Gravatar email'),
            'avatar'         => \Yii::t('user', 'User avatar'),
            'location'       => \Yii::t('user', 'Location'),
            'website'        => \Yii::t('user', 'Website'),
            'bio'            => \Yii::t('user', 'Bio'),
            'timezone'       => \Yii::t('user', 'Time zone'),
            'birthdate'      => \Yii::t('user', 'Date of birth'),
            'name'           => \Yii::t('user', 'Name'),
            'second_name'    => \Yii::t('user', 'Second name'),
            'generic_name'   => \Yii::t('user', 'Generic name'),
            'phone'          => \Yii::t('user', 'Phone number'),
            'interests'      => \Yii::t('user', 'Interests'),
            'messengers'     => \Yii::t('user', 'Messengers'),
            'body_height'    => \Yii::t('easyii', 'Body height'),
            'body_weight'    => \Yii::t('easyii', 'Body weight'),
        ];
    }

    public function behaviors()
    {
        return [
            'avatar' => [
                'class' => CutterBehavior::className(),
                'attributes' => 'avatar',
                'baseDir' => '/uploads/avatars',
                'basePath' => '@webroot/uploads/avatars',
            ],
        ];
    }

    public function validateTimeZone($attribute, $params)
    {
        if (!in_array($this->$attribute, timezone_identifiers_list())) {
            $this->addError($attribute, \Yii::t('user', 'Time zone is not valid'));
        }
    }

    public function getTimeZone()
    {
        try {
            return new \DateTimeZone($this->timezone);
        } catch (\Exception $e) {
            return new \DateTimeZone(\Yii::$app->timeZone);
        }
    }

    public function setTimeZone(\DateTimeZone $timeZone)
    {
        $this->setAttribute('timezone', $timeZone->getName());
    }

    public function toLocalTime(\DateTime $dateTime = null)
    {
        if ($dateTime === null) {
            $dateTime = new \DateTime();
        }

        return $dateTime->setTimezone($this->getTimeZone());
    }

    public function beforeSave($insert)
    {
        if ($this->isAttributeChanged('gravatar_email')) {
            $this->setAttribute('gravatar_id', md5(strtolower(trim($this->getAttribute('gravatar_email')))));
        }

        return parent::beforeSave($insert);
    }

    public function afterDelete()
    {
        parent::afterDelete();

        if($this->avatar) {
            @unlink(Yii::getAlias('@webroot') . $this->avatar);
        }
    }

    public static function tableName()
    {
        return '{{%profile}}';
    }
}
