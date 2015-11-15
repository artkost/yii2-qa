<?php

namespace artkost\qa\models;

use yii\db\ActiveRecordInterface;

interface VoteInterface extends ActiveRecordInterface
{
    const CLASS_NAME = 'artkost\qa\models\VoteInterface';

    public function isUserCan($model, $userId);

    public function removeRelation($model);

    public function process(ActiveRecordInterface $model, $type);
}