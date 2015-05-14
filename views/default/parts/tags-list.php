<?php
/**
 * @var \artkost\qa\models\Question $model
 */
use artkost\qa\Module;
use yii\helpers\Html;

?>

<span class="qa-tags">
    <?php foreach ($model->tagsList as $tag): ?>
        <a href="<?= Module::url(['tags', 'tags' => $tag]) ?>" class="label label-primary" title="<?= Html::encode($tag) ?>"
           rel="tag"><?= Html::encode($tag) ?></a>
    <?php endforeach; ?>
</span>
