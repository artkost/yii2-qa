<?php

namespace artkost\qa;

use yii\base\BootstrapInterface;
use yii\base\InvalidConfigException;

class Bootstrap implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        if ($app instanceof \yii\console\Application) {
            $this->controllerNamespace = '\artkost\qa\commands';
        } else {
            if (!class_exists($app->get('user')->identityClass)) {
                throw new InvalidConfigException('Identity class does not exist');
            }
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
