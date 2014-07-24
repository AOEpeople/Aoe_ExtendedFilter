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
        $fullActions = $this->getColumn()->getActions();

        if (is_array($fullActions)) {
            $renderActions = array();
            foreach ($fullActions as $action) {
                if (isset($action['checks'])) {
                    $fail = false;
                    $checks = array_filter(array_map('trim', explode(',', $action['checks'])));
                    foreach ($checks as $check) {
                        $negativeCheck = (substr($check, 0, 1) === '!');
                        $check = ($negativeCheck ? substr($check, 1) : $check);
                        if ((bool)$row->getDataUsingMethod($check) === $negativeCheck) {
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
            $this->getColumn()->setActions($renderActions);
        }

        $result = parent::render($row);

        $this->getColumn()->setActions($fullActions);

        return $result;
    }
}
