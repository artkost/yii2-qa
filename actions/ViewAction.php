<?php

namespace artkost\qa\actions;

use artkost\qa\models\Answer;
use artkost\qa\models\Question;
use artkost\qa\models\Vote;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\web\NotFoundHttpException;

class ViewAction extends Action
{

    public $answerClass;
    public $voteClass;

    public $viewFile = 'view';

    /**
     * @param $id
     * @throws NotFoundHttpException
     * @return string
     */
    public function run($id)
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

            /** @var Answer $answer */
            $answer = Yii::$container->get($this->answerClass);

            /** @var Vote $answer */
            $vote = Yii::$container->get($this->voteClass);

            /** @var ActiveQuery $query */
            $query = $answer::find()->with('user');

            $answerOrder = $answer->applyOrder($query, Yii::$app->request->get('answers', 'votes'));

            $answerDataProvider = new ActiveDataProvider([
                'query' => $query->where(['question_id' => $model->id]),
                'pagination' => [
                    'pageSize' => 20,
                ],
            ]);

            return $this->render(compact('model', 'answer', 'vote', 'answerDataProvider', 'answerOrder'));
        }

        return $this->notFoundException();
    }
}
