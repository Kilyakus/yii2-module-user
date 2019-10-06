<?php
namespace kilyakus\module\user\controllers;

use yii\db\Query;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\helpers\Html;
use kilyakus\module\user\Finder;

class DataController extends Controller
{
    protected $finder;

    public function __construct($id, $module, Finder $finder, $config = [])
    {
        $this->finder = $finder;
        parent::__construct($id, $module, $config);
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    ['allow' => true, 'actions' => ['user-list'], 'roles' => ['@']],
                ],
            ],
        ];
    }

    public function actionUserList($q = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $className = $this->module->modelMap['User'];

        $out = ['results' => ['id' => '', 'text' => ''],'parents' => []];

        if (!is_null($q)) {

            $query = new Query;

            $query = $query->select('id, username, name AS text')->from('user');

            $query->where(['or',['like', 'id', $q], ['like', 'username', $q], ['like', 'name', $q]]);

            $query = $query->createCommand()->queryAll();

            $out['results'] = array_values($query);

            foreach ($query as $key => $result) {

                $user = $className::findOne($result['id']);

                if($user){

                    $avatar = Html::img($user->avatar,['width' => 25, 'height' => 25, 'class' => 'img-circle']);

                    $out['results'][$key]['text'] = $avatar . '&nbsp; ' . $user->abbreviate;

                }

            }

        }

        return $out;
    }
}
