<?php

/**
 * @author Lee Saferite <lee.saferite@aoe.com>
 * @since  3/25/14
 */
abstract class Aoe_ExtendedFilter_Controller_Abstract extends Mage_Adminhtml_Controller_Action
{
    /**
     * @author Lee Saferite <lee.saferite@aoe.com>
     * @return Mage_Admin_Model_Session
     */
    public function getAdminSession()
    {
        return Mage::getSingleton('admin/session');
    }
}
