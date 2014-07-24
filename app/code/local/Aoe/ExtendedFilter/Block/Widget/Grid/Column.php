<?php

/**
 * @author Lee Saferite <lee.saferite@aoe.com>
 * @since  2014-04-25
 */
class Aoe_ExtendedFilter_Block_Widget_Grid_Column extends Mage_Adminhtml_Block_Widget_Grid_Column
{
    public function getSortable()
    {
        return ($this->hasData('sortable') ? (bool)$this->getData('sortable') : true);
    }

    public function getFilter()
    {
        if($this->hasData('filter') && !$this->getData('filter')) {
            $this->setData('filter', false);
        }

        return parent::getFilter();
    }

    protected function _getFilterByType()
    {
        $filterClass = parent::_getFilterByType();
        return ($filterClass === null || $filterClass ? $filterClass : false);
    }

    public function setGrid($grid)
    {
        $this->_grid = $grid;
        return $this;
    }
}
