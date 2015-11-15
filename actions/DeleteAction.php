<?php

namespace artkost\qa\actions;

use artkost\qa\models\Question;
use yii\helpers\ArrayHelper;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class DeleteAction extends Action
{
    public $redirectRoute;

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
        $model = $this->findModelByID($id);

        if ($model->isAuthor() && $model->delete()) {
            return $this->controller->redirect($this->getValue('redirectRoute', ['index']));
        }

        return $this->forbiddenException();
    }
}
