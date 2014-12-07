<?php
/**
 * @var artkost\qa\models\Tag[] $models
 */
use artkost\qa\Module;

?>

<div class="panel panel-default">
    <div class="panel-heading"><?= Module::t('Popular tags') ?></div>
    <div class="panel-body">
        <span class="qa-tags">
            <?php foreach ($models as $tag): ?>
                <a href="<?= Module::url(['/qa/default/tags', 'tags' => $tag['name']]) ?>"
                   class="label label-primary"
                   title="<?= $tag['name'] ?>"
                   rel="tag"><?= $tag['name'] ?></a>
            <?php endforeach; ?>
        </span>
    </div>
</div>

