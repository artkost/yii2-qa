<?php

use artkost\qa\Module;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$action = isset($action) ? $action : '';

/** @var ActiveForm $form */
$form = ActiveForm::begin(['id' => 'answer-form', 'action' => $action]);
?>

<?= $form->errorSummary($model); ?>

<?= $form->field($model, 'content')
    ->textarea(['rows' => 6])
    ->hint(Module::t('main', 'Markdown powered content'))
    ->label(''); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Module::t('main', 'Answer') : Module::t('main', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end() ?>
