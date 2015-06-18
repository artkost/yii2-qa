<?php

namespace artkost\qa\actions;

use artkost\qa\models\Question;
use Yii;
use yii\web\Response;

class FavoriteAction extends Action
{

    public $viewRoute = 'view';
    public $partialViewFile = 'parts/favorite';

    /**
     * @param $id
     * @return string
     */
    public function run($id)
    {
        /** @var Question $model */
        $modelClass = get_class($this->getModel());
        $model = $modelClass::find()->with('favorite')->where(['id' => $id])->one();

        $response = [
            'data' => ['status' => false],
            'format' => 'json'
        ];

        if ($model && $status = $model->toggleFavorite()) {
            $response['data']['status'] = $status;
            $response['data']['html'] = $this->controller->renderPartial($this->partialViewFile, compact('model'));
        }

        if (Yii::$app->request->isAjax) {
            return new Response($response);
        }

        return $this->controller->redirect([$this->viewRoute, 'id' => $id]);
    }

}
