<?php

namespace artkost\qa;

use yii\web\AssetBundle;

/**
 * This declares the asset files required by QA.
 *
 * @author Nikolay Kostyurin <nikolay@artkost.ru>
 * @since 2.0
 */
class Asset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@artkost/qa/assets';
    /**
     * @inheritdoc
     */
    public $css = [
        'css/qa.css',
    ];
    /**
     * @inheritdoc
     */
    public $js = [
        'js/qa.js',
    ];
    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}