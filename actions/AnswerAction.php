<?php

namespace artkost\qa\actions;

use artkost\qa\models\Answer;
use artkost\qa\models\Question;
use yii\web\Response;

class AnswerAction extends Action
{
    const EVENT_SUBMITTED = 'answerSubmitted';

    /**
     * @var string
     */
    public $viewRoute = 'view';

    /**
     * @var string
     */
    public $viewFile = 'answer';

    /**
     * @param $id
     * @return string|Response
     */
    public function run($id)
    {
        /** @var Answer $model */
        $model = $this->getModel(['question_id' => $id]);

        /** @var Question $question */
        $question = $model->question;

        if (!$question) {
            $this->notFoundException();
        }

        if ($model->load($_POST) && $model->save()) {
            $this->trigger(self::EVENT_SUBMITTED);

            return $this->controller->redirect([$this->viewRoute, 'id' => $question->id, 'alias' => $question->alias]);
        } else {
            return $this->render(compact('model', 'question'));
        }
    }
}
