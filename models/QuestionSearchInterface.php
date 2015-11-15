<?php
namespace artkost\qa\models;

interface QuestionSearchInterface
{
    const CLASS_NAME = 'artkost\qa\models\QuestionSearchInterface';

    public function search($params);

    public function searchFavorite($params, $userID);

    public function searchMy($params, $userID);
}