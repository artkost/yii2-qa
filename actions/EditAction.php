<?php

namespace artkost\qa\actions;

use artkost\qa\models\Question;
use artkost\qa\models\QuestionInterface;
use artkost\qa\Module;
use yii\db\Exception;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class EditAction extends Action
{
    const EVENT_SUBMITTED = 'questionSubmitted';

    /**
     * @var string
     */
    public $viewRoute = 'view';

    /**
     * @var string
     */
    public $viewFile = 'edit';

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
        $model = $this->findModel($this->modelClass, $id);

        if ($model->isAuthor()) {
            if ($model->load($_POST)) {
                if ($model->haveDraft($_POST)) {
                    $model->status = QuestionInterface::STATUS_DRAFT;
                } else {
                    $model->status = QuestionInterface::STATUS_PUBLISHED;
                }

                if (!$model->save()) {
                    throw new Exception(Module::t('main', 'Error save question'));
                }

                $this->trigger(self::EVENT_SUBMITTED);
                return $this->controller->redirect([$this->viewRoute, 'id' => $model->id]);
            }

            return $this->render(compact('model'));
        }

        return $this->forbiddenException();
    }
}
