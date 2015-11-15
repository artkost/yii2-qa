<?php

namespace artkost\qa\models;

use artkost\qa\ActiveRecord;
use artkost\qa\Module;
use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\helpers\HtmlPurifier;
use yii\helpers\Inflector;
use yii\helpers\Markdown;

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
class Question extends ActiveRecord implements QuestionInterface
{
    /**
     * Old tags populated after find record
     * @var string
     */
    protected $_oldTags = '';

    /**
     * Markdown processed content
     * @var string
     */
    public $body;

    /**
     * @inheritdoc
     */
    public static function find()
    {
        return Yii::$container->get(QuestionQueryInterface::CLASS_NAME, [get_called_class()]);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%qa_question}}';
    }

    /**
     * @param $id
     */
    public function incrementAnswers($id)
    {
        self::updateAllCounters(['answers' => 1], ['id' => $id]);
    }

    /**
     * @param $id
     */
    public function decrementAnswers($id)
    {
        self::updateAllCounters(['answers' => -1], ['id' => $id]);
    }

    /**
     * @return object
     * @throws \yii\base\InvalidConfigException
     */
    public function getTagModel()
    {
        return Yii::$container->get(TagInterface::CLASS_NAME);
    }

    /**
     * @return object
     * @throws \yii\base\InvalidConfigException
     */
    public function getVoteModel()
    {
        return Yii::$container->get(VoteInterface::CLASS_NAME);
    }

    /**
     * @return object
     * @throws \yii\base\InvalidConfigException
     */
    public function getAnswerModel()
    {
        return Yii::$container->get(AnswerInterface::CLASS_NAME);
    }

    /**
     * @return object
     * @throws \yii\base\InvalidConfigException
     */
    public function getFavoriteModel()
    {
        return Yii::$container->get(FavoriteInterface::CLASS_NAME);
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
                    ActiveRecord::EVENT_AFTER_FIND => 'body'
                ],
                'value' => function ($event) {
                    return HtmlPurifier::process(Markdown::process($event->sender->content, 'gfm'));
                }
            ],
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_VALIDATE => 'tags'
                ],
                'value' => function ($event) {
                    return $event->sender->tags ? strip_tags($event->sender->tags) : $event->sender->tags;
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
            'id' => Module::t('model', 'ID'),
            'title' => Module::t('model', 'Title'),
            'alias' => Module::t('model', 'Alias'),
            'content' => Module::t('model', 'Content'),
            'tags' => Module::t('model', 'Tags'),
            'status' => Module::t('model', 'Status'),
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
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        $this->tagModel->updateFrequency($this->_oldTags, $this->tags);
    }

    /**
     * This is invoked after the record is deleted.
     */
    public function afterDelete()
    {
        parent::afterDelete();

        $this->tagModel->updateFrequency($this->tags, '');
        $this->voteModel->removeRelation($this);
        $this->answerModel->removeRelation($this->id);
        $this->favoriteModel->removeRelation($this->id);
    }

    /**
     * Normalizes the user-entered tags.
     */
    public function normalizeTags($attribute, $params)
    {
        $this->tags = $this->tagModel->array2String(array_unique($this->tagModel->string2Array($this->tags)));
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
     * @param bool $user
     * @return bool
     */
    public function isFavorite($user = false)
    {
        $user = ($user) ? $user : Yii::$app->user;
        $modelClass = get_class($this->favoriteModel);

        return $modelClass::find()->where(['user_id' => $user->id, 'question_id' => $this->id])->exists();
    }

    /**
     * @param $data
     * @return bool
     */
    public function haveDraft($data)
    {
        return isset($data['draft']);
    }

    /**
     * @return bool
     */
    public function isDraft()
    {
        return $this->status == QuestionInterface::STATUS_DRAFT;
    }

    /**
     * Check if is given user unique
     * @return bool
     */
    public function isUserUnique()
    {
        return $this->user_id !== Yii::$app->user->id;
    }

    /**
     * @return bool
     */
    public function toggleFavorite()
    {
        if ($this->isFavorite()) {
            return $this->favoriteModel->remove($this->id);
        } else {
            return $this->favoriteModel->add($this->id);
        }
    }

    /**
     * @return array a list of links that point to the post list filtered by every tag of this post
     */
    public function getTagsList()
    {
        return $this->tagModel->string2Array($this->tags);
    }

    /**
     * @return string
     */
    public function getUpdated()
    {
        return Module::getInstance()->getDate($this, 'updated_at');
    }

    /**
     * @return string
     */
    public function getCreated()
    {
        return Module::getInstance()->getDate($this, 'created_at');
    }

    /**
     * @return int|string
     * @throws \yii\base\InvalidConfigException
     */
    public function getUserName()
    {
        return $this->user ? Module::getInstance()->getUserName($this->user, 'id') : $this->user_id;
    }

    /**
     * Answer Relation
     * @return \yii\db\ActiveQueryInterface
     */
    public function getAnswers()
    {
        return $this->hasMany(get_class($this->answerModel), ['question_id' => 'id']);
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
     * Favorite Relation
     * @return \yii\db\ActiveQueryInterface
     */
    public function getFavorite()
    {
        return $this->hasOne(get_class($this->favoriteModel), ['question_id' => 'id']);
    }

    /**
     * Favorite Relation
     * @return \yii\db\ActiveQueryInterface
     */
    public function getFavorites()
    {
        return $this->hasMany(get_class($this->favoriteModel), ['question_id' => 'id']);
    }
}
