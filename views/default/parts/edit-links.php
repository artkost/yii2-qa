<?php
/**
 * @var \artkost\qa\models\Question $model
 */
use artkost\qa\Module;
?>

<?php if ($model->isAuthor()) : ?>
    <a href="<?= Module::url(['edit', 'id' => $model->id]) ?>"
       class="label label-success"><?= Yii::t('artkost\qa', 'Edit'); ?></a>
    <a href="<?= Module::url(['delete', 'id' => $model->id]) ?>" class="label label-danger"
       data-confirm="<?= Yii::t('artkost\qa', 'Sure?'); ?>" data-method="post" data-pjax="0"><span
            class="glyphicon glyphicon-remove"></span></a>
<?php endif; ?>