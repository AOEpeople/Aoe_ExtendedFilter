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
        if (!$this->_filter) {
            if ($this->hasData('filter') && !$this->getData('filter') && $this->getData('filter') !== null) {
                return false;
            }

            $filterClass = $this->getData('filter');
            if (!$filterClass || $filterClass === true) {
                $filterClass = $this->_getFilterByType();
                if ($filterClass === false) {
                    return false;
                }
            }
            $this->_filter = $this->getLayout()->createBlock($filterClass)->setColumn($this);
        }

        return $this->_filter;
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
