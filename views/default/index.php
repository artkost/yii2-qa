<?php
/**
 * @var \artkost\qa\models\Question[] $models
 * @var \yii\data\ActiveDataProvider $dataProvider
 */

use artkost\qa\Module;

$this->title = Module::t('Questions');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="qa-index container">
    <div class="row">
        <div class="col-md-12">
            <?= $this->render('_tabs', ['route' => $this->context->action->id]) ?>
            <?= $this->render('parts/list', ['models' => $models]) ?>
        </div>
    </div>
    <?= ($dataProvider) ? $this->render('parts/pager', ['dataProvider' => $dataProvider]) : false ?>
</div>


