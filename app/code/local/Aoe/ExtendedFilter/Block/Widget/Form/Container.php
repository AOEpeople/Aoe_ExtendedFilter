<?php

/**
 * @author Lee Saferite <lee.saferite@aoe.com>
 * @since  3/24/14
 */
class Aoe_ExtendedFilter_Block_Widget_Form_Container extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();

        if (!$this->hasData('template')) {
            $this->setTemplate('Aoe/ExtendedFilter/widget/form/container.phtml');
        }
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

    /**
     * Skip parent method and call grandparent
     *
     * @return $this
     *
     * @author Lee Saferite <lee.saferite@aoe.com>
     */
    protected function _prepareLayout()
    {
        return Mage_Adminhtml_Block_Widget_Container::_prepareLayout();
    }
}
