<?php

namespace artkost\qa\actions;

use artkost\qa\models\Question;
use artkost\qa\models\QuestionInterface;
use artkost\qa\Module;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class EditAction extends Action
{
    const EVENT_SUBMITTED = 'questionSubmitted';

    /**
     * @var string
     */
    public $redirectRoute;

    /**
     * @param $id
     * @throws ForbiddenHttpException
     * @throws Exception
     * @throws NotFoundHttpException
     * @return string
     */
    public function run($id)
    {
        /** @var Question $model */
        $model = $this->findModelByID($id);

        if ($model->isAuthor()) {
            if ($model->load($_POST)) {
                if ($model->haveDraft($_POST)) {
                    $model->status = QuestionInterface::STATUS_DRAFT;
                } else {
                    $model->status = QuestionInterface::STATUS_PUBLISHED;
                }

                if ($model->save()) {
                    $this->trigger(self::EVENT_SUBMITTED);
                }

                return $this->controller->redirect($this->getValue('redirectRoute', ['view', 'id' => $model->id]));
            }

            return $this->render(compact('model'));
        }

        return $this->forbiddenException();
    }
}
