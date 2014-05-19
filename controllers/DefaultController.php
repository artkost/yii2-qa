<?php

namespace artkost\qa\controllers;

use Yii;
use artkost\qa\ActiveRecord;
use artkost\qa\Asset;
use artkost\qa\models\Answer;
use artkost\qa\models\Question;
use artkost\qa\models\QuestionSearch;
use artkost\qa\models\Tag;
use artkost\qa\models\Vote;
use artkost\qa\models\Favorite;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\filters\VerbFilter;

class DefaultController extends Controller
{
    /**
     * @var \artkost\qa\Module
     */
    public $module;

    public function init()
    {
        parent::init();
        Asset::register($this->view);
    }

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'tag-suggest'],
                        'roles' => ['?', '@']
                    ],
                    [
                        'allow' => true,
                        'actions' => ['ask', 'answer', 'edit', 'delete', 'my', 'favorite', 'question-favorite', 'question-vote', 'answer-vote'],
                        'roles' => ['@']
                    ],
                    [
                        'allow' => false
                    ]
                ]
            ]
        ];
    }

    public function actionIndex()
    {
        $searchModel = new  QuestionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        $models = $dataProvider->getModels();

        return $this->render('index', compact('searchModel', 'models', 'dataProvider'));
    }

    public function actionFavorite()
    {
        $searchModel = new  QuestionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        $dataProvider->query->joinWith('favorites', true, 'RIGHT JOIN')->where([Question::tableName().'.user_id' => Yii::$app->user->id]);
        $models = $dataProvider->getModels();

        return $this->render('index', compact('searchModel', 'models', 'dataProvider'));
    }

    public function actionMy()
    {
        $searchModel = new  QuestionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        $dataProvider->query->where(['user_id' => Yii::$app->user->id]);
        $models = $dataProvider->getModels();
        return $this->render('index', compact('searchModel', 'models', 'dataProvider'));
    }

    public function actionTagSuggest()
    {
        $tags = [];
        if (isset($_GET['q']) && ($keyword = trim($_GET['q'])) !== '') {
            $tags = Tag::suggest($keyword);
        }

        return new Response([
            'data' => $tags,
        ]);
    }

    public function actionView($id)
    {
        /** @var Question $model */
        $model = Question::find()->with('user')->where(['id' => $id])->one();

        if ($model) {
            if ($this->module->isUserUnique()) {
                $model->updateCounters(['views' => 1]);
            }

            $answer = new Answer;

            $query = Answer::find()->with('user');

            $answerOrder = Answer::applyOrder($query, Yii::$app->request->get('answers', 'votes'));

            $answerDataProvider = new ActiveDataProvider([
                'query' => $query->where(['question_id' => $model->id]),
                'pagination' => [
                    'pageSize' => 20,
                ],
            ]);

            return $this->render('view', compact('model', 'answer', 'answerDataProvider', 'answerOrder'));
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionEdit($id)
    {
        /** @var Question $model */
        $model = $this->findQuestionModel($id);

        if ($model->isAuthor()) {
            if ($model->load($_POST) && $model->save()) {
                Yii::$app->session->setFlash('questionFormSubmitted');
                return $this->redirect(['view', 'id' => $model->id]);
            }

            return $this->render('edit', compact('model'));
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    public function actionDelete($id)
    {
        /** @var Question $model */
        $model = $this->findQuestionModel($id);

        if ($model->isAuthor()) {
            $model->delete();
            return $this->redirect(['index']);
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    public function actionAsk()
    {
        $model = new Question();

        if ($model->load($_POST) && $model->save()) {
            Yii::$app->session->setFlash('questionFormSubmitted');
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('ask', compact('model'));
        }
    }

    public function actionQuestionFavorite($id)
    {
        /** @var Question $model */
        $model = Question::find()->with('favorite')->where(['id' => $id])->one();

        $response = [
            'data' => ['status' => false],
            'format' => 'json'
        ];

        if ($model && $status = $model->toggleFavorite()) {
            $response['data']['status'] = $status;
            $response['data']['html'] = $this->renderPartial('parts/favorite', compact('model'));
        }

        if (Yii::$app->request->isAjax) {
            return new Response($response);
        }

        return $this->redirect(['view', 'id' => $id]);
    }

    public function actionQuestionVote($id, $vote)
    {
        return $this->entityVote($this->findModel(Question::className(), $id), $vote);
    }

    public function actionAnswerVote($id, $vote)
    {
        return $this->entityVote($this->findModel(Answer::className(), $id), $vote);
    }

    public function actionAnswer($id)
    {
        $model = new Answer(['question_id' => $id]);

        if ($model->load($_POST) && $model->save()) {
            Yii::$app->session->setFlash('answerFormSubmitted');
            return $this->redirect(['view', 'id' => $id]);
        } else {
            return $this->render('answer', compact('model'));
        }
    }

    /**
     * Increment or decrement votes of model by given type
     * @param ActiveRecord $model
     * @param string $type can be 'up' or 'down'
     * @param string $format
     * @return Response
     */
    protected function entityVote($model, $type, $format = 'json')
    {
        $data = ['status' => false];

        if ($model && Vote::isUserCan($model, Yii::$app->user->id)) {
            $data = [
                'status' => true,
                'html' => $this->renderPartial('parts/vote', ['model' => Vote::process($model, $type)])
            ];
        }

        if (Yii::$app->request->isAjax) {
            return new Response([
                'data' => $data,
                'format' => $format
            ]);
        }

        return $this->redirect(['view', 'id' => $model['id']]);
    }

    /**
     * @param string $modelClass
     * @param null $id
     * @return null|\yii\db\ActiveRecord|static
     * @throws \yii\web\NotFoundHttpException
     */
    protected function findModel($modelClass, $id = null)
    {
        if (($model = $modelClass::find()->where(['id'=> $id])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}