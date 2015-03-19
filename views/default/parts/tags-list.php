<?php
/**
 * @var \artkost\qa\models\Question $model
 */
use artkost\qa\Module;
?>

<span class="qa-tags">
<?php foreach ($model->tagsList as $tag): ?>
    <a href="<?= Module::url(['tags', 'tags' => $tag]) ?>" class="label label-primary" title=""
       rel="tag"><?= $tag ?></a>
<?php endforeach; ?>
</span>