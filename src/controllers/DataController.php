<?php
namespace kilyakus\module\user\controllers;

use Yii;
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

                    $avatar = Html::img($user->avatar,['width' => 40, 'height' => 40, 'class' => 'img-circle']);

                    $html = '<div class="kt-user-card-v2">
                                <div class="kt-user-card-v2__pic">'.$avatar.'</div>
                                <div class="kt-user-card-v2__details">
                                    <span class="kt-user-card-v2__name">'.$user->abbreviate.'</span>
                                    <span class="kt-user-card-v2__desc">'.array_values(Yii::$app->authManager->getPermissionsByUser($user->id))[0]->description.'</span>
                                </div>
                            </div>';

                    $out['results'][$key]['text'] = $html;


                }

            }

        }

        return $out;
    }
}
