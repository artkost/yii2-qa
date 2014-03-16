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
        <a class="btn btn-primary" href="<?= Url::toRoute(['ask']) ?>">Ask a Question</a>
    </div>
    <div class="row">
        <div class="qa-list list-group">
            <?php foreach ($models as $model): ?>
                <div class="list-group-item clearfix" id="question-<?= $model->id ?>">
                    <div class="cp pull-left">
                        <div class="votes pull-left">
                            <div class="mini-counts"><?= $model->votes ?></div>
                            <div>votes</div>
                        </div>
                        <div class="status pull-left unanswered">
                            <div class="mini-counts"><?= $model->answers ?></div>
                            <div>answers</div>
                        </div>
                        <div class="views pull-left">
                            <div class="mini-counts"><?= $model->views ?></div>
                            <div>views</div>
                        </div>
                    </div>
                    <div class="summary">
                        <h4 class="list-group-item-heading">
                            <a href="<?= Url::toRoute(['view', 'id' => $model->id, 'alias' => $model->alias]) ?>"
                               class="question-hyperlink" title=""><?= $model->title ?></a>
                            <?= $this->render('_edit_links', compact('model')) ?>
                        </h4>

                        <div class="tags">
                            <?= $this->render('_tags', ['model' => $model]) ?>
                        </div>
                        <?= $this->render('_created', ['model' => $model]) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="row">
        <?= LinkPager::widget(['pagination' => $dataProvider->getPagination()]) ?>
    </div>
</div>


