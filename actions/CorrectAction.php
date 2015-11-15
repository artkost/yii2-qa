<?php

namespace artkost\qa\actions;

use artkost\qa\models\Answer;
use artkost\qa\models\Question;
use Yii;
use yii\web\Response;

class CorrectAction extends Action
{
    public $redirectRoute;

    public $partialViewFile = 'parts/answer-correct';

    public function run($id, $questionId)
    {
        /** @var Answer $answer */
        $answer = $this->findModelByID($id);
        /** @var Question $question */
        $question = $answer->question;

        $response = [
            'data' => [
                'status' => false
            ],
            'format' => 'json'
        ];

        if ($question && $question->id == $questionId && $question->isAuthor()) {
            $response['data']['status'] = $answer->toggleCorrect();
            $response['data']['html'] = $this->controller->renderPartial($this->partialViewFile, compact('answer', 'question'));
        }

        if (Yii::$app->request->isAjax) {
            return new Response($response);
        }

        return $this->controller->redirect($this->getValue('redirectRoute', ['view', 'id' => $questionId]));
    }
}
