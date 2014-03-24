<?php

namespace artkost\qa\models;

use artkost\qa\ActiveRecord;
use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Inflector;

/**
 * Question Model
 * @package artkost\qa\models
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $title
 * @property string $alias
 * @property string $content
 * @property string $tags
 * @property integer $answers
 * @property integer $views
 * @property integer $votes
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @author Nikolay Kostyurin <nikolay@artkost.ru>
 * @since 2.0
 */
class Question extends ActiveRecord
{
    const STATUS_DRAFT = 0;
    const STATUS_PUBLISHED = 1;

    /**
     * Old tags populated after find record
     * @var string
     */
    protected $_oldTags = '';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'qa_question';
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
                    ActiveRecord::EVENT_BEFORE_INSERT => 'alias'
                ],
                'value' => function ($event) {
                        return Inflector::slug($event->sender->title);
                    }
            ],
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'status'
                ],
                'value' => function ($event) {
                        return self::STATUS_PUBLISHED;
                    }
            ],
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
            [['title', 'content', 'tags'], 'required'],
            [['tags'], 'normalizeTags']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => $this->t('ID'),
            'title' => $this->t('Title'),
            'alias' => $this->t('Alias'),
            'content' => $this->t('Content'),
            'tags' => $this->t('Tags'),
            'status' => $this->t('Status'),
        ];
    }

    /**
     * This is invoked when a record is populated with data from a find() call.
     */
    public function afterFind()
    {
        parent::afterFind();
        $this->_oldTags = $this->tags;
    }

    /**
     * This is invoked after the record is saved.
     */
    public function afterSave($insert)
    {
        parent::afterSave($insert);
        Tag::updateFrequency($this->_oldTags, $this->tags);
    }

    /**
     * This is invoked after the record is deleted.
     */
    public function afterDelete()
    {
        parent::afterDelete();
        Tag::updateFrequency($this->tags, '');
    }

    /**
     * @return array a list of links that point to the post list filtered by every tag of this post
     */
    public function getTagsList()
    {
        return Tag::string2Array($this->tags);
    }

    public function getUpdated()
    {
        return Yii::$app->formatter->asTime($this->updated_at);
    }

    public function getCreated()
    {
        return Yii::$app->formatter->asTime($this->created_at);
    }

    public function getUserName()
    {
        return $this->user ? $this->getModule()->getUserName($this->user) : $this->user_id;
    }

    /**
     * Check if current user can edit this model
     */
    public function isAuthor()
    {
        return $this->user_id == Yii::$app->user->id;
    }

    /**
     * Answer Relation
     * @return \yii\db\ActiveQueryInterface
     */
    public function getAnswers()
    {
        return $this->hasMany(Answer::className(), ['question_id' => 'id']);
    }

    /**
     * User Relation
     * @return \yii\db\ActiveQueryInterface
     */
    public function getUser()
    {
        return $this->hasOne($this->getModule()->userClass, ['id' => 'user_id']);
    }

    /**
     * Normalizes the user-entered tags.
     */
    public function normalizeTags($attribute, $params)
    {
        $this->tags = Tag::array2String(array_unique(Tag::string2Array($this->tags)));
    }

    public static function incrementAnswers($id)
    {
        self::updateAllCounters(['answers' => 1], ['id' => $id]);
    }

    public static function decrementAnswers($id)
    {
        self::updateAllCounters(['answers' => -1], ['id' => $id]);
    }
}