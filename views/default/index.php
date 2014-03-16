<?php

use yii\helpers\Url;
use yii\widgets\LinkPager;

/**
 * @var \artkost\qa\models\Question[] $models
 * @var \yii\data\ActiveDataProvider $dataProvider
 */
$models = $dataProvider->getModels();


$this->title = 'Questions';

?>
<div class="qa-index container">
    <div class="row">
        <a class="btn btn-primary" href="<?=Url::toRoute(['ask'])?>">Ask a Question</a>
    </div>
    <div class="row">
        <div class="qa-list list-group">
            <?php foreach ($models as $model): ?>
                <div class="list-group-item clearfix" id="question-<?=$model->id?>">
                    <div class="cp pull-left">
                        <div class="votes pull-left">
                            <div class="mini-counts"><?=$model->votes?></div>
                            <div>votes</div>
                        </div>
                        <div class="status pull-left unanswered">
                            <div class="mini-counts"><?=$model->answers?></div>
                            <div>answers</div>
                        </div>
                        <div class="views pull-left">
                            <div class="mini-counts"><?=$model->views?></div>
                            <div>views</div>
                        </div>
                    </div>
                    <div class="summary">
                        <h4 class="list-group-item-heading">
                            <a href="<?=Url::toRoute(['view', 'id' => $model->id, 'alias' => $model->alias])?>" class="question-hyperlink" title=""><?=$model->title?></a>
                            <?php if ($model->isAuthor()) :?> <a href="<?=Url::toRoute(['edit', 'id' => $model->id])?>" class="question-edit label label-default">Edit</a> <?php endif; ?>
                        </h4>
                        <div class="tags">
                            <?php foreach ($model->tagsList as $tag): ?>
                                <a href="<?=Url::toRoute(['tagged', 'name' => $tag])?>" class="label label-default" title="show questions tagged 'java'" rel="tag"><?=$tag?></a>
                            <?php endforeach; ?>
                        </div>
                        <div class="created">
                            <span title="2014-03-14 07:24:18Z" class="relativetime"><?=$model->updated_at ?></span>
                            <span class="user"><?=$model->user_id ?></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="row">
        <?= LinkPager::widget(['pagination' => $dataProvider->getPagination()]) ?>
    </div>
</div>


