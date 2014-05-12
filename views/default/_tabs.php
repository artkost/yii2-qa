<?php
/**
 * @var string $route
 */
use artkost\qa\Module;
?>
<div class="qa-index-header">
    <a class="qa-index-add-button btn btn-primary" href="<?= Module::url(['ask']) ?>"><?= Module::t('Ask a Question') ?></a>
    <ul class="qa-index-tabs nav nav-tabs">
        <li <?= ($route == 'index') ? 'class="active"':''?>><a href="<?= Module::url(['index']) ?>"><?= Module::t('Active') ?></a></li>
        <? if(!Yii::$app->user->isGuest): ?>
            <li <?= ($route == 'my') ? 'class="active"':''?>><a href="<?= Module::url(['my']) ?>"><?= Module::t('My') ?></a></li>
            <li <?= ($route == 'favorite') ? 'class="active"':''?>><a href="<?= Module::url(['favorite']) ?>"><?= Module::t('Favorite') ?></a></li>
        <? endif; ?>
    </ul>
</div>