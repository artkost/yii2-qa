<?php

namespace artkost\qa\models;

interface TagInterface
{
    const CLASS_NAME = 'artkost\qa\models\TagInterface';

    public function array2String($tags);

    public function string2Array($tags);

    public function updateFrequency($tags, $string);

    public function addTags($tags);

    public function removeTags($tags);

    public function suggest($keyword, $limit = 20);
}