<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;

$action = isset($action) ? $action : '';

/** @var ActiveForm $form */
$form = ActiveForm::begin(['id' => 'answer-form', 'action' => $action]);
?>

<?= $form->errorSummary($model); ?>

<?=$form->field($model, 'content')->textarea(['rows' => 6])->label(''); ?>

<div class="form-group">
    <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end() ?>