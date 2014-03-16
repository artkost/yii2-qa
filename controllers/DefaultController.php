<?php

namespace artkost\qa\controllers;

use artkost\qa\Asset;
use artkost\qa\models\Answer;
use artkost\qa\models\Question;
use artkost\qa\models\QuestionSearch;
use artkost\qa\models\Tag;
use artkost\qa\models\Vote;
use Yii;
use yii\web\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\VerbFilter;

class DefaultController extends Controller
{
    /**
     * @var \artkost\qa\Module
     */
    public $module;

    public function init()
    {
        parent::init();
        Asset::register($this->getView());
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
                        'actions' => ['index', 'view', 'tagged', 'tag-suggest'],
                        'roles' => ['?', '@']
                    ],
                    [
                        'allow' => true,
                        'actions' => ['ask', 'answer', 'edit', 'question-vote', 'answer-vote'],
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

        return $this->render('index', compact('searchModel', 'dataProvider'));
    }

    public function actionTagged($name)
    {
        $tags = Tag::string2Array($name);

        $models = $this->findQuestionModel()->where(['like', 'tags', $tags])->all();

        return $this->render('index', compact('models'));
    }

    public function actionTagSuggest()
    {
        if (isset($_GET['q']) && ($keyword = trim($_GET['q'])) !== '') {
            $tags = Tag::suggestTags($keyword);
            if ($tags !== array()) {
                return implode("\n", $tags);
            }

        }
    }

    public function actionView($id)
    {
        $model = $this->findQuestionModel($id);

        if ($this->isUserUnique()) {
            Question::updateAllCounters(['views' => 1], ['id' => $id]);
        }

        $answer = new Answer;

        return $this->render('view', compact('model', 'answer'));
    }

    public function actionEdit($id)
    {
        $model = $this->findQuestionModel($id);

        if ($model->load($_POST) && $model->save()) {
            Yii::$app->session->setFlash('questionFormSubmitted');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('edit', compact('model'));
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

    public function actionQuestionVote($id, $vote)
    {
        $model = $this->findQuestionModel($id);

        return $this->incrementVote($model, $vote);
    }

    public function actionAnswerVote($id, $vote)
    {
        $model = $this->findQuestionModel($id);

        return $this->incrementVote($model, $vote);
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
     * @param $model
     * @param $type
     * @param string $format
     * @return Response
     */
    protected function incrementVote($model, $type, $format = 'json')
    {
        $data = ['status' => false];

        if ($model && !Vote::isUserVoted()) {
            $data = [
                'status' => true,
                'votes' => Vote::increment($model, $type)
            ];
        }

        return new Response([
            'data' => $data,
            'format' => $format
        ]);
    }

    /**
     * Check if is given user unique
     */
    protected function isUserUnique()
    {
        return true;
    }

    /**
     * @param null $id
     * @return null|\yii\db\ActiveQuery|static
     * @throws \yii\web\NotFoundHttpException
     */
    protected function findAnswerModel($id = null)
    {
        /** if $id is null, return ActiveQuery */
        if (($model = Answer::find($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * @param null $id
     * @return \yii\db\ActiveQuery
     * @throws \yii\web\NotFoundHttpException
     */
    protected function findQuestionModel($id = null)
    {
        /** if $id is null, return ActiveQuery */
        if (($model = Question::find($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}