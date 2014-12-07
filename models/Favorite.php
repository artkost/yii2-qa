<?php

namespace artkost\qa\models;

use artkost\qa\ActiveRecord;
use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "qa_favorite".
 *
 * @property integer $id
 * @property string $user_id
 * @property string $question_id
 * @property string $created_at
 * @property string $created_ip
 */
class Favorite extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%qa_favorite}}';
    }

    /**
     * @param $id
     * @return bool
     */
    public static function add($id)
    {
        $favorite = new self();
        $favorite->attributes = ['question_id' => $id];
        if ($favorite->save()) {
            return true;
        }

        return false;
    }

    /**
     * @param $id
     * @return bool
     * @throws \Exception
     */
    public static function remove($id)
    {
        /** @var \yii\db\ActiveQuery $query */
        $query = self::find()->where(['question_id' => $id, 'user_id' => Yii::$app->user->id]);

        if ($query->exists() && $query->one()->delete()) {
            return true;
        }

        return false;
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
                'value' => function ($event) {
                    return ip2long(Yii::$app->request->getUserIP());
                }
            ],
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_AFTER_FIND => 'created_ip'
                ],
                'value' => function ($event) {
                    return long2ip($event->sender->created_ip);
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
            [['question_id'], 'required'],
            [['user_id', 'question_id', 'created_at', 'created_ip'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'question_id' => 'Question ID',
            'created_at' => 'Created At',
            'created_ip' => 'Created Ip',
        ];
    }

    /**
     * Questions Relation
     * @return \yii\db\ActiveQueryInterface
     */
    public function getQuestions()
    {
        return $this->hasMany(Question::className(), ['id' => 'question_id']);
    }
}
