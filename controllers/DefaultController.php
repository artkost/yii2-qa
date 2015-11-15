<?php

namespace artkost\qa\controllers;

use artkost\qa\actions as Actions;
use artkost\qa\ActiveRecord;
use artkost\qa\Asset;
use artkost\qa\models\Answer;
use artkost\qa\models\AnswerInterface;
use artkost\qa\models\Question;
use artkost\qa\models\QuestionInterface;
use artkost\qa\models\QuestionSearch;
use artkost\qa\models\QuestionSearchInterface;
use artkost\qa\models\Tag;
use artkost\qa\models\TagInterface;
use artkost\qa\models\Vote;
use artkost\qa\models\VoteInterface;
use artkost\qa\Module;
use Yii;
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
                            'question-vote',
                            'question-favorite',
                            'answer-vote',
                            'answer-correct',
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

    public function actions() {
        return [
            'index' => [
                'class' => Actions\IndexAction::className(),
                'modelClass' => QuestionSearchInterface::CLASS_NAME,
                'viewFile' => 'index'
            ],
            'tags' => [
                'class' => Actions\TagsAction::className(),
                'modelClass' => QuestionSearchInterface::CLASS_NAME,
                'viewFile' => 'tags'
            ],
            'favorite' => [
                'class' => Actions\FavoriteListAction::className(),
                'modelClass' => QuestionSearchInterface::CLASS_NAME,
                'viewFile' => 'index'
            ],
            'my' => [
                'class' => Actions\MyListAction::className(),
                'modelClass' => QuestionSearchInterface::CLASS_NAME,
                'viewFile' => 'index'
            ],
            'tag-suggest' => [
                'class' => Actions\TagSuggestAction::className(),
                'modelClass' => TagInterface::CLASS_NAME
            ],
            'view' => [
                'class' => Actions\ViewAction::className(),
                'modelClass' => QuestionInterface::CLASS_NAME,
                'answerClass' => AnswerInterface::CLASS_NAME,
                'voteClass' => VoteInterface::CLASS_NAME,
                'viewFile' => 'view'
            ],
            'ask' => [
                'class' => Actions\AskAction::className(),
                'modelClass' => QuestionInterface::CLASS_NAME,
                'viewFile' => 'ask'
            ],
            'edit' => [
                'class' => Actions\EditAction::className(),
                'modelClass' => QuestionInterface::CLASS_NAME,
                'viewFile' => 'edit'
            ],
            'delete' => [
                'class' => Actions\DeleteAction::className(),
                'modelClass' => QuestionInterface::CLASS_NAME,
                'redirectRoute' => ['index']
            ],
            'question-favorite' => [
                'class' => Actions\FavoriteAction::className(),
                'modelClass' => QuestionInterface::CLASS_NAME,
                'viewFile' => 'ask'
            ],
            'question-vote' => [
                'class' => Actions\VoteAction::className(),
                'modelClass' => QuestionInterface::CLASS_NAME,
                'partialFile' => 'parts/vote'
            ],
            'answer' => [
                'class' => Actions\AnswerAction::className(),
                'modelClass' => AnswerInterface::CLASS_NAME,
            ],
            'answer-correct' => [
                'class' => Actions\CorrectAction::className(),
                'modelClass' => AnswerInterface::CLASS_NAME
            ],
            'answer-vote' => [
                'class' => Actions\VoteAction::className(),
                'modelClass' => AnswerInterface::CLASS_NAME,
                'partialFile' => 'parts/like'
            ]
        ];
    }
}
