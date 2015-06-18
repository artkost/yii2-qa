<?php

namespace artkost\qa\actions;

use artkost\qa\models\Question;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class DeleteAction extends Action
{
    public $indexRoute = 'index';

    /**
     * @param $id
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     * @throws \Exception
     * @return string
     */
    public function run($id)
    {
        /** @var Question $model */
        $model = $this->findModel($this->modelClass, $id);

        if ($model->isAuthor() && $model->delete()) {
            return $this->controller->redirect([$this->indexRoute]);
        }

        return $this->forbiddenException();
    }
}
