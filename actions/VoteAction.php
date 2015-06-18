<?php

namespace artkost\qa\actions;

use artkost\qa\ActiveRecord;
use artkost\qa\models\Vote;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class VoteAction extends Action
{

    public $viewRoute = 'view';

    /**
     * @param $id
     * @param $vote
     * @return Response
     * @throws NotFoundHttpException
     */
    public function run($id, $vote)
    {
        return $this->entityVote($this->findModel($this->modelClass, $id), $vote);
    }

    /**
     * Increment or decrement votes of model by given type
     * @param ActiveRecord $model
     * @param string $type can be 'up' or 'down'
     * @param string $partial template name
     * @param string $format
     * @return Response
     */
    protected function entityVote($model, $type, $partial = 'parts/vote', $format = 'json')
    {
        $data = ['status' => false];

        if ($model && Vote::isUserCan($model, Yii::$app->user->id)) {
            $data = [
                'status' => true,
                'html' => $this->controller->renderPartial($partial, ['model' => Vote::process($model, $type)])
            ];
        }

        if (Yii::$app->request->isAjax) {
            return new Response([
                'data' => $data,
                'format' => $format
            ]);
        }

        return $this->controller->redirect([$this->viewRoute, 'id' => $model['id']]);
    }
}
