<?php
/**
 * @var array $tags
 * @var \artkost\qa\models\Question[] $models
 * @var \yii\data\ActiveDataProvider $dataProvider
 */

use artkost\qa\Module;
use yii\helpers\Html;

$this->title = Module::t('Questions by tags');
$this->params['breadcrumbs'][] = $this->title;
$this->params['breadcrumbs'][] = Html::encode(implode(', ', $tags));
?>
<div class="qa-tags">
    <div class="row">
        <div class="col-md-12">
            <?= $this->render('_tabs', ['route' => $this->context->action->id]) ?>
            <?= $this->render('parts/list', ['models' => $models]) ?>
        </div>
    </div>
    <?= ($dataProvider) ? $this->render('parts/pager', ['dataProvider' => $dataProvider]) : false ?>
</div>


