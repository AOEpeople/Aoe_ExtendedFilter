<?php

/**
 * @author Lee Saferite <lee.saferite@aoe.com>
 * @since  2014-04-23
 */
abstract class Aoe_ExtendedFilter_Helper_AbstractModel extends Aoe_ExtendedFilter_Helper_Data
{
    /**
     * Get the frontname and controller portion of the route
     *
     * @return string
     *
     * @author Lee Saferite <lee.saferite@aoe.com>
     */
    abstract protected function getControllerRoute();

    /**
     * @param $action
     *
     * @return bool
     *
     * @author Lee Saferite <lee.saferite@aoe.com>
     */
    abstract public function getAclPermission($action);

    /**
     * @return string
     *
     * @author Lee Saferite <lee.saferite@aoe.com>
     */
    abstract public function getCurrentRecordKey();

    /**
     * Get a model instance
     *
     * @return Mage_Core_Model_Abstract
     *
     * @author Lee Saferite <lee.saferite@aoe.com>
     */
    abstract public function getModel();

    /**
     * Get a collection of model objects
     *
     * @return Mage_Core_Model_Resource_Db_Collection_Abstract
     *
     * @author Lee Saferite <lee.saferite@aoe.com>
     */
    public function getCollection()
    {
        /** @var $collection Mage_Core_Model_Resource_Db_Collection_Abstract */
        $collection = $this->getModel()->getCollection();

        return $collection;
    }

    /**
     *
     * @return string
     *
     * @author Lee Saferite <lee.saferite@aoe.com>
     */
    public function getGridUrl()
    {
        return Mage::helper('adminhtml')->getUrl($this->getControllerRoute());
    }

    /**
     *
     * @param Mage_Core_Model_Abstract $model
     *
     * @return string
     * @throws RuntimeException
     *
     * @author David Robinson <david.robinson@aoe.com>
     * @since 2014-94-22
     */
    public function getViewUrl($model = null)
    {
        if (!$model instanceof Mage_Core_Model_Abstract) {
            $model = $this->getCurrentRecord();
        } else {
            $expectedClass = get_class($this->getModel());
            if (!is_a($model, $expectedClass)) {
                throw new RuntimeException($this->__('Invalid model class. Expected:%1$s Passed:%2$s', $expectedClass, get_class($model)));
            }
        }

        return Mage::helper('adminhtml')->getUrl($this->getControllerRoute() . '/view', array('id' => $model->getId()));
    }

    /**
     * @return Mage_Core_Model_Abstract
     * @throws RuntimeException
     *
     * @author Lee Saferite <lee.saferite@aoe.com>
     */
    public function getCurrentRecord()
    {
        $model = Mage::registry($this->getCurrentRecordKey());

        if (!$model) {
            $model = $this->getModel();
        } else {
            $expectedClass = get_class($this->getModel());
            if (!is_a($model, $expectedClass)) {
                throw new RuntimeException($this->__('Invalid model class. Expected:%1$s Passed:%2$s', $expectedClass, get_class($model)));
            }
        }

        return $model;
    }

    /**
     * @param Mage_Core_Model_Abstract $model
     *
     * @return $this
     * @throws RuntimeException
     *
     * @author Lee Saferite <lee.saferite@aoe.com>
     */
    public function setCurrentRecord(Mage_Core_Model_Abstract $model = null)
    {
        if ($model) {
            $expectedClass = get_class($this->getModel());
            if (!is_a($model, $expectedClass)) {
                throw new RuntimeException($this->__('Invalid model class. Expected:%1$s Passed:%2$s', $expectedClass, get_class($model)));
            }
        }

        Mage::unregister($this->getCurrentRecordKey());

        if ($model) {
            Mage::register($this->getCurrentRecordKey(), $model);
        }

        return $this;
    }
}
