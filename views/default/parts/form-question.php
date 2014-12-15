<?php
use artkost\qa\components\ActiveField;
use artkost\qa\Module;
use yii\widgets\ActiveForm;

/** @var ActiveForm $form */
$form = ActiveForm::begin([
    'id' => 'question-form',
    'fieldConfig' => ['class' => ActiveField::className()]
]);
?>

<?= $form->errorSummary($model); ?>

<?= $form->field($model, 'title')
    ->textInput()
    ->hint(Module::t('main', "What's your question? Be specific.")); ?>

<?= $form->field($model, 'content')
    ->textarea()
    ->hint(Module::t('main', 'HTML filtered content')); ?>

<?= $form->field($model, 'tags')
    ->autoComplete(['/qa/default/tag-suggest'])
    ->textInput()
    ->hint(Module::t('main', 'Comma separated list of tags')) ?>

    <div class="form-group">
        <div class="btn-group btn-group-lg">
            <button type="submit" name="draft" class="btn"><?= Module::t('main', 'Draft') ?></button>
            <?php if ($model->isNewRecord): ?>
                <button type="submit" name="submit" class="btn btn-primary"><?= Module::t('main', 'Publish') ?></button>
            <?php else: ?>
                <button type="submit" name="update" class="btn btn-success"><?= Module::t('main', 'Update') ?></button>
            <?php endif; ?>
        </div>
    </div>

<? ActiveForm::end(); ?>