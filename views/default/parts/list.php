<?php
/**
 * @var \artkost\qa\models\Question[] $models
 */
use artkost\qa\Module;
use yii\helpers\Html;

?>
<div class="qa-list list-group">
    <?php if (!empty($models)): foreach ($models as $model): ?>
        <div class="qa-item list-group-item clearfix" id="question-<?= $model->id ?>">
            <div class="qa-panels">
                <div class="qa-panel votes">
                    <div class="mini-counts"><?= $model->votes ?></div>
                    <div><?= Module::t('votes')?></div>
                </div>
                <div class="qa-panel status-unanswered">
                    <div class="mini-counts"><?= $model->answers ?></div>
                    <div><?= Module::t('answers')?></div>
                </div>
                <div class="qa-panel views">
                    <div class="mini-counts"><?= $model->views ?></div>
                    <div><?= Module::t('views') ?></div>
                </div>
            </div>
            <div class="qa-summary">
                <h4 class="question-heading list-group-item-heading">
                    <a href="<?= Module::url(['view', 'id' => $model->id, 'alias' => $model->alias]) ?>"
                       class="question-link" title=""><?= Html::encode($model->title) ?></a>
                </h4>
                <div class="question-meta">
                    <?= $this->render('edit-links', ['model' => $model]) ?>
                    <?= $this->render('created', ['model' => $model]) ?>
                </div>
                <div class="question-tags">
                    <?= $this->render('tags-list', ['model' => $model]) ?>
                </div>
            </div>
        </div>
    <?php endforeach; else: ?>
        <div class="qa-item-not-found list-group-item">
            <h4 class="question-heading list-group-item-heading"><?= Module::t('No results found') ?></h4>
        </div>
    <?php endif; ?>
</div>