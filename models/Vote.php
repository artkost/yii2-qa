<?php

namespace artkost\qa\models;

use yii\behaviors\AttributeBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use Yii;

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
            [['name', 'user_id', 'entity_id', 'vote'], 'required'],
            [['user_id'], 'in']
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
     */
    public static function isUserVoted()
    {
        $userId = Yii::$app->user->identity->id;
        $model = self::find();

        return true;
    }

    /**
     * Increment votes for given model
     * @param ActiveRecord $model
     * @param $type
     * @return int|mixed
     */
    public static function increment(ActiveRecord $model, $type)
    {
        switch ($type) {
            case self::TYPE_UP:
                $value = 1;
                break;
            case self::TYPE_DOWN:
                $value = -1;
                break;
            default:
                $value = 0;
        }

        $votes = 0;

        if ($model->hasAttribute('votes')) {
            $votes = $model->votes + $value;
            $model->votes = $votes;

            $vote = new self(['entity_id' => $model->id, 'vote' => $value]);

            if ($model instanceof Question) {
                $vote->entity = self::ENTITY_QUESTION;
            } elseif ($model instanceof Answer) {
                $vote->entity = self::ENTITY_ANSWER;
            }

            if ($vote->save() && $model->save()) {
                return $model->votes;
            }
        }

        return $votes;
    }

}