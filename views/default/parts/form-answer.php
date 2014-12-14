<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use artkost\qa\Module;

$action = isset($action) ? $action : '';

/** @var ActiveForm $form */
$form = ActiveForm::begin(['id' => 'answer-form', 'action' => $action]);
?>

<?= $form->errorSummary($model); ?>

<?= $form->field($model, 'content')->textarea(['rows' => 6])->label(''); ?>

    <div class="form-group">
        <?= Html::submitButton(Module::t('main', $model->isNewRecord ? 'Answer' : 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end() ?>