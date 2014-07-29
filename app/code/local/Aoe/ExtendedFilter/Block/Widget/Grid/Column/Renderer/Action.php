<?php

/**
 * @author Lee Saferite <lee.saferite@aoe.com>
 * @since  2014-04-15
 */
class Aoe_ExtendedFilter_Block_Widget_Grid_Column_Renderer_Action extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Action
{
    /**
     * Renders column
     *
     * @param Varien_Object $row
     *
     * @return string
     */
    public function render(Varien_Object $row)
    {
        $renderActions = array();

        $actions = $this->getColumn()->getActions();
        if (is_array($actions)) {
            foreach ($actions as $action) {
                if (isset($action['checks'])) {
                    $fail = false;
                    $checks = array_filter(array_map('trim', explode(',', $action['checks'])));
                    foreach ($checks as $check) {
                        $negativeCheck = (substr($check, 0, 1) === '!');
                        $check = ($negativeCheck ? substr($check, 1) : $check);
                        $value = (strpos($check, '/') === false ? $row->getDataUsingMethod($check) : $row->getData($check));
                        if ((bool)$value === $negativeCheck) {
                            $fail = true;
                            break;
                        }
                    }
                    if ($fail) {
                        continue;
                    }
                }
                $renderActions[] = $action;
            }
        }

        if (empty($renderActions)) {
            return '&nbsp;';
        }

        $linkLimit = ($this->getColumn()->getNoLink() ? 0 : max(intval($this->getColumn()->getLinkLimit()), 1));

        $out = '';

        if (count($renderActions) <= $linkLimit) {
            foreach ($renderActions as $action) {
                if (is_array($action)) {
                    $out .= $this->_toLinkHtml($action, $row);
                }
            }
        } else {
            $out .= '<select class="action-select" onchange="varienGridAction.execute(this);">';
            $out .= '<option value=""></option>';
            foreach ($renderActions as $action) {
                if (is_array($action)) {
                    $out .= $this->_toOptionHtml($action, $row);
                }
            }
            $out .= '</select>';
        }

        return $out;
    }
}
