<?php

namespace artkost\qa\actions;

use artkost\qa\models\Answer;
use artkost\qa\models\Question;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

class ViewAction extends Action
{

    public $answerClass;

    public $viewFile = 'view';

    /**
     * @param $id
     * @throws NotFoundHttpException
     * @return string
     */
    public function actionView($id)
    {
        /** @var Question $model */
        $modelClass = get_class($this->getModel());
        $model = $modelClass::find()->with('user')->where(['id' => $id])->one();

        if ($model) {
            if ($model->isDraft() && !$model->isAuthor()) {
                $this->notFoundException();
            }

            if ($model->isUserUnique()) {
                $model->updateCounters(['views' => 1]);
            }

            $answerClass = $this->answerClass;
            /** @var Answer $model */
            $answer = new $answerClass;

            $query = $answerClass::find()->with('user');

            $answerOrder = $answerClass::applyOrder($query, Yii::$app->request->get('answers', 'votes'));

            $answerDataProvider = new ActiveDataProvider([
                'query' => $query->where(['question_id' => $model->id]),
                'pagination' => [
                    'pageSize' => 20,
                ],
            ]);

            return $this->render(compact('model', 'answer', 'answerDataProvider', 'answerOrder'));
        }

        return $this->notFoundException();
    }
}
