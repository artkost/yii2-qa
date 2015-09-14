<?php

namespace artkost\qa\models;

use yii\db\ActiveRecordInterface;

interface VoteInterface extends ActiveRecordInterface
{
    public static function removeRelation($this);
}