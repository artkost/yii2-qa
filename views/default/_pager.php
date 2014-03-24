<?php
/**
 * @var $dataProvider \yii\data\ActiveDataProvider
 */
use yii\widgets\LinkPager;
?>

<div class="row">
    <?= ($dataProvider->pagination->totalCount > $dataProvider->pagination->pageSize ) ? LinkPager::widget(['pagination' => $dataProvider->pagination]) : ''?>
</div>