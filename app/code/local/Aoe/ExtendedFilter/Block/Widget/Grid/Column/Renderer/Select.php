<?php

/**
 * @author Lee Saferite <lee.saferite@aoe.com>
 * @since  2014-04-11
 */
class Aoe_ExtendedFilter_Block_Widget_Grid_Column_Renderer_Select extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Select
{
    /**
     * Renders grid column
     *
     * @param   Varien_Object $row
     *
     * @return  string
     */
    public function render(Varien_Object $row)
    {
        $name = trim($this->getColumn()->getNamePattern());
        if (!empty($name)) {
            $nameParams = array_map('trim', array_filter(explode(',', $this->getColumn()->getNameParams())));
            if (count($nameParams)) {
                $params = array($name);
                foreach ($nameParams as $key) {
                    $params[] = $row->getDataUsingMethod($key);
                }

                $name = call_user_func_array(array($this, '__'), $params);
            }
            $this->getColumn()->setName($name);
        }

        return parent::render($row);
    }
}
