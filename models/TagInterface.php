<?php

namespace artkost\qa\models;

use yii\db\ActiveRecordInterface;

interface TagInterface extends ActiveRecordInterface
{

    public static function updateFrequency($tags, $string);

    public static function array2String(array $array);

    public static function string2Array($tags);
}