<?php

/**
 * @author Lee Saferite <lee.saferite@aoe.com>
 * @since  3/24/14
 */
class Aoe_ExtendedFilter_Model_Layout extends Mage_Core_Model_Layout
{
    /**
     * Convert an action node into a method call on the parent block
     *
     * @param Varien_Simplexml_Element $node
     * @param Varien_Simplexml_Element $parent
     *
     * @return Mage_Core_Model_Layout
     */
    protected function _generateAction($node, $parent)
    {
        if (isset($node['ifconfig']) && ($configPath = (string)$node['ifconfig'])) {
            if (substr($configPath, 0, 1) === '!') {
                if (Mage::getStoreConfigFlag($configPath)) {
                    return $this;
                }
            } else {
                if (!Mage::getStoreConfigFlag($configPath)) {
                    return $this;
                }
            }
        }

        if (isset($node['acl']) && ($aclPath = (string)$node['acl'])) {
            if (!Mage::getSingleton('admin/session')->isAllowed($aclPath)) {
                return $this;
            }
        }

        $method = (string)$node['method'];
        if (!empty($node['block'])) {
            $parentName = (string)$node['block'];
        } else {
            $parentName = $parent->getBlockName();
        }

        $_profilerKey = 'BLOCK ACTION: ' . $parentName . ' -> ' . $method;
        Varien_Profiler::start($_profilerKey);

        if (!empty($parentName)) {
            $block = $this->getBlock($parentName);
        }
        if (!empty($block)) {
            $args = (array)$node->children();

            $jsonArgs = (isset($node['json']) ? explode(' ', (string)$node['json']) : array());
            $jsonHelper = Mage::helper('core');
            $translateArgs = (isset($node['translate']) ? explode(' ', (string)$node['translate']) : array());
            $translateHelper = Mage::helper(isset($node['module']) ? (string)$node['module'] : 'core');
            $args = $this->processActionArgs($args, $jsonArgs, $jsonHelper, $translateArgs, $translateHelper);

            call_user_func_array(array($block, $method), $args);
        }

        Varien_Profiler::stop($_profilerKey);

        return $this;
    }

    /**
     * @param array  $args
     * @param array  $jsonArgs
     * @param null   $jsonHelper
     * @param array  $transArgs
     * @param null   $transHelper
     * @param string $currentPath
     *
     * @return array
     *
     * @author Lee Saferite <lee.saferite@aoe.com>
     */
    protected function processActionArgs(array $args, $jsonArgs = array(), $jsonHelper = null, $transArgs = array(), $transHelper = null, $currentPath = '')
    {
        $jsonHelper = ((!is_null($jsonHelper) && method_exists($jsonHelper, 'jsonDecode')) ? $jsonHelper : Mage::helper('core'));
        $transHelper = ((!is_null($transHelper) && method_exists($transHelper, '__')) ? $transHelper : Mage::helper('core'));

        foreach ($args as $key => $arg) {
            $path = $currentPath . $key;
            if ($arg instanceof Mage_Core_Model_Layout_Element) {
                // Process depth-first
                if ($arg->hasChildren()) {
                    $children = $this->processActionArgs((array)$arg->children(), $jsonArgs, $jsonHelper, $transArgs, $transHelper, $path . '.');
                }

                // Attempt to process helpers
                if (isset($arg['helper'])) {
                    $helperName = explode('/', (string)$arg['helper']);
                    $helperMethod = array_pop($helperName);
                    $helperName = implode('/', $helperName);

                    if (isset($children)) {
                        $translateArgs = $children;
                    } else {
                        $translateArgs = array((string)$arg);
                    }

                    $args[$key] = call_user_func_array(array(Mage::helper($helperName), $helperMethod), $translateArgs);
                } else {
                    if (isset($children)) {
                        $args[$key] = $children;
                    } else {
                        $args[$key] = (string)$arg;
                    }
                }
                
                unset($children);
            }

            if (is_string($args[$key]) && in_array($path, $jsonArgs)) {
                $args[$key] = $jsonHelper->jsonDecode($args[$key]);
            }

            if (in_array($path, $transArgs)) {
                if (is_string($args[$key])) {
                    $args[$key] = $transHelper->__($args[$key]);
                } else {
                    $args[$key] = call_user_func_array(array($transHelper, '__'), $args[$key]);
                }
            }
        }

        return $args;
    }
}
