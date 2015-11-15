<?php

namespace artkost\qa\actions;

use artkost\qa\models\Question;
use artkost\qa\models\QuestionInterface;
use yii\db\Exception;
use yii\helpers\ArrayHelper;

class AskAction extends Action
{
    const EVENT_SUBMITTED = 'questionSubmitted';

    /**
     * @var string
     */
    public $redirectRoute;

    /**
     * @var string
     */
    public $viewFile = 'ask';

    /**
     * @return string
     * @throws Exception
     */
    public function run()
    {
        /** @var Question $model */
        $model = $this->getModel();

        if ($model->load($_POST)) {
            if ($model->haveDraft($_POST)) {
                $model->status = QuestionInterface::STATUS_DRAFT;
            }

            if ($model->save()) {
                $this->trigger(self::EVENT_SUBMITTED);
            }

            return $this->controller->redirect($this->getValue('redirectRoute', ['view', 'id' => $model->id]));
        } else {
            return $this->render(compact('model'));
        }
    }
}
