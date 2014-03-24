<?php

namespace artkost\qa;

use artkost\qa\Module;
use Yii;
use yii\base\InvalidConfigException;

/**
 * ActiveRecord
 * @package artkost\qa
 */
class ActiveRecord extends \yii\db\ActiveRecord
{
    /**
     * @var Module
     */
    protected static $module;

    /**
     * Get instance of module Active Record depends on
     * @return Module
     * @throws InvalidConfigException
     */
    public function getModule()
    {
        if (self::$module == null) {

            $modules = Yii::$app->getModules(true);

            foreach ($modules as $id => $module) {
                if ($module instanceof Module) {
                    self::$module = $module;
                } elseif(is_array($module) && $module['class'] == Module::className()) {
                    self::$module = Yii::$app->getModule($id);
                }
            }

            if (self::$module == null) {
                throw new InvalidConfigException('Module not defined for this model');
            }
        }

        return self::$module;
    }

    /**
     * Alias of [[Module::t()]]
     * @param $message
     * @param array $params
     * @param null $language
     * @return string
     */
    public function t($message, $params = [], $language = null)
    {
        return Module::t($message, $params, $language);
    }
}