<?php

/**
 * Class Aoe_ExtendedFilter_Helper_Data
 *
 * @author Lee Saferite <lee.saferite@aoe.com>
 * @
 */
class Aoe_ExtendedFilter_Helper_Data extends Mage_Core_Helper_Abstract
{
    protected $directives = array();
    protected $modifiers = array();

    public function __construct()
    {
        $config = Mage::app()->getConfig()->getNode('global/filter/directives');
        if ($config && $config->children()) {
            foreach ($config->children() as $directive => $model) {
                $handler = Mage::getModel($model);
                if ($handler instanceof Aoe_ExtendedFilter_Model_Directive_Interface) {
                    $this->addDirective($directive, $handler);
                }
            }
        }
    }

    /**
     * @param                                                     $name
     * @param Aoe_ExtendedFilter_Model_Directive_Interface $handler
     *
     * @return $this
     */
    public function addDirective($name, Aoe_ExtendedFilter_Model_Directive_Interface $handler)
    {
        $name = strtolower(trim($name));
        $this->directives[$name] = $handler;
        return $this;
    }

    /**
     * @param $name
     *
     * @return $this
     */
    public function removeDirective($name)
    {
        $name = strtolower(trim($name));
        unset($this->directives[$name]);
        return $this;
    }

    /**
     * @param $name
     *
     * @return Aoe_ExtendedFilter_Model_Directive_Interface|null
     */
    public function getDirective($name)
    {
        $name = strtolower(trim($name));
        return (isset($this->directives[$name]) ? $this->directives[$name] : null);
    }

    /**
     * @param string   $name
     * @param callable $callback
     *
     * @return mixed
     */
    public function addModifier($name, callable $callback)
    {
        $this->modifiers[$name] = $callback;
        return $this;
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function removeModifier($name)
    {
        unset($this->modifiers[$name]);
        return $this;
    }

    /**
     * @param $name
     *
     * @return callable|null
     */
    public function getModifier($name)
    {
        if (isset($this->modifiers[$name])) {
            $callback = $this->modifiers[$name];
            if (!$callback) {
                $callback = $name;
            }
            return $callback;
        }

        return null;
    }

    public function getObjectData(Varien_Object $object, $key = null)
    {
        if ($key) {
            return $object->getDataUsingMethod($key);
        } else {
            return $object->getData();
        }
    }

    public function getSourceModelArray($modelRef, $useCollection = false)
    {
        $model = Mage::getSingleton($modelRef);
        if (!$model) {
            throw new RuntimeException($this->__('Could not create source model (%s)', $modelRef));
        }

        $useCollection = (bool)$useCollection;

        if ($useCollection) {
            if (!$model instanceof Mage_Core_Model_Abstract) {
                throw new RuntimeException($this->__('Invalid source model type (%s)', $modelRef));
            }

            $collection = $model->getCollection();
            if (!$collection) {
                throw new RuntimeException($this->__('Could not create collection for source model (%s)', $modelRef));
            }

            $model = $collection;
        }

        $additionalArgs = func_get_args();
        array_shift($additionalArgs);
        array_shift($additionalArgs);

        if (method_exists($model, 'toOptionArray')) {
            $optionArray = call_user_func_array(array($model, 'toOptionArray'), $additionalArgs);
        } elseif (method_exists($model, 'toOptionHash')) {
            $optionHash = call_user_func_array(array($model, 'toOptionHash'), $additionalArgs);
            $optionArray = array();
            foreach ($optionHash as $value => $label) {
                $optionArray[] = array(
                    'value' => $value,
                    'label' => $label
                );
            }
        } else {
            if ($useCollection) {
                throw new RuntimeException($this->__('Source model (%s) collection does not have required method toOptionArray or toOptionHash', $modelRef));
            } else {
                throw new RuntimeException($this->__('Source model (%s) does not have required method toOptionArray or toOptionHash', $modelRef));
            }
        }

        return $optionArray;
    }

    public function getSourceModelHash($modelRef, $useCollection = false)
    {
        $model = Mage::getSingleton($modelRef);
        if (!$model) {
            throw new RuntimeException($this->__('Could not create source model (%s)', $modelRef));
        }

        $useCollection = (bool)$useCollection;

        if ($useCollection) {
            if (!$model instanceof Mage_Core_Model_Abstract) {
                throw new RuntimeException($this->__('Invalid source model type (%s)', $modelRef));
            }

            $collection = $model->getCollection();
            if (!$collection) {
                throw new RuntimeException($this->__('Could not create collection for source model (%s)', $modelRef));
            }

            $model = $collection;
        }

        $additionalArgs = func_get_args();
        array_shift($additionalArgs);
        array_shift($additionalArgs);

        if (method_exists($model, 'toOptionHash')) {
            $optionHash = call_user_func_array(array($model, 'toOptionHash'), $additionalArgs);
        } elseif (method_exists($model, 'toOptionArray')) {
            $optionArray = call_user_func_array(array($model, 'toOptionArray'), $additionalArgs);
            $optionHash = array();
            foreach ($optionArray as $option) {
                $optionHash[$option['value']] = $option['label'];
            }
        } else {
            if ($useCollection) {
                throw new RuntimeException($this->__('Source model (%s) collection does not have required method toOptionArray or toOptionHash', $modelRef));
            } else {
                throw new RuntimeException($this->__('Source model (%s) does not have required method toOptionArray or toOptionHash', $modelRef));
            }
        }

        return $optionHash;
    }

    /**
     * @param Mage_Core_Model_Resource_Db_Collection_Abstract $collection
     * @param array                                           $filters
     * @param array                                           $ifConditionals
     *
     * @return Mage_Core_Model_Resource_Db_Collection_Abstract
     */
    public function filterCollection(Mage_Core_Model_Resource_Db_Collection_Abstract $collection, array $filters, array $ifConditionals = array())
    {
        $filter = true;
        foreach ($ifConditionals as $ifConditional) {
            if ($ifConditional === 'false' || !(bool)$ifConditional) {
                $filter = false;
                break;
            }
        }

        if ($filter) {
            foreach ($filters as $field => $condition) {
                $collection->addFieldToFilter($field, $condition);
            }
        }

        return $collection;
    }

    /**
     * @author Lee Saferite <lee.saferite@aoe.com>
     * @return Mage_Admin_Model_Session
     */
    public function getAdminSession()
    {
        return Mage::getSingleton('admin/session');
    }
}
