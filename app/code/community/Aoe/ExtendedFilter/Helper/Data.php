<?php

/**
 * Class Aoe_ExtendedFilter_Helper_Data
 *
 * @author Lee Saferite <lee.saferite@aoe.com>
 * @
 */
class Aoe_ExtendedFilter_Helper_Data extends Mage_Core_Helper_Abstract
{
    protected $directives = [];
    protected $modifiers = [];

    public function __construct()
    {
        $config = Mage::app()->getConfig()->getNode('global/filter/directives');
        if ($config && $config->children()) {
            foreach ($config->children() as $directive => $model) {
                $handler = Mage::getModel($model);
                if ($handler instanceof Aoe_ExtendedFilter_Model_Directive_Interface) {
                    $this->addDirective($directive, $handler);
                }
            }
        }
    }

    /**
     * @param                                                     $name
     * @param Aoe_ExtendedFilter_Model_Directive_Interface        $handler
     *
     * @return $this
     */
    public function addDirective($name, Aoe_ExtendedFilter_Model_Directive_Interface $handler)
    {
        $name = strtolower(trim($name));
        $this->directives[$name] = $handler;
        return $this;
    }

    /**
     * @param $name
     *
     * @return $this
     */
    public function removeDirective($name)
    {
        $name = strtolower(trim($name));
        unset($this->directives[$name]);
        return $this;
    }

    /**
     * @param $name
     *
     * @return Aoe_ExtendedFilter_Model_Directive_Interface|null
     */
    public function getDirective($name)
    {
        $name = strtolower(trim($name));
        return (isset($this->directives[$name]) ? $this->directives[$name] : null);
    }

    /**
     * @param string   $name
     * @param callable $callback
     *
     * @return mixed
     */
    public function addModifier($name, $callback)
    {
        if (!is_callable($callback)) {
            throw new InvalidArgumentException('Invalid argument type. Callbacks must be callable.');
        }
        $this->modifiers[$name] = $callback;
        return $this;
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function removeModifier($name)
    {
        unset($this->modifiers[$name]);
        return $this;
    }

    /**
     * @param $name
     *
     * @return callable|null
     */
    public function getModifier($name)
    {
        if (isset($this->modifiers[$name])) {
            $callback = $this->modifiers[$name];
            if (!$callback) {
                $callback = $name;
            }
            return $callback;
        }

        return null;
    }
}
