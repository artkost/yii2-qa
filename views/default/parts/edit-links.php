<?php
/**
 * @var \artkost\qa\models\Question $model
 */
use artkost\qa\models\Question;
use artkost\qa\Module;

$editRoute = ['edit', 'id' => $model->id];
$deleteRoute = ['delete', 'id' => $model->id];

?>

<?php if ($model->isAuthor()) : ?>
    <a href="<?= Module::url($editRoute) ?>" title="<?= Module::t('main', 'Edit'); ?>"
       class="label label-success"><span
            class="glyphicon glyphicon-pencil"></span></a>

    <?php if ($model instanceof Question): ?>
        <a href="<?= Module::url($deleteRoute) ?>" title="<?= Module::t('main', 'Delete'); ?>"
           class="label label-danger"
           data-confirm="<?= Module::t('main', 'Sure?'); ?>" data-method="post" data-pjax="0"><span
                class="glyphicon glyphicon-remove"></span></a>
    <?php endif; ?>
<?php endif; ?>
