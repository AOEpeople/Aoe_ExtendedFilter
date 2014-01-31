<?php

/**
 * Interface Aoe_ExtendedFilter_Model_Directive_Interface
 *
 * @author Lee Saferite <lee.saferite@aoe.com>
 */
interface Aoe_ExtendedFilter_Model_Directive_Interface
{
    /**
     * @param Aoe_ExtendedFilter_Model_Interface $filter
     * @param array                                     $params
     *
     * @return mixed
     */
    public function process(Aoe_ExtendedFilter_Model_Interface $filter, array $params);
}
