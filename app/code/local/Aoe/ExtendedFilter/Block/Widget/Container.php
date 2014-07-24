<?php

/**
 * @author Lee Saferite <lee.saferite@aoe.com>
 * @since  2014-04-15
 */
class Aoe_ExtendedFilter_Block_Widget_Container extends Mage_Adminhtml_Block_Widget_Container
{
    /**
     * Class constructor
     *
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('Aoe/ExtendedFilter/widget/container.phtml');
    }

    /**
     * Set header text
     *
     * @param $text
     *
     * @return $this
     *
     * @author Lee Saferite <lee.saferite@aoe.com>
     */
    public function setHeaderText($text)
    {
        $this->_headerText = $text;

        return $this;
    }
}
