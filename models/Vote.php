<?php

namespace artkost\qa\models;

use artkost\qa\ActiveRecord;
use Yii;
use yii\base\UnknownClassException;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * Class Votes
 * @package artkost\qa\models
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $entity_id
 * @property string $entity
 * @property integer $vote
 * @property integer $created_at
 * @property integer $created_ip
 *
 * @author Nikolay Kostyurin <nikolay@artkost.ru>
 * @since 2.0
 */
class Vote extends ActiveRecord
{
    const TYPE_UP = 'up';
    const TYPE_DOWN = 'down';

    const ENTITY_QUESTION = 'question';
    const ENTITY_ANSWER = 'answer';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'qa_vote';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['entity', 'entity_id', 'vote'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at']
                ],
            ],
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'created_ip'
                ],
                'value' => function ($event) { return ip2long(Yii::$app->request->getUserIP()); }
            ],
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_AFTER_FIND => 'created_ip'
                ],
                'value' => function ($event) { return long2ip($event->sender->created_ip); }
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
     * Check if current user has access to vote
     * @param ActiveRecord $model
     * @param int $userId
     * @return bool
     */
    public static function isUserCan($model, $userId)
    {
        //if he try vote on its own question
        if (isset($model['user_id']) && $model['user_id'] == $userId) {
            return false;
        } else {
            return !self::find()->where([
                'user_id' => $userId,
                'entity' => self::modelEntityType($model),
                'entity_id' => $model['id']
            ])->exists();
        }
    }

    /**
     * Increment votes for given model
     * @param ActiveRecord $model
     * @param string $type of vote, up or down
     * @return ActiveRecord
     */
    public static function process(ActiveRecord $model, $type)
    {
        $value = self::value($type);


        if (isset($model['votes'])) {
            $model['votes'] += $value;

            $vote = new self([
                'entity_id' => $model['id'],
                'vote' => $value,
                'entity' => self::modelEntityType($model)
            ]);

            if ($vote->save() && $model->save()) {
                return $model;
            }
        }

        return false;
    }

    /**
     * Get entity type by given instance of model
     * @param $model
     * @throws UnknownClassException
     * @return string
     */
    protected static function modelEntityType($model)
    {
        if ($model instanceof Question) {
            return self::ENTITY_QUESTION;
        } elseif ($model instanceof Answer) {
            return self::ENTITY_ANSWER;
        } else {
            $className = get_class($model);
            throw new UnknownClassException("Model class '{$className}' not supported");
        }
    }

    /**
     * Get value to increment or decrement
     * @param $type
     * @return int
     */
    protected static function value($type)
    {
        switch ($type) {
            case self::TYPE_UP:
                return 1;
                break;
            case self::TYPE_DOWN:
                return 1;
                break;
            default:
                return 0;
        }
    }

}