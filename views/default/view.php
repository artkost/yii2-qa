<?php
/**
 * @var \artkost\qa\models\Question $model
 * @var \yii\data\ActiveDataProvider $answerDataProvider
 * @var string $answerOrder
 * @var \artkost\qa\models\Answer $answer
 * @var \yii\web\View $this
 */

use artkost\qa\Module;
use yii\helpers\Html;

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Module::t('main', 'Questions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$answerOrders = [
    'Active' => 'active',
    'Oldest' => 'oldest',
    'Votes' => 'votes'
];

?>
<div class="qa-view row">
    <div class="col-md-12">
        <div class="qa-view-question">
            <div class="qa-view-actions">
                <?= $this->render('parts/vote', ['model' => $model, 'route' => 'question-vote']) ?>
                <?= $this->render('parts/favorite', ['model' => $model]) ?>
            </div>
            <div class="qa-view-body">
                <div class="page-header">
                    <h1 class="qa-view-title">
                        <?= Html::encode($this->title) ?>
                        <?php if ($model->isDraft()): ?>
                            <small><span class="label label-default"><?= Module::t('main', 'Draft') ?></span></small>
                        <?php endif; ?>
                    </h1>
                </div>

                <div class="panel panel-default">
                    <div class="panel-body qa-view-text">
                        <?= $model->body ?>
                    </div>
                    <div class="panel-footer">
                        <div class="qa-view-meta">
                            <?= $this->render('parts/tags-list', ['model' => $model]) ?>
                            <?= $this->render('parts/edit-links', ['model' => $model]) ?>
                            <?= $this->render('parts/created', ['model' => $model]) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="qa-view-answers-heading clearfix">
            <h3 class="qa-view-title"><?= Module::t('main', '{n, plural, =0{No answers yet} =1{One answer} other{# answers}}', ['n' => $answerDataProvider->totalCount]); ?></h3>

            <?php if ($answerDataProvider->totalCount): ?>
                <ul class="qa-view-tabs nav nav-tabs">
                    <?php foreach ($answerOrders as $aId => $aOrder): ?>
                        <li <?= ($aOrder == $answerOrder) ? 'class="active"' : '' ?> >
                            <a href="<?= Module::url(['view', 'id' => $model->id, 'alias' => $model->alias, 'answers' => $aOrder]) ?>">
                                <?= Module::t('main', $aId) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

        <div class="qa-view-answers">
            <?php foreach ($answerDataProvider->models as $row): ?>
                <div class="qa-view-answer panel panel-default">
                    <div class="panel-body">
                        <div class="qa-view-text">
                            <?= $row->body ?>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <div class="qa-view-meta">
                            <?= $this->render('parts/edit-links', ['model' => $row]) ?>
                            <?= $this->render('parts/created', ['model' => $row]) ?>
                        </div>
                        <div class="qa-answer-like">
                            <?= $this->render('parts/like', ['model' => $row, 'route' => 'answer-vote']) ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="qa-view-pager">
            <?= $this->render('parts/pager', ['dataProvider' => $answerDataProvider]) ?>
        </div>

        <div class="qa-view-answer-form">
            <?= $this->render('parts/form-answer', ['model' => $answer, 'action' => Module::url(['answer', 'id' => $model->id])]); ?>
        </div>
    </div>
</div>
