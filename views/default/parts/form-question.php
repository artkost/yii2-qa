<?php
use artkost\qa\components\ActiveField;
use artkost\qa\Module;
use yii\helpers\Html;
use yii\helpers\Url;
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
    ->hint(Module::t('')); ?>
<?= $form->field($model, 'content')
    ->textarea()
    ->hint(Module::t('Markdown powered content')); ?>

<?= $form->field($model, 'tags')
    ->autoComplete(Url::toRoute('tag-suggest'))
    ->textInput()
    ->hint(Module::t('Comma separated list of tags')) ?>

    <div class="form-group">
        <?= Html::submitButton(Module::t($model->isNewRecord ? 'Ask' : 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

<? ActiveForm::end(); ?>