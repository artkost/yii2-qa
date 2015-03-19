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
    public $sourcePath = '@artkost/qa/assets';
    public $css = [
        'css/qa.css',
    ];
    public $js = [
        'js/qa.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'artkost\qa\TypeAheadAsset',
    ];
}