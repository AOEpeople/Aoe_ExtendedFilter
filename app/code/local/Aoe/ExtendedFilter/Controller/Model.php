<?php

/**
 * @author Lee Saferite <lee.saferite@aoe.com>
 * @since  2014-04-01
 */
abstract class Aoe_ExtendedFilter_Controller_Model extends Aoe_ExtendedFilter_Controller_Abstract
{
    /**
     * List existing records via a grid
     *
     * @author Lee Saferite <lee.saferite@aoe.com>
     */
    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * View existing record
     *
     * @author Lee Saferite <lee.saferite@aoe.com>
     */
    public function viewAction()
    {
        $model = $this->loadModel();
        if (!$model->getId()) {
            $this->_forward('noroute');
            return;
        }

        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * @return Mage_Core_Model_Abstract
     *
     * @author Lee Saferite <lee.saferite@aoe.com>
     */
    protected function loadModel()
    {
        /** @var Mage_Core_Model_Abstract $model */
        $model = $this->getHelper()->getModel()->load($this->getRequest()->getParam('id'));

        $this->getHelper()->setCurrentRecord($model);

        return $model;
    }

    /**
     * @return Aoe_ExtendedFilter_Helper_AbstractModelManager
     *
     * @author Lee Saferite <lee.saferite@aoe.com>
     */
    abstract protected function getHelper();

    /**
     * @return bool
     *
     * @author Lee Saferite <lee.saferite@aoe.com>
     */
    protected function _isAllowed()
    {
        return $this->getHelper()->getAclPermission($this->getAclActionName());
    }

    protected function getAclActionName()
    {
        $action = $this->getRequest()->getActionName();

        if ($action === 'index') {
            $action = 'view';
        }

        return $action;
    }
}
