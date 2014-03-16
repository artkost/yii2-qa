<?php

namespace artkost\qa\models;

/**
 * Tag Model
 * @package artkost\qa\models
 *
 * @property integer $id
 * @property string $name
 * @property integer $frequency
 *
 * @author Nikolay Kostyurin <nikolay@artkost.ru>
 * @since 2.0
 */
class Tag extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'qa_tag';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'frequency' => 'Frequency',
        ];
    }

    public static function string2Array($tags)
    {
        return preg_split('/\s*,\s*/',trim($tags),-1,PREG_SPLIT_NO_EMPTY);
    }

    public static function array2String($tags)
    {
        return implode(', ',$tags);
    }

    public static function suggestTags($keyword, $limit=20)
    {
        $tags = self::find()
            ->where(['like', 'name', $keyword])
            ->orderBy('frequency DESC, name')
            ->limit($limit)
            ->all();

        $names=array();
        foreach($tags as $tag) {
            $names[]=$tag->name;
        }

        return $names;
    }
}