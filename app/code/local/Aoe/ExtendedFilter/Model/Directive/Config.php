<?php

/**
 * Class Aoe_ExtendedFilter_Model_Directive_Config
 *
 * @author Lee Saferite <lee.saferite@aoe.com>
 */
class Aoe_ExtendedFilter_Model_Directive_Config implements Aoe_ExtendedFilter_Model_Directive_Interface
{
    /**
     * @param Aoe_ExtendedFilter_Model_Interface $filter
     * @param array                              $params
     *
     * @return mixed
     */
    public function process(Aoe_ExtendedFilter_Model_Interface $filter, array $params)
    {
        // Re-parse the third parameter with the parameter tokenizer and discard original parameters
        $params = $filter->getIncludeParameters($params[2]);

        // Grab the store
        $storeId = $filter->getStoreId();

        // Pull out expected parameters
        $path = (isset($params['path']) ? $params['path'] : '');
        $modifiers = (isset($params['modifiers']) ? $params['modifiers'] : '');

        // Lookup config
        $value = '';
        if (!empty($path)) {
            $value = Mage::getStoreConfig($path, $storeId);
        }

        // If we have modifiers, process now
        if (!empty($modifiers)) {
            $value = $filter->amplifyModifiers($value, $modifiers);
        }

        return $value;
    }
}
