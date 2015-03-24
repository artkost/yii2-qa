<?php

namespace artkost\qa\controllers;

use artkost\qa\ActiveRecord;
use artkost\qa\Asset;
use artkost\qa\models\Answer;
use artkost\qa\models\Question;
use artkost\qa\models\QuestionSearch;
use artkost\qa\models\Tag;
use artkost\qa\models\Vote;
use artkost\qa\Module;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Exception as DbException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;


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
                    'get' => ['tag-suggest'],
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'tags', 'tag-suggest'],
                        'roles' => ['?', '@']
                    ],
                    [
                        'allow' => true,
                        'actions' => [
                            'my',
                            'ask',
                            'edit',
                            'answer',
                            'delete',
                            'favorite',
                            'answer-vote',
                            'question-vote',
                            'question-favorite',
                        ],
                        'roles' => ['@']
                    ],
                    [
                        'allow' => false
                    ]
                ]
            ]
        ];
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new QuestionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        $models = $dataProvider->getModels();

        return $this->render('index', compact('searchModel', 'models', 'dataProvider'));
    }

    /**
     * @param $tags
     * @return string
     */
    public function actionTags($tags)
    {
        $tags = Tag::string2Array($tags);
        $searchModel = new QuestionSearch();
        $dataProvider = $searchModel->search(['tags' => $tags]);
        $models = $dataProvider->getModels();

        return $this->render('tags', compact('searchModel', 'models', 'dataProvider', 'tags'));
    }

    /**
     * @return string
     */
    public function actionFavorite()
    {
        $searchModel = new QuestionSearch();
        $dataProvider = $searchModel->searchFavorite(Yii::$app->request->getQueryParams(), Yii::$app->user->id);
        $models = $dataProvider->getModels();

        return $this->render('index', compact('searchModel', 'models', 'dataProvider'));
    }

    /**
     * @return string
     */
    public function actionMy()
    {
        $searchModel = new QuestionSearch();
        $dataProvider = $searchModel->searchMy(Yii::$app->request->getQueryParams(), Yii::$app->user->id);

        $models = $dataProvider->getModels();

        return $this->render('index', compact('searchModel', 'models', 'dataProvider'));
    }

    /**
     * @return Response
     */
    public function actionTagSuggest()
    {
        $response = [
            'data' => ['status' => false],
            'format' => 'json'
        ];

        if (isset($_GET['q']) && ($keyword = trim($_GET['q'])) !== '') {
            $response['data']['items'] = Tag::suggest($keyword);
        }

        return new Response($response);
    }

    /**
     * @param $id
     * @throws NotFoundHttpException
     * @return string
     */
    public function actionView($id)
    {
        /** @var Question $model */
        $model = Question::find()->with('user')->where(['id' => $id])->one();

        if ($model) {
            if ($model->isUserUnique()) {
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
            throw new NotFoundHttpException(Module::t('main', 'The requested page does not exist.'));
        }
    }

    /**
     * @param $id
     * @throws ForbiddenHttpException
     * @throws DbException
     * @throws NotFoundHttpException
     * @return string
     */
    public function actionEdit($id)
    {
        /** @var Question $model */
        $model = $this->findModel(Question::className(), $id);

        if ($model->isAuthor()) {
            if ($model->load($_POST)) {
                if ($model->haveDraft($_POST)) {
                    $model->status = Question::STATUS_DRAFT;
                }

                if (!$model->save()) {
                    throw new DbException(Module::t('main', 'Error save question'));
                }

                Yii::$app->session->setFlash('questionFormSubmitted');
                return $this->redirect(['view', 'id' => $model->id]);
            }

            return $this->render('edit', compact('model'));
        } else {
            throw new ForbiddenHttpException(Module::t('main', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * @param $id
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     * @throws \Exception
     * @return string
     */
    public function actionDelete($id)
    {
        /** @var Question $model */
        $model = $this->findModel(Question::className(), $id);

        if ($model->isAuthor()) {
            $model->delete();
            return $this->redirect(['index']);
        } else {
            throw new ForbiddenHttpException(Module::t('main', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * @return string|Response
     * @throws DbException
     */
    public function actionAsk()
    {
        $model = new Question();

        if ($model->load($_POST)) {
            if ($model->haveDraft($_POST)) {
                $model->status = Question::STATUS_DRAFT;
            }

            if (!$model->save()) {
                throw new DbException(Module::t('main', 'Error create question'));
            }

            Yii::$app->session->setFlash('questionFormSubmitted');
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('ask', compact('model'));
        }
    }

    /**
     * @param $id
     * @return Response
     */
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

    /**
     * @param $id
     * @param $vote
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionQuestionVote($id, $vote)
    {
        return $this->entityVote($this->findModel(Question::className(), $id), $vote);
    }

    /**
     * @param $id
     * @param $vote
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionAnswerVote($id, $vote)
    {
        return $this->entityVote($this->findModel(Answer::className(), $id), $vote, 'parts/like');
    }

    /**
     * @param $id
     * @return string|Response
     */
    public function actionAnswer($id)
    {
        $model = new Answer(['question_id' => $id]);

        if ($model->load($_POST) && $model->save()) {
            Yii::$app->session->setFlash('answerFormSubmitted');

            /** @var Question $question */
            $question = $model->question;

            return $this->redirect(['view', 'id' => $question->id, 'alias' => $question->alias]);
        } else {
            return $this->render('answer', compact('model'));
        }
    }

    /**
     * Increment or decrement votes of model by given type
     * @param ActiveRecord $model
     * @param string $type can be 'up' or 'down'
     * @param string $partial template name
     * @param string $format
     * @return Response
     */
    protected function entityVote($model, $type, $partial = 'parts/vote', $format = 'json')
    {
        $data = ['status' => false];

        if ($model && Vote::isUserCan($model, Yii::$app->user->id)) {
            $data = [
                'status' => true,
                'html' => $this->renderPartial($partial, ['model' => Vote::process($model, $type)])
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
     * @return ActiveRecord
     * @throws \yii\web\NotFoundHttpException
     */
    protected function findModel($modelClass, $id = null)
    {
        if (($model = $modelClass::find()->where(['id' => $id])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
