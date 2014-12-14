<?php
/**
 * @var string $route
 */
use artkost\qa\Module;

?>
<div class="qa-index-header">
    <a class="qa-index-add-button btn btn-primary"
       href="<?= Module::url(['ask']) ?>"><?= Module::t('main', 'Ask a Question') ?></a>
    <ul class="qa-index-tabs nav nav-tabs">
        <li <?= ($route == 'index') ? 'class="active"' : '' ?>><a
                href="<?= Module::url(['index']) ?>"><?= Module::t('main', 'Active') ?></a></li>
        <?php if (!Yii::$app->user->isGuest): ?>
            <li <?= ($route == 'my') ? 'class="active"' : '' ?>><a
                    href="<?= Module::url(['my']) ?>"><?= Module::t('main', 'My') ?></a></li>
            <li <?= ($route == 'favorite') ? 'class="active"' : '' ?>><a
                    href="<?= Module::url(['favorite']) ?>"><?= Module::t('main', 'Favorite') ?></a></li>
        <?php endif; ?>
    </ul>
</div>