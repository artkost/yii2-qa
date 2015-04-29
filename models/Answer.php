<?php

namespace artkost\qa\models;

use artkost\qa\ActiveRecord;
use artkost\qa\Module;
use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\helpers\HtmlPurifier;
use yii\helpers\Markdown;

/**
 * Answer Model
 * @package artkost\qa\models
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $question_id
 * @property string $content
 * @property integer $votes
 * @property integer $status
 * @property integer $is_correct
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @author Nikolay Kostyurin <nikolay@artkost.ru>
 * @since 2.0
 */
class Answer extends ActiveRecord
{
    const STATUS_DRAFT = 0;
    const STATUS_PUBLISHED = 1;

    /**
     * Markdown processed content
     * @var string
     */
    public $body;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%qa_answer}}';
    }

    /**
     * @param int $question_id
     * @return int
     */
    public static function removeRelation($question_id)
    {
        return self::deleteAll(
            'question_id=:question_id',
            [
                ':question_id' => $question_id,
            ]
        );
    }

    /**
     * Apply possible answers order to query
     * @param ActiveQuery $query
     * @param $order
     * @return string
     */
    public static function applyOrder(ActiveQuery $query, $order)
    {
        switch ($order) {
            case 'oldest':
                $query->orderBy('created_at DESC');
                break;

            case 'active':
                $query->orderBy('created_at ASC');
                break;

            case 'votes':
            default:
                $query->orderBy('votes DESC');
                break;
        }

        return $order;
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_AFTER_FIND => 'body'
                ],
                'value' => function ($event) {
                    return HtmlPurifier::process(Markdown::process($event->sender->content, 'gfm-comment'));
                }
            ],
            [
                'class' => BlameableBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'user_id',
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'required'],
            [['question_id'], 'exist', 'targetClass' => Question::className(), 'targetAttribute' => 'id']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('model', 'ID'),
            'content' => Module::t('model', 'Content'),
            'status' => Module::t('model', 'Status'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($insert) {
            Question::incrementAnswers($this->question_id);
        }
    }

    /**
     * This is invoked after the record is deleted.
     */
    public function afterDelete()
    {
        parent::afterDelete();
        Question::decrementAnswers($this->question_id);
        Vote::removeRelation($this);
    }

    /**
     * Check if current user can edit this model
     * @return bool
     */
    public function isAuthor()
    {
        return $this->user_id == Yii::$app->user->id;
    }

    /**
     * Check if this answer is correct
     * @return bool
     */
    public function isCorrect()
    {
        return $this->is_correct;
    }

    /**
     * Toggles correct or not
     * @return bool
     */
    public function toggleCorrect()
    {
        $this->is_correct = ! $this->isCorrect();

        return $this->save();
    }

    /**
     * User Relation
     * @return \yii\db\ActiveQueryInterface
     */
    public function getUser()
    {
        return $this->hasOne(Yii::$app->user->identityClass, ['id' => 'user_id']);
    }

    /**
     * Formatted date
     * @return string
     */
    public function getUpdated()
    {
        return Module::getInstance()->getDate($this, 'updated_at');
    }

    /**
     * Formatted date
     * @return string
     */
    public function getCreated()
    {
        return Module::getInstance()->getDate($this, 'created_at');
    }

    /**
     * Formatted user
     * @return int
     */
    public function getUserName()
    {
        return $this->user ? Module::getInstance()->getUserName($this->user, 'id') : $this->user_id;
    }

    /**
     * @return Question
     */
    public function getQuestion()
    {
        return $this->hasOne(Question::className(), ['id' => 'question_id']);
    }
}
