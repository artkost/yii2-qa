<?php

namespace artkost\qa\models;

use artkost\qa\ActiveRecord;
use artkost\qa\Module;

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
class Tag extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%qa_tag}}';
    }

    /**
     * Convert array of strings to comma separated values
     * @param $tags
     * @return string
     */
    public static function array2String($tags)
    {
        return implode(', ', $tags);
    }

    /**
     * Convert string of comma separated values to array
     * @param $tags
     * @return array
     */
    public static function string2Array($tags)
    {
        return preg_split('/\s*,\s*/', trim($tags), -1, PREG_SPLIT_NO_EMPTY);
    }

    /**
     * Update frequency of new tags
     * @param $oldTags
     * @param $newTags
     */
    public static function updateFrequency($oldTags, $newTags)
    {
        $oldTags = self::string2Array($oldTags);
        $newTags = self::string2Array($newTags);
        self::addTags(array_values(array_diff($newTags, $oldTags)));
        self::removeTags(array_values(array_diff($oldTags, $newTags)));
    }

    /**
     *  Update frequency of tags and add new tags
     * @param $tags
     */
    public static function addTags($tags)
    {
        self::updateAllCounters(['frequency' => 1], ['name' => $tags]);

        foreach ($tags as $name) {
            if (!self::find()->where(['name' => $name])->exists()) {
                (new self([
                    'name' => $name,
                    'frequency' => 1
                ]))->save();
            }
        }
    }

    /**
     * Update frequency of tags and remove with frequency < 0
     * @param $tags
     */
    public static function removeTags($tags)
    {
        if (empty($tags)) {
            return;
        }

        self::updateAllCounters(['frequency' => -1], ['name' => $tags]);
        self::deleteAll('frequency<=0');
    }

    /**
     * Suggest tags by given keyword
     * @param $keyword
     * @param int $limit
     * @return array
     */
    public static function suggest($keyword, $limit = 20)
    {
        /** @var self[] $tags */
        $tags = self::find()
            ->where(['like', 'name', $keyword])
            ->orderBy('frequency DESC, name')
            ->limit($limit)
            ->all();

        $names = array();

        foreach ($tags as $tag) {
            $names[] = ['word' => $tag->name];
        }

        return $names;
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
            'id' => Module::t('model', 'ID'),
            'name' => Module::t('model', 'Name'),
            'frequency' => Module::t('model', 'Frequency'),
        ];
    }
}