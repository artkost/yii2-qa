<?php

namespace artkost\qa;

use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface
{

    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        if ($app instanceof \yii\console\Application) {
            $this->controllerNamespace = '\artkost\qa\commands';
        } else if ($app instanceof \yii\web\Application) {
            $configUrlRule = [
                'routePrefix' => $this->id,
                'prefix' => $this->urlPrefix,
                'rules' => $this->urlRules
            ];

            $app->get('urlManager')->rules[] = new GroupUrlRule($configUrlRule);
        }

        $app->get('i18n')->translations['artkost/qa/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => __DIR__ . '/messages',
            'fileMap' => [
                'qa/main' => 'main.php',
                'qa/model' => 'model.php'
            ]
        ];
    }
}
