<?php

/**
 * Class Aoe_ExtendedFilter_Model_Cms
 *
 * @author Lee Saferite <lee.saferite@aoe.com>
 */
class Aoe_ExtendedFilter_Model_Cms extends Mage_Cms_Model_Template_Filter implements Aoe_ExtendedFilter_Model_Interface
{
    protected $directives;

    /**
     * @param string $value
     *
     * @return string
     */
    public function filter($value)
    {
        try {
            $handlers = [
                self::CONSTRUCTION_DEPEND_PATTERN => [$this, 'dependDirective'],
                self::CONSTRUCTION_IF_PATTERN     => [$this, 'ifDirective'],
                self::CONSTRUCTION_PATTERN        => [$this, 'directiveLookup']
            ];
            foreach ($handlers as $pattern => $callback) {
                $value = preg_replace_callback($pattern, $callback, $value);
            }
        } catch (Exception $e) {
            $value = '';
            Mage::logException($e);
        }
        return $value;
    }

    /**
     *
     *
     * @param array $params
     *
     * @return string
     */
    public function directiveLookup(array $params)
    {
        /** @var $helper Aoe_ExtendedFilter_Helper_Data */
        $helper = Mage::helper('Aoe_ExtendedFilter');
        $directive = $helper->getDirective($params[1]);
        if ($directive instanceof Aoe_ExtendedFilter_Model_Directive_Interface) {
            return $directive->process($this, $params);
        }

        $legacyCallback = [$this, $params[1] . 'Directive'];
        if (is_callable($legacyCallback)) {
            return call_user_func($legacyCallback, $params);
        }

        return $params[0];
    }

    /**
     * Apply modifiers one by one, with specified params
     *
     * Modifier syntax: modifier1[:param1:param2:...][|modifier2:...]
     *
     * @param string $value
     * @param string $modifiers
     *
     * @return string
     */
    public function amplifyModifiers($value, $modifiers)
    {
        return $this->_amplifyModifiers($value, $modifiers);
    }

    /**
     * Apply modifiers one by one, with specified params
     *
     * Modifier syntax: modifier1[:param1:param2:...][|modifier2:...]
     *
     * @param string $value
     * @param string $modifiers
     *
     * @return string
     */
    protected function _amplifyModifiers($value, $modifiers)
    {
        /** @var $helper Aoe_ExtendedFilter_Helper_Data */
        $helper = Mage::helper('Aoe_ExtendedFilter');
        foreach (explode('|', $modifiers) as $part) {
            if (empty($part)) {
                continue;
            }
            $params = explode(':', $part);
            $modifier = array_shift($params);
            $callback = $helper->getModifier($modifier);

            // Legacy modifiers
            if (!is_callable($callback) && isset($this->_modifiers[$modifier])) {
                $callback = $this->_modifiers[$modifier];
                if (!$callback) {
                    $callback = $modifier;
                }
            }

            if (is_callable($callback)) {
                array_unshift($params, $value);
                $value = call_user_func_array($callback, $params);
            }
        }

        return $value;
    }

    /**
     * Retrieve block parameters
     *
     * @param mixed $value
     *
     * @return array
     */
    public function getBlockParameters($value)
    {
        return parent::_getBlockParameters($value);
    }

    /**
     * Return associative array of include construction.
     *
     * @param string $value raw parameters
     *
     * @return array
     */
    public function getIncludeParameters($value)
    {
        return parent::_getIncludeParameters($value);
    }

    /**
     * Return variable value for var construction
     *
     * @param string $value   raw parameters
     * @param string $default default value
     *
     * @return string
     */
    public function getVariable($value, $default = '{no_value_defined}')
    {
        return parent::_getVariable($value, $default);
    }
}
