<?php

/**
 * Interface Aoe_ExtendedFilter_Model_Filter_Interface
 *
 * @author Lee Saferite <lee.saferite@aoe.com>
 */
interface Aoe_ExtendedFilter_Model_Filter_Interface extends Zend_Filter_Interface
{
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
    public function amplifyModifiers($value, $modifiers);

    /**
     * Retrieve block parameters
     *
     * @param mixed $value
     *
     * @return array
     */
    public function getBlockParameters($value);

    /**
     * Return associative array of include construction.
     *
     * @param string $value raw parameters
     *
     * @return array
     */
    public function getIncludeParameters($value);

    /**
     * Return variable value for var construction
     *
     * @param string $value   raw parameters
     * @param string $default default value
     *
     * @return string
     */
    public function getVariable($value, $default = '{no_value_defined}');

    /**
     * Return either the manually set store ID or the current store ID
     *
     * @return integer
     */
    public function getStoreId();
}
