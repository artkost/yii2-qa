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
$this->params['breadcrumbs'][] = ['label' => Module::t('Questions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$answerOrders = [
    'Active' => 'active',
    'Oldest' => 'oldest',
    'Votes' => 'votes'
];

?>
<div class="container">
    <div class="qa-view row">
        <div class="col-md-12">
            <div class="qa-view-question">
                <div class="qa-view-actions">
                    <?= $this->render('parts/vote', ['model' => $model, 'route' => 'question-vote']) ?>
                    <?= $this->render('parts/favorite', ['model' => $model]) ?>
                </div>
                <div class="qa-view-body">
                    <h1 class="qa-view-title"><?= Html::encode($this->title) ?></h1>

                    <div class="qa-view-text">
                        <?= Html::encode($model->content) ?>
                    </div>

                    <div class="qa-view-meta">
                        <?= $this->render('parts/tags-list', ['model' => $model]) ?>
                        <?= $this->render('parts/edit-links', ['model' => $model]) ?>
                        <?= $this->render('parts/created', ['model' => $model]) ?>
                    </div>
                </div>
            </div>

            <div class="qa-view-answers-heading clearfix">
                <h3 class="qa-view-title"><?= Module::t('{n, plural, =0{No Answers yet} =1{One Answer} other{# Answers}}', ['n' => $answerDataProvider->totalCount]); ?></h3>

                <?php if ($answerDataProvider->totalCount): ?>
                    <ul class="qa-view-tabs nav nav-tabs">
                        <?php foreach ($answerOrders as $aId => $aOrder): ?>
                            <li <?= ($aOrder == $answerOrder) ? 'class="active"' : '' ?> >
                                <a href="<?= Module::url(['view', 'id' => $model->id, 'alias' => $model->alias, 'answers' => $aOrder]) ?>"><?= Module::t($aId) ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>

            <div class="qa-view-answers">
                <?php foreach ($answerDataProvider->models as $row): ?>
                    <div class="qa-view-answer">
                        <div class="qa-view-actions">
                            <?= $this->render('parts/vote', ['model' => $row, 'route' => 'answer-vote']) ?>
                        </div>
                        <div class="qa-view-body">
                            <div class="qa-view-text">
                                <?= Html::encode($row->content) ?>
                            </div>

                            <div class="qa-view-meta">
                                <?= $this->render('parts/edit-links', ['model' => $row]) ?>
                                <?= $this->render('parts/created', ['model' => $row]) ?>
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
</div>
