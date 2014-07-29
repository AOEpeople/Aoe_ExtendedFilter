<?php

/**
 * @author Lee Saferite <lee.saferite@aoe.com>
 * @since  3/18/14
 */
abstract class Aoe_ExtendedFilter_Helper_AbstractModelManager extends Aoe_ExtendedFilter_Helper_AbstractModel
{
    /**
     * @return Varien_Data_Form
     */
    abstract public function getForm();

    /**
     * @return string
     */
    public function getAddRoute()
    {
        return $this->getControllerRoute() . '/new';
    }

    /**
     * @return string
     */
    public function getAddUrl()
    {
        return $this->_getUrl($this->getAddRoute());
    }

    /**
     * @return string
     */
    public function getEditRoute()
    {
        return $this->getControllerRoute() . '/edit';
    }

    /**
     * @param Mage_Core_Model_Abstract $model
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEditUrl($model = null)
    {
        if (!$model instanceof Mage_Core_Model_Abstract) {
            $model = $this->getCurrentRecord();
        } else {
            $expectedClass = get_class($this->getModel());
            if (!is_a($model, $expectedClass)) {
                throw new RuntimeException($this->__('Invalid model class. Expected:%1$s Passed:%2$s', $expectedClass, get_class($model)));
            }
        }

        return $this->_getUrl($this->getEditRoute(), array('id' => $model->getId()));
    }

    /**
     * @return string
     */
    public function getDeleteRoute()
    {
        return $this->getControllerRoute() . '/delete';
    }


    /**
     * @param Mage_Core_Model_Abstract $model
     *
     * @return string
     * @throws RuntimeException
     */
    public function getDeleteUrl($model = null)
    {
        if (!$model instanceof Mage_Core_Model_Abstract) {
            $model = $this->getCurrentRecord();
        } else {
            $expectedClass = get_class($this->getModel());
            if (!is_a($model, $expectedClass)) {
                throw new RuntimeException($this->__('Invalid model class. Expected:%1$s Passed:%2$s', $expectedClass, get_class($model)));
            }
        }

        return $this->_getUrl($this->getDeleteRoute(), array('id' => $model->getId()));
    }
}
