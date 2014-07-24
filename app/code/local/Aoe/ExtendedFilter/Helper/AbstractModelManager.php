<?php

/**
 * @author Lee Saferite <lee.saferite@aoe.com>
 * @since  3/18/14
 */
abstract class Aoe_ExtendedFilter_Helper_AbstractModelManager extends Aoe_ExtendedFilter_Helper_AbstractModel
{
    /**
     * @return Varien_Data_Form
     *
     * @author Lee Saferite <lee.saferite@aoe.com>
     */
    abstract public function getForm();

    /**
     *
     * @return string
     *
     * @author Lee Saferite <lee.saferite@aoe.com>
     */
    public function getAddUrl()
    {
        return $this->_getUrl($this->getControllerRoute() . '/new');
    }

    /**
     *
     * @param Mage_Core_Model_Abstract $model
     *
     * @return string
     * @throws RuntimeException
     *
     * @author Lee Saferite <lee.saferite@aoe.com>
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

        return $this->_getUrl($this->getControllerRoute() . '/edit', array('id' => $model->getId()));
    }

    /**
     * @param Mage_Core_Model_Abstract $model
     *
     * @return string
     * @throws RuntimeException
     *
     * @author Lee Saferite <lee.saferite@aoe.com>
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

        return $this->_getUrl($this->getControllerRoute() . '/delete', array('id' => $model->getId()));
    }
}
