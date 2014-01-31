<?php

/**
 * Class Aoe_ExtendedFilter_Model_Directive_Translate
 *
 * @author Lee Saferite <lee.saferite@aoe.com>
 */
class Aoe_ExtendedFilter_Model_Directive_Translate implements Aoe_ExtendedFilter_Model_Directive_Interface
{
    /**
     * @param Aoe_ExtendedFilter_Model_Interface $filter
     * @param array                                     $params
     *
     * @return mixed
     */
    public function process(Aoe_ExtendedFilter_Model_Interface $filter, array $params)
    {
        // Re-parse the third parameter with the tokenizer and discard original parameters
        $params = $filter->getIncludeParameters($params[2]);

        // Pull out expected parameters
        $text = (isset($params['text']) ? $params['text'] : '');
        $modifiers = (isset($params['modifiers']) ? $params['modifiers'] : '');
        $module = (isset($params['module']) ? $params['module'] : 'core');

        // Create helper here as a fail-fast
        $helper = Mage::helper($module);

        // Prep parameters for translation call
        unset($params['text']);
        unset($params['modifiers']);
        unset($params['module']);
        array_unshift($params, $text);

        // Translate the text
        $text = call_user_func_array(array($helper, '__'), $params);

        // If we have modifiers, process now
        if (!empty($modifiers)) {
            $text = $filter->amplifyModifiers($text, $modifiers);
        }

        return $text;
    }
}
