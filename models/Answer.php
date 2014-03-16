<?php

namespace artkost\qa\models;

use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\ActiveQuery;
use Yii;

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
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'qa_answer';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            [
                'class' => BlameableBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'user_id',
                ],
            ]
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
            'id' => 'ID',
            'content' => 'Content',
            'status' => 'Status',
        ];
    }

    public function getUpdated()
    {
        return Yii::$app->formatter->asTime($this->updated_at);
    }

    public function getCreated()
    {
        return Yii::$app->formatter->asTime($this->created_at);
    }

    /**
     * Apply possible answers order to query
     * @param ActiveQuery $query
     * @param $order
     * @return ActiveQuery
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

        return $query;
    }

    /**
     * This is invoked after the record is saved.
     */
    public function afterSave($insert)
    {
        parent::afterSave($insert);

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
    }
}