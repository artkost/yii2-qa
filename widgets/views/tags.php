<?php
/**
 * @var artkost\qa\models\Tag[] $models
 */
use artkost\qa\Module;
use yii\helpers\Html;

?>

<div class="panel panel-default">
    <div class="panel-heading"><?= Module::t('main', 'Popular tags') ?></div>
    <div class="panel-body">
        <span class="qa-tags">
            <?php foreach ($models as $tag): ?>
                <a href="<?= Module::url(['/qa/default/tags', 'tags' => $tag['name']]) ?>"
                   class="label label-primary"
                   title="<?= Html::encode($tag['name']) ?>"
                   rel="tag"><?= Html::encode($tag['name']) ?></a>
            <?php endforeach; ?>
        </span>
    </div>
</div>

