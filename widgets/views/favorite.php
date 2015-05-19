<?php
/**
 * @var artkost\qa\models\Favorite[] $models
 */
use artkost\qa\Module;
use yii\helpers\Html;

?>

<div class="panel panel-default">
    <div class="panel-heading"><?= Module::t('main', 'Favorite Questions') ?></div>
    <ul class="qa-questions-list list-group">
        <?php if (!empty($models)): ?>
            <?php foreach ($models as $model): ?>
                <li class="list-group-item">
                    <a href="<?= Module::url(['/qa/default/view', 'id' => $model->question->id, 'alias' => $model->question->alias]) ?>">
                        <?= Html::encode($model->question->title) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <li class="list-group-item"><?= Module::t('main', 'No favorite questions') ?></li>
        <?php endif; ?>
    </ul>
</div>

