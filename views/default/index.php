<?php
/**
 * @var \artkost\qa\models\Question[] $models
 * @var \yii\data\ActiveDataProvider $dataProvider
 */

use artkost\qa\Module;

$models = $dataProvider->getModels();

$this->title = Module::t('Questions');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="qa-index container">
    <div class="row">
        <a class="btn btn-primary" href="<?= Module::url(['ask']) ?>"><?= Module::t('Ask a Question') ?></a>
    </div>
    <div class="row">
        <div class="qa-list list-group">
            <?php foreach ($models as $model): ?>
                <div class="list-group-item clearfix" id="question-<?= $model->id ?>">
                    <div class="cp pull-left">
                        <div class="votes pull-left">
                            <div class="mini-counts"><?= $model->votes ?></div>
                            <div><?= Module::t('votes')?></div>
                        </div>
                        <div class="status pull-left unanswered">
                            <div class="mini-counts"><?= $model->answers ?></div>
                            <div><?= Module::t('answers')?></div>
                        </div>
                        <div class="views pull-left">
                            <div class="mini-counts"><?= $model->views ?></div>
                            <div><?= Module::t('views') ?></div>
                        </div>
                    </div>
                    <div class="summary">
                        <h4 class="list-group-item-heading">
                            <a href="<?= Module::url(['view', 'id' => $model->id, 'alias' => $model->alias]) ?>"
                               class="question-hyperlink" title=""><?= $model->title ?></a>

                        </h4>

                        <div class="tags">
                            <?= $this->render('_tags', ['model' => $model]) ?>
                        </div>
                        <div class="question-meta">
                            <?= $this->render('_edit_links', ['model' => $model]) ?>
                            <?= $this->render('_created', ['model' => $model]) ?>
                        </div>

                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?= $this->render('_pager', ['dataProvider' => $dataProvider]) ?>
</div>


